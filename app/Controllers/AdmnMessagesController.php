<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
require_once(ROOT_PATH . '/classes/conn.php');
require_once ROOT_PATH . '/classes/main.class.php';

$systemObject = new BMISClass();
$userdetails = $systemObject->get_userdata();
$admin_name = isset($userdetails['fname']) ? $userdetails['fname'] . ' ' . $userdetails['lname'] : 'Admin';

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

// ---- Handle: Soft-delete a conversation ----
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

// ---- Handle: Reply ----
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
$id_uploads = $systemObject->getPendingIDUploads();
$pending_count = 0;
foreach ($id_uploads as $up) {
    if ($up['status'] === 'pending') $pending_count++;
}
?>
<?php require ROOT_PATH . '/app/Views/admn_messages_view.php'; ?>
