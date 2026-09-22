<?php

class ApiController extends Controller
{
    public function announcement()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);

$userdetails = $bmis->get_userdata();
$user_id     = $userdetails['id_resident'];

// Ensure tables exist on first run

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {

    // ── POST COMMENT ──────────────────────────────────────────────
    case 'add_comment':
        $ann_id = (int)($_POST['announcement_id'] ?? 0);
        $text   = trim($_POST['comment_text'] ?? '');
        if (!$ann_id || $text === '') {
            echo json_encode(['success' => false, 'message' => 'Invalid input']);
            exit;
        }
        $new_id = $bmis->add_comment($ann_id, $user_id, $text);
        $name   = $userdetails['firstname'] . ' ' . $userdetails['surname'];
        echo json_encode([
            'success'    => true,
            'id_comment' => $new_id,
            'full_name'  => htmlspecialchars($name),
            'comment_text' => htmlspecialchars($text),
            'created_at' => date('M j, Y g:i A'),
        ]);
        break;

    // ── DELETE COMMENT ────────────────────────────────────────────
    case 'delete_comment':
        $comment_id = (int)($_POST['comment_id'] ?? 0);
        if (!$comment_id) {
            echo json_encode(['success' => false]);
            exit;
        }
        $bmis->delete_comment($comment_id, $user_id);
        echo json_encode(['success' => true]);
        break;

    // ── TOGGLE REACTION ───────────────────────────────────────────
    case 'toggle_reaction':
        $ann_id       = (int)($_POST['announcement_id'] ?? 0);
        $reaction     = $_POST['reaction_type'] ?? '';
        $allowed      = ['like','love','haha','wow','sad','angry'];
        if (!$ann_id || !in_array($reaction, $allowed)) {
            echo json_encode(['success' => false]);
            exit;
        }
        $result       = $bmis->toggle_reaction($ann_id, $user_id, $reaction);
        $counts       = $bmis->get_reactions($ann_id);
        $user_reaction= $bmis->get_user_reaction($ann_id, $user_id);
        echo json_encode([
            'success'       => true,
            'result'        => $result,
            'counts'        => $counts,
            'user_reaction' => $user_reaction,
        ]);
        break;

    // ── DELETE ANNOUNCEMENT (hide from resident's feed) ───────────
    case 'delete_announcement':
        $ann_id = (int)($_POST['announcement_id'] ?? 0);
        if (!$ann_id) {
            echo json_encode(['success' => false]);
            exit;
        }
        $bmis->hide_announcement($user_id, $ann_id);
        echo json_encode(['success' => true]);
        break;

    // ── LOAD COMMENTS ─────────────────────────────────────────────
    case 'get_comments':
        $ann_id = (int)($_GET['announcement_id'] ?? 0);
        $comments = $bmis->get_comments($ann_id);
        echo json_encode(['success' => true, 'comments' => $comments, 'user_id' => $user_id]);
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Unknown action']);
}

    }

    public function fetchAnnouncementDetails()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

    // Ensure $bmis is initialized if not done automatically in the require
    // $bmis = new BMISClass(); 

    $id = (int)$_GET['id'];
    $details = $bmis->get_single_announcement($id);
    $reactions = $bmis->get_reactions($id);
    $comments = $bmis->get_comments($id);

    // Calculate total reactions
    $total_r = 0;
    if(is_array($reactions)) {
        foreach($reactions as $r) $total_r += $r['count'];
    }
?>

