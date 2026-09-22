<?php
define('BMIS_ROLE_REQUIRED', 'resident');
require(ROOT_PATH . '/secure_header.php');
require_once ROOT_PATH . '/classes/main.class.php';

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
?>
<?php require ROOT_PATH . '/app/Views/resident_programs_view.php'; ?>
