<?php

class AdminCertificateController extends Controller
{
    public function brgyClearance()
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
    $bmis->delete_clearance();
    $bmis->create_brgyclearance();
    $view = $bmis->view_clearance();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $resident = $residentbmis->get_single_clearance($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/brgyClearance.php');

    }

    public function brgyClearanceSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_clearance'])){
		$keyword = $_POST['keyword'];

				$stmnt = $conn->prepare("SELECT * FROM `tbl_clearance` WHERE `lname` LIKE ? or `mi` LIKE ? or `fname` LIKE ?
		or `age` LIKE ? or `id_resident` LIKE ? or `houseno` LIKE ? or `street` LIKE ?
		or `brgy` LIKE ? or `municipal` LIKE ? or `purpose` LIKE ? or `status` LIKE ?");
		$like = '%' . $keyword . '%';
		$stmnt->execute([$like, $like, $like, $like, $like, $like, $like, $like, $like, $like, $like]);
		$rows = $stmnt->fetchAll();

?>

<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;">
        <thead class="alert-info">
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Purpose </th>
            <th> Address </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $view): ?>
            <tr>
                <td>
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="brgyclearance_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a>
                        <input type="hidden" name="id_clearance" value="<?= $view['id_clearance']; ?>">
                        <button class="btn btn-danger"  style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_clearance"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'] ?>_<?= $view['id_clearance'] ?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'] ?>_<?= $view['id_clearance'] ?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
               <td> <?= $view['id_resident'];?> </td>
                    <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?>. </td>
                    <td> <?= $view['purpose'];?> </td>
                    <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>

<?php foreach ($rows as $view): ?>
    <div class="modal fade" id="messageModal<?= $view['id_resident'] ?>_<?= $view['id_clearance'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                </div>
                <form action="send_resident_msg.php" method="POST">
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label><strong>Recipient:</strong></label>
                            <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label><strong>Message Content:</strong></label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                        </div>
                        <input type="hidden" name="id_resident" value="<?= $view['id_resident'] ?>">
                        <input type="hidden" name="redirect_to" value="admn_brgyclearance.php">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                        <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>

<?php
	}else{
	    // Preserve the original list — don't let the loops below overwrite $view
	    $clearance_list = is_array($view) ? $view : [];
?>

<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;">
        <thead class="alert-info">
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Purpose </th>
            <th> Address </th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($clearance_list as $view): ?>
                <tr>
                    <td>
                        <form action="" method="post">
                            <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="brgyclearance_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a>
                            <input type="hidden" name="id_clearance" value="<?= $view['id_clearance']; ?>">
                            <button class="btn btn-danger"  style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_clearance"> Archive </button>
                            <?php if ((int)$view['id_resident'] === 0): ?>
                            <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'] ?>_<?= $view['id_clearance'] ?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'] ?>_<?= $view['id_clearance'] ?>">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td> <?= $view['id_resident'];?> </td>
                    <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?>. </td>
                    <td> <?= $view['purpose'];?> </td>
                    <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php foreach ($clearance_list as $view): ?>
        <div class="modal fade" id="messageModal<?= $view['id_resident'] ?>_<?= $view['id_clearance'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                        <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <form action="send_resident_msg.php" method="POST">
                        <div class="modal-body text-left">
                            <div class="form-group">
                                <label><strong>Recipient:</strong></label>
                                <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                            </div>
                            <div class="form-group mt-3">
                                <label><strong>Message Content:</strong></label>
                                <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                            </div>
                            <input type="hidden" name="id_resident" value="<?= $view['id_resident'] ?>">
                        <input type="hidden" name="redirect_to" value="admn_brgyclearance.php">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                            <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
<?php endforeach; ?>

<?php
	}
$con = null;

    }

    public function brgyId()
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
    $bmis->delete_brgyid();
    $bmis->create_brgyid();
    $view = $bmis->view_brgyid();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $residentbmis->get_single_brgyid($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/brgyId.php');

    }

    public function brgyIdSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
