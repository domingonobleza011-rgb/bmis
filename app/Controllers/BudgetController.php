<?php

class BudgetController extends Controller
{
    public function adminBudget()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

$userdetails=$bmis->get_userdata();
if(isset($_POST['create_budget'])){
    $bmis->create_budget();
    header("Location: admn_budget.php?toast=created");
    exit();
}
if(isset($_POST['update_budget'])){
    $bmis->update_budget();
    header("Location: admn_budget.php?toast=updated");
    exit();
}
if(isset($_POST['delete_budget'])){
    $bmis->delete_budget();
    header("Location: admn_budget.php?toast=deleted");
    exit();
}
$filter_year=$_GET['year']??null;
$view=$bmis->view_budget($filter_year);
$years=$bmis->get_budget_years();
$total=$bmis->get_budget_total($filter_year);
$all_images=[];
foreach($view as $row){
    if(!empty($row['image'])&&file_exists('uploads/'.$row['image'])){
        $all_images[]=[
            'src'=>'uploads/'.$row['image'],
            'id'=>$row['id_budget'],
            'title'=>$row['category'].' - '.$row['fiscal_year']
        ];
    }
}
?>
<?php 
        $this->view('budget/adminBudget.php');

    }

    public function residentBudget()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $bmis->get_userdata();
if (empty($userdetails) || ($userdetails['role'] ?? '') !== 'resident') {
    header("Location: index.php");
    exit();
}

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
        $this->view('budget/residentBudget.php');

    }

}
