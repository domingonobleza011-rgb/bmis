<?php
/**
 * admn_activity_logs_delete.php
 * Accepts a JSON POST: { "ids": [1, 2, 3, …] }
 * Returns:            { "success": true }  or  { "success": false, "message": "…" }
 */
require(ROOT_PATH . '/secure_header.php');
require(ROOT_PATH . '/classes/main.class.php');
require_once(ROOT_PATH . '/classes/conn.php');

header('Content-Type: application/json');

// Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Decode body
$body = json_decode(file_get_contents('php://input'), true);
$ids  = $body['ids'] ?? [];

// Validate
if (empty($ids) || !is_array($ids)) {
    echo json_encode(['success' => false, 'message' => 'No IDs provided.']);
    exit;
}

// Sanitise – keep only positive integers
$ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);

if (empty($ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid IDs.']);
    exit;
}

// ---- Run the delete via your existing class ----
// Option A – if your class exposes a method:
//   $result = $bmis->delete_activity_logs($ids);

// Option B – raw PDO query (replace $bmis->pdo with your actual connection property):
try {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // ── Protect certificate/document issuance records ──────────────────
    // GENERATE_DOCUMENT rows feed the "Certificates Issued" dashboard
    // chart and must never be removable through this endpoint either.
    $protectStmt = $conn->prepare("SELECT id_log FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action = 'GENERATE_DOCUMENT'");
    $protectStmt->execute(array_values($ids));
    $protectedIds = array_map('intval', $protectStmt->fetchAll(PDO::FETCH_COLUMN));

    $stmt = $conn->prepare("DELETE FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action != 'GENERATE_DOCUMENT'");
    $stmt->execute(array_values($ids));

    echo json_encode([
        'success'       => true,
        'deleted'       => $stmt->rowCount(),
        'protected'     => count($protectedIds),
        'protected_ids' => $protectedIds,
    ]);
} catch (Exception $e) {
    // Log internally; never expose raw DB errors to the client
    error_log('Bulk delete error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}