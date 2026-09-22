<?php
define('BMIS_ROLE_REQUIRED', 'resident');
require_once(ROOT_PATH . '/secure_header.php'); 
require_once ROOT_PATH . '/classes/main.class.php';
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
?>
<?php require ROOT_PATH . '/app/Views/resident_messages_view.php'; ?>
