
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

/* ===== LOADING OVERLAY ===== */
#page-loader {
    position: fixed;
    inset: 0;
    background: rgba(15, 45, 90, 0.72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1.25rem;
}
#page-loader.show { display: flex; }
#page-loader .spinner {
    width: 56px;
    height: 56px;
    border: 4px solid rgba(255,255,255,0.25);
    border-top-color: #c9943a;
    border-radius: 50%;
    animation: spin 0.85s linear infinite;
}
#page-loader .loader-text {
    color: #fff;
    font-weight: 600;
    font-size: 1.05rem;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}

/* ===== MOBILE ONLY (desktop untouched) ===== */
.pd-toggle, .pd-menu, .pd-mb { display:none; }
@media (max-width: 767.98px) {
    /* Awaiting Approval / Rejected side by side */
    .nav-tabs { flex-wrap:nowrap; }
    .nav-tabs .nav-item { flex:1 1 50%; min-width:0; }
    .nav-tabs .nav-link { width:100%; padding:8px 4px; font-size:.78rem; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
    .nav-tabs .nav-link i { display:none; }

    /* tables: only Applicant Name + Actions */
    .pd-table .pd-hide { display:none !important; }
    .pd-table th, .pd-table td { padding:10px 12px; }
    .pd-table th.pd-actions-col, .pd-table td.pd-actions-col { width:60px; text-align:center; }
    .pd-dt { display:none; }
    .pd-mb { display:inline; }
    /* higher specificity so Bootstrap's .d-flex (loaded later, !important) can't win */
    .pd-table .pd-desk, .pd-table td .pd-desk.d-flex { display:none !important; }
    .pd-toggle {
        display:inline-flex; align-items:center; justify-content:center;
        width:36px; height:36px; padding:0; border-radius:50%;
        border:1px solid #dde3ee; background:#fff; color:#4a5568; cursor:pointer;
    }
    .pd-toggle[aria-expanded="true"] { background:#e8eef7; color:var(--navy); }
    .pd-menu.pd-open {
        display:block; position:fixed; z-index:9990; min-width:170px; padding:6px;
        background:#fff; border:1px solid #e8ecf0; border-radius:12px; text-align:left;
        box-shadow:0 8px 28px rgba(15,45,90,.22);
    }
    .pd-item {
        display:flex; align-items:center; gap:10px; width:100%;
        padding:10px 12px; border:0; background:transparent; text-align:left;
        font-size:.92rem; color:#222; border-radius:8px; cursor:pointer;
    }
    .pd-item:hover, .pd-item:active { background:#f1f4f9; }
    .pd-item i { width:18px; text-align:center; }
    .pd-item.pd-danger { color:#c0392b; }
}
</style>

<?php include(ROOT_PATH . '/dashboard_sidebar_start.php'); ?>

<div class="container-fluid">
    <h1 class="mb-4 text-center page-title" style="font-weight:700;">Pending Resident Registrations</h1>

    <?php if (isset($_GET['toast'])): ?>
        <div class="alert alert-<?= $_GET['toast'] === 'error' ? 'danger' : 'success' ?> text-center" role="alert">
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
                        <table class="table table-hover mb-0 text-center align-middle pd-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Applicant Name</th>
                                    <th class="pd-hide">Contact</th>
                                    <th class="pd-hide">Address</th>
                                    <th class="pd-hide">Valid ID</th>
                                    <th class="pd-hide">Date Submitted</th>
                                    <th class="pd-actions-col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($pending_list)): ?>
                                <?php foreach ($pending_list as $p):
                                    $fullname = htmlspecialchars($p['fname'] . ' ' . $p['mi'] . ' ' . $p['lname']);
                                    $address  = htmlspecialchars(trim($p['houseno'] . ' ' . $p['street'] . ', ' . $p['brgy'] . ', ' . $p['municipal']));
                                    $row_json = htmlspecialchars(json_encode(array_diff_key($p, ['password' => 1]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE), ENT_QUOTES);
                                ?>
                                <tr>
                                    <td class="fw-bold text-start"><?= $fullname ?></td>
                                    <td class="pd-hide">
                                        <small><?= htmlspecialchars($p['email'] ?: $p['phone_number'] ?: '—') ?></small><br>
                                        <small class="text-muted"><?= htmlspecialchars($p['contact'] ?: '') ?></small>
                                    </td>
                                    <td class="text-start pd-hide"><small><?= $address ?></small></td>
                                    <td class="pd-hide">
                                        <?php if (!empty($p['valid_id_file'])): ?>
                                        <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3"
                                            onclick="openValidIdModal('uploads/valid_ids/<?= htmlspecialchars($p['valid_id_file']) ?>', '<?= $fullname ?>')">
                                            <i class="bi bi-eye me-1"></i> View ID
                                        </button>
                                        <?php else: ?>
                                            <span class="text-muted small">No file</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="pd-hide"><small><?= date('M d, Y g:i A', strtotime($p['date_submitted'])) ?></small></td>
                                    <td class="pd-actions-col">
                                        <div class="d-flex gap-2 justify-content-center pd-desk">
                                            <button type="button" class="btn btn-success btn-sm rounded-pill px-3 fw-bold"
                                                onclick="openApproveModal(<?= (int)$p['id_pending'] ?>, '<?= $fullname ?>')">
                                                <i class="bi bi-check-circle-fill me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold"
                                                onclick="openRejectModal(<?= (int)$p['id_pending'] ?>, '<?= $fullname ?>')">
                                                <i class="bi bi-x-circle-fill me-1"></i> Reject
                                            </button>
                                        </div>
                                        <!-- MOBILE ONLY: 3-dots menu -->
                                        <button type="button" class="pd-toggle" aria-label="Actions" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                                        <div class="pd-menu">
                                            <button type="button" class="pd-item" data-row="<?= $row_json ?>" data-kind="pending" onclick="openPendingView(this)">
                                                <i class="bi bi-eye text-primary"></i> View
                                            </button>
                                            <button type="button" class="pd-item" onclick="openApproveModal(<?= (int)$p['id_pending'] ?>, '<?= $fullname ?>')">
                                                <i class="bi bi-check-circle-fill text-success"></i> Approve
                                            </button>
                                            <button type="button" class="pd-item pd-danger" onclick="openRejectModal(<?= (int)$p['id_pending'] ?>, '<?= $fullname ?>')">
                                                <i class="bi bi-x-circle-fill"></i> Reject
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
                        <table class="table table-hover mb-0 text-center align-middle pd-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Applicant Name</th>
                                    <th class="pd-hide">Contact</th>
                                    <th class="pd-hide">Reason</th>
                                    <th class="pd-hide">Reviewed By</th>
                                    <th class="pd-actions-col"><span class="pd-dt">Delete</span><span class="pd-mb">Action</span></th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if (!empty($rejected_list)): ?>
                                <?php foreach ($rejected_list as $p):
                                    $rj_json = htmlspecialchars(json_encode(array_diff_key($p, ['password' => 1]), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_INVALID_UTF8_SUBSTITUTE), ENT_QUOTES);
                                ?>
                                <tr>
                                    <td class="fw-bold text-start"><?= htmlspecialchars($p['fname'] . ' ' . $p['lname']) ?></td>
                                    <td class="pd-hide"><small><?= htmlspecialchars($p['email'] ?: $p['phone_number'] ?: '—') ?></small></td>
                                    <td class="text-start pd-hide"><small><?= htmlspecialchars($p['reject_reason'] ?: '—') ?></small></td>
                                    <td class="pd-hide"><small><?= htmlspecialchars($p['reviewed_by'] ?: '—') ?></small></td>
                                    <td class="pd-actions-col">
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3 pd-desk"
                                            onclick="openDeleteModal(<?= (int)$p['id_pending'] ?>, '<?= htmlspecialchars($p['fname'] . ' ' . $p['lname']) ?>')">
                                            <i class="bi bi-trash-fill me-1"></i> Delete
                                        </button>
                                        <!-- MOBILE ONLY: 3-dots menu -->
                                        <button type="button" class="pd-toggle" aria-label="Actions" aria-haspopup="true" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></button>
                                        <div class="pd-menu">
                                            <button type="button" class="pd-item" data-row="<?= $rj_json ?>" data-kind="rejected" onclick="openPendingView(this)">
                                                <i class="bi bi-eye text-primary"></i> View
                                            </button>
                                            <button type="button" class="pd-item pd-danger" onclick="openDeleteModal(<?= (int)$p['id_pending'] ?>, '<?= htmlspecialchars($p['fname'] . ' ' . $p['lname']) ?>')">
                                                <i class="bi bi-trash-fill"></i> Delete
                                            </button>
                                        </div>
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
        <form method="post" class="show-loader" data-loader-msg="Approving registration...">
            <input type="hidden" name="id_pending" id="approveIdPending">
            <input type="hidden" name="approve_pending" value="1">
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeApproveModal()">Cancel</button>
                <button type="submit" name="approve_pending" class="btn-modal-confirm success">
                    <i class="fas fa-check-circle mr-1"></i> Yes, Approve
                </button>
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
        <form method="post" class="show-loader" data-loader-msg="Rejecting registration...">
            <input type="hidden" name="id_pending" id="rejectIdPending">
            <input type="hidden" name="reject_pending" value="1">
            <textarea name="reject_reason" class="form-control text-start" rows="3" placeholder="Reason (optional) — e.g. ID photo unreadable, mismatched details..."></textarea>
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeRejectModal()">Cancel</button>
                <button type="submit" name="reject_pending" class="btn-modal-confirm">
                    <i class="fas fa-times-circle mr-1"></i> Yes, Reject
                </button>
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
        <form method="post" class="show-loader" data-loader-msg="Deleting record...">
            <input type="hidden" name="id_pending" id="deleteIdPending">
            <input type="hidden" name="delete_pending" value="1">
            <div class="modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeDeleteModal()">Cancel</button>
                <button type="submit" name="delete_pending" class="btn-modal-confirm">
                    <i class="fas fa-trash-alt mr-1"></i> Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Valid ID Viewer Modal -->
<!-- Applicant details modal (used by the mobile 3-dots menu) -->
<div class="modal fade" id="pendingViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border-radius:14px; overflow:hidden;">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-vcard me-2"></i><span id="pv_title">Applicant Details</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <dl class="row mb-0" style="font-size:.9rem;" id="pv_list"></dl>
                <div class="mt-3">
                    <div class="fw-bold mb-2" style="font-size:.9rem;">Valid ID Submitted</div>
                    <div id="pv_id_wrap" class="text-center"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

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

<!-- Loading Overlay -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script>
function showLoader(msg) {
    const loader = document.getElementById('page-loader');
    const text   = document.getElementById('loader-msg');
    if (text) text.textContent = msg || 'Please wait...';
    if (loader) loader.classList.add('show');
}
function hideLoader() {
    const loader = document.getElementById('page-loader');
    if (loader) loader.classList.remove('show');
}
window.addEventListener('pageshow', function () {
    hideLoader();
});
let loaderTimeout = null;
document.querySelectorAll('form.show-loader').forEach(function (form) {
    form.addEventListener('submit', function () {
        const msg = this.dataset.loaderMsg || 'Please wait...';
        showLoader(msg);
        if (loaderTimeout) clearTimeout(loaderTimeout);
        loaderTimeout = setTimeout(hideLoader, 12000);
        const btn = this.querySelector('button[type="submit"], input[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
        }
    });
});

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
function openDeleteModal(id, name) {
    document.getElementById('deleteIdPending').value = id;
    document.getElementById('deleteApplicantName').textContent = name;
    document.getElementById('deleteModal').classList.add('open');
}
function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('open');
}

document.addEventListener('DOMContentLoaded', function () {
    ['approveModal', 'rejectModal', 'deleteModal'].forEach(function (id) {
        var overlay = document.getElementById(id);
        overlay.addEventListener('click', function (e) {
            if (e.target === overlay) overlay.classList.remove('open');
        });
    });
});

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
    document.getElementById('validIdImage').src = '';
    document.getElementById('validIdFrame').src = '';
}

document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeValidIdModal();
        closeApproveModal();
        closeRejectModal();
        closeDeleteModal();
    }
});

