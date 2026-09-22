<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 0);

define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
require(ROOT_PATH . '/classes/resident.class.php');

$userdetails = $bmis->get_userdata();
$bmis->validate_staff_or_admin();
$bmis->delete_certofguardianship();
$bmis->create_certofguardianship();
$view = $bmis->view_certofguardianship();
$residents_list = $residentbmis->view_resident_lite();
$id_resident = $_GET['id_resident'] ?? null;
if ($id_resident) {
    $residentbmis->get_single_certofguardianship($id_resident);
}
?>
<?php require ROOT_PATH . '/app/Views/admn_certofguardianship_view.php'; ?>
