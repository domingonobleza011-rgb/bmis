<?php

class MessageController extends Controller
{
    public function adminMessages()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',1);
    
$systemObject = new BMISClass();
$userdetails = $systemObject->get_userdata();
$admin_name = !empty($userdetails['firstname']) ? trim($userdetails['firstname'] . ' ' . ($userdetails['surname'] ?? '')) : 'Admin';

// ---- Handle: Verify (approve) resident ----
if (isset($_POST['approve_resident'])) {
    $id_resident = (int)$_POST['id_resident'];
    $id_upload   = (int)$_POST['id_upload'];
    if ($systemObject->approveResidentVerification($id_resident, $id_upload, $admin_name)) {
        header("Location: admn_messages.php?toast=approved");
    } else {
        header("Location: admn_messages.php?toast=error");
    }
    exit();
}

// ---- Handle: Delete upload record ----
if (isset($_POST['delete_upload'])) {
    $id_upload = (int)$_POST['id_upload'];
    $systemObject->delete_upload_record($id_upload);
    header("Location: admn_messages.php?toast=upload_deleted");
    exit();
}

// ---- Handle: Reject resident ID ----
if (isset($_POST['reject_resident'])) {
    $id_resident = (int)$_POST['id_resident'];
    $id_upload   = (int)$_POST['id_upload'];
    $reason      = $_POST['reject_reason'] ?? '';
    if ($systemObject->rejectResidentVerification($id_resident, $id_upload, $admin_name, $reason)) {
        header("Location: admn_messages.php?toast=rejected");
    } else {
        header("Location: admn_messages.php?toast=error");
    }
    exit();
}

// ---- Handle: Soft-delete a conversation (moves it to Archive) ----
if (isset($_POST['delete_msg'])) {
    $id_resident = (int)($_POST['id_resident'] ?? 0);
    $systemObject->archiveConversation($id_resident, $admin_name);
    header("Location: admn_messages.php?toast=msg_deleted");
    exit();
}

// ---- Handle: Bulk soft-delete conversations ----
if (isset($_POST['bulk_delete_msg']) && !empty($_POST['msg_ids'])) {
    $ids = array_map('intval', $_POST['msg_ids']);
    foreach ($ids as $id_resident) {
        $systemObject->archiveConversation($id_resident, $admin_name);
    }
    header("Location: admn_messages.php?toast=msg_deleted");
    exit();
}

// ---- Handle: Reply to a resident's conversation ----
if (isset($_POST['reply_msg'])) {
    $id_resident = (int)($_POST['id_resident'] ?? 0);
    $reply_text  = trim($_POST['reply_text'] ?? '');
    if ($id_resident && $reply_text !== '' && $systemObject->replyToMessage($id_resident, $reply_text, $admin_name)) {
        header("Location: admn_messages.php?toast=reply_sent");
    } else {
        header("Location: admn_messages.php?toast=error");
    }
    exit();
}

// ---- Fetch data ----
$conversations = $systemObject->getConversations();
$id_uploads = [];
$id_uploads = $systemObject->getPendingIDUploads();

$pending_count = 0;
foreach ($id_uploads as $up) {
    if ($up['status'] === 'pending') $pending_count++;
}
?>
<?php 
        $this->view('message/adminMessages.php');

    }

    public function ajaxMessages()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * ajax_messages.php
 * Returns messages table rows + ID upload cards as JSON.
 * Called by admn_messages.php every 8 seconds via fetch().
 */
error_reporting(0);


header('Content-Type: application/json');

