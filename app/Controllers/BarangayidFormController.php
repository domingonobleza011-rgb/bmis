<?php
require(ROOT_PATH . '/classes/resident.class.php');
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
    $residentbmis->mark_document_generated('tbl_brgyid', 'id_brgyid', $id_brgyid);

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

include ROOT_PATH . "/classes/conn.php"; 

// Fetch the staff member with position "Punong Barangay"
$stmt_pb = $conn->prepare("SELECT fname, mi, lname FROM tbl_user WHERE position = 'Punong Barangay' LIMIT 1");
$stmt_pb->execute();
$punong = $stmt_pb->fetch(PDO::FETCH_ASSOC);
$punong_name = $punong ? strtoupper($punong['lname'] . ', ' . $punong['fname'] . ' ' . $punong['mi']) : 'PUNONG BARANGAY';

date_default_timezone_set('Asia/Manila');
$date_issued = date('F j, Y'); 
$date_expires = date('F j, Y', strtotime('+1 year')); 
?>
<?php require ROOT_PATH . '/app/Views/barangayid_form_view.php'; ?>
