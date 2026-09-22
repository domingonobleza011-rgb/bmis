<?php

class ServiceController extends Controller
{
    public function brgyClearance()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_brgyclearance();
        $this->view('service/brgyClearance.php');

    }

    public function brgyId()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_brgyid();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/brgyId.php');

    }

    public function business()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_bspermit();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/business.php');

    }

    public function certOfGoodMoral()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_certofgoodmoral();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/certOfGoodMoral.php');

    }

    public function certOfGuardianship()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_certofguardianship();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/certOfGuardianship.php');

    }

    public function certOfIndigency()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_certofindigency();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/certOfIndigency.php');

    }

    public function certOfLivestock()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_certoflivestock();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/certOfLivestock.php');

    }

    public function certOfRes()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_certofres();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('service/certOfRes.php');

    }

}
