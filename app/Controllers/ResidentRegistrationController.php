<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirectUrl, true, 301);
    exit;
}
header('Permissions-Policy: camera=(self)');
require(ROOT_PATH . '/classes/resident.class.php');
require_once(ROOT_PATH . '/classes/turnstile.php');

// Cloudflare Turnstile: block the registration if the CAPTCHA wasn't passed.
$turnstile_error = '';
if (isset($_POST['add_resident'])) {
    $cap = bmis_verify_turnstile();
    if (!$cap['ok']) {
        $turnstile_error = $cap['error'];
        unset($_POST['add_resident']);
    }
}
$residentbmis->create_resident();
?>
<?php require ROOT_PATH . '/app/Views/resident_registration_view.php'; ?>
