include('dashboard_sidebar_start.php');
?>
<div class="container-fluid">
    <h1 class="mb-4 text-center page-title" style="font-weight:700;">Pending Resident Registrations</h1>

    <?php if (isset($_GET['toast'])): ?>
        <div class="alert alert-<?= $_GET['toast']==='error' ? 'danger' : 'success' ?> text-center" role="alert">
            <?php
                switch ($_GET['toast']) {
                    case 'approved': echo 'Registration approved. The resident can now log in.'; break;
                    case 'rejected': echo 'Registration rejected.'; break;
                    case 'deleted':  echo 'Record removed.'; break;
                    default: echo 'Something went wrong. Please try again.';
                }
            ?>
        </div>
    <?php endif; ?>

    <ul class="nav nav-tabs mb-3" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pending-panel" type="button">
                <i class="bi bi-hourglass-split me-1"></i> Awaiting Approval
                <span class="badge bg-warning text-dark ms-1"><?= $pending_count ?></span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#rejected-panel" type="button">
                <i class="bi bi-x-circle me-1"></i> Rejected
                <span class="badge bg-secondary ms-1"><?= count($rejected_list) ?></span>
            </button>
        </li>
    </ul>

    <div class="tab-content">
        <!-- PENDING -->
        <div class="tab-pane fade show active" id="pending-panel">
            <div class="card card-clean">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Contact</th>
                                    <th>Address</th>
                                    <th>Valid ID</th>
                                    <th>Date Submitted</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($pending_list)): ?>
                                <?php foreach ($pending_list as $p):
                                    $fullname = htmlspecialchars($p['fname'] . ' ' . $p['mi'] . ' ' . $p['lname']);
                                    $address  = htmlspecialchars(trim($p['houseno'] . ' ' . $p['street'] . ', ' . $p['brgy'] . ', ' . $p['municipal']));
                                ?>
                                <tr>
                                    <td class="fw-bold text-start"><?= $fullname ?></td>
                                    <td>
                                        <small><?= htmlspecialchars($p['email'] ?: $p['phone_number'] ?: '—') ?></small><br>
                                        <small class="text-muted"><?= htmlspecialchars($p['contact'] ?: '') ?></small>
                                    </td>
                                    <td class="text-start"><small><?= $address ?></small></td>
                                    <td>
                                        <?php if (!empty($p['valid_id_file'])): ?>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                            onclick="openValidIdModal('uploads/valid_ids/<?= htmlspecialchars($p['valid_id_file']) ?>', '<?= $fullname ?>')">
                                            <i class="bi bi-eye me-1"></i> View ID
                                        </button>
                                        <?php else: ?>
                                            <span class="text-muted small">No file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><small><?= date('M d, Y g:i A', strtotime($p['date_submitted'])) ?></small></td>
                                    <td>
                                        <div class="d-flex gap-2 justify-content-center">
                                            <button type="button" class="btn btn-success btn-sm rounded-pill px-3 fw-bold"
                                                onclick="openApproveModal(<?= (int)$p['id_pending'] ?>, '<?= $fullname ?>')">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"
                                                onclick="openRejectModal(<?= (int)$p['id_pending'] ?>, '<?= $fullname ?>')">
                                                <i class="bi bi-x-circle-fill me-1"></i> Reject
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="py-5 text-muted fst-italic">No pending registrations.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- REJECTED -->
        <div class="tab-pane fade" id="rejected-panel">
            <div class="card card-clean">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 text-center align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>Applicant Name</th>
                                    <th>Contact</th>
                                    <th>Reason</th>
                                    <th>Reviewed By</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($rejected_list)): ?>
                                <?php foreach ($rejected_list as $p): ?>
                                <tr>
                                    <td class="fw-bold text-start"><?= htmlspecialchars($p['fname'] . ' ' . $p['lname']) ?></td>
                                    <td><small><?= htmlspecialchars($p['email'] ?: $p['phone_number'] ?: '—') ?></small></td>
                                    <td class="text-start"><small><?= htmlspecialchars($p['reject_reason'] ?: '—') ?></small></td>
                                    <td><small><?= htmlspecialchars($p['reviewed_by'] ?: '—') ?></small></td>
<td>
    <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3"
        onclick="openDeleteModal(<?= (int)$p['id_pending'] ?>, '<?= htmlspecialchars($p['fname'] . ' ' . $p['lname']) ?>')">
        <i class="bi bi-trash-fill me-1"></i> Delete
    </button>
