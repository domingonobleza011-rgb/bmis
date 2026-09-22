<?php
    define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
    require(ROOT_PATH . '/classes/main.class.php');
$bmis->validate_staff_or_admin();
    $bmis->admin_changepass();
    $userdetails = $bmis->get_userdata();
    include(ROOT_PATH . '/dashboard_sidebar_start.php');
?>
<?php require ROOT_PATH . '/app/Views/admin_changepass_view.php'; ?>
