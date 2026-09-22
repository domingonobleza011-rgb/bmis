<?php
    
    error_reporting(E_ALL ^ E_WARNING);
    ini_set('display_errors',0);
define('BMIS_ROLE_REQUIRED', 'admin');
require(ROOT_PATH . '/secure_header.php');
    require(ROOT_PATH . '/classes/resident.class.php');
    $userdetails = $bmis->get_userdata();
    $bmis->validate_admin();
    $bmis->delete_youth();
    $view = $bmis->view_youth();
    $id_resident = $_GET['id_resident'];
    $resident = $residentbmis->get_single_youth($id_resident);
   
?>
<?php require ROOT_PATH . '/app/Views/admn_youth_profile_view.php'; ?>
