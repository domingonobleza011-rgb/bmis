<?php
    error_reporting(E_ALL ^ E_WARNING);
    require(ROOT_PATH . '/classes/staff.class.php');
    $userdetails = $bmis->get_userdata();
    //$bmis->validate_admin();
    $view = $staffbmis->view_staff();
    $staffbmis->create_staff();
    $upstaff = $staffbmis->update_staff();
    $staffbmis->delete_staff();
    $staffcount = $staffbmis->count_staff();
    $id_user = $_GET['id_user'];
    $staff = $staffbmis->get_single_staff($id_user);
?>
<?php require ROOT_PATH . '/app/Views/update_staff_form_view.php'; ?>
