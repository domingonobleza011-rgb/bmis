<?php
ini_set('display_errors',0);
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
$id_rescert = $_GET['id_rescert'];
$resident = $residentbmis->get_single_certofres($id_rescert);
// The certificate row itself carries which resident it belongs to;
// derive $id_resident from the fetched record (used below for logging/SMS).
$id_resident = $resident['id_resident'] ?? 0;

// ── Activity Log: Certificate of Residency Generated ────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Residency',
        "Generated Certificate of Residency for {$resident_name} (Resident ID: {$id_resident})"
    );
    $residentbmis->mark_document_generated('tbl_rescert', 'id_rescert', $id_rescert);

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
  ?>
<?php require ROOT_PATH . '/app/Views/rescert_form_view.php'; ?>
