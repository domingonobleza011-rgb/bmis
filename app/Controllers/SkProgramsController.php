<?php
error_reporting(E_ALL ^ E_WARNING);
require_once(ROOT_PATH . '/classes/security.php');
bmis_session_start();
bmis_set_security_headers();
require_once(ROOT_PATH . '/classes/conn.php');
include(ROOT_PATH . '/classes/staff.class.php');
include(ROOT_PATH . '/classes/resident.class.php');

// Enforce: only logged-in staff with position 'Sk Chairperson' may access this page
$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied. This page is restricted to the SK Chairperson only.');
}

require_once(ROOT_PATH . '/classes/main.class.php');
// ADD
if (isset($_POST['add_program'])) {
    $stmt = $conn->prepare("INSERT INTO tbl_youth_programs (program_title,program_type,description,venue,event_date,event_time,slots,requirements,status,created_by) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$_POST['program_title'],$_POST['program_type'],$_POST['description'],$_POST['venue'],$_POST['event_date'],$_POST['event_time'],$_POST['slots'],$_POST['requirements'],$_POST['status'],$userdetails['firstname'].' '.$userdetails['surname']]);
    header("Location: sk_programs.php?success=added"); exit;
}
// EDIT
if (isset($_POST['edit_program'])) {
    $stmt = $conn->prepare("UPDATE tbl_youth_programs SET program_title=?,program_type=?,description=?,venue=?,event_date=?,event_time=?,slots=?,requirements=?,status=? WHERE id_program=?");
    $stmt->execute([$_POST['program_title'],$_POST['program_type'],$_POST['description'],$_POST['venue'],$_POST['event_date'],$_POST['event_time'],$_POST['slots'],$_POST['requirements'],$_POST['status'],$_POST['id_program']]);
    header("Location: sk_programs.php?success=updated"); exit;
}
// DELETE
if (isset($_POST['delete_program'])) {
    $conn->prepare("DELETE FROM tbl_youth_programs WHERE id_program=?")->execute([$_POST['id_program']]);
    header("Location: sk_programs.php?success=deleted"); exit;
}

$status_filter = $_GET['status'] ?? '';
if ($status_filter) {
    $s = $conn->prepare("SELECT * FROM tbl_youth_programs WHERE status=? ORDER BY event_date DESC");
    $s->execute([$status_filter]);
} else {
    $s = $conn->prepare("SELECT * FROM tbl_youth_programs ORDER BY event_date DESC");
    $s->execute();
}
$programs = $s->fetchAll(PDO::FETCH_ASSOC);

$types = ['Training','Sports','Arts','Leadership','Health','Livelihood','Scholarship','Community Service','Other'];
$statuses = ['Upcoming','Ongoing','Completed','Cancelled'];
?>
<?php require ROOT_PATH . '/app/Views/sk_programs_view.php'; ?>
