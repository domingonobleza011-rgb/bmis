<?php
error_reporting(E_ALL ^ E_WARNING);
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php');
require(ROOT_PATH . '/classes/resident.class.php');
ini_set('display_errors',0);
$userdetails = $residentbmis->get_userdata();
// Always the logged-in resident's own record — ignore any id_resident in
// the URL so one resident can never view or edit another's profile.
$id_resident = $userdetails['id_resident'] ?? 0;
$resident = $residentbmis->get_single_resident($id_resident);
$residentbmis->profile_update();
$is_verified = $residentbmis->isResidentVerified($userdetails['id_resident']);
// ── FAMILY MEMBERS (household roster) ──────────────────────────────────────
// Only meaningful for residents registered as Household Head. All reads/
// writes are scoped to the logged-in resident's own id_resident inside the
// class methods themselves, never to the $_GET id_resident above.
$is_household_head = ($userdetails['family_role'] ?? '') === 'Yes';
$residentbmis->add_family_member();
$residentbmis->delete_family_member();
$family_members = $is_household_head ? $residentbmis->view_family_members($userdetails['id_resident']) : [];
?>
<?php require ROOT_PATH . '/app/Views/resident_profile_view.php'; ?>
