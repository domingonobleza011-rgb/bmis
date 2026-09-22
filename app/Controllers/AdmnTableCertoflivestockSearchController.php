<?php
	// require the database connection
	require ROOT_PATH . '/classes/conn.php';
	if(isset($_POST['search_certoflivestock'])){
		$keyword = $_POST['keyword'];
?>
<div class="table-responsive" style="width: 100%; overflow-x: auto;">
    <table class="table table-hover text-center table-bordered" style="min-width: 1000px;"> 
        <thead class="alert-info">
        
        <tr>
            <th> Actions</th>
            <th> Status</th>
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
                        <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="livestockcert_form.php?id_livestock=<?= $view['id_livestock'];?>">Generate</a> 
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
                <td>
                    <?php if ((int)($view['is_generated'] ?? 0) === 1): ?>
                        <span style="display:inline-block;background:#eafaf0;color:#1a7a40;border:1px solid #27ae60;border-radius:6px;padding:5px 12px;font-size:0.78rem;font-weight:700;white-space:nowrap;"><i class="fas fa-check-circle"></i> Generated</span>
                    <?php else: ?>
                        <span style="display:inline-block;background:#fff8ec;color:#7a5200;border:1px solid #f0a500;border-radius:6px;padding:5px 12px;font-size:0.78rem;font-weight:700;white-space:nowrap;"><i class="fas fa-clock"></i> Not Generated</span>
                    <?php endif; ?>
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
            <th> Status</th>
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
                                <a class="btn btn-success" style="width: 90px; font-size: 17px; border-radius:30px; margin-bottom: 2px;" href="livestockcert_form.php?id_livestock=<?= $view['id_livestock'];?>">Generate</a> 
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
                        <td>
                            <?php if ((int)($view['is_generated'] ?? 0) === 1): ?>
                                <span style="display:inline-block;background:#eafaf0;color:#1a7a40;border:1px solid #27ae60;border-radius:6px;padding:5px 12px;font-size:0.78rem;font-weight:700;white-space:nowrap;"><i class="fas fa-check-circle"></i> Generated</span>
                            <?php else: ?>
                                <span style="display:inline-block;background:#fff8ec;color:#7a5200;border:1px solid #f0a500;border-radius:6px;padding:5px 12px;font-size:0.78rem;font-weight:700;white-space:nowrap;"><i class="fas fa-clock"></i> Not Generated</span>
                            <?php endif; ?>
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
?>