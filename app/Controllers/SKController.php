<?php

class SKController extends Controller
{
    public function dashboard()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
bmis_session_start();
bmis_set_security_headers();

// Enforce: only logged-in staff with position 'Sk Chairperson' may access this page
$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied. This page is restricted to the SK Chairperson only.');
}

$bmis = new BMISClass();

// Count stats from existing tables
try {
    $total_youth    = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth")->fetchColumn();
    $total_programs = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_programs")->fetchColumn();
    $upcoming_prog  = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_programs WHERE status='Upcoming'")->fetchColumn();
    $total_enrolled = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_enrollment")->fetchColumn();
    $total_posts    = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_bulletin")->fetchColumn();
    $pinned_posts   = (int)$conn->query("SELECT COUNT(*) FROM tbl_youth_bulletin WHERE is_pinned=1")->fetchColumn();
    $recent_youth   = $conn->query("SELECT * FROM tbl_youth ORDER BY id_youth DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    $recent_prog    = $conn->query("SELECT * FROM tbl_youth_programs ORDER BY date_created DESC LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);
    $recent_posts   = $conn->query("SELECT * FROM tbl_youth_bulletin ORDER BY date_posted DESC LIMIT 3")->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $total_youth=$total_programs=$upcoming_prog=$total_enrolled=$total_posts=$pinned_posts=0;
    $recent_youth=$recent_prog=$recent_posts=[];
}
?>
<?php 
        $this->view('sk/dashboard.php');

    }

    public function announcements()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
bmis_session_start();
bmis_set_security_headers();

// Enforce: only logged-in staff with position 'Sk Chairperson' may access this page
$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied. This page is restricted to the SK Chairperson only.');
}

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
<?php 
        $this->view('sk/announcements.php');

    }

    public function enrollment()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
bmis_session_start();
bmis_set_security_headers();

// Enforce: only logged-in staff with position 'Sk Chairperson' may access this page
$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied. This page is restricted to the SK Chairperson only.');
}

// ADD enrollment
if (isset($_POST['add_enrollment'])) {
    // Get youth name from tbl_youth, contact from their resident profile
    $y = $conn->prepare("SELECT y.fname, y.lname, r.contact FROM tbl_youth y LEFT JOIN tbl_resident r ON r.id_resident = y.id_youth WHERE y.id_youth=?");
    $y->execute([$_POST['id_youth']]);
    $yu = $y->fetch(PDO::FETCH_ASSOC);
    $name = $yu ? ($yu['lname'].', '.$yu['fname']) : '';
    $contact = $yu ? ($yu['contact'] ?? '') : '';
    $stmt = $conn->prepare("INSERT INTO tbl_youth_enrollment (id_program,id_youth,youth_name,contact,status) VALUES (?,?,?,?,?)");
    $stmt->execute([$_POST['id_program'],$_POST['id_youth'],$name,$contact,$_POST['status']]);
    header("Location: sk_enrollment.php?success=added&program=".$_POST['id_program']); exit;
}
// UPDATE status
if (isset($_POST['update_enrollment'])) {
    $stmt = $conn->prepare("UPDATE tbl_youth_enrollment SET status=? WHERE id_enrollment=?");
    $stmt->execute([$_POST['status'],$_POST['id_enrollment']]);
    header("Location: sk_enrollment.php?success=updated&program=".$_POST['id_program']); exit;
}
// DELETE
if (isset($_POST['delete_enrollment'])) {
    $conn->prepare("DELETE FROM tbl_youth_enrollment WHERE id_enrollment=?")->execute([$_POST['id_enrollment']]);
    header("Location: sk_enrollment.php?success=deleted&program=".$_POST['id_program']); exit;
}

$prog_filter = $_GET['program'] ?? '';
// Get programs list
$programs = $conn->query("SELECT * FROM tbl_youth_programs ORDER BY event_date DESC")->fetchAll(PDO::FETCH_ASSOC);
// Get youth list
$youth_list = $conn->query("SELECT id_youth, fname, lname FROM tbl_youth ORDER BY lname")->fetchAll(PDO::FETCH_ASSOC);

