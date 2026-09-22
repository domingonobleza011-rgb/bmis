<?php
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors', 1);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
   require(ROOT_PATH . '/classes/resident.class.php');
   require(ROOT_PATH . '/classes/conn.php');
   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $pwd_count = $residentbmis->count_pwd();
?>
<?php require ROOT_PATH . '/app/Views/admn_table_pwd_view.php'; ?>
