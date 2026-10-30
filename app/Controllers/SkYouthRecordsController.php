<?php
error_reporting(E_ALL ^ E_WARNING);
require_once(ROOT_PATH . '/classes/security.php');
bmis_session_start();
bmis_set_security_headers();
require_once(ROOT_PATH . '/classes/conn.php');
include(ROOT_PATH . '/classes/staff.class.php');
include(ROOT_PATH . '/classes/resident.class.php');

$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied.');
}

require_once(ROOT_PATH . '/classes/main.class.php');
$bmis = new BMISClass();

// ── ADD / EDIT / DELETE HANDLERS ──────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_youth'])) {
        $civil = trim($_POST['civil_status'] ?? '');
        $emp   = trim($_POST['emp_status'] ?? '');
        if ($civil === '' || $emp === '') {
            header('Location: sk_youth_records.php?error=invalid'); exit;
        }

        $walkin = (($_POST['add_mode'] ?? '') === 'walkin');

        if ($walkin) {
            // Youth with NO resident account. They get an id in a reserved high
            // range (>= 1,000,000,000) so it can never collide with a real
            // resident id (id_youth normally equals id_resident).
            $lname = trim($_POST['w_lname'] ?? '');
            $fname = trim($_POST['w_fname'] ?? '');
            $mi    = trim($_POST['w_mi'] ?? '');
            $sex   = trim($_POST['w_sex'] ?? '');
            $bd    = trim($_POST['w_bdate'] ?? '');
            $bdt   = DateTime::createFromFormat('Y-m-d', $bd);
            if ($lname === '' || $fname === '' || !in_array($sex, ['Male','Female'], true) || !$bdt || $bdt->format('Y-m-d') !== $bd || $bdt > new DateTime()) {
                header('Location: sk_youth_records.php?error=invalid'); exit;
            }
            $age = (new DateTime())->diff($bdt)->y;
            if ($age < BMIS_YOUTH_MIN_AGE || $age >= BMIS_YOUTH_MAX_AGE) {
                header('Location: sk_youth_records.php?error=age'); exit;
            }
            // Same name + age already on the list?
            $dup = $conn->prepare("SELECT COUNT(*) FROM tbl_youth WHERE LOWER(lname)=LOWER(?) AND LOWER(fname)=LOWER(?) AND age=?");
            $dup->execute([$lname, $fname, $age]);
            if ((int)$dup->fetchColumn() > 0) {
                header('Location: sk_youth_records.php?error=exists'); exit;
            }
            $maxid = (int)$conn->query("SELECT COALESCE(MAX(id_youth),0) FROM tbl_youth WHERE id_youth >= 1000000000")->fetchColumn();
            $id_new = max(1000000000, $maxid) + 1;
        } else {
            // Youth tied to an existing resident: id_youth = id_resident. Name,
            // sex and age come from the resident record (age from birthdate).
            $id_res = (int)($_POST['id_resident'] ?? 0);
            $rs = $conn->prepare("SELECT id_resident, lname, fname, mi, sex, bdate FROM tbl_resident WHERE id_resident = ? AND (is_archived = 0 OR is_archived IS NULL)");
            $rs->execute([$id_res]);
            $res = $rs->fetch(PDO::FETCH_ASSOC);
            if (!$res || !$res['bdate']) {
                header('Location: sk_youth_records.php?error=invalid'); exit;
            }
            $age = (new DateTime())->diff(new DateTime($res['bdate']))->y;
            if ($age < BMIS_YOUTH_MIN_AGE || $age >= BMIS_YOUTH_MAX_AGE) {
                header('Location: sk_youth_records.php?error=age'); exit;
            }
            $ex = $conn->prepare("SELECT civil_status FROM tbl_youth WHERE id_youth = ?");
            $ex->execute([$id_res]);
            $exrow = $ex->fetch(PDO::FETCH_ASSOC);
            if ($exrow && !empty($exrow['civil_status'])) {
                header('Location: sk_youth_records.php?error=exists'); exit;
            }
            $id_new = $res['id_resident'];
            $lname = $res['lname']; $fname = $res['fname']; $mi = $res['mi']; $sex = $res['sex'];
        }

        // Optional photo
        $photo_filename = null;
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $validated = bmis_validate_image_upload($_FILES['photo'], false);
            if ($validated['ok']) {
                $upload_dir = 'uploads/youth_photos/';
                if (!is_dir($upload_dir)) { mkdir($upload_dir, 0755, true); }
                $new_filename = 'youth_' . $id_new . '_' . time() . '_' . $validated['safe_name'];
                if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_dir . $new_filename)) {
                    $photo_filename = $new_filename;
                }
            }
        }

        $stmt = $conn->prepare("INSERT INTO tbl_youth
                (id_youth, lname, fname, mi, age, sex, civil_status, email_address, educ_attain, emp_status, skill_name, photo)
            VALUES (?,?,?,?,?,?,?,?,?,?,?,?)
            ON DUPLICATE KEY UPDATE
                lname=VALUES(lname), fname=VALUES(fname), mi=VALUES(mi), age=VALUES(age), sex=VALUES(sex),
                civil_status=VALUES(civil_status), email_address=VALUES(email_address),
                educ_attain=VALUES(educ_attain), emp_status=VALUES(emp_status),
                skill_name=VALUES(skill_name), photo=COALESCE(VALUES(photo), photo)");
        $stmt->execute([
            $id_new, $lname, $fname, $mi, $age, $sex,
            $civil, trim($_POST['email_address'] ?? ''), trim($_POST['educ_attain'] ?? ''),
            $emp, trim($_POST['skill_name'] ?? ''), $photo_filename
        ]);
        header('Location: sk_youth_records.php?success=added'); exit;
    }

    if (isset($_POST['edit_youth'])) {
        // Age is recomputed from the resident's birthdate server-side —
        // never trust a posted age value.
        $bstmt = $conn->prepare("SELECT bdate FROM tbl_resident WHERE id_resident = ?");
        $bstmt->execute([$_POST['id_youth']]);
        $bdate = $bstmt->fetchColumn();
        $age = $bdate ? (new DateTime())->diff(new DateTime($bdate))->y : (is_numeric($_POST['age'] ?? null) ? (int)$_POST['age'] : null);

        $stmt = $conn->prepare("UPDATE tbl_youth SET lname=?, fname=?, mi=?, age=?, sex=?, civil_status=?, email_address=?, educ_attain=?, emp_status=?, skill_name=? WHERE id_youth=?");
        $stmt->execute([
            $_POST['lname'], $_POST['fname'], $_POST['mi'],
            $age,   $_POST['sex'],   $_POST['civil_status'],
            $_POST['email_address'], $_POST['educ_attain'],
            $_POST['emp_status'], $_POST['skill_name'], $_POST['id_youth']
        ]);
        header('Location: sk_youth_records.php?success=updated'); exit;
    }

    if (isset($_POST['delete_youth'])) {
        $id_youth = $_POST['id_youth'];

        // Remove the uploaded photo file along with the record, so
        // uploads/youth_photos/ doesn't accumulate orphaned images.
        $pstmt = $conn->prepare("SELECT photo FROM tbl_youth WHERE id_youth=?");
        $pstmt->execute([$id_youth]);
        $photo = $pstmt->fetchColumn();
        if ($photo && is_file('uploads/youth_photos/' . $photo)) {
            unlink('uploads/youth_photos/' . $photo);
        }

        // Remove their enrollment in every program along with the profile.
        $conn->prepare("DELETE FROM tbl_youth_enrollment WHERE id_youth=?")->execute([$id_youth]);
        $stmt = $conn->prepare("DELETE FROM tbl_youth WHERE id_youth=?");
        $stmt->execute([$id_youth]);
        header('Location: sk_youth_records.php?success=deleted'); exit;
    }

    if (isset($_POST['bulk_delete_youth'])) {
        $ids = $_POST['selected_ids'] ?? [];
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Remove each record's uploaded photo file along with the profiles.
            $pstmt = $conn->prepare("SELECT photo FROM tbl_youth WHERE id_youth IN ($placeholders)");
            $pstmt->execute($ids);
            foreach ($pstmt->fetchAll(PDO::FETCH_COLUMN) as $photo) {
                if ($photo && is_file('uploads/youth_photos/' . $photo)) {
                    unlink('uploads/youth_photos/' . $photo);
                }
            }

            // Remove their enrollment in every program along with the profiles.
            $conn->prepare("DELETE FROM tbl_youth_enrollment WHERE id_youth IN ($placeholders)")->execute($ids);
            $stmt = $conn->prepare("DELETE FROM tbl_youth WHERE id_youth IN ($placeholders)");
            $stmt->execute($ids);
        }
        header('Location: sk_youth_records.php?success=deleted'); exit;
    }
}