if (!defined('BMIS_ROLE_REQUIRED')) {  require_once('secure_header.php'); }

	// require the database connection
	if(isset($_POST['search_brgyid'])){
		$keyword = $_POST['keyword'];
?>
<table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
        
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Address </th>
        </tr>
    </thead>

    <tbody id="cert-tbody-brgyid"> 
        <?php
            
            $stmnt = $conn->prepare("SELECT * FROM `tbl_brgyid` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
            or `brgyid` LIKE '%$keyword%' or  `id_resident` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%' or  `street` LIKE '%$keyword%'
            or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `industry` LIKE '%$keyword%' or `aoe` LIKE '%$keyword%' ");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="barangayid_form.php?id_brgyid=<?= $view['id_brgyid'];?>">Generate</a> 
                        <input type="hidden" name="id_brgyid" value="<?= $view['id']; ?>">
                        <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_brgyid"> Delete </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?></td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>


                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_brgyid.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
           <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Address </th>

        </tr>
    </thead>
    
    <tbody id="cert-tbody-brgyid">
        <?php if(is_array($view)) {?>
            <?php foreach($view as $row) {?>
                <tr>
                    <td>    
                        <form action="" method="post">
                            <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="barangayid_form.php?id_brgyid=<?= $row['id_brgyid'];?>">Generate</a> 
                            <input type="hidden" name="id_brgyid" value="<?= $row['id_brgyid']; ?>">
                            <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_brgyid"> Delete </button>
                            <?php if ((int)$row['id_resident'] === 0): ?>
                            <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $row['id_resident'];?>">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                                    <td> <?= $row['id_resident'];?> </td> 
                <td> <?= $row['lname'];?>, <?= $row['fname'];?> <?= $row['mi'];?></td>
                <td> <?= $row['houseno'];?>, <?= $row['street'];?>, <?= $row['brgy'];?>, <?= $row['municipal'];?> </td>


                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $row['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
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
                                    <input type="hidden" name="id_resident" value="<?= $row['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_brgyid.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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

    public function bsPermit()
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
    $bmis->delete_bspermit();
    $bmis->create_bspermit();
    $view = $bmis->view_bspermit();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $resident = $residentbmis->get_single_bspermit($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/bsPermit.php');

    }

    public function bsPermitSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_bspermit'])){
		$keyword = $_POST['keyword'];
?>

<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Fullname </th>
            <th> Address </th>
            <th> Business Name </th>
        </tr>
    </thead>
</div>
    <tbody>     
        <?php
            
            $stmnt = $conn->prepare("SELECT * FROM `tbl_bspermit` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
            or `bsname` LIKE '%$keyword%' or  `id_resident` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%' or  `street` LIKE '%$keyword%'
            or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `bsindustry` LIKE '%$keyword%' or `aoe` LIKE '%$keyword%' ");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success"  style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="businesspermit_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_bspermitid" value="<?= $view['id_bspermit'];?>">
                        <button class="btn btn-danger"  style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_certofres"> Delete</button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
<td> <?= $view['id_resident'];?> </td> 
                    <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                    <td> <?= $view['houseno'];?>,  <?= $view['street'];?>, <?= $view['brgy'];?>  <?= $view['municipal'];?></td>
                    <td> <?= $view['bsname'];?> </td>
                <td> 
                    

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_bspermit.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Fullname </th>
            <th> Address </th>
            <th> Business Name </th>

        </tr>
    </thead>
</div>

    <tbody>
        <?php if(is_array($view)) {?>
            <?php foreach($view as $view) {?>
                <tr>
                    <td>    
                        <form action="" method="post">
                            <a class="btn btn-success"  style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="businesspermit_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                            <input type="hidden" name="id_bspermit" value="<?= $view['id_bspermit'];?>">
                            <button class="btn btn-danger"  style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_bspermit"> Delete </button>
                            <?php if ((int)$view['id_resident'] === 0): ?>
                            <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td> <?= $view['id_resident'];?> </td> 
                    <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                    <td> <?= $view['houseno'];?>,  <?= $view['street'];?>, <?= $view['brgy'];?>  <?= $view['municipal'];?></td>
                    <td> <?= $view['bsname'];?> </td>

                    <!-- Message Modal -->
                    <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                </div>
                                <form action="send_resident_msg.php" method="POST">
                                    <div class="modal-body text-left">
                                        <div class="form-group">
                                            <label><strong>Recipient:</strong></label>
                                            <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                        </div>
                                        <div class="form-group mt-3">
                                            <label><strong>Message Content:</strong></label>
                                            <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                        </div>
                                        <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                        <input type="hidden" name="redirect_to" value="admn_bspermit.php">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                        <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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

    public function certOfGoodMoral()
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
    $bmis->delete_certofgoodmoral();
    $bmis->create_certofgoodmoral();
    $view = $bmis->view_certofgoodmoral();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $resident = $bmis->get_single_certofgoodmoral($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/certOfGoodMoral.php');

    }

    public function certOfGoodMoralSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_certofgoodmoral'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
        
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Civil Status </th>
            <th> Address </th>
            <th> Date </th>
            <th> Purpose </th>
        </tr>
    </thead>
</div>
    <tbody>    
        <?php
            $stmnt = $conn->prepare("SELECT * FROM `tbl_goodmoral` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
            or `civil_status` LIKE '%$keyword%' or  `id_resident` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%'
            or `street` LIKE '%$keyword%' or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `date` LIKE '%$keyword%' or `purpose` LIKE '%$keyword%'");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="goodmoralcert_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_goodmoral" value="<?= $view['id_goodmoral'];?>">
                        <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_certofgoodmoral"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['civil_status'];?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                <td> <?= $view['date'];?> </td>
                <td> <?= $view['purpose'];?> </td>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certofgoodmoral.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

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
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Civil Status </th>
            <th> Address </th>
            <th> Date </th>
            <th> Purpose </th>
			</tr>
		</thead>

		<tbody>
		    <?php if(is_array($view)) {?>
                <?php foreach($view as $view) {?>
                    <tr>
                        <td>    
                            <form action="" method="post">
                                <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="goodmoralcert_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                                <input type="hidden" name="id_goodmoral" value="<?= $view['id_goodmoral'];?>">
                                <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_certofgoodmoral"> Archive </button>
                                <?php if ((int)$view['id_resident'] === 0): ?>
                                <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                    <i class="fas fa-comment-alt"></i> Message
                                </button>
                                <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                                    <i class="fas fa-comment-alt"></i> Message
                                </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td> <?= $view['id_resident'];?> </td> 
                        <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                        <td> <?= $view['civil_status'];?> </td>
                        <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                        <td> <?= $view['date'];?> </td>
                        <td> <?= $view['purpose'];?> </td>

                        <!-- Message Modal -->
                        <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="send_resident_msg.php" method="POST">
                                        <div class="modal-body text-left">
                                            <div class="form-group">
                                                <label><strong>Recipient:</strong></label>
                                                <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label><strong>Message Content:</strong></label>
                                                <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                            </div>
                                            <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                            <input type="hidden" name="redirect_to" value="admn_certofgoodmoral.php">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                            <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </tr>
                <?php
                    }
                ?>
			<?php
				}
			?>
		</tbody>

	</table>
</div>
<?php
	}
$con = null;

    }

    public function certOfGuardianship()
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
    $bmis->delete_certofguardianship();
    $bmis->create_certofguardianship();
    $view = $bmis->view_certofguardianship();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $residentbmis->get_single_certofguardianship($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/certOfGuardianship.php');

    }

    public function certOfGuardianshipSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_certofguardianship'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">

        <tr>
             <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Minor </th>
            <th> Address </th>
        </tr>
    </thead>
</div>
    <tbody>     
        <?php
            $stmnt = $conn->prepare("SELECT * FROM `tbl_guardianship` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
                or  `id_resident` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%'
            or `street` LIKE '%$keyword%' or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `date` LIKE '%$keyword%' or `purpose` LIKE '%$keyword%' or `minor_name` LIKE '%$keyword%'");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="guardianship_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_guardianship" value="<?= $view['id_guardianship'];?>">
                        <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_certofguardianship"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_guardianship'];?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_guardianship'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                                    <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= htmlspecialchars($view['minor_name']);?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>_<?= $view['id_guardianship'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certofguardianship.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Minor </th>
            <th> Address </th>
        </tr>
    </thead>
</div>
    <tbody>
        <?php if(is_array($view)) {?>
            <?php foreach($view as $view) {?>
                <tr>
                    <td>    
                        <form action="" method="post">
                            <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="guardianship_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                            <input type="hidden" name="id_guardianship" value="<?= $view['id_guardianship'];?>">
                            <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_certofguardianship"> Archive </button>
                            <?php if ((int)$view['id_resident'] === 0): ?>
                            <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_guardianship'];?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_guardianship'];?>">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= htmlspecialchars($view['minor_name']);?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>


                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>_<?= $view['id_guardianship'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certofguardianship.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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

    public function certOfIndigency()
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
    $bmis->delete_certofindigency();
    $bmis->create_certofindigency();
    $view = $bmis->view_certofindigency();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $residentbmis->get_single_certofindigency($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/certOfIndigency.php');

    }

    public function certOfIndigencySearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_certofindigency'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">

        <tr>
             <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Address </th>
        </tr>
    </thead>
</div>
    <tbody>     
        <?php
            $stmnt = $conn->prepare("SELECT * FROM `tbl_indigency` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
                or  `id_resident` LIKE '%$keyword%' or  `nationality` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%'
            or `street` LIKE '%$keyword%' or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `date` LIKE '%$keyword%' or `purpose` LIKE '%$keyword%'");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="indigency_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_indigency" value="<?= $view['id_indigency'];?>">
                        <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_certofindigency"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_indigency'];?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_indigency'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                                    <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>_<?= $view['id_indigency'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certofindigency.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Address </th>
        </tr>
    </thead>
</div>
    <tbody>
        <?php if(is_array($view)) {?>
            <?php foreach($view as $view) {?>
                <tr>
                    <td>    
                        <form action="" method="post">
                            <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="indigency_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                            <input type="hidden" name="id_indigency" value="<?= $view['id_indigency'];?>">
                            <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_certofindigency"> Archive </button>
                            <?php if ((int)$view['id_resident'] === 0): ?>
                            <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_indigency'];?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_indigency'];?>">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>


                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>_<?= $view['id_indigency'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certofindigency.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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

    public function certOfLivestock()
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
    $bmis->delete_certoflivestock();
    $bmis->create_certoflivestock();
    $view = $bmis->view_certoflivestock();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $resident = $bmis->get_single_certoflivestock($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/certOfLivestock.php');

    }

    public function certOfLivestockSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_certoflivestock'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
        
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Animal Type </th>
            <th> Qty </th>
            <th> Address </th>
            <th> Date </th>
            <th> Purpose </th>
        </tr>
    </thead>
</div>
    <tbody>    
        <?php
            $stmnt = $conn->prepare("SELECT * FROM `tbl_livestock` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
            or `animal_type` LIKE '%$keyword%' or  `id_resident` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%'
            or `street` LIKE '%$keyword%' or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `date` LIKE '%$keyword%' or `purpose` LIKE '%$keyword%'");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="livestockcert_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_livestock" value="<?= $view['id_livestock'];?>">
                        <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_certoflivestock"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['animal_type'];?> </td>
                <td> <?= $view['quantity'];?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                <td> <?= $view['date'];?> </td>
                <td> <?= $view['purpose'];?> </td>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certoflivestock.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

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
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Animal Type </th>
            <th> Qty </th>
            <th> Address </th>
            <th> Date </th>
            <th> Purpose </th>
			</tr>
		</thead>

		<tbody>
		    <?php if(is_array($view)) {?>
                <?php foreach($view as $view) {?>
                    <tr>
                        <td>    
                            <form action="" method="post">
                                <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="livestockcert_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                                <input type="hidden" name="id_livestock" value="<?= $view['id_livestock'];?>">
                                <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_certoflivestock"> Archive </button>
                                <?php if ((int)$view['id_resident'] === 0): ?>
                                <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                    <i class="fas fa-comment-alt"></i> Message
                                </button>
                                <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                                    <i class="fas fa-comment-alt"></i> Message
                                </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td> <?= $view['id_resident'];?> </td> 
                        <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                        <td> <?= $view['animal_type'];?> </td>
                        <td> <?= $view['quantity'];?> </td>
                        <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                        <td> <?= $view['date'];?> </td>
                        <td> <?= $view['purpose'];?> </td>

                        <!-- Message Modal -->
                        <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="send_resident_msg.php" method="POST">
                                        <div class="modal-body text-left">
                                            <div class="form-group">
                                                <label><strong>Recipient:</strong></label>
                                                <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label><strong>Message Content:</strong></label>
                                                <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                            </div>
                                            <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                            <input type="hidden" name="redirect_to" value="admn_certoflivestock.php">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                            <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </tr>
                <?php
                    }
                ?>
			<?php
				}
			?>
		</tbody>

	</table>
</div>
<?php
	}
$con = null;

    }

    public function certOfRes()
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
    $bmis->delete_certofres();
    $bmis->create_certofres();
    $view = $bmis->view_certofres();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $resident = $residentbmis->get_single_certofres($id_resident);
   
?>

<?php 
    
        $this->view('admin_certificate/certOfRes.php');

    }

    public function certOfResSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_certofres'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
        
        <tr>
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Age </th>
            <th> Nationality </th>
            <th> Address </th>
            <th> Date </th>
            <th> Purpose </th>
        </tr>
    </thead>
</div>
    <tbody>    
        <?php
            $stmnt = $conn->prepare("SELECT * FROM `tbl_rescert` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
            or `age` LIKE '%$keyword%' or  `id_resident` LIKE '%$keyword%' or  `nationality` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%'
            or `street` LIKE '%$keyword%' or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `date` LIKE '%$keyword%' or `purpose` LIKE '%$keyword%'");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="rescert_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_rescert" value="<?= $view['id_rescert'];?>">
                        <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_certofres"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= $view['age'];?> </td>
                <td> <?= $view['nationality'];?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                <td> <?= $view['date'];?> </td>
                <td> <?= $view['purpose'];?> </td>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                            <div class="modal-header bg-info text-white">
                                <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <form action="send_resident_msg.php" method="POST">
                                <div class="modal-body text-left">
                                    <div class="form-group">
                                        <label><strong>Recipient:</strong></label>
                                        <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                    </div>
                                    <div class="form-group mt-3">
                                        <label><strong>Message Content:</strong></label>
                                        <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                    </div>
                                    <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                    <input type="hidden" name="redirect_to" value="admn_certofres.php">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                    <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

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
            <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Age </th>
            <th> Nationality </th>
            <th> Address </th>
            <th> Date </th>
            <th> Purpose </th>
			</tr>
		</thead>

		<tbody>
		    <?php if(is_array($view)) {?>
                <?php foreach($view as $view) {?>
                    <tr>
                        <td>    
                            <form action="" method="post">
                                <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="rescert_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                                <input type="hidden" name="id_rescert" value="<?= $view['id_rescert'];?>">
                                <button class="btn btn-danger" type="submit" style="width: 90px; font-size: 17px; border-radius:30px;" name="delete_certofres"> Archive </button>
                                <?php if ((int)$view['id_resident'] === 0): ?>
                                <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                    <i class="fas fa-comment-alt"></i> Message
                                </button>
                                <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>">
                                    <i class="fas fa-comment-alt"></i> Message
                                </button>
                                <?php endif; ?>
                            </form>
                        </td>
                        <td> <?= $view['id_resident'];?> </td> 
                        <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                        <td> <?= $view['age'];?> </td>
                        <td> <?= $view['nationality'];?> </td>
                        <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>
                        <td> <?= $view['date'];?> </td>
                        <td> <?= $view['purpose'];?> </td>

                        <!-- Message Modal -->
                        <div class="modal fade" id="messageModal<?= $view['id_resident'];?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                                    <div class="modal-header bg-info text-white">
                                        <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                                        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                                    </div>
                                    <form action="send_resident_msg.php" method="POST">
                                        <div class="modal-body text-left">
                                            <div class="form-group">
                                                <label><strong>Recipient:</strong></label>
                                                <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($view['fname']) ?> <?= htmlspecialchars($view['lname']) ?>" readonly>
                                            </div>
                                            <div class="form-group mt-3">
                                                <label><strong>Message Content:</strong></label>
                                                <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                                            </div>
                                            <input type="hidden" name="id_resident" value="<?= $view['id_resident'];?>">
                                            <input type="hidden" name="redirect_to" value="admn_certofres.php">
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                            <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </tr>
                <?php
                    }
                ?>
			<?php
				}
			?>
		</tbody>

	</table>
</div>
<?php
	}
$con = null;

    }

    public function certificatesData()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * admn_certificates_data.php
 * ─────────────────────────────────────────────────────────────
 * Lightweight JSON endpoint that powers the "Certificates Issued"
 * grouped bar chart on admn_dashboard.php.
 *
 * Source of truth: tbl_activity_log, action = 'GENERATE_DOCUMENT'
 * (already written on every certificate/permit/ID generation by
 *  rescert_form.php, indigency_form.php, brgyclearance_form.php,
 *  businesspermit_form.php and barangayid_form.php via log_activity()).
 *
 * GET params:
 *   range = week | month | year   (default: week)
 *
 * Response shape (Chart.js friendly):
 * {
 *   "range": "week",
 *   "labels": ["Jul 7–13", "Jul 14–20", ...],
 *   "datasets": [
 *       { "label": "Certificate of Residency", "data": [3,5,2,...] },
 *       { "label": "Certificate of Indigency",  "data": [1,0,4,...] },
 *       ...
 *   ],
 *   "total": 42
 * }
 */

error_reporting(0);


header('Content-Type: application/json');
header('Cache-Control: no-store');

date_default_timezone_set('Asia/Manila');

// ── Validate requested range ─────────────────────────────────
$range = $_GET['range'] ?? 'week';
if (!in_array($range, ['week', 'month', 'year'], true)) {
    $range = 'week';
}

// How many buckets to show per range
$bucketCounts = ['week' => 8, 'month' => 6, 'year' => 5];
$bucketCount  = $bucketCounts[$range];

$now = new DateTime('now');

// ── Build the ordered list of empty buckets + figure out the earliest datetime to query ──
$buckets    = []; // key => label
$rangeStart = null;

if ($range === 'week') {
    $mondayThisWeek = clone $now;
    $mondayThisWeek->modify('monday this week');

    for ($i = $bucketCount - 1; $i >= 0; $i--) {
        $ws = clone $mondayThisWeek;
        $ws->modify("-{$i} week");
        $we = clone $ws;
        $we->modify('+6 day');

        $key   = $ws->format('Y-m-d');
        $label = $ws->format('M j') . '–' . $we->format('j');
        $buckets[$key] = $label;

        if ($rangeStart === null) $rangeStart = clone $ws;
    }
} elseif ($range === 'month') {
    for ($i = $bucketCount - 1; $i >= 0; $i--) {
        $d = clone $now;
        $d->modify('first day of this month');
        $d->modify("-{$i} month");

        $key = $d->format('Y-m');
        $buckets[$key] = $d->format('M Y');

        if ($rangeStart === null) $rangeStart = clone $d;
    }
} else { // year
    for ($i = $bucketCount - 1; $i >= 0; $i--) {
        $y = (int)$now->format('Y') - $i;
        $buckets[(string)$y] = (string)$y;

        if ($rangeStart === null) $rangeStart = new DateTime("$y-01-01");
    }
}

$rangeStart->setTime(0, 0, 0);

// ── Helper: compute which bucket key a given DateTime falls into ──
function bucket_key_for(DateTime $dt, string $range): string {
    if ($range === 'week') {
        $d = clone $dt;
        $day = (int)$d->format('N'); // 1 (Mon) .. 7 (Sun)
        if ($day > 1) $d->modify('-' . ($day - 1) . ' day');
        $d->setTime(0, 0, 0);
        return $d->format('Y-m-d');
    } elseif ($range === 'month') {
        return $dt->format('Y-m');
    }
    return $dt->format('Y');
}

// ── Friendly display names / colors for known document types ──
// (falls back gracefully to whatever string is actually stored in `module`)
$knownColors = [
    'Certificate of Residency'  => '#1a4480', // navy-light
    'Certificate of Indigency'  => '#c9943a', // gold
    'Barangay Clearance'        => '#0d9488', // teal
    'Business Permit'           => '#dc2626', // danger
    'Barangay ID'               => '#6f42c1', // purple
];
$fallbackPalette = ['#2b5ea7', '#e8b86d', '#059669', '#d97706', '#0891b2', '#be185d'];

try {
    $stmt = $conn->prepare("
        SELECT module, created_at
        FROM tbl_activity_log
        WHERE action = 'GENERATE_DOCUMENT'
          AND created_at >= :start
        ORDER BY created_at ASC
    ");
    $stmt->execute([':start' => $rangeStart->format('Y-m-d H:i:s')]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $rows = [];
}

// ── Tally counts: [module][bucketKey] = count ──
$tally   = [];
$modules = []; // preserve first-seen order, known types first

foreach (array_keys($knownColors) as $km) {
    $modules[$km] = true;
}

foreach ($rows as $row) {
    $mod = trim($row['module']) !== '' ? trim($row['module']) : 'Other Document';
    $modules[$mod] = true;

    try {
        $dt = new DateTime($row['created_at']);
    } catch (Exception $e) {
        continue;
    }
    $bkey = bucket_key_for($dt, $range);

    if (!isset($tally[$mod])) $tally[$mod] = [];
    if (!isset($tally[$mod][$bkey])) $tally[$mod][$bkey] = 0;
    $tally[$mod][$bkey]++;
}

// ── Only keep modules that actually have at least one record in range ──
$activeModules = [];
foreach (array_keys($modules) as $mod) {
    $hasData = isset($tally[$mod]) && array_sum($tally[$mod]) > 0;
    if ($hasData) $activeModules[] = $mod;
}

// If nothing at all was logged yet, still show the 5 known categories at zero
// so the chart renders a friendly empty state instead of looking broken.
if (empty($activeModules)) {
    $activeModules = array_keys($knownColors);
}

// ── Build Chart.js dataset payload ──
$labels     = array_values($buckets);
$bucketKeys = array_keys($buckets);
$datasets   = [];
$colorIdx   = 0;
$total      = 0;

foreach ($activeModules as $mod) {
    $data = [];
    foreach ($bucketKeys as $bkey) {
        $c = $tally[$mod][$bkey] ?? 0;
        $data[] = (int)$c;
        $total += (int)$c;
    }
    $color = $knownColors[$mod] ?? $fallbackPalette[$colorIdx++ % count($fallbackPalette)];
    $datasets[] = [
        'label' => $mod,
        'data'  => $data,
        'color' => $color,
    ];
}

echo json_encode([
    'range'    => $range,
    'labels'   => $labels,
    'datasets' => $datasets,
    'total'    => $total,
]);

    }

}
