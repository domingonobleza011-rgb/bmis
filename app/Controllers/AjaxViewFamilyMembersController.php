<?php
/**
 * ajax_view_family_members.php
 * Returns an HTML fragment (a small table) listing the family members a
 * household head has added via resident_profile.php. Loaded into the
 * "View" modal on admn_table_totalhouse.php.
 */
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require_once(ROOT_PATH . '/secure_header.php');
require_once(ROOT_PATH . '/classes/resident.class.php');

header('Content-Type: text/html; charset=utf-8');

$id_household = isset($_GET['id_resident']) ? (int)$_GET['id_resident'] : 0;

if ($id_household <= 0) {
    echo '<div class="text-danger text-center py-3">Invalid household.</div>';
    exit;
}

$members = $residentbmis->view_family_members($id_household);

if (empty($members)) {
    echo '<div class="text-center text-muted py-4">
            <i class="fas fa-users" style="font-size:2rem;"></i>
            <p class="mb-0 mt-2">This household has no family members listed yet.</p>
          </div>';
    exit;
}
?>
<?php require ROOT_PATH . '/app/Views/ajax_view_family_members_view.php'; ?>
