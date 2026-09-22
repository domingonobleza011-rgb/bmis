<?php
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
 
require_once ROOT_PATH . '/classes/conn.php'; // gives $conn (PDO)
$pdo = $conn;
 
// ── Load staff list for assignment dropdown ───────────────────
$staff_list = $pdo->query(
    "SELECT id_user, fname, lname, position
     FROM tbl_user
     WHERE role = 'user'
     ORDER BY lname, fname"
)->fetchAll(PDO::FETCH_ASSOC);
 
$admin_name = trim(
    ($_SESSION['userdata']['firstname'] ?? '') . ' ' .
    ($_SESSION['userdata']['surname']   ?? '')
);
 
// ── Handle POST actions ───────────────────────────────────────
$action_msg  = '';
$action_type = '';
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    // Assign
    if (isset($_POST['action_assign'])) {
        $id          = (int)$_POST['complaint_id'];
        $id_user     = (int)$_POST['assigned_to'];
        $staff_row   = $pdo->prepare("SELECT fname, lname FROM tbl_user WHERE id_user = ?");
        $staff_row->execute([$id_user]);
        $sf = $staff_row->fetch(PDO::FETCH_ASSOC);
        $aname = $sf ? trim($sf['fname'] . ' ' . $sf['lname']) : '';
 
        $pdo->prepare("UPDATE tbl_complaints
                       SET assigned_to=?, assigned_name=?, assigned_at=NOW(),
                           assigned_by=?, status='in_progress', date_updated=NOW()
                       WHERE id=?")
            ->execute([$id_user ?: null, $aname, $admin_name, $id]);
 
        $action_msg  = "Complaint #$id assigned to <strong>$aname</strong> and set to <em>In Progress</em>.";
        $action_type = 'info';
    }
 
    // Unassign
    if (isset($_POST['action_unassign'])) {
        $id = (int)$_POST['complaint_id'];
        $pdo->prepare("UPDATE tbl_complaints
                       SET assigned_to=NULL, assigned_name=NULL, assigned_at=NULL,
                           assigned_by=NULL, status='pending', date_updated=NOW()
                       WHERE id=?")
            ->execute([$id]);
        $action_msg  = "Complaint #$id has been unassigned and set back to <em>Pending</em>.";
        $action_type = 'warning';
    }
 
    // Resolve
    if (isset($_POST['action_resolve'])) {
        $id      = (int)$_POST['complaint_id'];
        $remarks = trim($_POST['admin_remarks'] ?? '');
        $pdo->prepare("UPDATE tbl_complaints SET status='resolved', admin_remarks=?, date_updated=NOW() WHERE id=?")
            ->execute([$remarks, $id]);

        // Notify the resident who filed the complaint, if remarks were given
        if ($remarks !== '') {
            $cmp = $pdo->prepare("SELECT id_resident, category FROM tbl_complaints WHERE id=?");
            $cmp->execute([$id]);
            $crow = $cmp->fetch(PDO::FETCH_ASSOC);
            if ($crow && !empty($crow['id_resident'])) {
                require_once ROOT_PATH . '/classes/main.class.php';
                $main = new BMISClass();
                $main->sendMessage(
                    $crow['id_resident'],
                    "Your complaint (" . $crow['category'] . ") has been marked as Resolved.\n\nAdmin Remarks: " . $remarks
                );
            }
        }

        $action_msg  = "Complaint #$id marked as <strong>Resolved</strong>.";
        $action_type = 'success';
    }
 
    // Set Pending
    if (isset($_POST['action_pending'])) {
        $id = (int)$_POST['complaint_id'];
        $pdo->prepare("UPDATE tbl_complaints SET status='pending', date_updated=NOW() WHERE id=?")->execute([$id]);
        $action_msg  = "Complaint #$id reverted to <strong>Pending</strong>.";
        $action_type = 'warning';
    }
 
    // Delete
    if (isset($_POST['action_delete'])) {
        $id  = (int)$_POST['complaint_id'];
        $row = $pdo->query("SELECT photo_path FROM tbl_complaints WHERE id=$id")->fetch(PDO::FETCH_ASSOC);
        if ($row && $row['photo_path'] && file_exists(ROOT_PATH . '/' . $row['photo_path'])) {
            unlink(ROOT_PATH . '/' . $row['photo_path']);
        }
        $pdo->exec("DELETE FROM tbl_complaints WHERE id=$id");
        $action_msg  = "Complaint #$id permanently deleted.";
        $action_type = 'danger';
    }
}
 
// ── Filters ──────────────────────────────────────────────────
$filter_status   = $_GET['status']   ?? 'all';
$filter_category = $_GET['category'] ?? '';
$filter_search   = trim($_GET['search'] ?? '');
$filter_assigned = $_GET['assigned']  ?? '';
 
$where  = [];
$params = [];
 
if ($filter_status !== 'all') {
    $where[] = 'status = :status';
    $params[':status'] = $filter_status;
}
if ($filter_category !== '') {
    $where[] = 'category LIKE :cat';
    $params[':cat'] = '%' . $filter_category . '%';
}
if ($filter_search !== '') {
    $where[] = '(full_name LIKE :s OR description LIKE :s2 OR location LIKE :s3)';
    $params[':s'] = $params[':s2'] = $params[':s3'] = '%' . $filter_search . '%';
}
if ($filter_assigned === 'me' && !empty($_SESSION['userdata']['id_user'])) {
    $where[] = 'assigned_to = :aid';
    $params[':aid'] = (int)$_SESSION['userdata']['id_user'];
} elseif ($filter_assigned === 'unassigned') {
    $where[] = 'assigned_to IS NULL';
}
 
$sql = 'SELECT * FROM tbl_complaints';
if ($where) $sql .= ' WHERE ' . implode(' AND ', $where);
$sql .= ' ORDER BY date_submitted DESC';
 
$count_all        = (int)$pdo->query("SELECT COUNT(*) FROM tbl_complaints")->fetchColumn();
$count_pending    = (int)$pdo->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='pending'")->fetchColumn();
$count_inprogress = (int)$pdo->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='in_progress'")->fetchColumn();
$count_resolved   = (int)$pdo->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='resolved'")->fetchColumn();
 
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$complaints = $stmt->fetchAll(PDO::FETCH_ASSOC);
 
$all_categories = $pdo->query("SELECT DISTINCT category FROM tbl_complaints ORDER BY category")->fetchAll(PDO::FETCH_COLUMN);
 
$sidebar_exists = file_exists(ROOT_PATH . '/dashboard_sidebar_start.php');
if ($sidebar_exists) include(ROOT_PATH . '/dashboard_sidebar_start.php');
?>
<?php require ROOT_PATH . '/app/Views/admn_complaints_view.php'; ?>
