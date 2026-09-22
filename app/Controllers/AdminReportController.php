<?php

class AdminReportController extends Controller
{
    public function femaleRes()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_female_paginated(10);
   
?>

<?php 
    
        $this->view('admin_report/femaleRes.php');

    }

    public function femaleResSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

if (isset($_POST['search_femaleres'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_resident` WHERE (sex = 'Female' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw OR `bplace` LIKE :kw OR `nationality` LIKE :kw OR `family_role` LIKE :kw OR `role` LIKE :kw OR `email` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE (sex = 'Female' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw OR `bplace` LIKE :kw OR `nationality` LIKE :kw OR `family_role` LIKE :kw OR `role` LIKE :kw OR `email` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Surname</th><th>First name</th><th>Middle name</th><th>Sex</th><th>Status</th><th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th><th>Birth date</th><th>Birth place</th><th>Nationality</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?></td>
            <td><?= htmlspecialchars($row['street']) ?></td>
            <td><?= htmlspecialchars($row['brgy']) ?></td>
            <td><?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="14" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Surname</th><th>First name</th><th>Middle name</th><th>Sex</th><th>Status</th><th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th><th>Birth date</th><th>Birth place</th><th>Nationality</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?></td>
            <td><?= htmlspecialchars($row['street']) ?></td>
            <td><?= htmlspecialchars($row['brgy']) ?></td>
            <td><?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="14" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

    public function maleRes()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_male_paginated(10);
   
?>

<?php 
    
        $this->view('admin_report/maleRes.php');

    }

    public function maleResSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

if (isset($_POST['search_maleres'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_resident` WHERE (sex = 'Male' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw OR `bplace` LIKE :kw OR `nationality` LIKE :kw OR `family_role` LIKE :kw OR `role` LIKE :kw OR `email` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE (sex = 'Male' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw OR `bplace` LIKE :kw OR `nationality` LIKE :kw OR `family_role` LIKE :kw OR `role` LIKE :kw OR `email` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Surname</th><th>First name</th><th>Middle name</th><th>Sex</th><th>Status</th><th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th><th>Birth date</th><th>Birth place</th><th>Nationality</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['mi']) ?></td>
           
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?></td>
            <td><?= htmlspecialchars($row['street']) ?></td>
            <td><?= htmlspecialchars($row['brgy']) ?></td>
            <td><?= htmlspecialchars($row['municipal']) ?></td>

            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="14" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Surname</th><th>First name</th><th>Middle name</th><th>Sex</th><th>Status</th><th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th><th>Birth date</th><th>Birth place</th><th>Nationality</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?></td>
            <td><?= htmlspecialchars($row['street']) ?></td>
            <td><?= htmlspecialchars($row['brgy']) ?></td>
            <td><?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="14" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

    public function femaleStaff()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $staffbmis->view_staff_female();
   
?>

<?php 
    
        $this->view('admin_report/femaleStaff.php');

    }

    public function femaleStaffSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

	// require the database connection
	if(isset($_POST['search_totalstaff'])){
		$keyword = $_POST['keyword'];
?>

<table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
		<tr>
		
			<th> Surname </th>
			<th> First name </th>
			<th> Middle name </th>
			<th> Age </th>
			<th> Sex </th>
			<th> Address </th>
			<th> Contact # </th>
			<th> Position </th>
		</tr>
	</thead>

	<tbody>     
		<?php
			$stmnt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
			or `age` LIKE '%$keyword%' or  `sex` LIKE '%$keyword%' or  `address` LIKE '%$keyword%' or  `contact` LIKE '%$keyword%'
			or `email` LIKE '%$keyword%'");
			$stmnt->execute();
			
			while($view = $stmnt->fetch()){
		?>
			<tr>
			
				<td> <?= $view['lname'];?> </td>
				<td> <?= $view['fname'];?> </td>
				<td> <?= $view['mi'];?> </td>
				<td> <?= $view['age'];?> </td>
				<td> <?= $view['sex'];?> </td>
                <td> <?= $view['address'];?> </td>
				<td> <?= $view['contact'];?> </td>
				<td> <?= $view['position'];?> </td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<?php		
	}else{
?>

<table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
		<tr>
			
			<th> Surname </th>
			<th> First name </th>
			<th> Middle name </th>
			<th> Age </th>
			<th> Sex </th>
			<th> Address </th>
			<th> Contact # </th>
			<th> Position </th>
		</tr>
	</thead>

	<tbody>
		<?php if(is_array($view)) {?>
			<?php foreach($view as $view) {?>
				<tr>
					
					<td> <?= $view['lname'];?> </td>
					<td> <?= $view['fname'];?> </td>
					<td> <?= $view['mi'];?> </td>
					<td> <?= $view['age'];?> </td>
					<td> <?= $view['sex'];?> </td>
					<td> <?= $view['address'];?> </td>
					<td> <?= $view['contact'];?> </td>
					<td> <?= $view['position'];?> </td>
				</tr>
			
			<?php
				}
			?>
		<?php
			}
		?>
	</tbody>
</table>

<?php
	}
$con = null;

    }

    public function maleStaff()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $staffbmis->view_staff_male();
   
?>

<?php 
    
        $this->view('admin_report/maleStaff.php');

    }

    public function maleStaffSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

	// require the database connection
	if(isset($_POST['search_totalstaff'])){
		$keyword = $_POST['keyword'];
?>

<table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
			
			<th> Surname </th>
			<th> First name </th>
			<th> Middle name </th>
			<th> Age </th>
			<th> Sex </th>
			<th> Address </th>
			<th> Contact # </th>
			<th> Position </th>
		</tr>
	</thead>

	<tbody>     
		<?php
			$stmnt = $conn->prepare("SELECT * FROM `tbl_user` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
			or `age` LIKE '%$keyword%' or  `sex` LIKE '%$keyword%' or  `address` LIKE '%$keyword%' or  `contact` LIKE '%$keyword%'
			or `email` LIKE '%$keyword%'");
			$stmnt->execute();
			
			while($view = $stmnt->fetch()){
		?>
			<tr>
			
				<td> <?= $view['lname'];?> </td>
				<td> <?= $view['fname'];?> </td>
				<td> <?= $view['mi'];?> </td>
				<td> <?= $view['age'];?> </td>
				<td> <?= $view['sex'];?> </td>
				<td> <?= $view['address'];?> </td>
				<td> <?= $view['contact'];?> </td>
				<td> <?= $view['position'];?> </td>
			</tr>
		<?php
		}
		?>
	</tbody>
</table>

<?php		
	}else{
?>

<table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
		<tr>
			
			<th> Surname </th>
			<th> First name </th>
			<th> Middle name </th>
			<th> Age </th>
			<th> Sex </th>
			<th> Address</th>
			<th> Contact # </th>
			<th> Position </th>
		</tr>
	</thead>
	<tbody>
		<?php if(is_array($view)) {?>
			<?php foreach($view as $view) {?>
				<tr>
					
					<td> <?= $view['lname'];?> </td>
					<td> <?= $view['fname'];?> </td>
					<td> <?= $view['mi'];?> </td>
					<td> <?= $view['age'];?> </td>
					<td> <?= $view['sex'];?> </td>
					<td> <?= $view['address'];?> </td>
					<td> <?= $view['contact'];?> </td>
					<td> <?= $view['position'];?> </td>
				</tr>
			<?php
				}
			?>
		<?php
			}
		?>
	</tbody>
</table>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-modal/2.2.6/js/bootstrap-modalmanager.min.js" integrity="sha512-/HL24m2nmyI2+ccX+dSHphAHqLw60Oj5sK8jf59VWtFWZi9vx7jzoxbZmcBeeTeCUc7z1mTs3LfyXGuBU32t+w==" crossorigin="anonymous"></script>
<!-- responsive tags for screen compatibility -->
<meta name="viewport" content="width=device-width, initial-scale=1 shrink-to-fit=no">
<!-- custom css --> 
<link href="../BarangaySystem/customcss/regiformstyle.css" rel="stylesheet" type="text/css">
<!-- bootstrap css --> 
<link href="../BarangaySystem/bootstrap/css/bootstrap.css" rel="stylesheet" type="text/css"> 
<!-- fontawesome icons -->
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
<script src="../BarangaySystem/bootstrap/js/bootstrap.bundle.js" type="text/javascript"> </script>

<?php
	}
$con = null;

    }

    public function pwd()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors', 1);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $pwd_count = $residentbmis->count_pwd();
?>

<?php 
        $this->view('admin_report/pwd.php');

    }

    public function senior()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_senior_paginated(2);
   
?>

<?php 
    
        $this->view('admin_report/senior.php');

    }

    public function seniorSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

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
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Fullname</th><th>Sex</th><th>Status</th><th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th><th>Birth date</th><th>Birth place</th><th>Nationality</th><th>Age</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?> <?= htmlspecialchars($row['fname']) ?>, <?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?></td>
            <td><?= htmlspecialchars($row['street']) ?></td>
            <td><?= htmlspecialchars($row['brgy']) ?></td>
            <td><?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
            <td><?= htmlspecialchars($row['computed_age']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="14" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Fullname</th><th>Sex</th><th>Status</th><th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th><th>Birth date</th><th>Birth place</th><th>Nationality</th><th>Age</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?> <?= htmlspecialchars($row['fname']) ?>, <?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?></td>
            <td><?= htmlspecialchars($row['street']) ?></td>
            <td><?= htmlspecialchars($row['brgy']) ?></td>
            <td><?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
            <td><?= htmlspecialchars($row['computed_age']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="14" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

    public function totalHouse()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_household_paginated(10);
   
?>

<?php 
    
        $this->view('admin_report/totalHouse.php');

    }

    public function totalHouseSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

if (isset($_POST['search_totalhouse'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_resident` WHERE (family_role = 'Yes' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE (family_role = 'Yes' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Fullname</th><th>Sex</th><th>Status</th><th>Address</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?>,<?= htmlspecialchars($row['fname']) ?><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?>, <?= htmlspecialchars($row['street']) ?>, <?= htmlspecialchars($row['brgy']) ?>, <?= htmlspecialchars($row['municipal']) ?>, <?= htmlspecialchars($row['province']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="11" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Fullname</th><th>Sex</th><th>Status</th><th>Address</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?>,<?= htmlspecialchars($row['fname']) ?><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?>, <?= htmlspecialchars($row['street']) ?>, <?= htmlspecialchars($row['brgy']) ?>, <?= htmlspecialchars($row['municipal']) ?>, <?= htmlspecialchars($row['province']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="11" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

    public function totalRes()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_paginated(10);
   $residentbmis->create_resident();
   $residentbmis->update_resident();
   $residentbmis->delete_resident();
   

   $rescount = $residentbmis->count_resident();
   $rescountm = $residentbmis->count_male_resident();
   $rescountf = $residentbmis->count_female_resident();
   $rescountfh = $residentbmis->count_head_resident();
   $rescountfm = $residentbmis->count_member_resident();
   
?>

<?php 
    
        $this->view('admin_report/totalRes.php');

    }

    public function totalResSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

if (isset($_POST['search_totalres'])) {
    $keyword = $_POST['keyword'];
    $kw = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_resident WHERE
        lname LIKE :kw OR mi LIKE :kw OR fname LIKE :kw OR age LIKE :kw OR sex LIKE :kw OR
        status LIKE :kw OR houseno LIKE :kw OR contact LIKE :kw OR bdate LIKE :kw OR
        bplace LIKE :kw OR nationality LIKE :kw OR family_role LIKE :kw OR role LIKE :kw OR
        email LIKE :kw OR brgy LIKE :kw OR street LIKE :kw OR municipal LIKE :kw");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM tbl_resident WHERE
        lname LIKE :kw OR mi LIKE :kw OR fname LIKE :kw OR age LIKE :kw OR sex LIKE :kw OR
        status LIKE :kw OR houseno LIKE :kw OR contact LIKE :kw OR bdate LIKE :kw OR
        bplace LIKE :kw OR nationality LIKE :kw OR family_role LIKE :kw OR role LIKE :kw OR
        email LIKE :kw OR brgy LIKE :kw OR street LIKE :kw OR municipal LIKE :kw
        LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info">
        <tr>
            <th>Fullname</th><th>Sex</th><th>Status</th>
            <th>House No.</th><th>Street</th><th>Barangay</th><th>Municipality</th>
            <th>Birth date</th><th>Birth place</th><th>Nationality</th><th>Family Head</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
           <td><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?>, <?= htmlspecialchars($row['street']) ?>, <?= htmlspecialchars($row['brgy']) ?>, <?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
            <td><?= htmlspecialchars($row['family_role']) ?></td>
            <td><?= htmlspecialchars($row['voter']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
        <tr><td colspan="13" class="text-muted py-3">No records found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    // $view is the $paged array from view_resident_paginated()
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info">
        <tr>
            <th>Fullname</th><th>Sex</th><th>Status</th>
            <th>Complete Address</th><th>Birth date</th>
            <th>Birth place</th><th>Nationality</th><th>Family Head</th><th>Registered Voter</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?>, <?= htmlspecialchars($row['street']) ?>, <?= htmlspecialchars($row['brgy']) ?>, <?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
            <td><?= htmlspecialchars($row['bplace']) ?></td>
            <td><?= htmlspecialchars($row['nationality']) ?></td>
            <td><?= htmlspecialchars($row['family_role']) ?></td>
            <td><?= htmlspecialchars($row['voter']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?>
        <tr><td colspan="11" class="text-muted py-3">No residents found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

    public function totalStaff()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $staffbmis->view_staff_paginated(2);
   
?>

<?php 
    
        $this->view('admin_report/totalStaff.php');

    }

    public function totalStaffSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

if (isset($_POST['search_totalstaff'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_user` WHERE (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `address` LIKE :kw OR `contact` LIKE :kw OR `position` LIKE :kw OR `role` LIKE :kw OR `email` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM `tbl_user` WHERE (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `address` LIKE :kw OR `contact` LIKE :kw OR `position` LIKE :kw OR `role` LIKE :kw OR `email` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Surname</th><th>First name</th><th>Middle name</th><th>Age</th><th>Sex</th><th>Address</th><th>Contact</th><th>Position</th><th>Role</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="9" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>Surname</th><th>First name</th><th>Middle name</th><th>Age</th><th>Sex</th><th>Address</th><th>Contact</th><th>Position</th><th>Role</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['lname']) ?></td>
            <td><?= htmlspecialchars($row['fname']) ?></td>
            <td><?= htmlspecialchars($row['mi']) ?></td>
            <td><?= htmlspecialchars($row['age']) ?></td>
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['address']) ?></td>
            <td><?= htmlspecialchars($row['contact']) ?></td>
            <td><?= htmlspecialchars($row['position']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="9" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

    public function voters()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
   error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',0);

   $userdetails = $bmis->get_userdata();
   $bmis->validate_staff_or_admin();
   $view = $residentbmis->view_resident_voters_paginated(10);
   
?>

<?php 
    
        $this->view('admin_report/voters.php');

    }

    public function votersSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

require_once __DIR__ . '/../Core/pagination_helper.php';

if (isset($_POST['search_voters'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_resident` WHERE (voter = 'Yes' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE (voter = 'Yes' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw OR `bdate` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
    <thead class="alert-info"><tr><th>#</th><<th>Fullname</th><th>Sex</th><th>Status</th><th>Address</th><th>Birth date</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?></td>
          
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?>, <?= htmlspecialchars($row['street']) ?> <?= htmlspecialchars($row['brgy']) ?> <?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="12" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } else {
    $rows  = $view['rows']  ?? [];
    $paged = $view;
?>
<table class="table table-hover text-center table-bordered" style="min-width:1000px;">
     <thead class="alert-info"><tr><th>#</th><th>Fullname</th><th>Sex</th><th>Status</th><th>Address</th><th>Birth date</th></tr></thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
             <td><?= $i++ ?></td>
<td><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?></td>
          
            <td><?= htmlspecialchars($row['sex']) ?></td>
            <td><?= htmlspecialchars($row['status']) ?></td>
            <td><?= htmlspecialchars($row['houseno']) ?>, <?= htmlspecialchars($row['street']) ?> <?= htmlspecialchars($row['brgy']) ?> <?= htmlspecialchars($row['municipal']) ?></td>
            <td><?= htmlspecialchars($row['bdate']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($rows)): ?><tr><td colspan="12" class="text-muted py-3">No records found.</td></tr><?php endif; ?>
    </tbody>
</table>
<?php render_pagination($paged); ?>

<?php } $conn = null; 

    }

}
