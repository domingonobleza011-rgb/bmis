<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 0);

define('BMIS_ROLE_REQUIRED', 'staff');
require_once(ROOT_PATH . '/secure_header.php');
require_once(ROOT_PATH . '/classes/resident.class.php');

$userdetails = $bmis->get_userdata();
$bmis->validate_staff_or_admin();
$admin_name = isset($userdetails['fname']) ? $userdetails['fname'] . ' ' . $userdetails['lname'] : 'Admin';

// ---- Handle: Approve pending registration ----
if (isset($_POST['approve_pending'])) {
    $id_pending = (int)$_POST['id_pending'];
    if ($residentbmis->approve_pending_resident($id_pending, $admin_name)) {
        header("Location: admn_resident_pending.php?toast=approved");
    } else {
        header("Location: admn_resident_pending.php?toast=error");
    }
    exit();
}

// ---- Handle: Reject pending registration ----
if (isset($_POST['reject_pending'])) {
    $id_pending = (int)$_POST['id_pending'];
    $reason     = $_POST['reject_reason'] ?? '';
    if ($residentbmis->reject_pending_resident($id_pending, $admin_name, $reason)) {
        header("Location: admn_resident_pending.php?toast=rejected");
    } else {
        header("Location: admn_resident_pending.php?toast=error");
    }
    exit();
}

// ---- Handle: Delete a rejected/old pending record ----
if (isset($_POST['delete_pending'])) {
    $id_pending = (int)$_POST['id_pending'];
    $residentbmis->delete_pending_resident($id_pending);
    header("Location: admn_resident_pending.php?toast=deleted");
    exit();
}

$pending_list  = $residentbmis->view_pending_residents();
$pending_count = count($pending_list);

// Rejected records kept for reference
$connection = $bmis->openConn();
$stmt = $connection->prepare("SELECT * FROM tbl_resident_pending WHERE application_status = 'rejected' ORDER BY date_submitted DESC");
$stmt->execute();
$rejected_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php require ROOT_PATH . '/app/Views/admn_resident_pending_view.php'; ?>
