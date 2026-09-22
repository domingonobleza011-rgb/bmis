<?php
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $residentbmis->get_userdata();
$id_soloparent = $_GET['id_soloparent'];
$resident = $residentbmis->get_single_soloparent($id_soloparent);
// The certificate row itself carries which resident it belongs to;
// derive $id_resident from the fetched record (used below for logging/SMS).
$id_resident = $resident['id_resident'] ?? 0;

// ── Activity Log: Solo Parent Certification Generated ───────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Solo Parent Certification',
        "Generated Solo Parent Certification for {$resident_name} (Resident ID: {$id_resident})"
    );
    $residentbmis->mark_document_generated('tbl_soloparent', 'id_soloparent', $id_soloparent);

    // ── Auto-notify resident: Solo Parent Certification generated ───────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Solo Parent Certification has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────

// Parse the "children" textarea (one child per line, e.g. "Juan Dela Cruz - 8 years old")
$children_lines = [];
if (!empty($resident['children'])) {
    foreach (preg_split('/\r\n|\r|\n/', trim($resident['children'])) as $line) {
        $line = trim($line);
        if ($line !== '') { $children_lines[] = $line; }
    }
}
  ?>
<?php require ROOT_PATH . '/app/Views/soloparent_form_view.php'; ?>