$systemObject = new BMISClass();
$userdetails  = $systemObject->get_userdata();
$admin_name   = !empty($userdetails['firstname']) ? trim($userdetails['firstname'] . ' ' . ($userdetails['surname'] ?? '')) : 'Admin';

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
<tr id="msgRow<?= $rid ?>">
    <td class="align-middle">
        <input type="checkbox" class="form-check-input msg-checkbox" value="<?= $rid ?>" onchange="updateBulkToolbar()">
    </td>
    <td class="align-middle fw-bold">
        <?= $mfull ?>
        <?php if ($conv['unread_count'] > 0): ?>
            <span class="badge bg-danger rounded-pill ms-1"><?= (int)$conv['unread_count'] ?></span>
        <?php endif; ?>
    </td>
    <td class="align-middle">
        <?= date('M d, Y | h:i A', strtotime($conv['last_date'])) ?>
    </td>
    <td class="align-middle">
        <button class="btn btn-info btn-sm rounded-pill px-3 fw-bold"
                data-bs-toggle="modal" data-bs-target="#viewMsg<?= $rid ?>">
            <i class="bi bi-eye-fill me-1"></i> View
        </button>
    </td>
    <td class="align-middle">
        <button type="button" class="btn btn-danger btn-sm rounded-pill px-3"
            onclick="openDeleteMsgModal(<?= $rid ?>, '<?= $mfname ?>')">
            <i class="bi bi-trash-fill me-1"></i> Delete
        </button>
    </td>
</tr>
<!-- View Conversation Modal - full chat thread for this resident -->
<div class="modal fade" id="viewMsg<?= $rid ?>" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header chat-thread-header text-white">
                <div class="d-flex align-items-center gap-2">
                    <div class="chat-avatar"><?= strtoupper(substr($mfname, 0, 1)) ?></div>
                    <div>
                        <h6 class="modal-title fw-bold mb-0"><?= $mfull ?></h6>
                        <small class="opacity-75">Resident</small>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 chat-thread-bg">
                <?php foreach ($thread as $bubble): ?>
                    <?php if ($bubble['side'] === 'resident'): ?>
                        <div class="chat-row mt-3">
                            <div class="chat-avatar chat-avatar-sm"><?= strtoupper(substr($mfname, 0, 1)) ?></div>
                            <div class="chat-bubble chat-bubble-incoming">
                                <?= nl2br(htmlspecialchars($bubble['message_text'])) ?>
                            </div>
                        </div>
                        <div class="chat-timestamp">
                            <?= date('F j, Y, g:i a', strtotime($bubble['date_sent'])) ?>
                        </div>
                    <?php else: ?>
                        <div class="chat-row chat-row-outgoing mt-3">
                            <div class="chat-bubble chat-bubble-outgoing">
                                <?= nl2br(htmlspecialchars($bubble['message_text'])) ?>
                            </div>
                        </div>
                        <div class="chat-timestamp chat-timestamp-outgoing">
                            <?= date('F j, Y, g:i a', strtotime($bubble['date_sent'])) ?>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="reply-box d-none mt-3" id="replyBox<?= $rid ?>">
                    <textarea class="form-control reply-textarea" id="replyText<?= $rid ?>"
                              rows="3" placeholder="Type your reply to <?= $mfname ?>..."></textarea>
                    <div class="d-flex justify-content-end gap-2 mt-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                onclick="toggleReplyBox(<?= $rid ?>, false)">Cancel</button>
                        <button type="button" class="btn btn-primary btn-sm rounded-pill px-3"
                                onclick="sendReply(<?= $rid ?>)">
                            <i class="bi bi-send-fill me-1"></i> Send
                        </button>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0" id="viewFooter<?= $rid ?>">
                <button type="button" class="btn btn-primary rounded-pill px-4"
                        id="replyBtn<?= $rid ?>"
                        onclick="toggleReplyBox(<?= $rid ?>, true)">
                    <i class="bi bi-reply-fill me-1"></i>
                    <?= ($lastMsg && $lastMsg['side'] === 'admin') ? 'Reply Again' : 'Reply' ?>
                </button>
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php
    endforeach;
else:
    echo '<tr><td colspan="5" class="text-center text-muted py-4">No messages found.</td></tr>';
endif;
$messages_html = ob_get_clean();

