<?php
/**
 * Switch an admin / staff / SK Chairperson session into their own RESIDENT
 * account (matched by the same email or phone number), and back again.
 *
 * Safety rules:
 *  - POST + CSRF only.
 *  - "to_resident" only from an admin/staff session; the account row is
 *    re-read from the DB (identity is never taken from the request).
 *  - The original session data is kept server-side in $_SESSION['switch_origin'];
 *    "to_origin" only works if that exists, so a resident can never "switch up".
 *  - Logout destroys the whole session, so nothing lingers.
 */
require_once ROOT_PATH . '/classes/security.php';
bmis_session_start();
bmis_set_security_headers();
require_once ROOT_PATH . '/classes/conn.php';
require_once ROOT_PATH . '/classes/main.class.php';
$bmis = new BMISClass();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }
bmis_verify_csrf();

$u = $_SESSION['userdata'] ?? null;
if (!$u) { header('Location: index.php'); exit; }

function sw_home(array $u): string {
    if (($u['position'] ?? '') === 'Sk Chairperson' && ($u['role'] ?? '') === 'user') return 'sk_dashboard.php';
    return 'admn_dashboard.php';
}
function sw_account_page(array $u): string {
    return (($u['position'] ?? '') === 'Sk Chairperson' && ($u['role'] ?? '') === 'user') ? 'sk_myaccount.php' : 'admn_myaccount.php';
}

$action = $_POST['action'] ?? '';

if ($action === 'to_resident') {
    $role = $u['role'] ?? '';
    if (!in_array($role, ['administrator', 'Admin', 'user'], true) || !empty($_SESSION['switch_origin'])) {
        http_response_code(403); die('Access denied.');
    }
    if (!empty($u['id_admin']))     { $table = 'tbl_admin'; $idcol = 'id_admin'; $id = (int)$u['id_admin']; }
    elseif (!empty($u['id_user']))  { $table = 'tbl_user';  $idcol = 'id_user';  $id = (int)$u['id_user']; }
    else { http_response_code(403); die('Access denied.'); }

    $st = $conn->prepare("SELECT email, phone_number FROM `$table` WHERE `$idcol` = ?");
    $st->execute([$id]);
    $me = $st->fetch(PDO::FETCH_ASSOC) ?: [];
    $email = trim((string)($me['email'] ?? ''));
    $phone = trim((string)($me['phone_number'] ?? ''));

    $resident = null;
    if ($email !== '' || $phone !== '') {
        $st = $conn->prepare("SELECT * FROM tbl_resident
            WHERE role = 'resident' AND (is_archived = 0 OR is_archived IS NULL)
              AND ((email IS NOT NULL AND email <> '' AND email = ?) OR (phone_number IS NOT NULL AND phone_number <> '' AND phone_number = ?))
            LIMIT 1");
        $st->execute([$email, $phone]);
        $resident = $st->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    if (!$resident) {
        $_SESSION['acct_flash'] = ['ok' => false, 'msg' => 'No resident account is linked to your email / phone number.'];
        header('Location: ' . sw_account_page($u));
        exit;
    }

    $bmis->log_activity('SWITCH_Portal', 'Account', 'Switched to own resident portal');
    $_SESSION['switch_origin'] = $u;
    $bmis->set_userdata($resident);
    bmis_regenerate_session();
    header('Location: resident_homepage.php');
    exit;
}

if ($action === 'to_origin') {
    $origin = $_SESSION['switch_origin'] ?? null;
    if (!$origin || !is_array($origin)) { header('Location: index.php'); exit; }

    $_SESSION['userdata'] = $origin;
    unset($_SESSION['switch_origin']);
    bmis_regenerate_session();
    $bmis->log_activity('SWITCH_Portal', 'Account', 'Switched back from resident portal');
    header('Location: ' . sw_home($origin));
    exit;
}

http_response_code(400);
die('Bad request.');
