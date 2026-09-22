<?php

class YouthController extends Controller
{
    public function adminYouthProfile()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    
    error_reporting(E_ALL ^ E_WARNING);
    ini_set('display_errors',0);

    $userdetails = $bmis->get_userdata();
    $bmis->validate_admin();
    $bmis->delete_youth();
    $view = $bmis->view_youth();
    $id_resident = $_GET['id_resident'];
    $resident = $residentbmis->get_single_youth($id_resident);
   
?>

<?php 
    
        $this->view('youth/adminYouthProfile.php');

    }

    public function youthProfileSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  }

	// require the database connection
	if(isset($_POST['search_certofres'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
            <tr>
                <th style="width: 10%;">Actions</th>
                <th style="width: 5%;">Youth ID</th>
                <th style="width: 50%;">Surname</th>
                <th style="width: 50%;">First Name</th>
                <th style="width: 50%;">Age</th>
                <th style="width: 50%;">Sex</th>
                <th style="width: 50%;">status</th>
                <th style="width: 50%;">Email</th>
                <th style="width: 50%;">Education</th>
                <th style="width: 50%;">Employed</th>
                <th style="width: 100%;">Skills</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    </table>
</div>

    <tbody>    
        <?php
            $like = '%' . $keyword . '%';
            $stmnt = $conn->prepare("SELECT * FROM `tbl_youth` WHERE `lname` LIKE ? or `fname` LIKE ?
            or `age` LIKE ? or `id_youth` LIKE ? or `sex` LIKE ? or `civil_status` LIKE ?
            or `email_address` LIKE ? or `educ_attain` LIKE ? or `emp_status` LIKE ? or `skill_name` LIKE ?");
            $stmnt->execute([$like,$like,$like,$like,$like,$like,$like,$like,$like,$like]);
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post"> 
                        <input type="hidden" name="id_youth" value="<?= $view['id_youth'];?>">
                        <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_youth"> Delete </button>
                    </form>
                </td>
                <td> <?= $view['id_youth'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['age'];?> </td>
                <td> <?= $view['sex'];?> </td>
                <td> <?= $view['civil_status'];?> </td>
                <td> <?= $view['email_address'];?> </td>
                <td> <?= $view['educ_attain'];?> </td>
                <td> <?= $view['emp_status'];?> </td>
                <td> <?= $view['skill_name'];?> </td>

            </tr>
        <?php
        }
        ?>
    </tbody>

</table>

<?php		
	}else{
?>

<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
            <tr>
                <th style="width: 10%;">Actions</th>
                <th style="width: 8%;">Youth ID</th>
                <th style="width: 10%;">Fullname</th>
                <th style="width: 5%;">Age</th>
                <th style="width: 8%;">Sex</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 10%;">Email</th>
                <th style="width: 10%;">Education</th>
                <th style="width: 10%;">Employed</th>
                <th style="width: 10%;">Skills</th>
            </tr>
        </thead>
        <tbody>
            </tbody>
    
</div>


		<tbody>
		    <?php if(is_array($view)) {?>
                <?php foreach($view as $view) {?>
                    <tr>
                <td>    
                    <form action="" method="post">
                        <input type="hidden" name="id_youth" value="<?= $view['id_youth'];?>">
                        <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_youth"> Delete </button>
                    </form>
                </td>
                <td> <?= $view['id_youth'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['age'];?> </td>
                <td> <?= $view['sex'];?> </td>
                <td> <?= $view['civil_status'];?> </td>
                <td> <?= $view['email_address'];?> </td>
                <td> <?= $view['educ_attain'];?> </td>
                <td> <?= $view['emp_status'];?> </td>
                <td> <?= $view['skill_name'];?> </td>

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

    public function residentYouthProfile()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
date_default_timezone_set('Asia/Manila');


$userdetails = $bmis->get_userdata();
$id_resident = $userdetails['id_resident'] ?? 0;

// ── AGE GATE: block residents aged 60+ from youth profiling ──────────────────
$_age_for_gate = 0;
if (!empty($userdetails['bdate'])) {
    $_age_for_gate = (new DateTime())->diff(new DateTime($userdetails['bdate']))->y;
} elseif (!empty($userdetails['age'])) {
    $_age_for_gate = (int)$userdetails['age'];
}
if ($_age_for_gate >= 60) {
    echo "<!DOCTYPE html><html><head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1'>
        <title>Not Eligible - Barangay San Pedro</title>
        <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css' rel='stylesheet'>
        <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css'>
        </head><body class='bg-light d-flex align-items-center justify-content-center' style='min-height:100vh;'>
        <div class='text-center p-4' style='max-width:420px;'>
            <div style='font-size:4rem;'>&#x1F9D3;</div>
            <h4 class='fw-bold mt-3'>Not Eligible for Youth Profiling</h4>
            <p class='text-muted'>Youth profiling is only available for residents <strong>below 60 years old</strong>.
            Based on your birthdate, your current age is <strong>{$_age_for_gate}</strong>.</p>
            <a href='resident_homepage.php' class='btn btn-primary rounded-pill px-4'>
                <i class='bi bi-house-door-fill me-1'></i> Back to Home
            </a>
        </div>
        </body></html>";
    exit();
}
// ─────────────────────────────────────────────────────────────────────────────

$bmis->create_youth();

// ── RESOLVE YOUTH ID FIRST (always by id_resident only, no name fallback) ────
$youth_id_resolved  = null;
$existing_youth_photo = null;
$ry = $conn->prepare("SELECT id_youth, photo FROM tbl_youth WHERE id_youth = ? LIMIT 1");
$ry->execute([$id_resident]);
$ry_row = $ry->fetch(PDO::FETCH_ASSOC);
if ($ry_row) {
    $youth_id_resolved     = (int)$ry_row['id_youth'];
    $existing_youth_photo  = $ry_row['photo'] ?? null;
}

// ── ENROLL IN PROGRAM ─────────────────────────────────────────────────────────
if (isset($_POST['enroll_program'])) {
    $id_program = (int)$_POST['id_program'];

    // No profile = cannot enroll
    if (!$youth_id_resolved) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=noprofile"); exit;
    }

    // Fetch this resident's own youth record, pulling contact from their
    // resident profile (tbl_youth no longer stores a separate contact number)
    $ychk = $conn->prepare("SELECT y.id_youth, y.fname, y.lname, r.contact AS contact_number FROM tbl_youth y JOIN tbl_resident r ON r.id_resident = y.id_youth WHERE y.id_youth = ?");
    $ychk->execute([$youth_id_resolved]);
    $youth = $ychk->fetch(PDO::FETCH_ASSOC);

    if (!$youth) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=noprofile"); exit;
    }

    // Program must exist (prevents FK constraint fatal error if id_program is 0/invalid/deleted)
    $pchk = $conn->prepare("SELECT id_program, slots FROM tbl_youth_programs WHERE id_program = ?");
    $pchk->execute([$id_program]);
    $program_row = $pchk->fetch(PDO::FETCH_ASSOC);
    if (!$program_row) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=invalidprogram"); exit;
    }

    // Duplicate check
    $dup = $conn->prepare("SELECT id_enrollment FROM tbl_youth_enrollment WHERE id_program = ? AND id_youth = ?");
    $dup->execute([$id_program, $youth_id_resolved]);
    if ($dup->fetch()) {
        header("Location: resident_youth_profile.php?tab=programs&enroll=duplicate"); exit;
    }

    // Slot limit check — an empty/zero slots value means unlimited.
    if (!empty($program_row['slots'])) {
        $slotcnt = $conn->prepare("SELECT COUNT(*) FROM tbl_youth_enrollment WHERE id_program = ? AND status != 'Dropped'");
        $slotcnt->execute([$id_program]);
        if ((int)$slotcnt->fetchColumn() >= (int)$program_row['slots']) {
            header("Location: resident_youth_profile.php?tab=programs&enroll=full"); exit;
        }
    }

    $ins = $conn->prepare("INSERT INTO tbl_youth_enrollment (id_program, id_youth, youth_name, contact, status, enrolled_at) VALUES (?,?,?,?,?,?)");
    $ins->execute([
        $id_program,
        $youth_id_resolved,
        $youth['lname'].', '.$youth['fname'],
        $youth['contact_number'],
        'Enrolled',
        date('Y-m-d H:i:s')
    ]);
    header("Location: resident_youth_profile.php?tab=programs&enroll=success"); exit;
}

// ── FETCH BULLETINS ───────────────────────────────────────────────────────────
$type_filter = $_GET['type'] ?? '';
if ($type_filter) {
    $bs = $conn->prepare("SELECT * FROM tbl_youth_bulletin WHERE post_type = ? ORDER BY is_pinned DESC, date_posted DESC");
    $bs->execute([$type_filter]);
} else {
    $bs = $conn->query("SELECT * FROM tbl_youth_bulletin ORDER BY is_pinned DESC, date_posted DESC");
}
$bulletins  = $bs->fetchAll(PDO::FETCH_ASSOC);
$post_types = ['Announcement','Opportunity','Reminder','Achievement','General'];

// ── FETCH PROGRAMS ────────────────────────────────────────────────────────────
$status_filter = $_GET['pstatus'] ?? '';
if ($status_filter) {
    $ps = $conn->prepare("SELECT * FROM tbl_youth_programs WHERE status = ? ORDER BY event_date DESC");
    $ps->execute([$status_filter]);
} else {
    $ps = $conn->query("SELECT * FROM tbl_youth_programs ORDER BY event_date DESC");
}
$programs      = $ps->fetchAll(PDO::FETCH_ASSOC);
$prog_statuses = ['Upcoming','Ongoing','Completed','Cancelled'];

// Filled-slot counts per program (excludes dropped participants, since a
// dropped slot frees back up for someone else).
$prog_slot_counts = [];
$psc = $conn->query("SELECT id_program, COUNT(*) AS cnt FROM tbl_youth_enrollment WHERE status != 'Dropped' GROUP BY id_program");
foreach ($psc->fetchAll(PDO::FETCH_ASSOC) as $row) {
    $prog_slot_counts[(int)$row['id_program']] = (int)$row['cnt'];
}

// ── MY ENROLLMENTS (only if profile exists) ───────────────────────────────────
$my_enrolled = [];
if ($youth_id_resolved) {
    $yme = $conn->prepare("SELECT id_program FROM tbl_youth_enrollment WHERE id_youth = ?");
    $yme->execute([$youth_id_resolved]);
    foreach ($yme->fetchAll(PDO::FETCH_ASSOC) as $row)
        $my_enrolled[] = (int)$row['id_program'];
}

$active_tab = $_GET['tab'] ?? 'announcements';
        $this->view('youth/residentYouthProfile.php');

    }

}
