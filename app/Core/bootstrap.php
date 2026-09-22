<?php
/**
 * Application bootstrap.
 *
 * Every root-level entry file (the old admn_dashboard.php,
 * resident_homepage.php, etc.) is now a thin stub that does:
 *
 *     define('BMIS_ROLE_REQUIRED', '...');   // only on pages that need a guard
 *     require_once __DIR__ . '/app/Core/bootstrap.php';
 *     (new SomeController())->someAction();
 *
 * This file reproduces exactly what the old secure_header.php +
 * classes/conn.php + include('classes/xxx.class.php') combo used to
 * do on every page, in one place, so behaviour is unchanged.
 */

define('APP_PATH', __DIR__ . '/..');
define('ROOT_PATH', __DIR__ . '/../..');

require_once __DIR__ . '/Security.php';
require_once __DIR__ . '/Database.php'; // also sets $conn (lazy)
require_once __DIR__ . '/Controller.php';

// Autoload Models and Controllers by class name.
spl_autoload_register(function ($class) {
    foreach (['Models', 'Controllers'] as $dir) {
        $file = APP_PATH . "/$dir/$class.php";
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

bmis_session_start();
bmis_set_security_headers();

// ── Role guard ──────────────────────────────────────────────────────
// Mirrors secure_header.php: a page opts into the guard by defining
// BMIS_ROLE_REQUIRED before requiring this file. Pages that never
// included secure_header.php originally (public pages like login.php,
// index.php, forgot_password.php...) simply never define the constant,
// so no guard runs here either — identical to before.
$userdetails = null;
if (defined('BMIS_ROLE_REQUIRED')) {
    switch (BMIS_ROLE_REQUIRED) {
        case 'admin_dashboard': // Admin + Secretary + Punong Barangay
            $userdetails = bmis_require_admin_dashboard();
            break;
        case 'staff':
            $userdetails = bmis_require_staff_or_admin();
            if (($userdetails['role'] ?? '') === 'user' && ($userdetails['position'] ?? '') === 'Sk Chairperson') {
                http_response_code(403);
                die('Access denied. SK Chairperson can only access the SK Dashboard.');
            }
            break;
        case 'resident':
            $userdetails = bmis_require_resident();
            break;
        case 'any':
            $userdetails = bmis_require_login();
            break;
        default: // 'admin'
            $userdetails = bmis_require_admin();
            break;
    }
}

// ── Models (replaces include('classes/resident.class.php') etc.) ───
require_once APP_PATH . '/Models/BMISModel.php';
require_once APP_PATH . '/Models/ResidentModel.php';
require_once APP_PATH . '/Models/StaffModel.php';

$bmis         = new BMISClass();
$residentbmis = new ResidentClass();
$staffbmis    = new StaffClass();
