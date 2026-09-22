<?php
error_reporting(E_ALL ^ E_WARNING);
require_once(ROOT_PATH . '/classes/security.php');
bmis_session_start();
bmis_set_security_headers();
require_once(ROOT_PATH . '/classes/conn.php');
include(ROOT_PATH . '/classes/staff.class.php');
include(ROOT_PATH . '/classes/resident.class.php');

// Enforce: only logged-in staff with position 'Sk Chairperson' may access this page
$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied. This page is restricted to the SK Chairperson only.');
}

require_once(ROOT_PATH . '/classes/main.class.php');
// ADD
if (isset($_POST['add_post'])) {
    $stmt = $conn->prepare("INSERT INTO tbl_youth_bulletin (post_title,post_content,post_type,posted_by,is_pinned) VALUES (?,?,?,?,?)");
    $stmt->execute([$_POST['post_title'],$_POST['post_content'],$_POST['post_type'],$poster,isset($_POST['is_pinned'])?1:0]);
    header("Location: sk_announcements.php?success=added"); exit;
}
// EDIT
if (isset($_POST['edit_post'])) {
    $stmt = $conn->prepare("UPDATE tbl_youth_bulletin SET post_title=?,post_content=?,post_type=?,is_pinned=? WHERE id_post=?");
    $stmt->execute([$_POST['post_title'],$_POST['post_content'],$_POST['post_type'],isset($_POST['is_pinned'])?1:0,$_POST['id_post']]);
    header("Location: sk_announcements.php?success=updated"); exit;
}
// DELETE
if (isset($_POST['delete_post'])) {
    $conn->prepare("DELETE FROM tbl_youth_bulletin WHERE id_post=?")->execute([$_POST['id_post']]);
    header("Location: sk_announcements.php?success=deleted"); exit;
}
// TOGGLE PIN
if (isset($_POST['toggle_pin'])) {
    $cur = $conn->prepare("SELECT is_pinned FROM tbl_youth_bulletin WHERE id_post=?");
    $cur->execute([$_POST['id_post']]);
    $r = $cur->fetch(PDO::FETCH_ASSOC);
    $conn->prepare("UPDATE tbl_youth_bulletin SET is_pinned=? WHERE id_post=?")->execute([$r['is_pinned']?0:1,$_POST['id_post']]);
    header("Location: sk_announcements.php"); exit;
}

$type_filter = $_GET['type'] ?? '';
if ($type_filter) {
    $s = $conn->prepare("SELECT * FROM tbl_youth_bulletin WHERE post_type=? ORDER BY is_pinned DESC, date_posted DESC");
    $s->execute([$type_filter]);
} else {
    $s = $conn->query("SELECT * FROM tbl_youth_bulletin ORDER BY is_pinned DESC, date_posted DESC");
}
$posts = $s->fetchAll(PDO::FETCH_ASSOC);

$post_types = ['Announcement','Opportunity','Reminder','Achievement','General'];
?>
<?php require ROOT_PATH . '/app/Views/sk_announcements_view.php'; ?>
