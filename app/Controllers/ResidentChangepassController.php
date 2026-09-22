<?php
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php');
error_reporting(E_ALL ^ E_WARNING);
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
$residentbmis->resident_changepass();
?>
<?php require ROOT_PATH . '/app/Views/resident_changepass_view.php'; ?>
