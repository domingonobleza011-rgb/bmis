<?php
error_reporting(E_ALL ^ E_WARNING);
date_default_timezone_set('Asia/Manila');
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php');
require(ROOT_PATH . '/classes/main.class.php');
require(ROOT_PATH . '/classes/resident.class.php');
require_once(ROOT_PATH . '/classes/conn.php');

$userdetails = $bmis->get_userdata();
$id_resident = $userdetails['id_resident'] ?? 0;

// ── AGE GATE: block residents aged 60+ from youth profiling ──────────────────
$_age_for_gate = 0;
if (!empty($userdetails['bdate'])) {
    $_age_for_gate = (new DateTime())->diff(new DateTime($userdetails['bdate']))->y;
} elseif (!empty($userdetails['age'])) {
    $_age_for_gate = (int)$userdetails['age'];
}
if ($_age_for_gate >= 60) {
    echo "<!DOCTYPE html><html><head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Not Eligible - Barangay San Pedro</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>
        </head><body class='bg-light d-flex align-items-center justify-content-center' style='min-height:100vh;'>
        <div class='text-center p-4' style='max-width:420px;'>
            <div style='font-size:4rem;'>&#x1F9D3;</div>
            <h4 class='fw-bold mt-3'>Not Eligible for Youth Profiling</h4>
            <p class='text-muted'>Youth profiling is only available for residents <strong>below 60 years old</strong>.
            Based on your birthdate, your current age is <strong>{$_age_for_gate}</strong>.</p>
            <a href='resident_homepage.php' class='btn btn-primary rounded-pill px-4'>
                <i class='bi bi-house-door-fill me-1'></i> Back to Home
            </a>
        </div>
        </body></html>";
    exit();
}

$bmis->create_youth();

// ── RESOLVE YOUTH ID FIRST ───────────────────────────────────────────────────
$youth_id_resolved  = null;
$existing_youth_photo = null;
$youth_profile_incomplete = false;
$ry = $conn->prepare("SELECT id_youth, photo, civil_status FROM tbl_youth WHERE id_youth = ? LIMIT 1");
$ry->execute([$id_resident]);
$ry_row = $ry->fetch(PDO::FETCH_ASSOC);
if ($ry_row) {
    $youth_id_resolved     = (int)$ry_row['id_youth'];
    $existing_youth_photo  = $ry_row['photo'] ?? null;
    $youth_profile_incomplete = empty($ry_row['civil_status']);
}

// ── ENROLL IN PROGRAM ────────────────────────────────────────────────────────
if (isset($_POST['enroll_program'])) {
    $id_program = (int)$_POST['id_program'];

    if (!$youth_id_resolved || $youth_profile_incomplete) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=noprofile"); exit;
    }

    $ychk = $conn->prepare("SELECT y.id_youth, y.fname, y.lname, r.contact AS contact_number FROM tbl_youth y JOIN tbl_resident r ON r.id_resident = y.id_youth WHERE y.id_youth = ?");
    $ychk->execute([$youth_id_resolved]);
    $youth = $ychk->fetch(PDO::FETCH_ASSOC);

    if (!$youth) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=noprofile"); exit;
    }

    $pchk = $conn->prepare("SELECT id_program, slots FROM tbl_youth_programs WHERE id_program = ?");
    $pchk->execute([$id_program]);
    $program_row = $pchk->fetch(PDO::FETCH_ASSOC);
    if (!$program_row) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=invalidprogram"); exit;
    }

    $dup = $conn->prepare("SELECT id_enrollment FROM tbl_youth_enrollment WHERE id_program = ? AND id_youth = ?");
    $dup->execute([$id_program, $youth_id_resolved]);
    if ($dup->fetch()) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=duplicate"); exit;
    }

    if (!empty($program_row['slots'])) {
        $slotcnt = $conn->prepare("SELECT COUNT(*) FROM tbl_youth_enrollment WHERE id_program = ? AND status != 'Dropped'");
        $slotcnt->execute([$id_program]);
        if ((int)$slotcnt->fetchColumn() >= (int)$program_row['slots']) {
            header("Location: resident_youth_profile.php?tab=programs&enroll=full"); exit;
        }
    }

    $ins = $conn->prepare("INSERT INTO tbl_youth_enrollment (id_program, id_youth, youth_name, contact, status, enrolled_at) VALUES (?,?,?,?,?,?)");
    $ins->execute([
        $id_program,
        $youth_id_resolved,
        $youth['lname'].', '.$youth['fname'],
        $youth['contact_number'],
        'Enrolled',
        date('Y-m-d H:i:s')
    ]);
    header("Location: resident_youth_profile.php?tab=programs&enroll=success"); exit;
}

// ── FETCH BULLETINS ──────────────────────────────────────────────────────────
$type_filter = $_GET['type'] ?? '';
if ($type_filter) {
    $bs = $conn->prepare("SELECT * FROM tbl_youth_bulletin WHERE post_type = ? ORDER BY is_pinned DESC, date_posted DESC");
    $bs->execute([$type_filter]);
} else {
    $bs = $conn->query("SELECT * FROM tbl_youth_bulletin ORDER BY is_pinned DESC, date_posted DESC");
}
$bulletins  = $bs->fetchAll(PDO::FETCH_ASSOC);
$post_types = ['Announcement','Opportunity','Reminder','Achievement','General'];

// ── FETCH PROGRAMS ───────────────────────────────────────────────────────────
$status_filter = $_GET['pstatus'] ?? '';
if ($status_filter) {
    $ps = $conn->prepare("SELECT * FROM tbl_youth_programs WHERE status = ? ORDER BY event_date DESC");
    $ps->execute([$status_filter]);
} else {
    $ps = $conn->query("SELECT * FROM tbl_youth_programs ORDER BY event_date DESC");
}
$programs      = $ps->fetchAll(PDO::FETCH_ASSOC);
$prog_statuses = ['Upcoming','Ongoing','Completed','Cancelled'];

$prog_slot_counts = [];
$psc = $conn->query("SELECT id_program, COUNT(*) AS cnt FROM tbl_youth_enrollment WHERE status != 'Dropped' GROUP BY id_program");
foreach ($psc->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $prog_slot_counts[(int)$row['id_program']] = (int)$row['cnt'];
}

$my_enrolled = [];
if ($youth_id_resolved) {
    $yme = $conn->prepare("SELECT id_program FROM tbl_youth_enrollment WHERE id_youth = ?");
    $yme->execute([$youth_id_resolved]);
    foreach ($yme->fetchAll(PDO::FETCH_ASSOC) as $row)
        $my_enrolled[] = (int)$row['id_program'];
}

$active_tab = $_GET['tab'] ?? 'announcements';
?>
<?php require ROOT_PATH . '/app/Views/resident_youth_profile_view.php'; ?>
