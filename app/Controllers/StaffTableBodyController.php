<?php
/**
 * staff_table_body.php
 * Renders the Barangay Staff table + pagination. Expects $view (from
 * StaffClass::view_staff_paginated()) to already be set by the caller.
 *
 * Included by admn_staff_crud.php.
 */
?>
<div class="row">
    <div class="col-md-12">
        <table class="table table-hover text-center table-bordered" style="min-width: 1000px;">
            <thead class="alert-info">
                <tr>
                    <th>Actions</th>
                    <th>Fullname</th>
                    <th>Age</th>
                    <th>Sex</th>
                    <th>Address</th>
                    <th>Position</th>
                    <th>Role</th>
                    <th>AddedBy</th>
                </tr>
            </thead>
            <tbody>
                <?php require_once ROOT_PATH . '/pagination_helper.php'; $staffRows = $view['rows'] ?? []; ?>
                <?php if (!empty($staffRows)) { ?>
                    <?php foreach ($staffRows as $staff) { ?>
                        <tr>
                            <td>
                                <form action="" method="post" class="show-loader" data-loader-msg="Archiving staff...">
                                    <input type="hidden" name="id_user" value="<?= $staff['id_user']; ?>">
                                    <input type="hidden" name="delete_staff" value="1">
                                    <div class="dropdown">
                                        <button type="button" class="btn btn-light btn-sm border" style="width:34px;height:34px;padding:0;border-radius:50%;" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                            <li>
                                                <a class="dropdown-item" href="update_staff_form.php?id_user=<?= $staff['id_user']; ?>">
                                                    <i class="fas fa-user-edit mr-2 text-success"></i> Update
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#demoteModal<?= $staff['id_user'] ?>">
                                                    <i class="fas fa-user-minus mr-2 text-warning"></i> Demote
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button type="submit" name="delete_staff" class="dropdown-item text-danger">
                                                    <i class="fas fa-archive mr-2"></i> Archive
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </form>

                                <!-- Demote to Resident Modal -->
                                <div class="modal fade" id="demoteModal<?= $staff['id_user'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content" style="border-radius: 20px; overflow: hidden;">
                                            <div class="modal-header text-white" style="background: linear-gradient(135deg, #7b4d00, #b8860b);">
                                                <h5 class="modal-title"><i class="fas fa-user-minus mr-2"></i> Demote to Resident</h5>
                                                <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                                            </div>
                                            <form action="admn_staff_crud.php" method="POST" class="show-loader" data-loader-msg="Demoting staff...">
                                                <div class="modal-body text-left">
                                                    <div class="alert alert-warning" style="border-radius:10px;">
                                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                                        You are about to demote <strong><?= htmlspecialchars($staff['fname']) ?> <?= htmlspecialchars($staff['lname']) ?></strong>
                                                        from the position of <strong><?= htmlspecialchars($staff['position']) ?></strong> back to a Barangay Resident.
                                                    </div>
                                                    <p class="text-muted mb-0">
                                                        <small>Their staff account will be removed. They will retain their resident record and can still log in as a resident.</small>
                                                    </p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                                                    <input type="hidden" name="demote_id_user" value="<?= $staff['id_user'] ?>">
                                                    <input type="hidden" name="demote_staff" value="1">
                                                    <button type="submit" name="demote_staff" class="btn text-white" style="border-radius:30px; width: 140px; background: linear-gradient(135deg, #7b4d00, #b8860b);">
                                                        <i class="fas fa-user-minus mr-1"></i> Confirm Demote
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td><?= htmlspecialchars($staff['lname']); ?>, <?= htmlspecialchars($staff['fname']); ?> <?= htmlspecialchars($staff['mi']); ?></td>
                            <td><?= htmlspecialchars($staff['age']); ?></td>
                            <td><?= htmlspecialchars($staff['sex']); ?></td>
                            <td><?= htmlspecialchars($staff['address']); ?></td>
                            <td><?= htmlspecialchars($staff['position']); ?></td>
                            <td><?= htmlspecialchars($staff['role']); ?></td>
                            <td><?= htmlspecialchars($staff['addedby']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } ?>
            </tbody>
        </table>
        <?php render_pagination($view); ?>
    </div>
</div>