// ── Build ID uploads HTML ─────────────────────────────────────
ob_start();
foreach ($id_uploads as $up):
    $uid     = $up['id_upload'];
    $ufname  = htmlspecialchars($up['fname'] . ' ' . $up['lname']);
    $uemail  = htmlspecialchars($up['email'] ?? '');
    $uphone  = htmlspecialchars($up['phone_number'] ?? '');
    $utype   = htmlspecialchars($up['file_type'] ?? 'ID');
    $udate   = date('M d, Y g:i A', strtotime($up['upload_date']));
    $unote   = htmlspecialchars($up['message_note'] ?? '');
    $uresid  = $up['id_resident'];
?>
<div class="card mb-3 shadow-sm border-0 rounded-4" id="idUpload<?= $uid ?>">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
            <div>
                <h6 class="fw-bold mb-1"><?= $ufname ?></h6>
                <?php if ($uemail): ?><div class="text-muted small"><i class="bi bi-envelope me-1"></i><?= $uemail ?></div><?php endif; ?>
                <?php if ($uphone): ?><div class="text-muted small"><i class="bi bi-telephone me-1"></i><?= $uphone ?></div><?php endif; ?>
                <div class="text-muted small mt-1"><i class="bi bi-clock me-1"></i><?= $udate ?></div>
                <?php if ($unote): ?><div class="mt-2 text-secondary small"><i class="bi bi-chat-left-text me-1"></i><?= $unote ?></div><?php endif; ?>
            </div>
            <span class="badge bg-warning text-dark"><?= $utype ?></span>
        </div>
        <div class="mt-3 d-flex gap-2 flex-wrap">
            <form method="POST" class="d-inline">
                <input type="hidden" name="id_resident" value="<?= $uresid ?>">
                <input type="hidden" name="id_upload" value="<?= $uid ?>">
                <button type="submit" name="approve_resident" class="btn btn-success btn-sm rounded-pill px-3">
                    <i class="bi bi-check-circle me-1"></i> Approve
                </button>
            </form>
            <button class="btn btn-danger btn-sm rounded-pill px-3"
                    data-bs-toggle="modal" data-bs-target="#rejectModal<?= $uid ?>">
                <i class="bi bi-x-circle me-1"></i> Reject
            </button>
            <form method="POST" class="d-inline">
                <input type="hidden" name="id_upload" value="<?= $uid ?>">
                <button type="submit" name="delete_upload" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-trash me-1"></i> Delete
                </button>
            </form>
        </div>
    </div>
</div>
<!-- Reject Modal -->
<div class="modal fade" id="rejectModal<?= $uid ?>" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Reject ID Submission</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body p-4">
                    <p>Rejecting <strong><?= $ufname ?></strong>'s ID submission.</p>
                    <label class="form-label fw-semibold">Reason for rejection:</label>
                    <textarea name="reject_reason" class="form-control" rows="3" placeholder="e.g. Blurry image, incorrect document..."></textarea>
                    <input type="hidden" name="id_resident" value="<?= $uresid ?>">
                    <input type="hidden" name="id_upload" value="<?= $uid ?>">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="reject_resident" class="btn btn-danger px-4">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;
if (empty($id_uploads)) {
    echo '<p class="text-muted text-center py-3">No pending ID verifications.</p>';
}
$uploads_html = ob_get_clean();

echo json_encode([
    'messages_html'  => $messages_html,
    'uploads_html'   => $uploads_html,
    'msg_count'      => count($conversations),
    'pending_count'  => $pending_count,
]);

    }

    public function residentChat()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * ajax_resident_chat.php
 * Returns the logged-in resident's merged chat thread (admin replies + their own
 * sent messages) as an HTML fragment, plus a message count.
 * Called by resident_messages.php every few seconds via fetch() to auto-refresh
 * the conversation without a full page reload.
 */
error_reporting(0);


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
    <div class="chat-empty">
        <i class="bi bi-chat-dots" style="font-size:2.5rem; margin-bottom:10px;"></i>
        <div class="fw-semibold">No messages yet</div>
        <small>Send a message to the Barangay Administration below.</small>
    </div>
