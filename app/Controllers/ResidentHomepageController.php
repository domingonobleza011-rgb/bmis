<?php
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php');
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);
include(ROOT_PATH . '/classes/resident.class.php');

$userdetails = $bmis->get_userdata();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
$unread_msg_count = $bmis->getUnreadResidentMessageCount($userdetails['id_resident']);

$dt = new DateTime("now", new DateTimeZone('Asia/Manila'));
$cdate = $dt->format('Y/m/d');
$ctime = $dt->format('H');

// Handle announcement hiding
if (isset($_POST['delete_announcement'])) {
    $bmis->delete_announcement($userdetails['id_resident']);
}
$announcements = $bmis->view_active_announcements($userdetails['id_resident']);
?>
<?php require ROOT_PATH . '/app/Views/resident_homepage_view.php'; ?>
