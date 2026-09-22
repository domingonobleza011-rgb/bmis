<?php
require(ROOT_PATH . '/classes/resident.class.php');
$userdetails = $bmis->get_userdata();
if (empty($userdetails) || ($userdetails['role'] ?? '') !== 'resident') {
    header("Location: index.php");
    exit();
}
bmis_enforce_youth_profile_gate($userdetails);

$filter_year = $_GET['year'] ?? null;
$view  = $bmis->view_budget($filter_year);
$years = $bmis->get_budget_years();
$total = $bmis->get_budget_total($filter_year);

// Build image gallery
$all_images = [];
foreach ($view as $row) {
    if (!empty($row['image']) && file_exists('uploads/' . $row['image'])) {
        $all_images[] = ['src' => 'uploads/' . $row['image'], 'title' => ($row['category'] ?? 'Budget') . ' - ' . ($row['fiscal_year'] ?? '')];
    }
}
?>
<?php require ROOT_PATH . '/app/Views/resident_budget_view.php'; ?>