/* ===== Mobile 3-dots menu + applicant details modal ===== */
function pvEsc(v) {
    var d = document.createElement('div');
    d.textContent = (v === null || v === undefined || v === '') ? '—' : String(v);
    return d.innerHTML;
}
function openPendingView(btn) {
    var r = {};
    try { r = JSON.parse(btn.dataset.row); } catch (e) { return; }
    var name = [r.fname, r.mi, r.lname].filter(Boolean).join(' ');
    document.getElementById('pv_title').textContent = name || 'Applicant Details';
    var address = [r.houseno, r.street, r.brgy, r.municipal, r.province].filter(Boolean).join(', ');
    var rows = [
        ['Full Name', name],
        ['Email', r.email],
        ['Phone Number', r.phone_number],
        ['Contact', r.contact],
        ['Sex', r.sex],
        ['Civil Status', r.status],
        ['Birth Date', r.bdate],
        ['Birth Place', r.bplace],
        ['Nationality', r.nationality],
        ['Family Role', r.family_role],
        ['Voter', r.voter],
        ['PWD', r.pwd],
        ['Address', address],
        ['Date Submitted', r.date_submitted]
    ];
    if (btn.dataset.kind === 'rejected') {
        rows.push(['Reject Reason', r.reject_reason]);
        rows.push(['Reviewed By', r.reviewed_by]);
    }
    document.getElementById('pv_list').innerHTML = rows.map(function (x) {
        return '<dt class="col-5">' + x[0] + '</dt><dd class="col-7" style="word-break:break-word;">' + pvEsc(x[1]) + '</dd>';
    }).join('');

    var wrap = document.getElementById('pv_id_wrap');
    if (r.valid_id_file) {
        var url = 'uploads/valid_ids/' + encodeURIComponent(r.valid_id_file);
        var link = '<div class="mt-2"><a href="' + url + '" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm rounded-pill"><i class="bi bi-box-arrow-up-right me-1"></i>Open full size</a></div>';
        if (/\.pdf$/i.test(r.valid_id_file)) {
            wrap.innerHTML = '<iframe src="' + url + '" style="width:100%;height:60vh;border:0;"></iframe>' + link;
        } else {
            wrap.innerHTML = '<img src="' + url + '" alt="Valid ID" class="img-fluid rounded" style="max-height:60vh;border:1px solid #dde3ee;">' + link;
        }
    } else {
        wrap.innerHTML = '<span class="text-muted small">No file submitted</span>';
    }
    bootstrap.Modal.getOrCreateInstance(document.getElementById('pendingViewModal')).show();
}
document.getElementById('pendingViewModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('pv_id_wrap').innerHTML = '';
});