<?php
else:
    $prev_date = '';
    foreach ($all_messages as $msg):
        $this_date = date('F j, Y', strtotime($msg['date_sent']));
        if ($this_date !== $prev_date):
            $prev_date = $this_date;
?>
    <div class="chat-day-label">— <?= $this_date ?> —</div>
<?php
        endif;
        $side     = $msg['side'];
        $is_me    = ($side === 'me');
        $time_str = date('h:i A', strtotime($msg['date_sent']));
?>
    <div class="bubble-row <?= $is_me ? 'me' : 'admin' ?>">
        <div class="bubble-avatar <?= $is_me ? 'me-av' : 'admin-av' ?>">
            <?php if ($is_me): ?>
            <i class="bi bi-person-fill"></i>
            <?php else: ?>
            <img src="icons/logo.png" alt="Barangay San Pedro">
            <?php endif; ?>
        </div>
        <div class="bubble <?= $is_me ? 'me' : 'admin' ?>">
            <?= nl2br(htmlspecialchars($msg['message_text'])) ?>
            <span class="bubble-time"><?= $time_str ?></span>
        </div>
        <?php if (!$is_me && !empty($msg['id_msg'])): ?>
        <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">
            <input type="hidden" name="id_msg" value="<?= $msg['id_msg'] ?>">
            <button type="submit" name="delete_msg" class="bubble-delete" title="Delete">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </form>
        <?php endif; ?>
    </div>
<?php
    endforeach;
endif;
$chat_html = ob_get_clean();

echo json_encode([
    'chat_html' => $chat_html,
    'count'     => count($all_messages),
]);

    }

    public function deleteMessage()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$systemObject = new BMISClass();

if (isset($_POST['delete_msg'])) {
    $id = $_POST['id_admin_msg'];
    
    if ($systemObject->deleteMessage($id)) {
        // Redirect back to the messages page with a success message
        header("Location: admn_messages.php?status=deleted");
    } else {
        header("Location: admn_messages.php?status=error");
    }
    exit();
}

    }

    public function sendResidentMsg()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$main = new BMISClass(); 

if (isset($_POST['send_msg'])) {
    $id = $_POST['id_resident'];
    $msg = $_POST['message'];

    // Optional: return the admin to the page they sent the message from
    // (e.g. a certificate request table). Falls back to the resident list
    // to preserve existing behavior for callers that don't pass it.
    $redirect = $_POST['redirect_to'] ?? 'admn_resident_crud.php';
    if (!preg_match('/^[a-zA-Z0-9_\-]+\.php(\?[^\s]*)?$/', $redirect)) {
        $redirect = 'admn_resident_crud.php';
    }
    $separator = (strpos($redirect, '?') !== false) ? '&' : '?';

    if ($main->sendMessage($id, $msg)) {
        // Redirect with success
        header("Location: $redirect{$separator}status=sent");
    } else {
        // Redirect with error
        header("Location: $redirect{$separator}status=error");
    }
    exit();
}

    }

    public function residentMessages()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

$main = new BMISClass();

if (!isset($_SESSION['userdata']['id_resident'])) {
    header("Location: index.php");
    exit();
}

$userdetails = $_SESSION['userdata'];
$resident_id = $userdetails['id_resident']; 
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
$main->markResidentMessagesRead($resident_id);

// ---- Handle: Send message to admin ----
if (isset($_POST['send_to_admin'])) {
    $message_content = $_POST['admin_message_text'];
    if ($main->sendMessageToAdmin($resident_id, $message_content)) {
        echo "<script>window.location.href='resident_messages.php';</script>";
        exit();
    } else {
        echo "<script>alert('Error: Could not send message.');</script>";
    }
}

// ---- Handle: Delete admin->resident message ----
if (isset($_POST['delete_msg'])) {
    $id_msg = $_POST['id_msg']; 
    if ($main->deleteResidentMessage($id_msg)) { 
        echo "<script>window.location.href='resident_messages.php';</script>";
        exit();
    }
}

