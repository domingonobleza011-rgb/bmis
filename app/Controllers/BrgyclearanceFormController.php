<?php
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
// ── FIX: this form previously called get_single_bspermit() and rendered a
// Business Permit here (copy-paste from businesspermit_form.php). A Barangay
// Clearance has its own table/data — use that instead. ──────────────────────
$id_clearance = $_GET['id_clearance'];
$resident = $residentbmis->get_single_clearance($id_clearance);
// The certificate row itself carries which resident it belongs to;
// derive $id_resident from the fetched record (used below for logging/SMS).
$id_resident = $resident['id_resident'] ?? 0;

// ── Activity Log: Barangay Clearance Generated ───────────────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Barangay Clearance',
        "Generated Barangay Clearance for {$resident_name} (Resident ID: {$id_resident})"
    );
    $residentbmis->mark_document_generated('tbl_clearance', 'id_clearance', $id_clearance);

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
  ?>
<?php require ROOT_PATH . '/app/Views/brgyclearance_form_view.php'; ?>
