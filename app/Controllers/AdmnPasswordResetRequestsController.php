<?php
/**
 * admn_password_reset_requests.php
 * Admin page: view, approve, or reject resident password reset requests.
 */
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

require_once ROOT_PATH . '/classes/security.php';
bmis_session_start();
$userdetails = bmis_require_staff_or_admin();
date_default_timezone_set('Asia/Manila');
include(ROOT_PATH . '/autoloader.php');
require(ROOT_PATH . '/classes/conn.php');

$error   = '';
$success = '';

// ── Approve: generate temp password, update resident, mark resolved ──────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_reset'])) {
    bmis_verify_csrf();
    $req_id      = (int) $_POST['req_id'];
    $id_resident = (int) $_POST['id_resident'];

    $temp = 'BrgyReset@' . rand(1000, 9999);
    $hash = password_hash($temp, PASSWORD_DEFAULT);

    $conn->prepare("UPDATE tbl_resident SET password = ?, must_change_password = 1 WHERE id_resident = ?")
         ->execute([$hash, $id_resident]);

    $conn->prepare(
        "UPDATE tbl_password_reset_requests
         SET status='approved', temp_password=?, resolved_at=NOW(), resolved_by=?
         WHERE id=?"
    )->execute([$temp, $userdetails['fname'] . ' ' . $userdetails['lname'], $req_id]);

    $success = "Approved. Temporary password set to: <strong>" . htmlspecialchars($temp) . "</strong> — give this to the resident in person.";
}

// ── Reject ───────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_reset'])) {
    bmis_verify_csrf();
    $req_id = (int) $_POST['req_id'];
    $conn->prepare(
        "UPDATE tbl_password_reset_requests SET status='rejected', resolved_at=NOW(), resolved_by=? WHERE id=?"
    )->execute([$userdetails['fname'] . ' ' . $userdetails['lname'], $req_id]);
    $success = 'Request rejected.';
}

// ── Delete ───────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reset'])) {
    bmis_verify_csrf();
    $req_id = (int) $_POST['req_id'];
    $conn->prepare("DELETE FROM tbl_password_reset_requests WHERE id = ? AND status != 'pending'")
         ->execute([$req_id]);
    $success = 'Request deleted.';
}

// ── Fetch all requests ───────────────────────────────────────────────────────
$requests = $conn->query(
    "SELECT r.*, res.email, COALESCE(res.phone_number, r.phone_number) AS phone_number
     FROM tbl_password_reset_requests r
     LEFT JOIN tbl_resident res ON res.id_resident = r.id_resident
     ORDER BY FIELD(r.status,'pending','approved','rejected'), r.requested_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$pendingCount = count(array_filter($requests, fn($r) => $r['status'] === 'pending'));
?>
<?php require ROOT_PATH . '/app/Views/admn_password_reset_requests_view.php'; ?>
