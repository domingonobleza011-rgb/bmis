<?php

class AdminResidentController extends Controller
{
    public function crud()
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
   $view = $residentbmis->view_resident_paginated(10);
   $residentbmis->create_resident();
   $residentbmis->update_resident();
   $residentbmis->delete_resident();
   $residentbmis->promote_resident();
   

   $rescount = $residentbmis->count_resident();
   $rescountm = $residentbmis->count_male_resident();
   $rescountf = $residentbmis->count_female_resident();
   $rescountfh = $residentbmis->count_head_resident();
   $rescountfm = $residentbmis->count_member_resident();
   
?>

<?php 
    
        $this->view('admin_resident/crud.php');

    }

    public function pending()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    error_reporting(E_ALL ^ E_WARNING);
    ini_set('display_errors', 0);
    
    $userdetails = $bmis->get_userdata();
    $bmis->validate_staff_or_admin();
    $admin_name = isset($userdetails['fname']) ? $userdetails['fname'] . ' ' . $userdetails['lname'] : 'Admin';

    // ---- Handle: Approve pending registration ----
    if (isset($_POST['approve_pending'])) {
        $id_pending = (int)$_POST['id_pending'];
        if ($residentbmis->approve_pending_resident($id_pending, $admin_name)) {
            header("Location: admn_resident_pending.php?toast=approved");
        } else {
            header("Location: admn_resident_pending.php?toast=error");
        }
        exit();
    }

    // ---- Handle: Reject pending registration ----
    if (isset($_POST['reject_pending'])) {
        $id_pending = (int)$_POST['id_pending'];
        $reason     = $_POST['reject_reason'] ?? '';
        if ($residentbmis->reject_pending_resident($id_pending, $admin_name, $reason)) {
            header("Location: admn_resident_pending.php?toast=rejected");
        } else {
            header("Location: admn_resident_pending.php?toast=error");
        }
        exit();
    }

    // ---- Handle: Delete a rejected/old pending record ----
    if (isset($_POST['delete_pending'])) {
        $id_pending = (int)$_POST['id_pending'];
        $residentbmis->delete_pending_resident($id_pending);
        header("Location: admn_resident_pending.php?toast=deleted");
        exit();
    }

    $pending_list  = $residentbmis->view_pending_residents();
    $pending_count = count($pending_list);

    // Rejected records kept for reference
    $connection = $bmis->openConn();
    $stmt = $connection->prepare("SELECT * FROM tbl_resident_pending WHERE application_status = 'rejected' ORDER BY date_submitted DESC");
    $stmt->execute();
    $rejected_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --navy: #0f2d5a;
    --gold: #c9943a;
    --teal: #0d9488;
    --danger: #dc2626;
    --danger-bg: #fef2f2;
    --warning: #d97706;
    --success: #059669;
    --success-bg: #ecfdf5;
    --cream: #f7f8fc;
    --border: #e8ecf0;
    --radius: 14px;
}
body { font-family: 'DM Sans', -apple-system, sans-serif !important; background: var(--cream) !important; }
.page-title { font-weight: 700; color: var(--navy); }
.card-clean { border: none; border-radius: var(--radius); box-shadow: 0 6px 24px rgba(15,45,90,0.08); }
.status-pending  { background:#fffbeb; color:#92400e; border:1px solid #fde68a; }
.status-rejected { background:#fef2f2; color:#991b1b; border:1px solid #fecaca; }
.nav-tabs .nav-link.active { color: var(--navy); font-weight: 700; border-bottom: 3px solid var(--gold); }
.nav-tabs .nav-link { color: #64748b; }

/* ── Shared Confirm Modal (Approve / Reject) ── */
.modal-overlay {
    display:none; position:fixed; inset:0;
    background:rgba(15,24,37,.45); backdrop-filter:blur(3px);
    z-index:9999; align-items:center; justify-content:center;
}
.modal-overlay.open { display:flex; }
.modal-box {
    background:#fff; border-radius:18px; padding:32px 28px;
    max-width:420px; width:92%; text-align:center;
    box-shadow:0 24px 80px rgba(15,24,37,.22); animation:popIn .2s ease;
}
@keyframes popIn {
    from { transform:scale(.93); opacity:0; }
    to   { transform:scale(1);   opacity:1; }
}
.modal-icon {
    width:56px; height:56px; border-radius:50%;
    background:var(--danger-bg); color:var(--danger);
    font-size:22px; display:flex; align-items:center; justify-content:center;
    margin:0 auto 16px;
}
.modal-icon.success { background:var(--success-bg); color:var(--success); }
.modal-box h3 { font-family:'Playfair Display',serif; color:var(--navy); margin:0 0 8px; font-size:19px; }
.modal-box p  { font-size:13px; color:#64748b; margin:0 0 20px; }
.modal-box p strong { color:var(--navy); }
.modal-box textarea { font-family:'DM Sans',sans-serif; font-size:13px; margin-bottom:20px; }
.modal-actions { display:flex; gap:10px; justify-content:center; }
.btn-modal-cancel {
    padding:10px 22px; border-radius:9px; border:1.5px solid var(--border);
    background:#fff; color:#5a6a80; font-size:13px; font-weight:600; cursor:pointer;
}
.btn-modal-confirm {
    padding:10px 22px; border-radius:9px; border:none;
    background:var(--danger); color:#fff; font-size:13px; font-weight:600; cursor:pointer;
    box-shadow:0 4px 12px rgba(220,38,38,.3);
}
.btn-modal-confirm.success { background:var(--success); box-shadow:0 4px 12px rgba(5,150,105,.3); }
</style>
<?php 
    
        $this->view('admin_resident/pending.php');

    }

    public function searchHousehold()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection 

	if(isset($_POST['search_household'])){
	$keyword = $_POST['keyword'];

   
   
?>
	<table class="table table-dark table-responsive" >
		<thead> 
		    <tr>
                <th> Actions</th>
                <th> Surname </th>
                <th> Middle name </th>
                <th> House No.</th>
                <th> Street </th>
                <th> Barangay </th>
                <th> Nationality </th>
                <th> Role </th>
                <th> Added By </th>
			</tr>
		</thead>
		<tbody>
			<?php
				$stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
				or  `age` LIKE '%$keyword%' or  `sex` LIKE '%$keyword%' or  `status` LIKE '%$keyword%' or  `address` LIKE '%$keyword%' or  `contact` LIKE '%$keyword%'
				or  `bdate` LIKE '%$keyword%' or  `bplace` LIKE '%$keyword%' or  `nationality` LIKE '%$keyword%' or  `family_role` LIKE '%$keyword%' or  `role` LIKE '%$keyword%' or  `email` LIKE '%$keyword%'");
				$stmnt->execute();
				
				while($view = $stmnt->fetch()){
			?>
			<tr>
			    <td>    
                <form action="" method="post">
                    <a href="update_resident_form.php?id_resident=<?= $view['id_resident'];?>" class="btn btn-primary">  Update </a>
                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                    <button class="btn btn-danger" type="submit" name="delete_resident"> Delete </button>
                </form>
                </td>
                <td> <?= $view['lname'];?> </td>
                <td> <?= $view['mi'];?> </td>
                <td> <?= $view['houseno'];?> </td>
                <td> <?= $view['street'];?> </td>
                <td> <?= $view['brgy'];?> </td>
                <td> <?= $view['nationality'];?> </td>
                <td> <?= $view['role'];?> </td>
                <td> <?= $view['addedby'];?> </td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
<?php		
	}else{


?>
<table class="table table-dark table-responsive">
		<thead>
			<tr>
                <th> Actions</th>
                <th> Surname </th>
                <th> Middle name </th>
                <th> House No.</th>
                <th> Street </th>
                <th> Barangay </th>
                <th> Nationality </th>
                <th> Role </th>
                <th> Added By </th>
			</tr>
		</thead>
		<tbody>
		<?php if(is_array($view)) {?>
                    <?php foreach($view as $view) {?>
			<tr>
			<td>    
                <form action="" method="post">
                <a href="update_resident_form.php?id_resident=<?= $view['id_resident'];?>" class="btn btn-primary">  Update </a>
                <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                <button class="btn btn-danger" type="submit" name="delete_resident"> Delete </button>
                 </form>
            </td>
            <td> <?= $view['lname'];?> </td>
            <td> <?= $view['mi'];?> </td>
            <td> <?= $view['houseno'];?> </td>
            <td> <?= $view['street'];?> </td>
            <td> <?= $view['brgy'];?> </td>
            <td> <?= $view['nationality'];?> </td>
            <td> <?= $view['role'];?> </td>
            <td> <?= $view['addedby'];?> </td>
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

    public function searchVoter()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_admn_voter'])){

	$keyword = $_POST['keyword'];

?>

	<table class="table table-dark table-responsive" >
		<thead> 
		    <tr>
                <th> Actions</th>
                <th> Surname </th>
                <th> Middle name </th>
                <th> House No.</th>
                <th> Street </th>
                <th> Barangay </th>
                <th> Nationality </th>
                <th> Role </th>
                <th> Added By </th>
			</tr>
		</thead>
		<tbody>
			<?php
				$stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
				or `age` LIKE '%$keyword%' or   `status` LIKE '%$keyword%' or  `contact` LIKE '%$keyword%'
				or `nationality` LIKE '%$keyword%' or `family_role` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%' or  `street` LIKE '%$keyword%' or  `brgy` LIKE '%$keyword%'");
				$stmnt->execute();
				
				while($view = $stmnt->fetch()){
			?>
			<tr>
			    <td>    
                <form action="" method="post">
                    <a href="update_resident_form.php?id_resident=<?= $view['id_resident'];?>" class="btn btn-primary">  Update </a>
                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                    <button class="btn btn-danger" type="submit" name="delete_resident"> Delete </button>
                </form>
                </td>
                <td> <?= $view['lname'];?> </td>
                <td> <?= $view['mi'];?> </td>
                <td> <?= $view['houseno'];?> </td>
                <td> <?= $view['street'];?> </td>
                <td> <?= $view['brgy'];?> </td>
                <td> <?= $view['nationality'];?> </td>
                <td> <?= $view['role'];?> </td>
                <td> <?= $view['addedby'];?> </td>
			</tr>
			<?php
			}
			?>
		</tbody>
	</table>
<?php		
	}else{

?>
<table class="table table-dark table-responsive">
		<thead>
			<tr>
                <th> Actions</th>
                <th> Surname </th>
                <th> Middle name </th>
                <th> House No.</th>
                <th> Street </th>
                <th> Barangay </th>
                <th> Nationality </th>
                <th> Role </th>
                <th> Added By </th>
			</tr>
		</thead>
		<tbody>
		<?php if(is_array($view)) {?>
                    <?php foreach($view as $view) {?>
			<tr>
			<td>    
                <form action="" method="post">
                <a href="update_resident_form.php?id_resident=<?= $view['id_resident'];?>" class="btn btn-primary">  Update </a>
                <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                <button class="btn btn-danger" type="submit" name="delete_resident"> Delete </button>
                 </form>
            </td>
            <td> <?= $view['lname'];?> </td>
            <td> <?= $view['mi'];?> </td>
            <td> <?= $view['houseno'];?> </td>
            <td> <?= $view['street'];?> </td>
            <td> <?= $view['brgy'];?> </td>
            <td> <?= $view['nationality'];?> </td>
            <td> <?= $view['role'];?> </td>
            <td> <?= $view['addedby'];?> </td>
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

    public function search()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    require_once __DIR__ . '/../Core/pagination_helper.php';

    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

function format_resident_address(array $row): string {
        $parts = [
            $row['houseno']   ?? '',
            $row['street']    ?? '',
            $row['brgy']      ?? '',
            $row['municipal'] ?? '',
            $row['province']  ?? '',
        ];
        $parts = array_filter(array_map('trim', $parts), fn($p) => $p !== '');
 
        if (!empty($parts)) {
            return implode(', ', $parts);
        }
 
        // Structured fields are empty -> this is a pre-PSGC-fix account, use the legacy column
        return trim($row['address'] ?? '');
    }
    if (isset($_POST['search_resident'])) {
        $keyword = $_POST['keyword'];
        $kw = "%$keyword%";

        $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_resident WHERE
            lname LIKE :kw OR mi LIKE :kw OR fname LIKE :kw OR age LIKE :kw OR sex LIKE :kw OR
            status LIKE :kw OR address LIKE :kw OR contact LIKE :kw OR bdate LIKE :kw OR
            bplace LIKE :kw OR nationality LIKE :kw OR family_role LIKE :kw OR role LIKE :kw OR email LIKE :kw");
        $countStmt->execute([':kw' => $kw]);
        $total = (int)$countStmt->fetchColumn();

        $offset = ($page - 1) * $perPage;
        $stmnt = $conn->prepare("SELECT * FROM tbl_resident WHERE
            lname LIKE :kw OR mi LIKE :kw OR fname LIKE :kw OR age LIKE :kw OR sex LIKE :kw OR
            status LIKE :kw OR address LIKE :kw OR contact LIKE :kw OR bdate LIKE :kw OR
            bplace LIKE :kw OR nationality LIKE :kw OR family_role LIKE :kw OR role LIKE :kw OR email LIKE :kw
            LIMIT $perPage OFFSET $offset");
        $stmnt->execute([':kw' => $kw]);
        $rows = $stmnt->fetchAll();

        $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];

    } else {
        // $view is already the $paged array from view_resident_paginated()
        $rows  = $view['rows'] ?? [];
        $paged = $view;
    }
?>

<table class="table table-hover text-center table-bordered" style="min-width: 1000px;">
    <thead class="alert-info">
        <tr>
            <th>Actions</th>
            <th>Resident ID</th>
            <th>Full Name</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td>
                    <form action="" method="post">
                        <input type="hidden" name="id_resident" value="<?= $row['id_resident'] ?>">
                        <div class="dropdown">
                            <button type="button" class="btn btn-light btn-sm border" style="width:34px;height:34px;padding:0;border-radius:50%;" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['id_resident'] ?>"><i class="fa fa-eye mr-2 text-primary"></i> View</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#messageModal<?= $row['id_resident'] ?>"><i class="fas fa-comment-alt mr-2 text-info"></i> Message</a></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#promoteModal<?= $row['id_resident'] ?>"><i class="fas fa-user-tie mr-2 text-warning"></i> Promote</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><button type="submit" name="delete_resident" class="dropdown-item text-danger"><i class="fas fa-archive mr-2"></i> Archive</button></li>
                            </ul>
                        </div>
                    </form>
                </td>
                <td><?= $row['id_resident'] ?></td>
                <td><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?></td>
                <td><?= htmlspecialchars(format_resident_address($row)) ?></td>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal<?= $row['id_resident'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Resident Information</h5>
                                <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body text-left">
                                <p><strong>Resident ID:</strong> <?= $row['id_resident'] ?></p>
                                <hr>
                                <h5><strong>Personal Information</strong></h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Full Name:</strong><br><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?>.</p>
                                        <p><strong>Sex:</strong> <?= htmlspecialchars($row['sex']) ?></p>
                                        <p><strong>Civil Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Nationality:</strong> <?= htmlspecialchars($row['nationality']) ?></p>
                                        <p><strong>Birth Date:</strong> <?= htmlspecialchars($row['bdate']) ?></p>
                                        <p><strong>Birth Place:</strong> <?= htmlspecialchars($row['bplace']) ?></p>
                                        <p><strong>Family Role:</strong> <?= htmlspecialchars($row['family_role']) ?></p>
                                    </div>
                                </div>
                                <hr>
                                <h5><strong>Contact & Address</strong></h5>
                                <p><strong>Address:</strong> <?= htmlspecialchars(format_resident_address($row)) ?></p>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $row['id_resident'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $row['id_resident'] ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Promote Modal -->
                <div class="modal fade" id="promoteModal<?= $row['id_resident'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header text-white" style="background:linear-gradient(135deg,#b8860b,#daa520);">
                                <h5 class="modal-title"><i class="fas fa-user-tie mr-2"></i> Promote to Barangay Staff</h5>
                                <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <form action="admn_resident_crud.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="alert alert-warning" style="border-radius:10px;">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        You are about to promote <strong><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></strong> to a Barangay Staff member.
                                    </div>
                                    <div class="form-group">
                                        <label><strong>Assign Position:</strong></label>
                                        <select class="form-control" name="position" required style="border-radius:10px;">
                                            <option value="">-- Choose Position --</option>
                                            <option value="Punong Barangay">Punong Barangay</option>
                                            <option value="Secretary">Secretary</option>
                                            <option value="Treasurer">Treasurer</option>
                                            <option value="Clerk">Clerk</option>
                                            <option value="Book Keeper">Book Keeper</option>
                                            <option value="Committee on Appropriation">Committee on Appropriation</option>
                                            <option value="Committee on Health">Committee on Health</option>
                                            <option value="Committee on Women and Children">Committee on Women and Children</option>
                                            <option value="Committee on Education">Committee on Education</option>
                                            <option value="Committee on Peace and Order">Committee on Peace and Order</option>
                                            <option value="Committee on Infrastructure">Committee on Infrastructure</option>
                                            <option value="Committee on Ways and Means">Committee on Ways and Means</option>
                                            <option value="Committee on Agriculture">Committee on Agriculture</option>
                                            <option value="Committee on Tourism">Committee on Tourism</option>
                                            <option value="IPMRR Representative">IPMRR Representative</option>
                                            <option value="Sk Chairperson">Sk Chairperson</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="promote_id_resident" value="<?= $row['id_resident'] ?>">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="promote_resident" class="btn text-white" style="border-radius:30px;width:140px;background:linear-gradient(135deg,#b8860b,#daa520);">
                                        <i class="fas fa-level-up-alt mr-1"></i> Promote
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-muted py-3">No residents found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php render_pagination($paged); ?>

<?php if (!empty($_SESSION['swal'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire(<?= $_SESSION['swal'] ?>);
    });
</script>
<?php unset($_SESSION['swal']); endif; ?>

<?php $conn = null; 

    }

}