</td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="py-5 text-muted fst-italic">No rejected registrations.</td></tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal-overlay" id="approveModal">
    <div class="modal-box">
        <div class="modal-icon success"><i class="fas fa-check-circle"></i></div>
        <h3>Approve Registration?</h3>
        <p>You are about to approve <strong id="approveApplicantName"></strong>. They will be added to Barangay Residents and can log in.</p>
        <form method="post">
            <input type="hidden" name="id_pending" id="approveIdPending">
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeApproveModal()">Cancel</button>
                <button type="submit" name="approve_pending" class="btn-modal-confirm success"><i class="fas fa-check-circle mr-1"></i> Yes, Approve</button>
            </div>
        </form>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal-overlay" id="rejectModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-times-circle"></i></div>
        <h3>Reject Registration?</h3>
        <p>Reject the registration for <strong id="rejectApplicantName"></strong>.</p>
        <form method="post">
            <input type="hidden" name="id_pending" id="rejectIdPending">
            <textarea name="reject_reason" class="form-control text-start" rows="3" placeholder="Reason (optional) — e.g. ID photo unreadable, mismatched details..."></textarea>
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" name="reject_pending" class="btn-modal-confirm"><i class="fas fa-times-circle mr-1"></i> Yes, Reject</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete Record?</h3>
        <p>This will permanently remove the rejected record for <strong id="deleteApplicantName"></strong>. This cannot be undone.</p>
        <form method="post">
            <input type="hidden" name="id_pending" id="deleteIdPending">
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" name="delete_pending" class="btn-modal-confirm"><i class="fas fa-trash-alt mr-1"></i> Yes, Delete</button>
            </div>
        </form>
    </div>
</div>
<!-- Valid ID Viewer Modal -->
<div class="modal fade" id="validIdModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Valid ID — <span id="validIdApplicantName"></span></h5>
                <button type="button" class="btn-close" onclick="closeValidIdModal()"></button>
            </div>
            <div class="modal-body text-center">
                <img id="validIdImage" src="" alt="Valid ID" class="img-fluid rounded d-none" style="max-height: 75vh;">
                <iframe id="validIdFrame" src="" class="w-100 d-none" style="height: 75vh; border: 0;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
function openApproveModal(id, name) {
    document.getElementById('approveIdPending').value = id;
    document.getElementById('approveApplicantName').textContent = name;
    document.getElementById('approveModal').classList.add('open');
}
function closeApproveModal() {
    document.getElementById('approveModal').classList.remove('open');
}

function openRejectModal(id, name) {
    document.getElementById('rejectIdPending').value = id;
    document.getElementById('rejectApplicantName').textContent = name;
    document.getElementById('rejectModal').classList.add('open');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.remove('open');
}

document.addEventListener('DOMContentLoaded', function () {
    ['approveModal', 'rejectModal', 'deleteModal'].forEach(function (id) {
        var overlay = document.getElementById(id);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });
});

    function openDeleteModal(id, name) {
    document.getElementById('deleteIdPending').value = id;
    document.getElementById('deleteApplicantName').textContent = name;
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}
    
function openValidIdModal(fileUrl, name) {
    var img = document.getElementById('validIdImage');
    var frame = document.getElementById('validIdFrame');
    var isPdf = fileUrl.toLowerCase().endsWith('.pdf');

    if (isPdf) {
        img.classList.add('d-none');
        img.src = '';
        frame.src = fileUrl;
        frame.classList.remove('d-none');
    } else {
        frame.classList.add('d-none');
        frame.src = '';
        img.src = fileUrl;
        img.classList.remove('d-none');
    }

    document.getElementById('validIdApplicantName').textContent = name;

    var modalEl = document.getElementById('validIdModal');
    modalEl.style.display = 'block';
    modalEl.classList.add('show');
    modalEl.removeAttribute('aria-hidden');
    modalEl.setAttribute('aria-modal', 'true');
    document.body.classList.add('modal-open');

    if (!document.getElementById('validIdBackdrop')) {
        var backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'validIdBackdrop';
        backdrop.onclick = closeValidIdModal;
        document.body.appendChild(backdrop);
    }
}

function closeValidIdModal() {
    var modalEl = document.getElementById('validIdModal');
    modalEl.classList.remove('show');
    modalEl.style.display = 'none';
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.removeAttribute('aria-modal');
    document.body.classList.remove('modal-open');

    var backdrop = document.getElementById('validIdBackdrop');
    if (backdrop) backdrop.remove();

    // Stop any playing/loading media when closed
    document.getElementById('validIdImage').src = '';
    document.getElementById('validIdFrame').src = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeValidIdModal();
        closeApproveModal();
        closeRejectModal();
        closeDeleteModal();
    }
});
</script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
<?php include('dashboard_sidebar_end.php'); ?>