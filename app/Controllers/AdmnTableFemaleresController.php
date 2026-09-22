<?php
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require_once(ROOT_PATH . '/secure_header.php'); 
   require(ROOT_PATH . '/classes/resident.class.php');
   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_female_paginated(10);
   
?>
<?php require ROOT_PATH . '/app/Views/admn_table_femaleres_view.php'; ?>
