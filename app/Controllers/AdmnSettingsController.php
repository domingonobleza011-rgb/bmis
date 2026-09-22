<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define('BMIS_ROLE_REQUIRED', 'staff');
include(ROOT_PATH . '/secure_header.php'); 
    require(ROOT_PATH . '/classes/main.class.php');
    $bmis->create_admin(); 
    $userdetails = $bmis->get_userdata();

    include(ROOT_PATH . '/dashboard_sidebar_start.php');
?>
<?php require ROOT_PATH . '/app/Views/admn_settings_view.php'; ?>