if ($prog_filter) {
    $s = $conn->prepare("SELECT e.*,p.program_title,p.program_type,p.event_date FROM tbl_youth_enrollment e JOIN tbl_youth_programs p ON e.id_program=p.id_program WHERE e.id_program=? ORDER BY e.enrolled_at DESC");
    $s->execute([$prog_filter]);
} else {
    $s = $conn->query("SELECT e.*,p.program_title,p.program_type,p.event_date FROM tbl_youth_enrollment e JOIN tbl_youth_programs p ON e.id_program=p.id_program ORDER BY e.enrolled_at DESC");
}
$enrollments = $s->fetchAll(PDO::FETCH_ASSOC);

// Stats
$total_e    = count($enrollments);
$attended   = count(array_filter($enrollments, fn($e)=>$e['status']==='Attended'));
$enrolled   = count(array_filter($enrollments, fn($e)=>$e['status']==='Enrolled'));
$dropped    = count(array_filter($enrollments, fn($e)=>$e['status']==='Dropped'));
?>
<?php 
        $this->view('sk/enrollment.php');

    }

    public function programs()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
bmis_session_start();
bmis_set_security_headers();

// Enforce: only logged-in staff with position 'Sk Chairperson' may access this page
$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied. This page is restricted to the SK Chairperson only.');
}

// ADD
if (isset($_POST['add_program'])) {
    $stmt = $conn->prepare("INSERT INTO tbl_youth_programs (program_title,program_type,description,venue,event_date,event_time,slots,requirements,status,created_by) VALUES (?,?,?,?,?,?,?,?,?,?)");
    $stmt->execute([$_POST['program_title'],$_POST['program_type'],$_POST['description'],$_POST['venue'],$_POST['event_date'],$_POST['event_time'],$_POST['slots'],$_POST['requirements'],$_POST['status'],$userdetails['firstname'].' '.$userdetails['surname']]);
    header("Location: sk_programs.php?success=added"); exit;
}
// EDIT
if (isset($_POST['edit_program'])) {
    $stmt = $conn->prepare("UPDATE tbl_youth_programs SET program_title=?,program_type=?,description=?,venue=?,event_date=?,event_time=?,slots=?,requirements=?,status=? WHERE id_program=?");
    $stmt->execute([$_POST['program_title'],$_POST['program_type'],$_POST['description'],$_POST['venue'],$_POST['event_date'],$_POST['event_time'],$_POST['slots'],$_POST['requirements'],$_POST['status'],$_POST['id_program']]);
    header("Location: sk_programs.php?success=updated"); exit;
}
// DELETE
if (isset($_POST['delete_program'])) {
    $conn->prepare("DELETE FROM tbl_youth_programs WHERE id_program=?")->execute([$_POST['id_program']]);
    header("Location: sk_programs.php?success=deleted"); exit;
}

$status_filter = $_GET['status'] ?? '';
if ($status_filter) {
    $s = $conn->prepare("SELECT * FROM tbl_youth_programs WHERE status=? ORDER BY event_date DESC");
    $s->execute([$status_filter]);
} else {
    $s = $conn->prepare("SELECT * FROM tbl_youth_programs ORDER BY event_date DESC");
    $s->execute();
}
$programs = $s->fetchAll(PDO::FETCH_ASSOC);

$types = ['Training','Sports','Arts','Leadership','Health','Livelihood','Scholarship','Community Service','Other'];
$statuses = ['Upcoming','Ongoing','Completed','Cancelled'];
?>
<?php 
        $this->view('sk/programs.php');

    }

    public function youthRecords()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
bmis_session_start();
bmis_set_security_headers();

$userdetails = bmis_require_login();
if ($userdetails['role'] !== 'user' || ($userdetails['position'] ?? '') !== 'Sk Chairperson') {
    http_response_code(403);
    die('Access denied.');
}

$bmis = new BMISClass();

