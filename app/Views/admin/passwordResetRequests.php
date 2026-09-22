<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Password Reset Requests — Admin</title>
</head>
<body class="bg-light">
<?php include('dashboard_sidebar_start.php'); ?>

<style>
@import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&display=swap');

:root {
    --navy:          #0f2d5a;
    --navy-mid:      #1a4480;
    --navy-light:    #2b5ea7;
    --navy-pale:     #e8eef7;
    --gold:          #c9943a;
    --gold-light:    #e8b86d;
    --gold-pale:     #fdf3e3;
    --danger:        #dc2626;
    --danger-pale:   #fef2f2;
    --success:       #059669;
    --success-pale:  #ecfdf5;
    --warning:       #d97706;
    --warning-pale:  #fffbeb;
    --text-dark:     #1a1a2e;
    --text-mid:      #4a5568;
    --text-light:    #718096;
    --border:        #e8ecf0;
    --cream:         #f7f8fc;
    --shadow-sm:     0 2px 8px rgba(15,45,90,0.07);
    --shadow-md:     0 6px 24px rgba(15,45,90,0.11);
    --radius:        14px;
    --transition:    0.22s cubic-bezier(0.4,0,0.2,1);
}

body { font-family: 'DM Sans', -apple-system, sans-serif !important; background: var(--cream) !important; color: var(--text-dark) !important; }
.content-wrapper { background: var(--cream) !important; }

h4 { font-weight: 700 !important; font-size: 1.15rem !important; color: var(--navy) !important; letter-spacing: 0.2px; display: flex; align-items: center; }
h4 i { color: var(--gold); }

.badge.bg-danger { background: var(--danger) !important; border-radius: 999px; font-weight: 600; padding: 6px 12px; }

