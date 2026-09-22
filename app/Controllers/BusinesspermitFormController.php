<?php
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
$id_bspermit = $_GET['id_bspermit'];
$resident = $residentbmis->get_single_bspermit($id_bspermit);
// The certificate row itself carries which resident it belongs to;
// derive $id_resident from the fetched record (used below for logging/SMS).
$id_resident = $resident['id_resident'] ?? 0;

// ── Activity Log: Business Permit Generated ──────────────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $bsname        = $resident['bsname'] ?? 'N/A';
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Business Permit',
        "Generated Business Permit for {$resident_name} – Business: {$bsname} (Resident ID: {$id_resident})"
    );
    $residentbmis->mark_document_generated('tbl_bspermit', 'id_bspermit', $id_bspermit);

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
  ?>
<?php require ROOT_PATH . '/app/Views/businesspermit_form_view.php'; ?>