<?php if($details): ?>
    <div class="card announcement-preview shadow-lg">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h4 class="fw-bold text-dark mb-1">📢 <?= $details['addedby']; ?></h4>
                    <small class="text-muted">Posted on <?= $details['start_date']; ?></small>
                </div>
                <span class="badge bg-success">Selected Post</span>
            </div>
            
            <p class="lead mt-3 text-secondary" style="font-size: 1.1rem;">
                <?= nl2br(htmlspecialchars($details['event'])); ?>
            </p>

            <hr>

            <div class="row text-center mb-4">
                <div class="col-6 border-end">
                    <h3 class="fw-bold text-primary mb-0"><?= $total_r; ?></h3>
                    <small class="text-muted text-uppercase">Reactions</small>
                </div>
                <div class="col-6">
                    <h3 class="fw-bold text-success mb-0"><?= is_array($comments) ? count($comments) : 0; ?></h3>
                    <small class="text-muted text-uppercase">Comments</small>
                </div>
            </div>

            <h6 class="fw-bold mb-3">Recent Comments:</h6>
            <div class="comment-list" style="max-height: 300px; overflow-y: auto;">
                <?php if(is_array($comments) && !empty($comments)): ?>
                    <?php foreach($comments as $com): ?>
                        <div class="d-flex mb-3 p-2 bg-light rounded">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold" style="font-size: 0.9rem;"><?= $com['full_name']; ?></span>
                                    <small class="text-muted" style="font-size: 0.7rem;"><?= $com['created_at']; ?></small>
                                </div>
                                <div class="text-dark" style="font-size: 0.95rem;"><?= htmlspecialchars($com['comment_text']); ?></div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center text-muted my-4">No comments for this announcement.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-danger">Announcement details not found.</div>
<?php endif; 

    }

    public function pollStats()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * poll_stats.php
 * Lightweight JSON endpoint for dashboard live counters.
 * Replaces the old SSE (sse_stats.php) approach.
 *
 * Called by live-stats.js every 60 seconds via fetch().
 * One short HTTP request per minute instead of a persistent connection.
 */

error_reporting(0);


header('Content-Type: application/json');
header('Cache-Control: no-store');

try {
    $residentbmis = new ResidentBMIS();
    $staffbmis    = new StaffBMIS();

    // ── Resident counts ───────────────────────────────────────
    $res_male   = (int) $residentbmis->count_male_resident();
    $res_female = (int) $residentbmis->count_female_resident();
    $res_head   = (int) $residentbmis->count_head_resident();
    $res_member = (int) $residentbmis->count_member_resident();
    $res_voter  = (int) $residentbmis->count_voters();
    $res_senior = (int) $residentbmis->count_resident_senior();
    $res_pwd    = (int) $residentbmis->count_pwd();

    // ── Staff counts ──────────────────────────────────────────
    $staff_total  = (int) $staffbmis->count_staff();
    $staff_male   = (int) $staffbmis->count_mstaff();
    $staff_female = (int) $staffbmis->count_fstaff();

    // ── Complaint counts ──────────────────────────────────────
    $cmp_pending  = (int) $conn->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='pending'")->fetchColumn();
    $cmp_resolved = (int) $conn->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='resolved'")->fetchColumn();
    $cmp_total    = $cmp_pending + $cmp_resolved;

    // ── Messages (unread / all) ───────────────────────────────
    $msg_count = (int) $conn->query("SELECT COUNT(*) FROM admin_messages")->fetchColumn();

    // ── Pending ID uploads ────────────────────────────────────
    $id_pending = (int) $conn->query("SELECT COUNT(*) FROM admin_messages WHERE status='pending'")->fetchColumn();

    echo json_encode([
        'res_male'    => $res_male,
        'res_female'  => $res_female,
        'res_head'    => $res_head,
        'res_member'  => $res_member,
        'res_voter'   => $res_voter,
        'res_senior'  => $res_senior,
        'res_pwd'     => $res_pwd,
        'staff_total' => $staff_total,
        'staff_male'  => $staff_male,
        'staff_female'=> $staff_female,
        'cmp_total'   => $cmp_total,
        'cmp_pending' => $cmp_pending,
        'cmp_resolved'=> $cmp_resolved,
        'msg_count'   => $msg_count,
        'id_pending'  => $id_pending,
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'stats unavailable']);
}

    }

    public function saveFcmToken()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
// save_fcm_token.php
// Called via fetch() from the resident pages to store the FCM token in the DB.

header('Content-Type: application/json');
ini_set('display_errors', 0);