// ---- Handle: Upload Valid ID ----
if (isset($_POST['upload_valid_id'])) {
    $message_note = trim($_POST['id_note'] ?? '');
    $upload_error = '';

    if (!isset($_FILES['valid_id_file']) || $_FILES['valid_id_file']['error'] !== UPLOAD_ERR_OK) {
        $upload_error = 'Please select a valid file to upload.';
    } else {
        $file = $_FILES['valid_id_file'];
        $validated = bmis_validate_image_upload($file, /* allow_pdf */ true);

        if (!$validated['ok']) {
            $upload_error = $validated['msg'];
        } else {
            $upload_dir = 'uploads/valid_ids/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            $new_filename = 'validid_' . $resident_id . '_' . time() . '_' . $validated['safe_name'];
            $dest = $upload_dir . $new_filename;

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                if ($main->uploadValidID($resident_id, $new_filename, $file['name'], $validated['mime'], $message_note)) {
                    $main->sendMessageToAdmin($resident_id, "VALID ID SUBMITTED - Please verify my account. Note: " . ($message_note ?: 'none'));
                    echo "<script>alert('Your valid ID has been submitted! Please wait for admin approval.'); window.location.href='resident_messages.php';</script>";
                    exit();
                } else {
                    $upload_error = 'Could not save the upload record. Please try again.';
                }
            } else {
                $upload_error = 'File upload failed. Please try again.';
            }
        }
    }
    if ($upload_error) {
        echo "<script>alert('Upload Error: " . addslashes($upload_error) . "');</script>";
    }
}

// Fetch messages: from admin to resident
$admin_to_resident = $main->getResidentMessages($resident_id);

$id_uploads = $main->getResidentIDUploads($resident_id);

$has_pending = false;
$has_approved = false;
foreach ($id_uploads as $up) {
    if ($up['status'] === 'pending') $has_pending = true;
    if ($up['status'] === 'approved') $has_approved = true;
}

$auto_open_upload = isset($_GET['upload_id']) && $_GET['upload_id'] == 1;

// Build unified chat thread: merge both message arrays with a 'side' marker
$resident_sent_msgs = [];
try {
    $pdo = $main->openConn();
    $stmt = $pdo->prepare("SELECT id_admin_msg AS id_msg, message_text, date_sent, 'me' AS side FROM admin_messages WHERE id_resident = ? ORDER BY date_sent ASC");
    $stmt->execute([$resident_id]);
    $resident_sent_msgs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // silently fail; only admin replies will show
}

// Tag admin messages with side
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

// Merge & sort by date ascending
$all_messages = array_merge($admin_msgs_tagged, $resident_sent_msgs);
usort($all_messages, fn($a, $b) => strtotime($a['date_sent']) <=> strtotime($b['date_sent']));
        $this->view('message/residentMessages.php');

    }

    public function markNotificationRead()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * mark_notification_read.php
 * Marks resident->admin message notifications as read.
 * Called from the notification bell dropdown (dashboard_sidebar_start.php).
 */
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 0);


// Only allow a same-app relative page (e.g. "admn_dashboard.php" or "admn_messages.php?foo=1")
// as the redirect target, to avoid open-redirect issues.
$redirect = $_POST['redirect_to'] ?? 'admn_dashboard.php';
if (!preg_match('/^[a-zA-Z0-9_\-]+\.php(\?[^\s]*)?$/', $redirect)) {
    $redirect = 'admn_dashboard.php';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['mark_all_read'])) {
            // Mark every unread resident message as read
            $conn->exec("UPDATE admin_messages SET status = 'read' WHERE status = 'unread'");
        } elseif (isset($_POST['mark_read_id'])) {
            // Mark a single message as read
            $id = (int) $_POST['mark_read_id'];
            $stmt = $conn->prepare("UPDATE admin_messages SET status = 'read' WHERE id_admin_msg = ?");
            $stmt->execute([$id]);
        }
    } catch (Throwable $e) {
        // Fail silently — the notification will simply still appear as unread.
        error_log('mark_notification_read error: ' . $e->getMessage());
    }
}

header("Location: $redirect");
exit();

    }

}
