<?php
ini_set('display_errors', 1);
error_reporting(E_ALL ^ E_WARNING);

define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require_once(ROOT_PATH . '/secure_header.php');
require(ROOT_PATH . '/classes/staff.class.php');

$userdetails = $bmis->get_userdata();
$bmis->validate_staff_or_admin();
$staffbmis->create_staff();
$staffbmis->update_staff();
$staffbmis->delete_staff();
$staffbmis->demote_staff();
$view = $staffbmis->view_staff_paginated(10);
$staffcount = $staffbmis->count_staff();
?>
<?php require ROOT_PATH . '/app/Views/admn_staff_crud_view.php'; ?>
