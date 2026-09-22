<?php 
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php'); 
require(ROOT_PATH . '/classes/main.class.php');
require(ROOT_PATH . '/classes/resident.class.php');

$userdetails = $bmis->get_userdata();
$bmis->create_certofres();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
?>
<?php require ROOT_PATH . '/app/Views/services_certofres_view.php'; ?>
