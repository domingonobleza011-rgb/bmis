<?php
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
require(ROOT_PATH . '/classes/resident.class.php');

$userdetails = $bmis->get_userdata();

if (isset($_POST['create_budget'])) {
    $bmis->create_budget();
    header("Location: admn_budget.php?toast=created");
    exit();
}
if (isset($_POST['update_budget'])) {
    $bmis->update_budget();
    header("Location: admn_budget.php?toast=updated");
    exit();
}
if (isset($_POST['delete_budget'])) {
    $bmis->delete_budget();
    header("Location: admn_budget.php?toast=deleted");
    exit();
}

$filter_year = $_GET['year'] ?? null;
$view = $bmis->view_budget($filter_year);
$years = $bmis->get_budget_years();
$total = $bmis->get_budget_total($filter_year);

$all_images = [];
foreach ($view as $row) {
    if (!empty($row['image']) && file_exists('uploads/' . $row['image'])) {
        $all_images[] = [
            'src'   => 'uploads/' . $row['image'],
            'id'    => $row['id_budget'],
            'title' => $row['category'] . ' - ' . $row['fiscal_year']
        ];
    }
}
?>
<?php require ROOT_PATH . '/app/Views/admn_budget_view.php'; ?>
