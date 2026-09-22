<?php
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
   require(ROOT_PATH . '/classes/staff.class.php');
   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $staffbmis->view_staff_paginated(2);
   
?>
<?php require ROOT_PATH . '/app/Views/admn_table_totalstaff_view.php'; ?>