// ── FETCH / SEARCH ────────────────────────────────────────────────────────

$keyword  = trim($_GET['keyword'] ?? '');
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

if ($keyword) {
    $like  = "%$keyword%";
    $stmtC = $conn->prepare("SELECT COUNT(*) FROM tbl_youth WHERE lname LIKE ? OR fname LIKE ? OR email_address LIKE ?");
    $stmtC->execute([$like,$like,$like]);
    $total = (int)$stmtC->fetchColumn();

    $stmtR = $conn->prepare("SELECT * FROM tbl_youth WHERE lname LIKE ? OR fname LIKE ? OR email_address LIKE ? ORDER BY id_youth DESC LIMIT $per_page OFFSET $offset");
    $stmtR->execute([$like,$like,$like]);
} else {
    $stmtC = $conn->query("SELECT COUNT(*) FROM tbl_youth");
    $total = (int)$stmtC->fetchColumn();

    $stmtR = $conn->prepare("SELECT * FROM tbl_youth ORDER BY id_youth DESC LIMIT $per_page OFFSET $offset");
    $stmtR->execute();
}

$youths    = $stmtR->fetchAll(PDO::FETCH_ASSOC);

// Residents the chairperson can add: aged 15–29, not archived, and not yet
// registered as youth.
$eligible_residents = [];
try {
    $er = $conn->prepare("SELECT r.id_resident, r.lname, r.fname, r.mi, r.sex, r.email,
            TIMESTAMPDIFF(YEAR, r.bdate, CURDATE()) AS calc_age
        FROM tbl_resident r
        LEFT JOIN tbl_youth y ON y.id_youth = r.id_resident
        WHERE (r.is_archived = 0 OR r.is_archived IS NULL)
          AND r.bdate IS NOT NULL
          AND TIMESTAMPDIFF(YEAR, r.bdate, CURDATE()) >= ?
          AND TIMESTAMPDIFF(YEAR, r.bdate, CURDATE()) < ?
          AND (y.id_youth IS NULL OR y.civil_status IS NULL OR y.civil_status = '')
        ORDER BY r.lname, r.fname");
    $er->execute([BMIS_YOUTH_MIN_AGE, BMIS_YOUTH_MAX_AGE]);
    $eligible_residents = $er->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $e) { $eligible_residents = []; }

$total_pages = (int)ceil($total / $per_page);
?>
<?php require ROOT_PATH . '/app/Views/sk_youth_records_view.php'; ?>
