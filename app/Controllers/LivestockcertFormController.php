<?php
ini_set('display_errors',0);
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
$id_livestock = $_GET['id_livestock'];
$resident = $residentbmis->get_single_certoflivestock($id_livestock);
// The certificate row itself carries which resident it belongs to;
// derive $id_resident from the fetched record (used below for logging/SMS).
$id_resident = $resident['id_resident'] ?? 0;

// ── Activity Log: Certificate of Livestock Ownership Generated ─────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Certificate of Livestock Ownership',
        "Generated Certificate of Livestock Ownership for {$resident_name} (Resident ID: {$id_resident})"
    );
    $residentbmis->mark_document_generated('tbl_livestock', 'id_livestock', $id_livestock);
    
        if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Certificate of Livestock Ownership has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────
  ?>
<?php require ROOT_PATH . '/app/Views/livestockcert_form_view.php'; ?>