// ── ADD / EDIT / DELETE HANDLERS ──────────────────────────────────────────

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['edit_youth'])) {
        // Age is recomputed from the resident's birthdate server-side —
        // never trust a posted age value.
        $bstmt = $conn->prepare("SELECT bdate FROM tbl_resident WHERE id_resident = ?");
        $bstmt->execute([$_POST['id_youth']]);
        $bdate = $bstmt->fetchColumn();
        $age = $bdate ? (new DateTime())->diff(new DateTime($bdate))->y : ($_POST['age'] ?? null);

        $stmt = $conn->prepare("UPDATE tbl_youth SET lname=?, fname=?, mi=?, age=?, sex=?, civil_status=?, email_address=?, educ_attain=?, emp_status=?, skill_name=? WHERE id_youth=?");
        $stmt->execute([
            $_POST['lname'], $_POST['fname'], $_POST['mi'],
            $age,   $_POST['sex'],   $_POST['civil_status'],
            $_POST['email_address'], $_POST['educ_attain'],
            $_POST['emp_status'], $_POST['skill_name'], $_POST['id_youth']
        ]);
        header('Location: sk_youth_records.php?success=updated'); exit;
    }

    if (isset($_POST['delete_youth'])) {
        $id_youth = $_POST['id_youth'];

        // Remove the uploaded photo file along with the record, so
        // uploads/youth_photos/ doesn't accumulate orphaned images.
        $pstmt = $conn->prepare("SELECT photo FROM tbl_youth WHERE id_youth=?");
        $pstmt->execute([$id_youth]);
        $photo = $pstmt->fetchColumn();
        if ($photo && is_file('uploads/youth_photos/' . $photo)) {
            unlink('uploads/youth_photos/' . $photo);
        }

        // Remove their enrollment in every program along with the profile.
        $conn->prepare("DELETE FROM tbl_youth_enrollment WHERE id_youth=?")->execute([$id_youth]);
        $stmt = $conn->prepare("DELETE FROM tbl_youth WHERE id_youth=?");
        $stmt->execute([$id_youth]);
        header('Location: sk_youth_records.php?success=deleted'); exit;
    }

    if (isset($_POST['bulk_delete_youth'])) {
        $ids = $_POST['selected_ids'] ?? [];
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // Remove each record's uploaded photo file along with the profiles.
            $pstmt = $conn->prepare("SELECT photo FROM tbl_youth WHERE id_youth IN ($placeholders)");
            $pstmt->execute($ids);
            foreach ($pstmt->fetchAll(PDO::FETCH_COLUMN) as $photo) {
                if ($photo && is_file('uploads/youth_photos/' . $photo)) {
                    unlink('uploads/youth_photos/' . $photo);
                }
            }

            // Remove their enrollment in every program along with the profiles.
            $conn->prepare("DELETE FROM tbl_youth_enrollment WHERE id_youth IN ($placeholders)")->execute($ids);
            $stmt = $conn->prepare("DELETE FROM tbl_youth WHERE id_youth IN ($placeholders)");
            $stmt->execute($ids);
        }
        header('Location: sk_youth_records.php?success=deleted'); exit;
    }
}

// ── FETCH / SEARCH ────────────────────────────────────────────────────────

$keyword  = trim($_GET['keyword'] ?? '');
$per_page = 10;
$page     = max(1, (int)($_GET['page'] ?? 1));
$offset   = ($page - 1) * $per_page;

if ($keyword) {
    $like  = "%$keyword%";
    $stmtC = $conn->prepare("SELECT COUNT(*) FROM tbl_youth WHERE lname LIKE ? OR fname LIKE ? OR email_address LIKE ?");
    $stmtC->execute([$like,$like,$like]);
    $total = (int)$stmtC->fetchColumn();

    $stmtR = $conn->prepare("SELECT * FROM tbl_youth WHERE lname LIKE ? OR fname LIKE ? OR email_address LIKE ? ORDER BY id_youth DESC LIMIT $per_page OFFSET $offset");
    $stmtR->execute([$like,$like,$like]);
} else {
    $stmtC = $conn->query("SELECT COUNT(*) FROM tbl_youth");
    $total = (int)$stmtC->fetchColumn();

    $stmtR = $conn->prepare("SELECT * FROM tbl_youth ORDER BY id_youth DESC LIMIT $per_page OFFSET $offset");
    $stmtR->execute();
}

$youths    = $stmtR->fetchAll(PDO::FETCH_ASSOC);
$total_pages = (int)ceil($total / $per_page);
?>
<?php 
        $this->view('sk/youthRecords.php');

    }

}
