<?php
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
$id_guardianship = $_GET['id_guardianship'];
$resident = $residentbmis->get_single_certofguardianship($id_guardianship);
// The certificate row itself carries which resident it belongs to;
// derive $id_resident from the fetched record (used below for logging/SMS).
$id_resident = $resident['id_resident'] ?? 0;

// ── Activity Log: Certificate of Guardianship Generated ─────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Guardianship',
        "Generated Certificate of Guardianship for {$resident_name} (Resident ID: {$id_resident})"
    );
    $residentbmis->mark_document_generated('tbl_guardianship', 'id_guardianship', $id_guardianship);

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
  ?>
<?php require ROOT_PATH . '/app/Views/guardianship_form_view.php'; ?>
