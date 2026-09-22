<?php
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirectUrl, true, 301);
    exit;
}
header('Permissions-Policy: camera=(self)');
require(ROOT_PATH . '/classes/resident.class.php');
$residentbmis->create_resident();
?>
<?php require ROOT_PATH . '/app/Views/resident_registration_view.php'; ?>
