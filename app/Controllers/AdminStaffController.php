<?php

class AdminStaffController extends Controller
{
    public function staffCrud()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    ini_set('display_errors',1);
    error_reporting(E_ALL ^ E_WARNING);

    $userdetails = $bmis->get_userdata();
    $bmis->validate_staff_or_admin();
    $view = $staffbmis->view_staff_paginated(10);
    $staffbmis->create_staff();
    $staffbmis->update_staff();
    $staffbmis->delete_staff();
    $staffbmis->demote_staff();
    $staffcount = $staffbmis->count_staff();
    
?>

<?php 
    
        $this->view('admin_staff/staffCrud.php');

    }

    public function crud()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

    ini_set('display_errors',0);
    error_reporting(E_ALL ^ E_WARNING);

    $userdetails = $bmis->get_userdata();
    $bmis->validate_staff_or_admin();
    $view = $staffbmis->view_staff();
    $staffbmis->create_staff();
    $staffbmis->update_staff();
    $staffbmis->delete_staff();
    $staffcount = $staffbmis->count_staff();
    
?>

<?php 
    
        $this->view('admin_staff/crud.php');

    }

}
