<?php

class SoloParentController extends Controller
{
    public function adminSoloparent()
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
    $bmis->delete_soloparent();
    $bmis->create_soloparent();
    $view = $bmis->view_soloparent();
    $residents_list = $residentbmis->view_resident_lite();
    $id_resident = $_GET['id_resident'];
    $residentbmis->get_single_soloparent($id_resident);
   
?>

<?php 
    
        $this->view('soloparent/adminSoloparent.php');

    }

    public function soloparentSearch()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
	// require the database connection
	if(isset($_POST['search_soloparent'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">

        <tr>
             <th> Actions</th>
            <th> Resident ID </th>
            <th> Full Name </th>
            <th> Children </th>
            <th> Address </th>
        </tr>
    </thead>
</div>
    <tbody>     
        <?php
            $stmnt = $conn->prepare("SELECT * FROM `tbl_soloparent` WHERE `lname` LIKE '%$keyword%' or  `mi` LIKE '%$keyword%' or  `fname` LIKE '%$keyword%' 
                or  `id_resident` LIKE '%$keyword%' or  `houseno` LIKE '%$keyword%'
            or `street` LIKE '%$keyword%' or `brgy` LIKE '%$keyword%' or `municipal` LIKE '%$keyword%' or `date` LIKE '%$keyword%' or `purpose` LIKE '%$keyword%' or `children` LIKE '%$keyword%'");
            $stmnt->execute();
            
            while($view = $stmnt->fetch()){
        ?>
            <tr>
                <td>    
                    <form action="" method="post">
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="soloparent_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                        <input type="hidden" name="id_soloparent" value="<?= $view['id_soloparent'];?>">
                        <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_soloparent"> Archive </button>
                        <?php if ((int)$view['id_resident'] === 0): ?>
                        <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_soloparent'];?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_soloparent'];?>">
                            <i class="fas fa-comment-alt"></i> Message
                        </button>
                        <?php endif; ?>
                    </form>
                </td>
                                    <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= nl2br(htmlspecialchars(mb_strimwidth($view['children'], 0, 80, '...')));?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>_<?= $view['id_soloparent'];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <input type="hidden" name="redirect_to" value="admn_soloparent.php">
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
            <th> Children </th>
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
                            <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="soloparent_form.php?id_resident=<?= $view['id_resident'];?>">Generate</a> 
                            <input type="hidden" name="id_soloparent" value="<?= $view['id_soloparent'];?>">
                            <button class="btn btn-danger" style="width: 90px; font-size: 17px; border-radius:30px;" type="submit" name="delete_soloparent"> Archive </button>
                            <?php if ((int)$view['id_resident'] === 0): ?>
                            <button type="button" class="btn btn-secondary btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;opacity:0.65;cursor:not-allowed;" disabled title="Not available for walk-in / manual entries">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php else: ?>
<button type="button" class="btn btn-info btn-sm text-white" style="width:110px;font-size:17px;border-radius:30px;margin-bottom:2px;" data-toggle="modal" data-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_soloparent'];?>" data-bs-toggle="modal" data-bs-target="#messageModal<?= $view['id_resident'];?>_<?= $view['id_soloparent'];?>">
                                <i class="fas fa-comment-alt"></i> Message
                            </button>
                            <?php endif; ?>
                        </form>
                    </td>
                    <td> <?= $view['id_resident'];?> </td> 
                <td> <?= $view['lname'];?>, <?= $view['fname'];?> <?= $view['mi'];?> </td>
                <td> <?= nl2br(htmlspecialchars(mb_strimwidth($view['children'], 0, 80, '...')));?> </td>
                <td> <?= $view['houseno'];?>, <?= $view['street'];?>, <?= $view['brgy'];?>, <?= $view['municipal'];?> </td>


                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $view['id_resident'];?>_<?= $view['id_soloparent'];?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <input type="hidden" name="redirect_to" value="admn_soloparent.php">
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

    public function servicesSoloparent()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;



$userdetails = $bmis->get_userdata();
$bmis->create_soloparent();
$is_verified = $bmis->isResidentVerified($userdetails['id_resident']);
        $this->view('soloparent/servicesSoloparent.php');

    }

    public function soloparentForm()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
$userdetails = $residentbmis->get_userdata();
$id_resident = $_GET['id_resident'];
$resident = $residentbmis->get_single_soloparent($id_resident);

// ── Activity Log: Solo Parent Certification Generated ───────────────────────
if ($resident) {
    $resident_name = strtoupper(trim(($resident['lname'] ?? '') . ', ' . ($resident['fname'] ?? '') . ' ' . ($resident['mi'] ?? '')));
    $residentbmis->log_activity(
        'GENERATE_DOCUMENT',
        'Solo Parent Certification',
        "Generated Solo Parent Certification for {$resident_name} (Resident ID: {$id_resident})"
    );

    // ── Auto-notify resident: Solo Parent Certification generated ───────────
    // Only send if this record is tied to an actual resident account
    // (walk-in / manual entries use id_resident = 0 and have no account to message).
    if (!empty($id_resident) && (int)$id_resident !== 0) {
        $residentbmis->sendMessage(
            $id_resident,
            "Your Solo Parent Certification has been generated and is ready for release."
        );
    }
}
// ────────────────────────────────────────────────────────────────────────────

// Parse the "children" textarea (one child per line, e.g. "Juan Dela Cruz - 8 years old")
$children_lines = [];
if (!empty($resident['children'])) {
    foreach (preg_split('/\r\n|\r|\n/', trim($resident['children'])) as $line) {
        $line = trim($line);
        if ($line !== '') { $children_lines[] = $line; }
    }
}
  
        $this->view('soloparent/soloparentForm.php');

    }

}
