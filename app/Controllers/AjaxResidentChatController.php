<?php
/**
 * ajax_resident_chat.php
 * Returns the logged-in resident's merged chat thread (admin replies + their own
 * sent messages) as an HTML fragment, plus a message count.
 * Called by resident_messages.php every few seconds via fetch() to auto-refresh
 * the conversation without a full page reload.
 */
error_reporting(0);
define('BMIS_ROLE_REQUIRED', 'resident');
require_once(ROOT_PATH . '/secure_header.php');
require_once ROOT_PATH . '/classes/main.class.php';

header('Content-Type: application/json');

if (!isset($_SESSION['userdata']['id_resident'])) {
    echo json_encode(['error' => 'not_logged_in']);
    exit();
}

$main        = new BMISClass();
$resident_id = $_SESSION['userdata']['id_resident'];

// Viewing the thread counts as reading it — keeps the homepage badge in sync
// even when new replies arrive while this page is already open.
$main->markResidentMessagesRead($resident_id);

// Same merge logic as resident_messages.php: admin_to_resident (resident_messages
// table, side='admin') + resident_to_admin (admin_messages table, side='me').
$admin_to_resident = $main->getResidentMessages($resident_id);

$resident_sent_msgs = [];
try {
    $pdo  = $main->openConn();
    $stmt = $pdo->prepare("SELECT id_admin_msg AS id_msg, message_text, date_sent, 'me' AS side FROM admin_messages WHERE id_resident = ? ORDER BY date_sent ASC");
    $stmt->execute([$resident_id]);
    $resident_sent_msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // silently fail; only admin replies will show
}

$admin_msgs_tagged = [];
foreach ($admin_to_resident as $m) {
    $actual_id = isset($m['id_msg']) ? $m['id_msg'] : (isset($m['id_message']) ? $m['id_message'] : null);
    $admin_msgs_tagged[] = [
        'id_msg'       => $actual_id,
        'message_text' => $m['message_text'],
        'date_sent'    => $m['date_sent'],
        'side'         => 'admin'
    ];
}

$all_messages = array_merge($admin_msgs_tagged, $resident_sent_msgs);
usort($all_messages, fn($a, $b) => strtotime($a['date_sent']) <=> strtotime($b['date_sent']));

// ── Build the same bubble markup resident_messages.php uses ──────────────────
ob_start();
if (empty($all_messages)):
?>
<?php require ROOT_PATH . '/app/Views/ajax_resident_chat_view.php'; ?>
