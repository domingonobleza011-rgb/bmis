<?php 
    require_once(ROOT_PATH . '/classes/main.class.php');
    $bmis->logout();

    // Make sure logout.php itself is never cached/bfcache'd either.
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");
    header("Expires: 0");

    header("Location: login.php");
    exit();
?>