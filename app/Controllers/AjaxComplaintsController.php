<?php
error_reporting(0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
header('Content-Type: application/json');

require_once ROOT_PATH . '/classes/conn.php';
$pdo = $conn;

$staff_list = $pdo->query(
    "SELECT id_user, fname, lname, position FROM tbl_user WHERE role = 'user' ORDER BY lname, fname"
)->fetchAll(PDO::FETCH_ASSOC);

$filter_status   = $_GET['status']   ?? 'all';
$filter_category = $_GET['category'] ?? '';
$filter_search   = trim($_GET['search'] ?? '');
$filter_assigned = $_GET['assigned']  ?? '';

$where = []; $params = [];

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
if ($filter_assigned === 'unassigned') {
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

ob_start();
foreach ($complaints as $c) {
    $status     = $c['status'];
    $card_class = $status === 'pending' ? 'c-pending' : ($status === 'in_progress' ? 'c-inprog' : 'c-resolved');
    $modal_id   = 'modal-' . $c['id'];
    require ROOT_PATH . '/app/Views/ajax_complaints_view.php';
}
$html = ob_get_clean();

header('Content-Type: application/json');
echo json_encode([
    'html'             => $html,
    'count_all'        => $count_all,
    'count_pending'    => $count_pending,
    'count_inprogress' => $count_inprogress,
    'count_resolved'   => $count_resolved,
    'total_shown'      => count($complaints),
]);
