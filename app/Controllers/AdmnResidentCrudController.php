<?php
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors', 1);
define('BMIS_ROLE_REQUIRED', 'staff');
require_once(ROOT_PATH . '/secure_header.php'); 
   require(ROOT_PATH . '/classes/resident.class.php');
   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $residentbmis->create_resident();
   $residentbmis->update_resident();
   $residentbmis->delete_resident();
   $residentbmis->promote_resident();
   $view = $residentbmis->view_resident_paginated(10);
   

   $rescount = $residentbmis->count_resident();
   $rescountm = $residentbmis->count_male_resident();
   $rescountf = $residentbmis->count_female_resident();
   $rescountfh = $residentbmis->count_head_resident();
   $rescountfm = $residentbmis->count_member_resident();
   
?>
<?php require ROOT_PATH . '/app/Views/admn_resident_crud_view.php'; ?>
