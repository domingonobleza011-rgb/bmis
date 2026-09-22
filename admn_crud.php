<?php
define('BMIS_ROLE_REQUIRED', 'staff');
require_once __DIR__ . '/app/Core/bootstrap.php';
(new AdminStaffController())->crud();
