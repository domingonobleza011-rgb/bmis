<?php
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);
require(ROOT_PATH . '/classes/resident.class.php');

$userdetails = $bmis->get_userdata();
$current_admin_id = $userdetails['id_resident'];

if (isset($_POST['create_announce'])) {
    $bmis->create_announcement();
    header("Location: admn_announcement_crud.php?toast=created");
    exit();
}
if (isset($_POST['delete_announcement'])) {
    $bmis->admin_delete_announcement();
    header("Location: admn_announcement_crud.php?toast=deleted");
    exit();
}

$view = $bmis->view_announcement();
$announcementcount = $bmis->count_announcement();
$dt = new DateTime("now", new DateTimeZone('Asia/Manila'));
$cdate = $dt->format('Y/m/d');

/**
 * Escapes announcement text for safe HTML output, then turns any
 * http://, https://, or www. links inside it into clickable <a> tags
 * that open in a new tab.
 */
function bmis_linkify_announcement($text) {
    $escaped = htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
    return preg_replace_callback(
        '/((https?:\/\/|www\.)[^\s<]+)/i',
        function ($m) {
            $url = $m[0];
            $trailing = '';
            while ($url !== '' && strpos('.,!?;:)"\'', substr($url, -1)) !== false) {
                $trailing = substr($url, -1) . $trailing;
                $url = substr($url, 0, -1);
            }
            $href = (stripos($url, 'http') === 0) ? $url : 'https://' . $url;
            return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" '
                 . 'onclick="event.stopPropagation();" '
                 . 'style="color:#1a4480; font-weight:600; text-decoration:underline; word-break:break-all;">'
                 . $url . '</a>' . $trailing;
        },
        $escaped
    );
}

/**
 * Cleans announcement text before display: normalizes line endings,
 * strips trailing spaces on each line, and collapses runs of blank lines
 * into a single blank line. The CSS (white-space: pre-wrap) handles the
 * actual line breaks, so do NOT also use nl2br().
 */
function bmis_format_announcement($text) {
    $text = str_replace(["\r\n", "\r"], "\n", (string) $text);
    $text = preg_replace('/[ \t\x{00A0}]+\n/u', "\n", $text);
    $text = preg_replace('/\n{3,}/', "\n\n", $text);
    return bmis_linkify_announcement(trim($text));
}
?>
<?php require ROOT_PATH . '/app/Views/admn_announcement_crud_view.php'; ?>
