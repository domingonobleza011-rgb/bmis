<?php
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php');
error_reporting(E_ALL ^ E_WARNING);
include(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $bmis->get_userdata();

$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);

$dt = new DateTime("now", new DateTimeZone('Asia/Manila'));
$tm = new DateTime("now", new DateTimeZone('Asia/Manila'));
$cdate = $dt->format('Y/m/d');
$ctime = $tm->format('H');

$current_user_id = $userdetails['id_resident'];

// Handle delete via normal POST (fallback) — redirects back cleanly
if(isset($_POST['delete_announcement'])) {
    $bmis->delete_announcement($current_user_id);
}

$view = $bmis->view_active_announcements($current_user_id);

/**
 * Escapes announcement text for safe HTML output, then turns any
 * http://, https://, or www. links inside it into clickable <a> tags
 * that open in a new tab. Apply nl2br() AFTER this, not before.
 */
function bmis_linkify_announcement($text) {
    $escaped = htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');

    return preg_replace_callback(
        '/((https?:\/\/|www\.)[^\s<]+)/i',
        function ($m) {
            $url = $m[0];

            // Don't swallow trailing punctuation into the link (e.g. "visit site.com.")
            $trailing = '';
            while ($url !== '' && strpos('.,!?;:)"\'', substr($url, -1)) !== false) {
                $trailing = substr($url, -1) . $trailing;
                $url = substr($url, 0, -1);
            }

            $href = (stripos($url, 'http') === 0) ? $url : 'https://' . $url;

            return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" '
                 . 'onclick="event.stopPropagation();" '
                 . 'style="color:#1877f2; font-weight:600; text-decoration:underline; word-break:break-all;">'
                 . $url . '</a>' . $trailing;
        },
        $escaped
    );
}

/**
 * Truncate text to a specified word count with "See more" functionality.
 * Returns array with truncated text, full text, and whether it was truncated.
 */
function bmis_truncate_text($text, $word_limit = 20) {
    $words = preg_split('/\s+/', $text);
    if (count($words) <= $word_limit) {
        return [
            'truncated' => false,
            'short' => $text,
            'full' => $text
        ];
    }
    
    $short = implode(' ', array_slice($words, 0, $word_limit));
    return [
        'truncated' => true,
        'short' => $short . '...',
        'full' => $text
    ];
}

/**
 * Cleans announcement text before display: normalizes line endings,
 * strips trailing spaces on each line, and collapses runs of blank lines
 * into one. CSS (white-space: pre-line) draws the line breaks, so do NOT
 * also use nl2br().
 */
function bmis_format_announcement($text) {
    $text = str_replace(["\r\n", "\r"], "\n", (string) $text);
    $text = preg_replace('/[ \t\x{00A0}]+\n/u', "\n", $text);
    $text = preg_replace('/\n{3,}/', "\n\n", $text);
    return bmis_linkify_announcement(trim($text));
}
?>
<?php require ROOT_PATH . '/app/Views/resident_announcement_view.php'; ?>
