<?php
/**
 * ajax_messages.php
 * Returns messages table rows + ID upload cards as JSON.
 * Called by admn_messages.php every 8 seconds via fetch().
 */
error_reporting(0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
require_once ROOT_PATH . '/classes/main.class.php';

header('Content-Type: application/json');

$systemObject = new BMISClass();
$userdetails  = $systemObject->get_userdata();
$admin_name   = isset($userdetails['fname']) ? $userdetails['fname'] . ' ' . $userdetails['lname'] : 'Admin';

$conversations = $systemObject->getConversations();
$id_uploads    = $systemObject->getPendingIDUploads();

$pending_count = 0;
foreach ($id_uploads as $up) {
    if ($up['status'] === 'pending') $pending_count++;
}

// ── Build messages table rows HTML (one row per resident conversation) ────
// Kept in sync with admn_messages.php's table + modal markup — if you change
// one, change the other, or the 8s/60s poll will revert this view.
ob_start();
if (!empty($conversations)):
    foreach ($conversations as $conv):
        $rid     = $conv['id_resident'];
        $mfname  = htmlspecialchars($conv['fname']);
        $mfull   = htmlspecialchars($conv['fname'] . ' ' . $conv['lname']);
        $thread  = $systemObject->getConversationThread($rid);
        $lastMsg = end($thread);
?>
<?php require ROOT_PATH . '/app/Views/ajax_messages_view.php'; ?>
