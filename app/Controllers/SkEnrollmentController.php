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
// ADD enrollment
if (isset($_POST['add_enrollment'])) {
    // Get youth name from tbl_youth, contact from their resident profile
    $y = $conn->prepare("SELECT y.fname, y.lname, r.contact FROM tbl_youth y LEFT JOIN tbl_resident r ON r.id_resident = y.id_youth WHERE y.id_youth=?");
    $y->execute([$_POST['id_youth']]);
    $yu = $y->fetch(PDO::FETCH_ASSOC);
    $name = $yu ? ($yu['lname'].', '.$yu['fname']) : '';
    $contact = $yu ? ($yu['contact'] ?? '') : '';
    $stmt = $conn->prepare("INSERT INTO tbl_youth_enrollment (id_program,id_youth,youth_name,contact,status) VALUES (?,?,?,?,?)");
    $stmt->execute([$_POST['id_program'],$_POST['id_youth'],$name,$contact,$_POST['status']]);
    header("Location: sk_enrollment.php?success=added&program=".$_POST['id_program']); exit;
}
// UPDATE status
if (isset($_POST['update_enrollment'])) {
    $stmt = $conn->prepare("UPDATE tbl_youth_enrollment SET status=? WHERE id_enrollment=?");
    $stmt->execute([$_POST['status'],$_POST['id_enrollment']]);
    header("Location: sk_enrollment.php?success=updated&program=".$_POST['id_program']); exit;
}
// DELETE
if (isset($_POST['delete_enrollment'])) {
    $conn->prepare("DELETE FROM tbl_youth_enrollment WHERE id_enrollment=?")->execute([$_POST['id_enrollment']]);
    header("Location: sk_enrollment.php?success=deleted&program=".$_POST['id_program']); exit;
}

$prog_filter = $_GET['program'] ?? '';
// Get programs list
$programs = $conn->query("SELECT * FROM tbl_youth_programs ORDER BY event_date DESC")->fetchAll(PDO::FETCH_ASSOC);
// Get youth list
$youth_list = $conn->query("SELECT id_youth, fname, lname FROM tbl_youth ORDER BY lname")->fetchAll(PDO::FETCH_ASSOC);

if ($prog_filter) {
    $s = $conn->prepare("SELECT e.*,p.program_title,p.program_type,p.event_date FROM tbl_youth_enrollment e JOIN tbl_youth_programs p ON e.id_program=p.id_program WHERE e.id_program=? ORDER BY e.enrolled_at DESC");
    $s->execute([$prog_filter]);
} else {
    $s = $conn->query("SELECT e.*,p.program_title,p.program_type,p.event_date FROM tbl_youth_enrollment e JOIN tbl_youth_programs p ON e.id_program=p.id_program ORDER BY e.enrolled_at DESC");
}
$enrollments = $s->fetchAll(PDO::FETCH_ASSOC);

// Stats
$total_e    = count($enrollments);
$attended   = count(array_filter($enrollments, fn($e)=>$e['status']==='Attended'));
$enrolled   = count(array_filter($enrollments, fn($e)=>$e['status']==='Enrolled'));
$dropped    = count(array_filter($enrollments, fn($e)=>$e['status']==='Dropped'));
?>
<?php require ROOT_PATH . '/app/Views/sk_enrollment_view.php'; ?>
