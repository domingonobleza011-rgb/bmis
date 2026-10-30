<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

// Must be logged in as Admin / allowed official — blocks URL bar access without login
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require_once ROOT_PATH . '/secure_header.php';  // require_once = fatal if missing (cannot be skipped)

include(ROOT_PATH . '/classes/staff.class.php');
include(ROOT_PATH . '/classes/resident.class.php');
require_once(ROOT_PATH . '/classes/conn.php'); // needed for complaint counts

$userdetails = $bmis->get_userdata();
// Double-check (defense in depth) — redirects guests to login, denies wrong roles
$bmis->validate_staff_or_admin();

   
    $rescountm = $residentbmis->count_male_resident();
    $rescountf = $residentbmis->count_female_resident();
    $rescountfh = $residentbmis->count_head_resident();
    $rescountfm = $residentbmis->count_member_resident();
    $rescountvoter = $residentbmis->count_voters();
    $rescountsenior = $residentbmis->count_resident_senior();
    $rescountpwd = $residentbmis->count_pwd();

    $staffcount = $staffbmis->count_staff();
    $staffcountm = $staffbmis->count_mstaff();
    $staffcountf = $staffbmis->count_fstaff();

    // ── Complaint counts (uses the shared $conn PDO from conn.php) ──
    $complaint_pending  = 0;
    $complaint_resolved = 0;
    $complaint_total    = 0;
    try {
        $complaint_pending  = (int)$conn->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='pending'")->fetchColumn();
        $complaint_resolved = (int)$conn->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='resolved'")->fetchColumn();
        $complaint_total    = (int)$conn->query("SELECT COUNT(*) FROM tbl_complaints")->fetchColumn();
    } catch (Exception $e) { /* table may not exist yet */ }

    // ── KPI totals ──
    $kpi_residents = 0; $kpi_certs = 0; $kpi_announcements = 0;
    try {
        $kpi_residents = (int)$conn->query("SELECT COUNT(*) FROM tbl_resident WHERE (is_archived = 0 OR is_archived IS NULL)")->fetchColumn();
    } catch (Exception $e) {}
    foreach (['tbl_rescert','tbl_indigency','tbl_clearance','tbl_bspermit','tbl_brgyid','tbl_soloparent','tbl_livestock','tbl_guardianship','tbl_goodmoral'] as $t) {
        try { $kpi_certs += (int)$conn->query("SELECT COUNT(*) FROM $t")->fetchColumn(); } catch (Exception $e) {}
    }
    try {
        $kpi_announcements = (int)$conn->query("SELECT COUNT(*) FROM tbl_announcement")->fetchColumn();
    } catch (Exception $e) {}
?>
<?php require ROOT_PATH . '/app/Views/admn_dashboard_view.php'; ?>
