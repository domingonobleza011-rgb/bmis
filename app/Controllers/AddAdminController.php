<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
    require(ROOT_PATH . '/classes/main.class.php');
    $bmis->create_admin(); 
    $userdetails = $bmis->get_userdata();
?>
<?php require ROOT_PATH . '/app/Views/add_admin_view.php'; ?>
