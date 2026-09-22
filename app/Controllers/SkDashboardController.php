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
$bmis = new BMISClass();

// Count stats from existing tables
try {
    $total_youth    = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth")->fetchColumn();
    $total_programs = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_programs")->fetchColumn();
    $upcoming_prog  = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_programs WHERE status='Upcoming'")->fetchColumn();
    $total_enrolled = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_enrollment")->fetchColumn();
    $total_posts    = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_bulletin")->fetchColumn();
    $pinned_posts   = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_bulletin WHERE is_pinned=1")->fetchColumn();
    $recent_youth   = $conn->query("SELECT * FROM tbl_youth ORDER BY id_youth DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    $recent_prog    = $conn->query("SELECT * FROM tbl_youth_programs ORDER BY date_created DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    $recent_posts   = $conn->query("SELECT * FROM tbl_youth_bulletin ORDER BY date_posted DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $total_youth=$total_programs=$upcoming_prog=$total_enrolled=$total_posts=$pinned_posts=0;
    $recent_youth=$recent_prog=$recent_posts=[];
}
?>
<?php require ROOT_PATH . '/app/Views/sk_dashboard_view.php'; ?>
