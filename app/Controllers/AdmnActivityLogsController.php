<?php
// ⚠️ ABSOLUTELY NOTHING before this line – no spaces, no BOM
session_start();
require(ROOT_PATH . '/classes/main.class.php');
require_once(ROOT_PATH . '/classes/conn.php');

$userdetails = $bmis->get_userdata();

// ── FLEXIBLE ADMIN CHECK ─────────────────────────────────────────────
$admin_roles = ['admin', 'Administrator', 'Admin', 'ADMIN', 'administrator'];
$user_role   = $userdetails['role'] ?? '';
$is_admin    = in_array($user_role, $admin_roles);

// Staff ('user' role, except SK Chairperson) have the same access as admins.
$is_staff = ($user_role === 'user') && (($userdetails['position'] ?? '') !== 'Sk Chairperson');
if ($is_staff) { $is_admin = true; }

if (!$is_admin) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
        exit;
    }
    http_response_code(403);
    require(ROOT_PATH . '/403.php');
    exit;
}

// ── CSRF TOKEN ────────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ── POST HANDLER ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $body = json_decode(file_get_contents('php://input'), true);

    // CSRF check (shared by all POST actions)
    if (!isset($body['csrf_token']) || $body['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'CSRF token mismatch.']);
        exit;
    }

    $action = $body['action'] ?? '';

    // ── BULK DELETE (by checkbox IDs) ─────────────────────────────────
    if ($action === 'bulk_delete') {
        $ids_raw = $body['ids'] ?? [];
        if (!is_array($ids_raw)) {
            echo json_encode(['success' => false, 'message' => 'Invalid IDs format.']);
            exit;
        }
        $ids = array_filter(array_map('intval', $ids_raw), fn($id) => $id > 0);
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'No valid IDs provided.']);
            exit;
        }
        try {
            $conn = $bmis->openConn();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // ── Protect certificate/document issuance records ──────────────
            // GENERATE_DOCUMENT rows feed the "Certificates Issued" dashboard
            // chart. They are never deletable from this page, even if the
            // admin selects them — the chart depends on them permanently.
            $protectStmt = $conn->prepare("SELECT id_log FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action = 'GENERATE_DOCUMENT'");
            $protectStmt->execute(array_values($ids));
            $protectedIds = array_map('intval', $protectStmt->fetchAll(PDO::FETCH_COLUMN));

            $stmt = $conn->prepare("DELETE FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action != 'GENERATE_DOCUMENT'");
            $stmt->execute(array_values($ids));
            $deleted = $stmt->rowCount();
            $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log");
            $countStmt->execute();
            $newTotal = (int)$countStmt->fetchColumn();
            echo json_encode([
                'success'       => true,
                'deleted'       => $deleted,
                'protected'     => count($protectedIds),
                'protected_ids' => $protectedIds,
                'new_total'     => $newTotal
            ]);
        } catch (Exception $e) {
            error_log('Bulk delete error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    // ── RANGE COUNT (preview how many will be deleted) ─────────────────
    if ($action === 'range_count') {
        $range = $body['range'] ?? '';
        $cutoff = getRangeCutoff($range);
        if (!$cutoff) {
            echo json_encode(['success' => false, 'message' => 'Invalid range.']);
            exit;
        }
        try {
            $conn  = $bmis->openConn();
            $stmt  = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE created_at < ? AND action != 'GENERATE_DOCUMENT'");
            $stmt->execute([$cutoff]);
            $count = (int)$stmt->fetchColumn();

            // How many protected certificate-issuance rows fall in this range too
            // (shown to the admin so the preview count isn't a mystery, but they
            // are never included in $count / the eventual delete).
            $protectStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE created_at < ? AND action = 'GENERATE_DOCUMENT'");
            $protectStmt->execute([$cutoff]);
            $protectedCount = (int)$protectStmt->fetchColumn();

            echo json_encode(['success' => true, 'count' => $count, 'protected' => $protectedCount, 'cutoff' => $cutoff]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    // ── RANGE DELETE ───────────────────────────────────────────────────
    if ($action === 'delete_by_range') {
        $range = $body['range'] ?? '';
        $cutoff = getRangeCutoff($range);
        if (!$cutoff) {
            echo json_encode(['success' => false, 'message' => 'Invalid range.']);
            exit;
        }
        try {
            $conn  = $bmis->openConn();

            $protectStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE created_at < ? AND action = 'GENERATE_DOCUMENT'");
            $protectStmt->execute([$cutoff]);
            $protectedCount = (int)$protectStmt->fetchColumn();

            // Certificate/document issuance records are never purged, regardless
            // of age — they're the permanent source data for the dashboard chart.
            $stmt  = $conn->prepare("DELETE FROM tbl_activity_log WHERE created_at < ? AND action != 'GENERATE_DOCUMENT'");
            $stmt->execute([$cutoff]);
            $deleted = $stmt->rowCount();
            $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log");
            $countStmt->execute();
            $newTotal = (int)$countStmt->fetchColumn();
            echo json_encode([
                'success'   => true,
                'deleted'   => $deleted,
                'protected' => $protectedCount,
                'new_total' => $newTotal
            ]);
        } catch (Exception $e) {
            error_log('Range delete error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
    exit;
}

// ── HELPER: compute cutoff date from range key ────────────────────────
function getRangeCutoff(string $range): string|false {
    $now = new DateTime();
    return match($range) {
        'week'    => (clone $now)->modify('-1 week')->format('Y-m-d H:i:s'),
        'month'   => (clone $now)->modify('-1 month')->format('Y-m-d H:i:s'),
        '3months' => (clone $now)->modify('-3 months')->format('Y-m-d H:i:s'),
        '6months' => (clone $now)->modify('-6 months')->format('Y-m-d H:i:s'),
        'year'    => (clone $now)->modify('-1 year')->format('Y-m-d H:i:s'),
        default   => false,
    };
}

// ── GET: pagination & filters ─────────────────────────────────────────
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = 10;
$filters  = [
    'search'    => trim($_GET['search']    ?? ''),
    'module'    => trim($_GET['module']    ?? ''),
    'date_from' => trim($_GET['date_from'] ?? ''),
    'date_to'   => trim($_GET['date_to']   ?? ''),
];

$result      = $bmis->get_activity_logs($filters, $page, $per_page);
$logs        = $result['rows'];
$total       = $result['total'];
$total_pages = max(1, (int)ceil($total / $per_page));
?>
<?php require ROOT_PATH . '/app/Views/admn_activity_logs_view.php'; ?>
