
<?php include(ROOT_PATH . '/dashboard_sidebar_start_sk.php'); ?>
<style>
:root { --sk: #1a4480; --gold: #c9943a; --sk-pale: #e8eef8; --gold-pale: #fdf3e3; }
.page-header { background: linear-gradient(135deg,#1a4480,#2b5ea7); color:#fff; border-radius:16px; padding:26px 30px; margin-bottom:24px; display:flex; align-items:center; gap:16px; box-shadow:0 6px 24px rgba(26,107,58,0.2); }
.page-header .hdr-icon { width:60px;height:60px;border-radius:14px;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;font-size:1.7rem;flex-shrink:0; }
.page-header h2 { margin:0;font-size:1.5rem;font-weight:700; }
.page-header p  { margin:4px 0 0;opacity:.82;font-size:.88rem; }
.panel { background:#fff;border-radius:14px;padding:22px 24px;box-shadow:0 2px 8px rgba(26,107,58,0.07);border:1.5px solid #e8ecf0; }
.badge-emp  { background:#e8f5ed;color:#1a6b3a;border-radius:6px;padding:3px 9px;font-size:.75rem;font-weight:700; }
.badge-unemp { background:#fde8e8;color:#c0392b;border-radius:6px;padding:3px 9px;font-size:.75rem;font-weight:700; }
.badge-self { background:#fdf3e3;color:#c9943a;border-radius:6px;padding:3px 9px;font-size:.75rem;font-weight:700; }
.badge-stud { background:#e8f0fe;color:#1967d2;border-radius:6px;padding:3px 9px;font-size:.75rem;font-weight:700; }
.btn-sk  { background:#1a6b3a;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:.875rem;font-weight:600;transition:all .2s; }
.btn-sk:hover { background:#145230;color:#fff; }
.btn-sk-outline { border:2px solid #1a6b3a;color:#1a6b3a;background:transparent;border-radius:8px;padding:6px 14px;font-size:.82rem;font-weight:600;transition:all .2s; }
.btn-sk-outline:hover { background:#1a6b3a;color:#fff; }
.search-box { border-radius:10px;border:1.5px solid #e0e7ef;font-size:.875rem;padding:9px 14px; }
.search-box:focus { border-color:#1a6b3a;box-shadow:0 0 0 3px rgba(26,107,58,0.1); }
.table thead th { background:#f4f7fb;color:#1a6b3a;font-size:.78rem;text-transform:uppercase;letter-spacing:.06em;font-weight:700;border-bottom:2px solid #e8ecf0; }
.table td { font-size:.875rem;vertical-align:middle;border-bottom:1px solid #f0f4f8; }
.action-btn { border:none;border-radius:7px;padding:5px 12px;font-size:.8rem;font-weight:600;cursor:pointer;transition:all .15s; }
.dots-btn { width:40px;height:40px;border:0;border-radius:50%;background:#eef2f8;color:#1a4480;display:inline-flex;align-items:center;justify-content:center;font-size:1rem; }
.dots-btn:active, .dots-btn[aria-expanded="true"] { background:#1a4480;color:#fff; }
.sk-dots .dropdown-menu { min-width:150px;border-radius:12px;padding:6px; }
.sk-dots .dropdown-item { border-radius:8px;padding:10px 12px;font-size:.9rem; }
.sk-pager { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; margin-top:14px; padding-top:14px; border-top:1px solid #eef1f4; }
.sk-pager-info { color:#6b7a90; }
.sk-pager .page-link { color:#1a4480; min-width:36px; text-align:center; }
.sk-pager .page-item.active .page-link { background:#1a4480; border-color:#1a4480; color:#fff; }
@media (max-width:575.98px) { .sk-pager { flex-direction:column; align-items:center; } .sk-pager .page-link { min-width:40px; padding:8px 10px; } }
.view-btn { background:#e8f5ed;color:#1a6b3a; }
.view-btn:hover { background:#1a6b3a;color:#fff; }
.edit-btn { background:#e8f0fe;color:#1967d2; }
.edit-btn:hover { background:#1967d2;color:#fff; }
.del-btn { background:#fde8e8;color:#c0392b; }
.del-btn:hover { background:#c0392b;color:#fff; }
.modal-header { background:#1a4480;color:#fff;border-radius:12px 12px 0 0; }
.modal-header .btn-close { filter:invert(1); }
.alert-success-custom { background:#e8f5ed;color:#1a6b3a;border:1.5px solid #1a6b3a;border-radius:10px;padding:10px 18px;font-size:.875rem;font-weight:600;margin-bottom:16px; }
/* ── CUSTOM CHECKBOXES ── */
.form-check-input[type="checkbox"] {
    width: 18px !important;
    height: 18px !important;
    border: 2.5px solid #b0c4d8;
    border-radius: 5px !important;
    cursor: pointer;
    transition: all .2s ease;
    background-color: #fff;
    box-shadow: none;
}
.form-check-input[type="checkbox"]:hover {
    border-color: #c0392b;
    box-shadow: 0 0 0 4px rgba(192,57,43,0.08);
    transform: scale(1.05);
}
.form-check-input[type="checkbox"]:checked {
    background-color: #c0392b !important;
    border-color: #c0392b !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='none' stroke='%23ffffff' stroke-width='3' stroke-linecap='round' stroke-linejoin='round' d='M4 10l4 4 8-8'/%3E%3C/svg%3E") !important;
    background-size: 12px !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
    box-shadow: 0 0 0 4px rgba(192,57,43,0.15) !important;
}
#masterCheck[type="checkbox"] {
    width: 20px !important;
    height: 20px !important;
    border-color: #1a6b3a !important;
}
#masterCheck[type="checkbox"]:hover {
    border-color: #1a6b3a !important;
    box-shadow: 0 0 0 4px rgba(26,107,58,0.12) !important;
}
#masterCheck[type="checkbox"]:checked {
    background-color: #1a6b3a !important;
    border-color: #1a6b3a !important;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20'%3E%3Cpath fill='none' stroke='%23ffffff' stroke-width='3' stroke-linecap='round' stroke-linejoin='round' d='M4 10l4 4 8-8'/%3E%3C/svg%3E") !important;
    background-size: 13px !important;
    background-repeat: no-repeat !important;
    background-position: center !important;
    box-shadow: 0 0 0 4px rgba(26,107,58,0.18) !important;
}

/* Selected row highlight */
tr:has(.row-check:checked) td {
    background-color: #fff0f0 !important;
    border-bottom: 1px solid #f5c6c6 !important;
}
    /* ── CHECKBOX ALIGNMENT ── */
table thead tr th:first-child,
table tbody tr td:first-child {
    text-align: center;
    vertical-align: middle;
    padding-left: 12px;
    padding-right: 12px;
    width: 44px;
}

.form-check-input[type="checkbox"] {
    display: block;
    margin: 0 auto;
    float: none;
    vertical-align: middle;
    position: relative;
    top: 0;
}
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="hdr-icon"><i class="fas fa-users"></i></div>
        <div>
            <h2>Youth Member Records</h2>
            <p>Digital database of youth sector members aged 15–30</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="alert-success-custom"><i class="fas fa-check-circle me-2"></i>
        <?= $_GET['success']==='added'?'Youth record added successfully!':($_GET['success']==='updated'?'Record updated!':'Record deleted.') ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
    <div class="alert alert-danger py-2" style="border-radius:10px;font-size:.875rem;font-weight:600;"><i class="fas fa-exclamation-circle me-2"></i>
        <?= ['age'=>'Only residents aged 15–29 can be added as youth.','exists'=>'That resident is already registered as youth.'][$_GET['error']] ?? 'Could not add the youth. Please select a resident and fill in all required fields.' ?>
    </div>
    <?php endif; ?>

    <div class="panel mb-3">
    <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
        <form method="GET" class="d-flex gap-2 flex-wrap align-items-center">
            <input type="text" name="keyword" class="form-control search-box" placeholder="Search by name or email…" value="<?= htmlspecialchars($keyword) ?>" style="width:260px;">
            <button type="submit" class="btn-sk btn"><i class="fas fa-search me-1"></i> Search</button>
            <?php if ($keyword): ?><a href="sk_youth_records.php" class="btn btn-sm btn-outline-secondary">Clear</a><?php endif; ?>
        </form>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <span style="font-size:.82rem;color:#888;">Showing <strong><?= $total ?></strong> record(s)</span>
            <button type="button" class="btn-sk btn btn-sm" data-bs-toggle="modal" data-bs-target="#addYouthModal">
                <i class="fas fa-user-plus me-1"></i> Add Youth
            </button>
            <button type="button" id="selectAllBtn" class="btn btn-sm btn-outline-secondary" onclick="toggleSelectAll()">
                <i class="fas fa-check-square me-1"></i> Select All
            </button>
            <button type="button" id="bulkDeleteBtn" class="btn btn-sm btn-danger d-none" data-bs-toggle="modal" data-bs-target="#bulkDelModal">
                <i class="fas fa-trash me-1"></i> Delete Selected (<span id="selectedCount">0</span>)
            </button>

        </div>
    </div>
</div>

    <div class="panel">
    <form method="POST" id="bulkForm">
    <div class="table-responsive">
        <table class="table table-hover sk-youth-table">
            <thead>
                <tr>
                    <th style="width:40px;">
                    <input type="checkbox" id="masterCheck" onchange="toggleSelectAll(this.checked)" class="form-check-input">
                </th>
                    <th>#</th><th>Name</th><th>Age</th><th>Sex</th><th>Civil Status</th>
                    <th>Email</th><th>Education</th><th>Employment</th><th>Skills</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($youths)): ?>
                <tr><td colspan="11" class="text-center py-4 text-muted">No youth records found.</td></tr>
            <?php else: foreach ($youths as $y): ?>
            <tr>
                <td>
                <input type="checkbox" name="selected_ids[]" value="<?= $y['id_youth'] ?>" 
                    class="form-check-input row-check" onchange="updateBulkBtn()">
            </td>
                <td><?= $y['id_youth'] ?></td>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <?php $photo_src = $y['photo'] ? 'uploads/youth_photos/' . htmlspecialchars($y['photo']) : 'https://via.placeholder.com/36x36.png?text=%20'; ?>
                        <img src="<?= $photo_src ?>"
                            alt="" class="rounded-circle" style="width:36px;height:36px;object-fit:cover;border:1px solid #e8ecf0;flex-shrink:0;cursor:pointer;"
                            data-bs-toggle="modal" data-bs-target="#photoModal"
                            data-photo="<?= $photo_src ?>"
                            data-name="<?= htmlspecialchars($y['lname'].', '.$y['fname'],ENT_QUOTES) ?>"
                            onclick="showYouthPhoto(this)">
                        <strong><?= htmlspecialchars($y['lname'].', '.$y['fname'].' '.$y['mi']) ?></strong>
                    </div>
                </td>
                <td><?= htmlspecialchars($y['age']) ?></td>
                <td><?= htmlspecialchars($y['sex']) ?></td>
                <td><?= htmlspecialchars($y['civil_status']) ?></td>
                <td><?= htmlspecialchars($y['email_address']) ?></td>
                <td><?= htmlspecialchars($y['educ_attain']) ?></td>
                <td>
                    <?php
                    $bc=['Employed'=>'badge-emp','Unemployed'=>'badge-unemp','Self-Employed'=>'badge-self','Student'=>'badge-stud'];
                    $bc2=$bc[$y['emp_status']]??'badge-emp';
                    ?><span class="<?= $bc2 ?>"><?= htmlspecialchars($y['emp_status']) ?></span>
                </td>
                <td><?= htmlspecialchars($y['skill_name']) ?></td>
                <td>
                    <!-- Mobile: three-dots menu (the buttons below are hidden on phones and triggered from here) -->
                    <div class="dropdown d-md-none sk-dots">
                        <button type="button" class="dots-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions"><i class="fas fa-ellipsis-v"></i></button>
                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li><button type="button" class="dropdown-item" onclick="skRowAct(this,'.view-btn')"><i class="fas fa-eye fa-fw me-2"></i>View</button></li>
                            <li><button type="button" class="dropdown-item" onclick="skRowAct(this,'.edit-btn')"><i class="fas fa-edit fa-fw me-2"></i>Edit</button></li>
                            <li><button type="button" class="dropdown-item text-danger" onclick="skRowAct(this,'.del-btn')"><i class="fas fa-trash fa-fw me-2"></i>Delete</button></li>
                        </ul>
                    </div>
                    <button type="button" class="action-btn view-btn me-1 d-md-none"
                        data-bs-toggle="modal" data-bs-target="#viewYouthModal"
                        data-name="<?= htmlspecialchars($y['lname'].', '.$y['fname'].' '.$y['mi'],ENT_QUOTES) ?>"
                        data-age="<?= htmlspecialchars($y['age'],ENT_QUOTES) ?>"
                        data-sex="<?= htmlspecialchars($y['sex'],ENT_QUOTES) ?>"
                        data-civil="<?= htmlspecialchars($y['civil_status'],ENT_QUOTES) ?>"
                        data-email="<?= htmlspecialchars($y['email_address'],ENT_QUOTES) ?>"
                        data-educ="<?= htmlspecialchars($y['educ_attain'],ENT_QUOTES) ?>"
                        data-emp="<?= htmlspecialchars($y['emp_status'],ENT_QUOTES) ?>"
                        data-skill="<?= htmlspecialchars($y['skill_name'],ENT_QUOTES) ?>"
                        data-id="<?= (int)$y['id_youth'] ?>"
                        data-photo="<?= $y['photo'] ? 'uploads/youth_photos/'.htmlspecialchars($y['photo'],ENT_QUOTES) : '' ?>"
                        onclick="fillViewYouth(this)"
                    ><i class="fas fa-eye"></i> View</button>
                    <button type="button" class="action-btn edit-btn me-1"
                        data-bs-toggle="modal" data-bs-target="#editModal"
                        data-id="<?= $y['id_youth'] ?>"
                        data-fname="<?= htmlspecialchars($y['fname'],ENT_QUOTES) ?>"
                        data-lname="<?= htmlspecialchars($y['lname'],ENT_QUOTES) ?>"
                        data-mi="<?= htmlspecialchars($y['mi'],ENT_QUOTES) ?>"
                        data-age="<?= htmlspecialchars($y['age'],ENT_QUOTES) ?>"
                        data-sex="<?= htmlspecialchars($y['sex'],ENT_QUOTES) ?>"
                        data-civil="<?= htmlspecialchars($y['civil_status'],ENT_QUOTES) ?>"
                        data-email="<?= htmlspecialchars($y['email_address'],ENT_QUOTES) ?>"
                        data-educ="<?= htmlspecialchars($y['educ_attain'],ENT_QUOTES) ?>"
                        data-emp="<?= htmlspecialchars($y['emp_status'],ENT_QUOTES) ?>"
                        data-skill="<?= htmlspecialchars($y['skill_name'],ENT_QUOTES) ?>"
                    ><i class="fas fa-edit"></i> Edit</button>
                    <button type="button" class="action-btn del-btn" 
                    data-bs-toggle="modal" data-bs-target="#delModal" 
                    data-id="<?= $y['id_youth'] ?>" 
                    data-name="<?= htmlspecialchars($y['lname'].', '.$y['fname'],ENT_QUOTES) ?>">
                    <i class="fas fa-trash"></i>
                </button>
                </td>
            </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
      </div>
    </form>

    <?php if (($total_pages ?? 1) > 1):
        $qs = $_GET; unset($qs['page']);
        $pg_url = function ($n) use ($qs) { return htmlspecialchars('sk_youth_records.php?' . http_build_query($qs + ['page' => $n])); };
        $from = ($page - 1) * $per_page + 1;
        $to   = min($total, $page * $per_page);
        $start = max(1, $page - 1); $end = min($total_pages, $page + 1);
    ?>
    <div class="sk-pager">
        <small class="sk-pager-info">Showing <strong><?= $from ?></strong>–<strong><?= $to ?></strong> of <strong><?= $total ?></strong></small>
        <nav aria-label="Youth records pages">
            <ul class="pagination pagination-sm mb-0">
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>"><a class="page-link" href="<?= $pg_url($page - 1) ?>" aria-label="Previous">&laquo;</a></li>
                <?php if ($start > 1): ?>
                    <li class="page-item"><a class="page-link" href="<?= $pg_url(1) ?>">1</a></li>
                    <?php if ($start > 2): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                <?php endif; ?>
                <?php for ($i = $start; $i <= $end; $i++): ?>
                    <li class="page-item <?= $i === $page ? 'active' : '' ?>"><a class="page-link" href="<?= $pg_url($i) ?>"><?= $i ?></a></li>
                <?php endfor; ?>
                <?php if ($end < $total_pages): ?>
                    <?php if ($end < $total_pages - 1): ?><li class="page-item disabled"><span class="page-link">…</span></li><?php endif; ?>
                    <li class="page-item"><a class="page-link" href="<?= $pg_url($total_pages) ?>"><?= $total_pages ?></a></li>
                <?php endif; ?>
                <li class="page-item <?= $page >= $total_pages ? 'disabled' : '' ?>"><a class="page-link" href="<?= $pg_url($page + 1) ?>" aria-label="Next">&raquo;</a></li>
            </ul>
        </nav>
    </div>
    <?php endif; ?>
    </div>
</div>

<!-- Add Youth Modal -->
<div class="modal fade" id="addYouthModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user-plus me-2"></i>Add Youth Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
      <div class="modal-body">
        <input type="hidden" name="add_mode" id="add_mode" value="resident">
        <ul class="nav nav-pills mb-3" style="gap:6px;">
            <li class="nav-item"><button type="button" class="btn btn-sm btn-outline-secondary active" id="modeResBtn" onclick="setAddMode('resident')"><i class="fas fa-id-card me-1"></i> Existing resident</button></li>
            <li class="nav-item"><button type="button" class="btn btn-sm btn-outline-secondary" id="modeWalkBtn" onclick="setAddMode('walkin')"><i class="fas fa-user-plus me-1"></i> No account (walk-in)</button></li>
        </ul>
        <div class="row g-3">
            <!-- Existing resident -->
            <div class="col-12 mode-resident">
                <?php if (empty($eligible_residents)): ?>
                <div class="text-muted small">No eligible residents found (aged 15–29 and not yet registered). Use “No account (walk-in)” instead.</div>
                <?php else: ?>
                <label class="form-label fw-semibold">Resident <span class="text-danger">*</span></label>
                <input type="text" id="add_res_filter" class="form-control mb-2" placeholder="Type to filter residents by name…" autocomplete="off">
                <select name="id_resident" id="add_res" class="form-select" size="6">
                    <?php foreach ($eligible_residents as $r): ?>
                    <option value="<?= (int)$r['id_resident'] ?>"
                        data-sex="<?= htmlspecialchars($r['sex'] ?? '', ENT_QUOTES) ?>"
                        data-age="<?= (int)$r['calc_age'] ?>"
                        data-email="<?= htmlspecialchars($r['email'] ?? '', ENT_QUOTES) ?>">
                        <?= htmlspecialchars($r['lname'] . ', ' . $r['fname'] . ' ' . ($r['mi'] ?? '')) ?> — <?= (int)$r['calc_age'] ?> yrs
                    </option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Name, sex and age are taken from the resident record.</div>
                <?php endif; ?>
            </div>
            <div class="col-md-3 mode-resident"><label class="form-label fw-semibold">Age</label><input id="add_age" class="form-control" readonly tabindex="-1" style="background:#f4f7fb;"></div>
            <div class="col-md-3 mode-resident"><label class="form-label fw-semibold">Sex</label><input id="add_sex" class="form-control" readonly tabindex="-1" style="background:#f4f7fb;"></div>

            <!-- Walk-in (no account) -->
            <div class="col-md-4 mode-walkin d-none"><label class="form-label fw-semibold">Last Name <span class="text-danger">*</span></label><input name="w_lname" class="form-control w-field" required disabled></div>
            <div class="col-md-4 mode-walkin d-none"><label class="form-label fw-semibold">First Name <span class="text-danger">*</span></label><input name="w_fname" class="form-control w-field" required disabled></div>
            <div class="col-md-4 mode-walkin d-none"><label class="form-label fw-semibold">Middle Initial</label><input name="w_mi" class="form-control w-field" maxlength="5" disabled></div>
            <div class="col-md-6 mode-walkin d-none"><label class="form-label fw-semibold">Birthdate <span class="text-danger">*</span></label><input type="date" name="w_bdate" class="form-control w-field" max="<?= date('Y-m-d') ?>" required disabled><div class="form-text">Must be aged 15–29.</div></div>
            <div class="col-md-6 mode-walkin d-none"><label class="form-label fw-semibold">Sex <span class="text-danger">*</span></label>
                <select name="w_sex" class="form-select w-field" required disabled><option value="">--</option><option>Male</option><option>Female</option></select>
            </div>

            <div class="col-md-6"><label class="form-label fw-semibold">Civil Status <span class="text-danger">*</span></label>
                <select name="civil_status" class="form-select" required><option value="">--</option><option>Single</option><option>Married</option><option>Solo Parent</option><option>Widowed</option></select>
            </div>
            <div class="col-md-6"><label class="form-label fw-semibold">Email Address</label><input name="email_address" id="add_email" type="email" class="form-control"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Educational Attainment</label><input name="educ_attain" class="form-control"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Employment Status <span class="text-danger">*</span></label>
                <select name="emp_status" class="form-select" required><option value="">--</option><option>Employed</option><option>Unemployed</option><option>Self-Employed</option><option>Student</option></select>
            </div>
            <div class="col-md-6"><label class="form-label fw-semibold">Skills</label><input name="skill_name" class="form-control"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Photo <small class="text-muted fw-normal">(optional)</small></label><input type="file" name="photo" accept="image/*" class="form-control"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="add_youth" class="btn-sk btn"><i class="fas fa-save me-1"></i> Add Youth</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Youth Member</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
      <input type="hidden" name="id_youth" id="edit_id">
      <div class="modal-body">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label fw-semibold">Last Name</label><input name="lname" id="edit_lname" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">First Name</label><input name="fname" id="edit_fname" class="form-control" required></div>
            <div class="col-md-4"><label class="form-label fw-semibold">Middle Initial</label><input name="mi" id="edit_mi" class="form-control" maxlength="5"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Age <small class="text-muted fw-normal">(from birthdate)</small></label><input name="age" id="edit_age" class="form-control" type="number" min="15" max="29" readonly tabindex="-1" style="background:#f4f7fb;"></div>
            <div class="col-md-3"><label class="form-label fw-semibold">Sex</label>
                <select name="sex" id="edit_sex" class="form-select" required><option value="">--</option><option>Male</option><option>Female</option></select>
            </div>
            <div class="col-md-6"><label class="form-label fw-semibold">Civil Status</label>
                <select name="civil_status" id="edit_civil" class="form-select" required><option value="">--</option><option>Single</option><option>Married</option><option>Solo Parent</option><option>Widowed</option></select>
            </div>
            <div class="col-md-6"><label class="form-label fw-semibold">Email Address</label><input name="email_address" id="edit_email" class="form-control" type="email"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Educational Attainment</label><input name="educ_attain" id="edit_educ" class="form-control"></div>
            <div class="col-md-6"><label class="form-label fw-semibold">Employment Status</label>
                <select name="emp_status" id="edit_emp" class="form-select" required><option value="">--</option><option>Employed</option><option>Unemployed</option><option>Self-Employed</option><option>Student</option></select>
            </div>
            <div class="col-12"><label class="form-label fw-semibold">Skills</label><input name="skill_name" id="edit_skill" class="form-control"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="edit_youth" class="btn-sk btn"><i class="fas fa-save me-1"></i> Update Record</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="delModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#c0392b;">
        <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Youth Record</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong id="del_name"></strong>?</p>
        <p class="text-danger"><small>This action cannot be undone.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form method="POST">
          <input type="hidden" name="id_youth" id="del_id">
          <button type="submit" name="delete_youth" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Bulk Delete Modal -->
<div class="modal fade" id="bulkDelModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#c0392b;">
        <h5 class="modal-title text-white"><i class="fas fa-trash me-2"></i>Delete Selected Records</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p>Are you sure you want to delete <strong><span id="bulkCount">0</span> selected record(s)</strong>?</p>
        <p class="text-danger"><small>This action cannot be undone.</small></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" onclick="submitBulkDelete()">
            <i class="fas fa-trash me-1"></i> Delete All Selected
        </button>
      </div>
    </div>
  </div>
</div>
<!-- View Youth Details Modal (used on mobile where the table only shows name + actions) -->
<div class="modal fade" id="viewYouthModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user me-2"></i>Youth Details</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="text-center mb-3">
          <img id="vy_photo" src="" alt="" class="rounded-circle d-none" style="width:96px;height:96px;object-fit:cover;border:2px solid #e8ecf0;">
          <div id="vy_name" class="fw-bold mt-2" style="font-size:1.05rem;"></div>
        </div>
        <dl class="vy-list mb-0">
          <div><dt>ID</dt><dd id="vy_id"></dd></div>
          <div><dt>Age</dt><dd id="vy_age"></dd></div>
          <div><dt>Sex</dt><dd id="vy_sex"></dd></div>
          <div><dt>Civil Status</dt><dd id="vy_civil"></dd></div>
          <div><dt>Email</dt><dd id="vy_email"></dd></div>
          <div><dt>Education</dt><dd id="vy_educ"></dd></div>
          <div><dt>Employment</dt><dd id="vy_emp"></dd></div>
          <div><dt>Skills</dt><dd id="vy_skill"></dd></div>
        </dl>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<style>
.vy-list > div { display:flex; justify-content:space-between; gap:14px; padding:9px 0; border-bottom:1px dashed #e6ebf2; }
.vy-list > div:last-child { border-bottom:0; }
.vy-list dt { font-size:.7rem; font-weight:700; letter-spacing:.05em; text-transform:uppercase; color:#6b7a90; flex:0 0 36%; margin:0; padding-top:2px; }
.vy-list dd { margin:0; text-align:right; font-size:.9rem; overflow-wrap:anywhere; flex:1; }
</style>
<script>
function skRowAct(item, sel) {
    var td = item.closest('td'), b = td && td.querySelector(sel);
    if (b) b.click();   // reuse the existing View / Edit / Delete buttons and their modals
}
function fillViewYouth(btn) {
    var d = btn.dataset, set = function (id, v) { document.getElementById(id).textContent = v && v !== '' ? v : '—'; };
    set('vy_name', d.name); set('vy_id', d.id); set('vy_age', d.age); set('vy_sex', d.sex);
    set('vy_civil', d.civil); set('vy_email', d.email); set('vy_educ', d.educ);
    set('vy_emp', d.emp); set('vy_skill', d.skill);
    var img = document.getElementById('vy_photo');
    if (d.photo) { img.src = d.photo; img.classList.remove('d-none'); } else { img.classList.add('d-none'); img.removeAttribute('src'); }
}
</script>
<!-- Photo Preview Modal -->
<div class="modal fade" id="photoModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="photo_modal_name"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <img id="photo_modal_img" src="" alt="" style="max-width:100%;max-height:70vh;border-radius:8px;">
      </div>
    </div>
  </div>
</div>
<script>
function showYouthPhoto(el) {
    document.getElementById('photo_modal_img').src = el.dataset.photo;
    document.getElementById('photo_modal_name').textContent = el.dataset.name;
}
</script>
<script>
function toggleSelectAll(checked) {
    const boxes = document.querySelectorAll('.row-check');
    const master = document.getElementById('masterCheck');
    // called from button or checkbox
    const state = (checked !== undefined) ? checked : !allChecked();
    boxes.forEach(b => b.checked = state);
    if (master) master.checked = state;
    updateBulkBtn();
}

function allChecked() {
    const boxes = document.querySelectorAll('.row-check');
    return [...boxes].every(b => b.checked);
}

function updateBulkBtn() {
    const checked = document.querySelectorAll('.row-check:checked');
    const btn = document.getElementById('bulkDeleteBtn');
    const count = document.getElementById('selectedCount');
    const bulkCount = document.getElementById('bulkCount');
    const master = document.getElementById('masterCheck');
    const total = document.querySelectorAll('.row-check').length;

    count.textContent = checked.length;
    if (bulkCount) bulkCount.textContent = checked.length;
    btn.classList.toggle('d-none', checked.length === 0);
    if (master) master.checked = checked.length === total && total > 0;
}

function submitBulkDelete() {
    const form = document.getElementById('bulkForm');
    
    // Add bulk_delete_youth flag
    let hidden = document.createElement('input');
    hidden.type = 'hidden';
    hidden.name = 'bulk_delete_youth';
    hidden.value = '1';
    form.appendChild(hidden);

    // Copy checked IDs into the form explicitly
    document.querySelectorAll('.row-check:checked').forEach(function(cb) {
        let input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'selected_ids[]';
        input.value = cb.value;
        form.appendChild(input);
    });

    form.method = 'POST';
    form.action = 'sk_youth_records.php';

    // form.submit() called below is a *programmatic* submit, which does not
    // fire the 'submit' event our generic listener (further down) listens
    // for — so the loading screen has to be shown explicitly here instead.
    if (typeof showPageNavLoader === 'function') showPageNavLoader('Deleting selected records...');
    form.submit();
}

</script>
<script>
function setAddMode(mode) {
    var walk = (mode === 'walkin');
    document.getElementById('add_mode').value = walk ? 'walkin' : 'resident';
    document.getElementById('modeResBtn').classList.toggle('active', !walk);
    document.getElementById('modeWalkBtn').classList.toggle('active', walk);
    document.querySelectorAll('.mode-resident').forEach(function (e) { e.classList.toggle('d-none', walk); });
    document.querySelectorAll('.mode-walkin').forEach(function (e) { e.classList.toggle('d-none', !walk); });
    document.querySelectorAll('.w-field').forEach(function (e) { e.disabled = !walk; });
    var sel = document.getElementById('add_res');
    if (sel) sel.required = !walk;
}
(function () {
    var sel = document.getElementById('add_res');
    setAddMode('resident');
    if (!sel) return;
    var all = Array.prototype.slice.call(sel.options);
    sel.addEventListener('change', function () {
        var o = sel.options[sel.selectedIndex];
        if (!o) return;
        document.getElementById('add_age').value = o.dataset.age || '';
        document.getElementById('add_sex').value = o.dataset.sex || '';
        var em = document.getElementById('add_email');
        if (!em.value) em.value = o.dataset.email || '';
    });
    document.getElementById('add_res_filter').addEventListener('input', function () {
        var q = this.value.toLowerCase().trim();
        sel.innerHTML = '';
        all.forEach(function (o) { if (!q || o.text.toLowerCase().indexOf(q) !== -1) sel.appendChild(o); });
    });
})();
</script>
<script>
document.getElementById('editModal').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    document.getElementById('edit_id').value      = b.dataset.id;
    document.getElementById('edit_lname').value   = b.dataset.lname;
    document.getElementById('edit_fname').value   = b.dataset.fname;
    document.getElementById('edit_mi').value      = b.dataset.mi;
    document.getElementById('edit_age').value     = b.dataset.age;
    var walk = parseInt(b.dataset.id, 10) >= 1000000000;   // walk-in youth (no resident record): age is editable
    document.getElementById('edit_age').readOnly = !walk;
    document.getElementById('edit_age').style.background = walk ? '' : '#f4f7fb';
    document.getElementById('edit_sex').value     = b.dataset.sex;
    document.getElementById('edit_civil').value   = b.dataset.civil;
    document.getElementById('edit_email').value   = b.dataset.email;
    document.getElementById('edit_educ').value    = b.dataset.educ;
    document.getElementById('edit_emp').value     = b.dataset.emp;
    document.getElementById('edit_skill').value   = b.dataset.skill;
});
document.getElementById('delModal').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    document.getElementById('del_id').value   = b.dataset.id;
    document.getElementById('del_name').textContent = b.dataset.name;
});

// Show the shared sidebar loading screen (from dashboard_sidebar_end.php)
// for every other feature on this page: the search form and its "Clear"
// link, and the Edit/Delete Youth forms — all of which reload the page.
// (The bulk-delete button shows its own loader directly in
// submitBulkDelete() above, since it submits programmatically and never
// fires this form's 'submit' event.) Sidebar links are skipped since
// dashboard_sidebar_end.php already handles those, and modal-trigger
// buttons are left alone since they don't navigate.
document.querySelectorAll('a[href]').forEach(function (link) {
    if (link.closest('.sidebar')) return;
    link.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;
        var href = link.getAttribute('href');
        if (!href || href === '#' || href.indexOf('javascript:') === 0) return;
        if (link.hasAttribute('data-bs-toggle') || link.hasAttribute('data-bs-dismiss')) return;
        if (link.target && link.target !== '' && link.target !== '_self') return;
        if (typeof showPageNavLoader === 'function') showPageNavLoader('Loading...');
    });
});

document.querySelectorAll('form').forEach(function (form) {
    if (form.id === 'bulkForm') return; // handled by submitBulkDelete() instead
    form.addEventListener('submit', function () {
        if (form.checkValidity && !form.checkValidity()) return;
        if (typeof showPageNavLoader === 'function') showPageNavLoader('Saving...');
    });
});
</script>
<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>