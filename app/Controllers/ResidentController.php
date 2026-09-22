<?php

class ResidentController extends Controller
{
    public function homepage()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

$userdetails = $bmis->get_userdata();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
$unread_msg_count = $bmis->getUnreadResidentMessageCount($userdetails['id_resident']);

$dt = new DateTime("now", new DateTimeZone('Asia/Manila'));
$cdate = $dt->format('Y/m/d');
$ctime = $dt->format('H');

// Handle announcement hiding
if (isset($_POST['delete_announcement'])) {
    $bmis->delete_announcement($userdetails['id_resident']);
}
$announcements = $bmis->view_active_announcements($userdetails['id_resident']);
        $this->view('resident/homepage.php');

    }

    public function profile()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

error_reporting(E_ALL ^ E_WARNING);

ini_set('display_errors',0);
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_resident($id_resident);

$residentbmis->profile_update();
$is_verified = $residentbmis->isResidentVerified($userdetails['id_resident']);
        $this->view('resident/profile.php');

    }

    public function registration()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off') {
    $redirectUrl = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('Location: ' . $redirectUrl, true, 301);
    exit;
}

// InfinityFree sends a restrictive default Permissions-Policy header that
// blocks camera access on every page ("camera is not allowed in this
// document"), regardless of what the browser's own permission prompt says.
// Sending our own header here overrides it so the Take Photo feature can
// actually request camera access on this page.
header('Permissions-Policy: camera=(self)');

$residentbmis->create_resident();
        $this->view('resident/registration.php');

    }

    public function changepass()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;


error_reporting(E_ALL ^ E_WARNING); 

$userdetails = $residentbmis->get_userdata();
$residentbmis->resident_changepass();
        $this->view('resident/changepass.php');

    }

    public function announcement()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

error_reporting(E_ALL ^ E_WARNING);
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
        $this->view('resident/announcement.php');

    }

    public function programs()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;


// Get filter parameters
$category = $_GET['category'] ?? null;
$status = $_GET['status'] ?? 'Registration Open';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT p.*, 
        COUNT(DISTINCT pr.id) as total_registrations,
        COUNT(DISTINCT CASE WHEN pr.user_id = :user_id THEN pr.id END) as user_registered
        FROM programs p
        LEFT JOIN program_registrations pr ON p.id = pr.program_id AND pr.status != 'Cancelled'
        WHERE 1=1";

$params = [':user_id' => $_SESSION['user_id']];

if ($category) {
    $sql .= " AND p.category = :category";
    $params[':category'] = $category;
}

if ($status) {
    $sql .= " AND p.status = :status";
    $params[':status'] = $status;
}

if ($search) {
    $sql .= " AND (p.title LIKE :search OR p.description LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " GROUP BY p.id ORDER BY p.start_date DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$programs = $stmt->fetchAll();
        $this->view('resident/programs.php');

    }

}
