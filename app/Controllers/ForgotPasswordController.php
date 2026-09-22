<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);
require_once ROOT_PATH . '/classes/security.php';
require_once ROOT_PATH . '/classes/mailer.php';
bmis_session_start();
date_default_timezone_set('Asia/Manila');
include(ROOT_PATH . '/autoloader.php');
require(ROOT_PATH . '/classes/conn.php');

$step    = $_GET['step'] ?? 'identify';
$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['find_account'])) {
    bmis_verify_csrf();
    $identity = trim($_POST['identity']);
    $stmt = $conn->prepare(
        "SELECT id_resident, fname, lname, security_question, security_answer
         FROM tbl_resident
         WHERE (email = ? OR phone_number = ?) LIMIT 1"
    );
    $stmt->execute([$identity, $identity]);
    $resident = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$resident) {
        $error = 'No account found with that email or phone number.';
    } elseif (empty($resident['security_question'])) {
        $_SESSION['reset_id']   = $resident['id_resident'];
        $_SESSION['reset_name'] = $resident['fname'] . ' ' . $resident['lname'];
        header('Location: forgot_password.php?step=admin_request');
        exit;
    } else {
        $_SESSION['reset_id']       = $resident['id_resident'];
        $_SESSION['reset_name']     = $resident['fname'] . ' ' . $resident['lname'];
        $_SESSION['reset_question'] = $resident['security_question'];
        $_SESSION['reset_answer']   = $resident['security_answer'];
        header('Location: forgot_password.php?step=security_question');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_answer'])) {
    bmis_verify_csrf();
    if (empty($_SESSION['reset_id'])) { header('Location: forgot_password.php'); exit; }
    $answer = strtolower(trim($_POST['security_answer']));
    $stored = strtolower(trim($_SESSION['reset_answer'] ?? ''));
    if ($answer !== $stored) {
        $error = 'Incorrect answer. Please try again.';
        $step  = 'security_question';
    } else {
        $_SESSION['reset_verified'] = true;
        header('Location: forgot_password.php?step=new_password');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_password'])) {
    bmis_verify_csrf();
    if (empty($_SESSION['reset_verified']) || empty($_SESSION['reset_id'])) {
        header('Location: forgot_password.php'); exit;
    }
    $new  = $_POST['new_password'];
    $conf = $_POST['confirm_password'];
    if (strlen($new) < 8) {
        $error = 'Password must be at least 8 characters.'; $step = 'new_password';
    } elseif ($new !== $conf) {
        $error = 'Passwords do not match.'; $step = 'new_password';
    } else {
        $hashed = password_hash($new, PASSWORD_DEFAULT);
        $conn->prepare("UPDATE tbl_resident SET password = ? WHERE id_resident = ?")
             ->execute([$hashed, $_SESSION['reset_id']]);
        unset($_SESSION['reset_id'], $_SESSION['reset_name'], $_SESSION['reset_question'],
              $_SESSION['reset_answer'], $_SESSION['reset_verified']);
        $success = 'Password changed successfully! You may now log in.';
        $step    = 'done';
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_admin_reset'])) {
    bmis_verify_csrf();
    if (empty($_SESSION['reset_id'])) { header('Location: forgot_password.php'); exit; }
    $id = $_SESSION['reset_id'];
    $check = $conn->prepare("SELECT id FROM tbl_password_reset_requests WHERE id_resident = ? AND status = 'pending' LIMIT 1");
    $check->execute([$id]);
    if ($check->fetch()) {
        $success = 'You already have a pending reset request. Please wait for admin approval.';
    } else {
        $conn->prepare(
            "INSERT INTO tbl_password_reset_requests (id_resident, full_name, phone_number)
             SELECT ?, CONCAT(fname,' ',lname), phone_number FROM tbl_resident WHERE id_resident = ?"
        )->execute([$id, $id]);

        if (function_exists('bmis_notify_admins_new_password_reset')) {
            $reset_name = $_SESSION['reset_name'] ?? 'A resident';
            bmis_notify_admins_new_password_reset($conn, $reset_name);
        }

        unset($_SESSION['reset_id'], $_SESSION['reset_name']);
        $success = 'Reset request submitted! Please visit the barangay hall or wait for admin to contact you.';
    }
    $step = 'done';
}

if (empty($step) || $step === 'identify') {
    $step = $_GET['step'] ?? 'identify';
}
?>
<?php require ROOT_PATH . '/app/Views/forgot_password_view.php'; ?>
