<?php
if (!defined('BMIS_ROLE_REQUIRED')) { define('BMIS_ROLE_REQUIRED', 'admin_dashboard'); }
require_once ROOT_PATH . '/secure_header.php';

require ROOT_PATH . '/classes/conn.php';
require_once ROOT_PATH . '/pagination_helper.php';

if (isset($_POST['search_senior'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 2;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_resident` WHERE (TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 60 AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw OR `bplace` LIKE :kw OR `nationality` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT *, TIMESTAMPDIFF(YEAR, bdate, CURDATE()) AS computed_age FROM `tbl_resident` WHERE (TIMESTAMPDIFF(YEAR, bdate, CURDATE()) >= 60 AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw OR `bplace` LIKE :kw OR `nationality` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<?php $res_empty_text = 'No senior citizens found.'; require ROOT_PATH . '/app/Views/resident_cards_partial.php'; ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<?php $res_empty_text = 'No senior citizens found.'; require ROOT_PATH . '/app/Views/resident_cards_partial.php'; ?>

<?php } $conn = null; ?>