.alert-success { background: var(--success-pale) !important; border: 1px solid #bbf7d0 !important; color: #065f46 !important; border-radius: 10px !important; }
.alert-danger  { background: var(--danger-pale) !important; border: 1px solid #fecaca !important; color: #991b1b !important; border-radius: 10px !important; }

.card { border: none !important; border-radius: var(--radius) !important; box-shadow: var(--shadow-sm) !important; }
.table { font-size: 0.875rem; margin-bottom: 0; }
.table thead th, .table thead.table-dark th { background: var(--navy) !important; color: #fff !important; font-weight: 600; letter-spacing: 0.5px; font-size: 0.75rem; text-transform: uppercase; border: none !important; padding: 12px 16px; }
.table td { border-color: var(--border); vertical-align: middle; padding: 12px 16px; }
.table tbody tr:hover { background: var(--navy-pale); }

.badge.bg-warning   { background: var(--warning) !important; }
.badge.bg-success   { background: var(--success) !important; }
.badge.bg-secondary { background: #9ca3af !important; }
.badge { font-weight: 600; letter-spacing: 0.3px; padding: 5px 10px; border-radius: 999px; }

.btn-success { background: linear-gradient(135deg, var(--success), #10b981) !important; border: none !important; border-radius: 8px !important; font-weight: 600 !important; box-shadow: 0 3px 8px rgba(5,150,105,0.25) !important; transition: all var(--transition) !important; }
.btn-success:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(5,150,105,0.32) !important; }
.btn-danger  { background: linear-gradient(135deg, var(--danger), #ef4444) !important; border: none !important; border-radius: 8px !important; font-weight: 600 !important; box-shadow: 0 3px 8px rgba(220,38,38,0.25) !important; transition: all var(--transition) !important; }
.btn-danger:hover { transform: translateY(-1px); box-shadow: 0 5px 14px rgba(220,38,38,0.32) !important; }
.btn-outline-danger { border-radius: 8px !important; font-weight: 600 !important; transition: all var(--transition) !important; }

/* ── Confirmation modals (shared design) ── */
.bmis-modal-backdrop {
    display: none; position: fixed; inset: 0; z-index: 9999;
    background: rgba(10,15,26,0.5); align-items: center; justify-content: center;
    backdrop-filter: blur(2px);
}
.bmis-modal-backdrop.open { display: flex; }
.bmis-modal-card {
    background: #fff; border-radius: 16px; padding: 28px 30px;
    width: 100%; max-width: 440px; margin: 0 16px;
    box-shadow: 0 20px 50px rgba(15,45,90,0.25);
    animation: bmisModalIn 0.2s ease;
}
@keyframes bmisModalIn { from { opacity: 0; transform: translateY(10px) scale(0.98); } to { opacity: 1; transform: translateY(0) scale(1); } }
.bmis-modal-icon {
    width: 44px; height: 44px; border-radius: 12px; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center; font-size: 19px;
}
.bmis-modal-title { font-size: 16px; font-weight: 700; margin: 0; color: var(--navy); }
.bmis-modal-sub   { font-size: 13px; color: var(--text-light); margin: 2px 0 0; }
.bmis-modal-info  { border-radius: 10px; padding: 13px 16px; margin: 18px 0 22px; display: flex; align-items: flex-start; gap: 12px; }
.bmis-modal-info p { margin: 0; }
.bmis-modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
.bmis-btn-cancel {
    padding: 9px 18px; font-size: 13px; font-weight: 600; border-radius: 9px; cursor: pointer;
    border: 1px solid var(--border); background: #fff; color: var(--text-mid); transition: all var(--transition);
}
.bmis-btn-cancel:hover { background: var(--cream); border-color: #d1d5db; }
.bmis-btn-confirm {
    padding: 9px 20px; font-size: 13px; font-weight: 700; border-radius: 9px;
    cursor: pointer; border: none; color: #fff; transition: all var(--transition);
    display: inline-flex; align-items: center; gap: 7px;
}
.bmis-btn-confirm:hover { transform: translateY(-1px); }

/* Temp-password reveal chip inside approve modal */
.temp-pw-note {
    font-size: 11.5px; color: var(--text-light); background: var(--navy-pale);
    border-radius: 8px; padding: 8px 12px; margin-top: -6px; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}
.temp-pw-note i { color: var(--navy-light); }
</style>

<div class="content-wrapper p-4">
    <div class="d-flex align-items-center mb-4">
        <h4 class="mb-0"><i class="fas fa-key me-2"></i>Password Reset Requests</h4>
        <span class="badge bg-danger ms-2"><?= $pendingCount ?> Pending</span>
    </div>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Resident</th>
                    <th>Contact</th>
                    <th>Requested</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($requests)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">No reset requests yet.</td></tr>
            <?php endif; ?>
            <?php foreach ($requests as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td>
                        <strong><?= htmlspecialchars($r['full_name']) ?></strong><br>
                        <small class="text-muted">#<?= $r['id_resident'] ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars($r['email'] ?? '—') ?><br>
                        <small><?= htmlspecialchars($r['phone_number'] ?? '—') ?></small>
                    </td>
                    <td><?= date('M d, Y g:i A', strtotime($r['requested_at'])) ?></td>
                    <td>
                        <?php
                        $badges = ['pending'=>'warning','approved'=>'success','rejected'=>'danger'];
                        $badge  = $badges[$r['status']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?= $badge ?>"><?= ucfirst($r['status']) ?></span>
                        <?php if ($r['status']==='approved' && $r['temp_password']): ?>
                            <br><small class="text-muted">Temp: <?= htmlspecialchars($r['temp_password']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                    <?php if ($r['status'] === 'pending'): ?>
                        <button type="button" class="btn btn-success btn-sm"
                                data-req-id="<?= (int) $r['id'] ?>"
                                data-resident-id="<?= (int) $r['id_resident'] ?>"
                                data-full-name="<?= htmlspecialchars($r['full_name'], ENT_QUOTES) ?>"
                                onclick="openApproveModal(this)">
                            <i class="fas fa-check"></i> Approve
                        </button>
                        <button type="button" class="btn btn-danger btn-sm ms-1"
                                data-req-id="<?= (int) $r['id'] ?>"
                                data-full-name="<?= htmlspecialchars($r['full_name'], ENT_QUOTES) ?>"
                                onclick="openRejectModal(this)">
                            <i class="fas fa-times"></i> Reject
                        </button>
                    <?php else: ?>
                        <small class="text-muted">Resolved by <?= htmlspecialchars($r['resolved_by'] ?? '—') ?></small><br>
                        <small class="text-muted"><?= $r['resolved_at'] ? date('M d, Y', strtotime($r['resolved_at'])) : '' ?></small>
                        <br>
                        <button type="button" class="btn btn-outline-danger btn-sm mt-1"
                                data-req-id="<?= (int) $r['id'] ?>"
                                data-full-name="<?= htmlspecialchars($r['full_name'], ENT_QUOTES) ?>"
                                onclick="openDeleteModal(this)">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        </div>
    </div>
</div>


<!-- ════════════════════════════════════════════════════════
     HIDDEN ACTION FORMS
═══════════════════════════════════════════════════════════ -->
<form id="approveResetForm" method="post" style="display:none;">
    <?= bmis_csrf_field() ?>
    <input type="hidden" name="req_id" id="approveReqId">
    <input type="hidden" name="id_resident" id="approveResidentId">
    <input type="hidden" name="approve_reset" value="1">
</form>

<form id="rejectResetForm" method="post" style="display:none;">
    <?= bmis_csrf_field() ?>
    <input type="hidden" name="req_id" id="rejectReqId">
    <input type="hidden" name="reject_reset" value="1">
</form>

<form id="deleteResetForm" method="post" style="display:none;">
    <?= bmis_csrf_field() ?>
    <input type="hidden" name="req_id" id="deleteReqId">
    <input type="hidden" name="delete_reset" value="1">
</form>


<!-- ════════════════════════════════════════════════════════
     APPROVE MODAL
═══════════════════════════════════════════════════════════ -->
<div id="approveResetModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px;">
      <div class="bmis-modal-icon" style="background:var(--success-pale);">
        <i class="fas fa-key" style="color:var(--success);"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Approve password reset?</p>
        <p class="bmis-modal-sub">A new temporary password will be generated.</p>
      </div>
    </div>
    <div class="bmis-modal-info" style="background:var(--success-pale); border:1.5px solid #bbf7d0;">
      <i class="fas fa-user-check" style="color:var(--success); font-size:18px; margin-top:1px;"></i>
      <div>
        <p style="font-size:13px; font-weight:700; color:#065f46;" id="approveResetName"></p>
        <p style="font-size:12px; color:#047857; margin-top:2px;">
            This resets their password immediately. Share the new temporary password with them in person or through a verified channel — not over unverified text/chat.
        </p>
      </div>
    </div>
    <div class="bmis-modal-actions">
      <button type="button" class="bmis-btn-cancel" onclick="closeAllResetModals()">Cancel</button>
      <button type="button" class="bmis-btn-confirm" style="background:linear-gradient(135deg,var(--success),#10b981);"
              onclick="document.getElementById('approveResetForm').submit();">
        <i class="fas fa-check"></i> Yes, approve
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════════════════════
     REJECT MODAL
═══════════════════════════════════════════════════════════ -->
<div id="rejectResetModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px;">
      <div class="bmis-modal-icon" style="background:var(--warning-pale);">
        <i class="fas fa-ban" style="color:var(--warning);"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Reject this request?</p>
        <p class="bmis-modal-sub">The resident will remain on their current password.</p>
      </div>
    </div>
    <div class="bmis-modal-info" style="background:var(--warning-pale); border:1.5px solid #fde68a;">
      <i class="fas fa-exclamation-circle" style="color:var(--warning); font-size:18px; margin-top:1px;"></i>
      <div>
        <p style="font-size:13px; font-weight:700; color:#92400e;" id="rejectResetName"></p>
        <p style="font-size:12px; color:#b45309; margin-top:2px;">No password changes will be made. This can't be undone once submitted.</p>
      </div>
    </div>
    <div class="bmis-modal-actions">
      <button type="button" class="bmis-btn-cancel" onclick="closeAllResetModals()">Cancel</button>
      <button type="button" class="bmis-btn-confirm" style="background:linear-gradient(135deg,var(--warning),#f59e0b);"
              onclick="document.getElementById('rejectResetForm').submit();">
        <i class="fas fa-times"></i> Yes, reject
      </button>
    </div>
  </div>
</div>


<!-- ════════════════════════════════════════════════════════
     DELETE MODAL
═══════════════════════════════════════════════════════════ -->
<div id="deleteResetModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px;">
      <div class="bmis-modal-icon" style="background:var(--danger-pale);">
        <i class="fas fa-trash-alt" style="color:var(--danger);"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Delete this record?</p>
        <p class="bmis-modal-sub">This action cannot be undone.</p>
      </div>
    </div>
    <div class="bmis-modal-info" style="background:var(--danger-pale); border:1.5px solid #fecaca;">
      <i class="fas fa-exclamation-triangle" style="color:var(--danger); font-size:18px; margin-top:1px;"></i>
      <div>
        <p style="font-size:13px; font-weight:700; color:#991b1b;" id="deleteResetName"></p>
        <p style="font-size:12px; color:#b91c1c; margin-top:2px;">This request record will be permanently removed.</p>
      </div>
    </div>
    <div class="bmis-modal-actions">
      <button type="button" class="bmis-btn-cancel" onclick="closeAllResetModals()">Cancel</button>
      <button type="button" class="bmis-btn-confirm" style="background:linear-gradient(135deg,var(--danger),#ef4444);"
              onclick="document.getElementById('deleteResetForm').submit();">
        <i class="fas fa-trash-alt"></i> Yes, delete
      </button>
    </div>
  </div>
</div>


<script>
function openApproveModal(btn) {
    document.getElementById('approveReqId').value      = btn.dataset.reqId;
    document.getElementById('approveResidentId').value = btn.dataset.residentId;
    document.getElementById('approveResetName').textContent = btn.dataset.fullName;
    document.getElementById('approveResetModal').classList.add('open');
}

function openRejectModal(btn) {
    document.getElementById('rejectReqId').value = btn.dataset.reqId;
    document.getElementById('rejectResetName').textContent = btn.dataset.fullName;
    document.getElementById('rejectResetModal').classList.add('open');
}

function openDeleteModal(btn) {
    document.getElementById('deleteReqId').value = btn.dataset.reqId;
    document.getElementById('deleteResetName').textContent = btn.dataset.fullName;
    document.getElementById('deleteResetModal').classList.add('open');
}

function closeAllResetModals() {
    document.querySelectorAll('.bmis-modal-backdrop').forEach(function (m) {
        m.classList.remove('open');
    });
}

// Close on backdrop click
document.querySelectorAll('.bmis-modal-backdrop').forEach(function (modal) {
    modal.addEventListener('click', function (e) {
        if (e.target === this) closeAllResetModals();
    });
});

// Close on Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') closeAllResetModals();
});
</script>

<?php include('dashboard_sidebar_end.php'); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
</body>
</html>