(function () {
    var open = null;
    function closeMenu() {
        if (!open) return;
        open.menu.classList.remove('pd-open');
        open.menu.style.top = open.menu.style.left = '';
        open.btn.setAttribute('aria-expanded', 'false');
        open = null;
    }
    document.querySelectorAll('.pd-toggle').forEach(function (btn) {
        var menu = btn.parentElement.querySelector('.pd-menu');
        if (!menu) return;
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            if (open && open.menu === menu) { closeMenu(); return; }
            closeMenu();
            menu.classList.add('pd-open');
            btn.setAttribute('aria-expanded', 'true');
            open = { btn: btn, menu: menu };
            var r = btn.getBoundingClientRect(), w = menu.offsetWidth, h = menu.offsetHeight, pad = 8;
            var left = Math.min(Math.max(pad, r.right - w), window.innerWidth - w - pad);
            var top = r.bottom + 4;
            if (top + h > window.innerHeight - pad) top = Math.max(pad, r.top - h - 4);
            menu.style.left = left + 'px';
            menu.style.top = top + 'px';
        });
        menu.addEventListener('click', function (e) {
            if (e.target.closest('.pd-item')) setTimeout(closeMenu, 0);
        });
    });
    document.addEventListener('click', function (e) {
        if (open && !open.menu.contains(e.target)) closeMenu();
    });
    window.addEventListener('scroll', closeMenu, true);
    window.addEventListener('resize', closeMenu);
})();
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>