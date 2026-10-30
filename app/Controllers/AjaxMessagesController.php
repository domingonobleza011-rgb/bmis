<?php
/**
 * ajax_messages.php
 * Returns the chat list + thread panes as JSON for the admin Messages page.
 * Called by admn_messages.php every 60 seconds via fetch().
 */
error_reporting(0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
require_once ROOT_PATH . '/classes/main.class.php';

header('Content-Type: application/json');

$systemObject = new BMISClass();
$conversations = $systemObject->getConversations();
$id_uploads    = $systemObject->getPendingIDUploads();

$pending_count = 0;
foreach ($id_uploads as $up) {
    if ($up['status'] === 'pending') $pending_count++;
}

// Same markup as admn_messages_view.php (shared partial) — list items + thread panes.
$items_html = '';
$panes_html = '';
foreach ($conversations as $conv) {
    $thread  = $systemObject->getConversationThread($conv['id_resident']);
    $lastMsg = end($thread);

    $part = 'item';
    ob_start(); require ROOT_PATH . '/app/Views/admn_messages_parts.php'; $items_html .= ob_get_clean();

    $part = 'pane';
    ob_start(); require ROOT_PATH . '/app/Views/admn_messages_parts.php'; $panes_html .= ob_get_clean();
}

echo json_encode([
    'messages_html' => $items_html,
    'threads_html'  => $panes_html,
    'msg_count'     => count($conversations),
    'pending_count' => $pending_count,
]);
