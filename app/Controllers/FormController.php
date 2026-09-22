<?php

class FormController extends Controller
{
    public function barangayId()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $residentbmis->get_userdata();
$id_brgyid = $_GET['id_brgyid'];
$resident = $residentbmis->get_single_brgyid($id_brgyid);

// ── Activity Log: Barangay ID Generated ─────────────────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Barangay ID',
        "Generated Barangay ID for {$resident_name} (ID Record: {$id_brgyid})"
    );

    // ── Auto-notify resident: Barangay ID generated ──────────────────────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    $notify_id_resident = $resident['id_resident'] ?? null;
    if (!empty($notify_id_resident) && (int)$notify_id_resident !== 0) {
        $residentbmis->sendMessage(
            $notify_id_resident,
            "Your Barangay ID has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────


// Fetch the staff member with position "Punong Barangay"
$stmt_pb = $conn->prepare("SELECT fname, mi, lname FROM tbl_user WHERE position = 'Punong Barangay' LIMIT 1");
$stmt_pb->execute();
$punong = $stmt_pb->fetch(PDO::FETCH_ASSOC);
$punong_name = $punong ? strtoupper($punong['lname'] . ', ' . $punong['fname'] . ' ' . $punong['mi']) : 'PUNONG BARANGAY';

date_default_timezone_set('Asia/Manila');
$date_issued = date('F j, Y'); 
$date_expires = date('F j, Y', strtotime('+1 year')); 
        $this->view('form/barangayId.php');

    }

    public function brgyClearance()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
// ── FIX: this form previously called get_single_bspermit() and rendered a
// Business Permit here (copy-paste from businesspermit_form.php). A Barangay
// Clearance has its own table/data — use that instead. ──────────────────────
$resident = $residentbmis->get_single_clearance($id_resident);

// ── Activity Log: Barangay Clearance Generated ───────────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Barangay Clearance',
        "Generated Barangay Clearance for {$resident_name} (Resident ID: {$id_resident})"
    );

    // ── Auto-notify resident: Barangay Clearance generated ──────────────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Barangay Clearance has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────

// Set the correct time zone
date_default_timezone_set('Asia/Manila');

// Logic for automatic dates
$date_issued = date('m/d/Y');
$date_expires = date('m/d/Y', strtotime('+1 year'));
  
        $this->view('form/brgyClearance.php');

    }

    public function businessPermit()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_bspermit($id_resident);

// ── Activity Log: Business Permit Generated ──────────────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $bsname        = $resident['bsname'] ?? 'N/A';
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Business Permit',
        "Generated Business Permit for {$resident_name} – Business: {$bsname} (Resident ID: {$id_resident})"
    );

    // ── Auto-notify resident: Business Permit generated ─────────────────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Business Permit has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────

// Set the correct time zone
date_default_timezone_set('Asia/Manila');

// Logic for automatic dates
$date_issued = date('m/d/Y'); 
$date_expires = date('m/d/Y', strtotime('+1 year')); 
  
        $this->view('form/businessPermit.php');

    }

    public function goodMoralCert()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
ini_set('display_errors',0);
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_certofgoodmoral($id_resident);

// ── Activity Log: Certificate of Good Moral Character Generated ─────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Good Moral Character',
        "Generated Certificate of Good Moral Character for {$resident_name} (Resident ID: {$id_resident})"
    );
    
        if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Certificate of Goodmoral has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────
  
        $this->view('form/goodMoralCert.php');

    }

    public function guardianship()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_certofguardianship($id_resident);

// ── Activity Log: Certificate of Guardianship Generated ─────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Guardianship',
        "Generated Certificate of Guardianship for {$resident_name} (Resident ID: {$id_resident})"
    );

    // ── Auto-notify resident: Certificate of Guardianship generated ─────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Certificate of Guardianship has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────
  
        $this->view('form/guardianship.php');

    }

    public function indigency()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_certofindigency($id_resident);

// ── Activity Log: Certificate of Indigency Generated ────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Indigency',
        "Generated Certificate of Indigency for {$resident_name} (Resident ID: {$id_resident})"
    );

    // ── Auto-notify resident: Certificate of Indigency generated ────────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Certificate of Indigency has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────
  
        $this->view('form/indigency.php');

    }

    public function livestockCert()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
ini_set('display_errors',0);
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_certoflivestock($id_resident);

// ── Activity Log: Certificate of Livestock Ownership Generated ─────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Livestock Ownership',
        "Generated Certificate of Livestock Ownership for {$resident_name} (Resident ID: {$id_resident})"
    );
    
        if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Certificate of Livestock Ownership has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────
  
        $this->view('form/livestockCert.php');

    }

    public function resCert()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
ini_set('display_errors',0);
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_certofres($id_resident);

// ── Activity Log: Certificate of Residency Generated ────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Residency',
        "Generated Certificate of Residency for {$resident_name} (Resident ID: {$id_resident})"
    );

    // ── Auto-notify resident: Certificate of Residency generated ────────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Certificate of Residency has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────
  
        $this->view('form/resCert.php');

    }

    public function updateStaff()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    error_reporting(E_ALL ^ E_WARNING);
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
 
<?php 
    
        $this->view('form/updateStaff.php');

    }

}
