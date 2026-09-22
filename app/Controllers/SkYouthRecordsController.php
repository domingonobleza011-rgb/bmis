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

    if (isset($_POST['edit_youth'])) {
        // Age is recomputed from the resident's birthdate server-side —
        // never trust a posted age value.
        $bstmt = $conn->prepare("SELECT bdate FROM tbl_resident WHERE id_resident = ?");
        $bstmt->execute([$_POST['id_youth']]);
        $bdate = $bstmt->fetchColumn();
        $age = $bdate ? (new DateTime())->diff(new DateTime($bdate))->y : ($_POST['age'] ?? null);

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
$total_pages = (int)ceil($total / $per_page);
?>
<?php require ROOT_PATH . '/app/Views/sk_youth_records_view.php'; ?>