session_start();
if (empty($_SESSION['userdata']['id_resident'])) {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$id_resident = (int) $_SESSION['userdata']['id_resident'];
$token       = trim($_POST['token'] ?? '');

if (empty($token)) {
    echo json_encode(['success' => false, 'message' => 'No token provided']);
    exit;
}


try {
    // Upsert: insert or update the token for this resident
    $stmt = $conn->prepare("
        INSERT INTO tbl_fcm_tokens (resident_id, fcm_token, updated_at)
        VALUES (?, ?, NOW())
        ON DUPLICATE KEY UPDATE fcm_token = VALUES(fcm_token), updated_at = NOW()
    ");
    $stmt->execute([$id_resident, $token]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log("save_fcm_token error: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'DB error']);
}

    }

    public function saveToken()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
session_start();

if (isset($_SESSION['resident_id']) && isset($_POST['token'])) {
    $resident_id = $_SESSION['resident_id'];
    $token = $_POST['token'];

    $stmt = $conn->prepare("INSERT INTO resident_tokens (resident_id, token) VALUES (?, ?) 
                            ON DUPLICATE KEY UPDATE last_updated = CURRENT_TIMESTAMP");
    $stmt->bind_param("is", $resident_id, $token);
    $stmt->execute();
    echo "Token saved.";
}

    }

    public function sseStats()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * sse_stats.php — Server-Sent Events endpoint
 * Streams live dashboard stats every 5 seconds.
 * No WebSocket library needed — works on any shared hosting.
 */

// ── Auth: only logged-in staff/admin may connect ──────────────
session_start();
if (empty($_SESSION['userdata'])) {
    http_response_code(401);
    exit('Unauthorized');
}

// ── SSE headers ───────────────────────────────────────────────
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header('X-Accel-Buffering: no');   // disable nginx buffering
set_time_limit(0);
ignore_user_abort(true);

// ── DB connection ─────────────────────────────────────────────
$host   = 'localhost';
$dbname = 'bmis';
$dbuser = 'root';
$dbpass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "event: error\ndata: " . json_encode(['msg' => 'DB error']) . "\n\n";
    flush();
    exit;
}

// ── Helper: fetch all stats in one go ─────────────────────────
function get_stats(PDO $pdo): array {
    $q = fn(string $sql) => (int)$pdo->query($sql)->fetchColumn();
    return [
        // Residents
        'res_male'     => $q("SELECT COUNT(*) FROM tbl_resident WHERE sex='Male'"),
        'res_female'   => $q("SELECT COUNT(*) FROM tbl_resident WHERE sex='Female'"),
        'res_head'     => $q("SELECT COUNT(*) FROM tbl_resident WHERE type='Head'"),
        'res_member'   => $q("SELECT COUNT(*) FROM tbl_resident WHERE type='Member'"),
        'res_voter'    => $q("SELECT COUNT(*) FROM tbl_resident WHERE voter='Yes'"),
        'res_senior'   => $q("SELECT COUNT(*) FROM tbl_resident WHERE senior_citizen='Yes'"),
        'res_pwd'      => $q("SELECT COUNT(*) FROM tbl_resident WHERE pwd='Yes'"),
        // Staff
        'staff_total'  => $q("SELECT COUNT(*) FROM tbl_staff"),
        'staff_male'   => $q("SELECT COUNT(*) FROM tbl_staff WHERE sex='Male'"),
        'staff_female' => $q("SELECT COUNT(*) FROM tbl_staff WHERE sex='Female'"),
        // Complaints
        'cmp_total'    => $q("SELECT COUNT(*) FROM tbl_complaints"),
        'cmp_pending'  => $q("SELECT COUNT(*) FROM tbl_complaints WHERE status='pending'"),
        'cmp_resolved' => $q("SELECT COUNT(*) FROM tbl_complaints WHERE status='resolved'"),
        // Messages & ID uploads
        'msg_count'    => $q("SELECT COUNT(*) FROM admin_messages"),
        'id_pending'   => $q("SELECT COUNT(*) FROM tbl_id_uploads WHERE status='pending'"),
    ];
}

// ── Stream loop ───────────────────────────────────────────────
$last = [];

while (true) {
    if (connection_aborted()) break;

    try {
        $stats = get_stats($pdo);
    } catch (Throwable $e) {
        // DB dropped — try reconnecting next tick
        sleep(5);
        continue;
    }

    // Only push when something changed (saves bandwidth)
    if ($stats !== $last) {
        echo "event: stats\n";
        echo "data: " . json_encode($stats) . "\n\n";
        flush();
        $last = $stats;
    }

    // Keep-alive ping every loop so the browser doesn't time out
    echo ": ping\n\n";
    flush();

    sleep(5);
}

    }

}
