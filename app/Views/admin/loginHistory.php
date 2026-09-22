include('dashboard_sidebar_start.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BMIS - Login History</title>
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@600&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --blue-deep:   #1a2e4d;
            --blue-mid:    #2e5fa3;
            --blue-bright: #4a90d9;
            --blue-glow:   rgba(74,144,217,.16);
            --gold:        #c9a84c;
            --ink:         #0f1825;
            --mist:        #f4f7fb;
            --border:      rgba(46,95,163,.16);
            --card-shadow: 0 20px 60px rgba(26,46,77,.12),0 2px 8px rgba(26,46,77,.07);
            --danger:      #d63031;
            --danger-bg:   #fde8e8;
        }
        body { background:var(--mist); font-family:'DM Sans',sans-serif; }

        .page-heading { display:flex; align-items:center; gap:14px; margin-bottom:28px; }
        .page-heading .head-icon {
            width:46px; height:46px; border-radius:12px;
            background:linear-gradient(135deg,var(--blue-deep),var(--blue-mid));
            display:flex; align-items:center; justify-content:center;
            color:#fff; font-size:18px;
            box-shadow:0 4px 14px rgba(46,95,163,.3);
        }
        .page-heading h1 { font-family:'Playfair Display',serif; font-size:22px; color:var(--blue-deep); margin:0; }
        .page-heading p  { font-size:12px; color:#7a91b0; margin:2px 0 0; }

        .tab-bar { display:flex; gap:4px; margin-bottom:20px; }
        .tab-bar a {
            padding:9px 18px; border-radius:10px; font-size:13px; font-weight:500;
            color:#7a91b0; text-decoration:none; border:1.5px solid transparent;
            transition:all .2s;
        }
        .tab-bar a:hover { background:#eef3fb; color:var(--blue-mid); }
        .tab-bar a.active { background:var(--blue-mid); color:#fff; box-shadow:0 4px 12px rgba(46,95,163,.28); }

        .filter-card {
            background:#fff; border-radius:16px; box-shadow:var(--card-shadow);
            padding:20px 24px; margin-bottom:20px;
            display:flex; flex-wrap:wrap; gap:12px; align-items:flex-end;
        }
        .filter-card .fg { display:flex; flex-direction:column; gap:5px; }
        .filter-card label { font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:.4px; color:var(--blue-deep); }
        .filter-card input,
        .filter-card select {
            padding:8px 12px; border:1.5px solid var(--border); border-radius:8px;
            font-family:'DM Sans',sans-serif; font-size:13px; color:var(--ink);
            background:var(--mist); outline:none;
            transition:border-color .2s, box-shadow .2s;
        }
        .filter-card input:focus,
        .filter-card select:focus { border-color:var(--blue-bright); background:#fff; box-shadow:0 0 0 3px var(--blue-glow); }
        .fg-search { flex:1; min-width:200px; }
        .btn-filter {
            padding:9px 20px; border-radius:8px; border:none;
            background:linear-gradient(135deg,var(--blue-mid),var(--blue-bright));
            color:#fff; font-size:13px; font-weight:600; cursor:pointer;
            display:inline-flex; align-items:center; gap:6px;
            box-shadow:0 4px 12px rgba(46,95,163,.25);
        }
        .btn-reset {
            padding:9px 16px; border-radius:8px; border:1.5px solid var(--border);
            background:transparent; color:#7a91b0; font-size:13px; cursor:pointer;
            text-decoration:none; display:inline-flex; align-items:center;
        }

        /* Stat pills */
        .stat-row { display:flex; gap:12px; flex-wrap:wrap; margin-bottom:20px; }
        .stat-pill {
            background:#fff; border-radius:12px; padding:14px 20px;
            display:flex; align-items:center; gap:12px;
            box-shadow:0 4px 16px rgba(26,46,77,.08);
            flex:1; min-width:140px;
        }
        .stat-pill .s-icon {
            width:38px; height:38px; border-radius:10px;
            display:flex; align-items:center; justify-content:center;
            font-size:16px;
        }
        .stat-pill .s-val { font-size:22px; font-weight:600; color:var(--ink); line-height:1; }
        .stat-pill .s-lbl { font-size:11px; color:#7a91b0; margin-top:2px; }

        .main-card { background:#fff; border-radius:20px; box-shadow:var(--card-shadow); overflow:hidden; }
        .main-card::before {
            content:''; display:block; height:5px;
            background:linear-gradient(90deg,var(--blue-deep),var(--blue-bright),var(--gold));
        }
        .card-header-strip {
            background:linear-gradient(135deg,var(--blue-deep),var(--blue-mid));
            padding:18px 28px; display:flex; align-items:center; justify-content:space-between;
        }
        .card-header-strip h2 { font-family:'Playfair Display',serif; font-size:18px; color:#fff; margin:0; }
        .card-header-strip span { font-size:12px; color:rgba(255,255,255,.6); }

        .log-table { width:100%; border-collapse:collapse; }
        .log-table thead th {
            padding:11px 16px; font-size:11px; font-weight:600; letter-spacing:.5px;
            text-transform:uppercase; color:#7a91b0;
            background:#f8fafd; border-bottom:1px solid var(--border); white-space:nowrap;
        }
        .log-table tbody tr { border-bottom:1px solid #f0f4fb; transition:background .15s; }
        .log-table tbody tr:hover { background:#f5f8fe; }
        .log-table td { padding:11px 16px; font-size:13px; color:var(--ink); vertical-align:middle; }
        .log-table td.muted { color:#7a91b0; }

        .ev-badge {
            display:inline-flex; align-items:center; gap:5px;
            font-size:11px; font-weight:600; padding:3px 10px; border-radius:20px;
        }
        .ev-login  { background:#e3f3e9; color:#1e6e3e; }
        .ev-logout { background:#eef1f6; color:#5a6a80; }
        .ev-failed { background:#fde8e8; color:#a32d2d; }

        .empty-state { text-align:center; padding:60px 20px; color:#a0b4cc; }
        .empty-state i { font-size:40px; margin-bottom:12px; display:block; }
        .empty-state p { font-size:14px; margin:0; }

        .pagination-row {
            padding:16px 24px; display:flex; align-items:center;
            justify-content:space-between; border-top:1px solid var(--border);
            flex-wrap:wrap; gap:10px;
        }
        .pagination-row span { font-size:12px; color:#7a91b0; }
        .pagination-links { display:flex; gap:4px; }
        .pagination-links a, .pagination-links span {
            padding:5px 11px; border-radius:7px; font-size:12px; font-weight:500;
            border:1.5px solid var(--border); text-decoration:none; color:var(--blue-mid);
            transition:background .15s;
        }
        .pagination-links a:hover { background:var(--blue-glow); }
        .pagination-links .current { background:var(--blue-mid); color:#fff; border-color:var(--blue-mid); }
        .pagination-links .disabled { color:#c0cdd8; pointer-events:none; }

        .btn-bulk-delete {
            background: linear-gradient(135deg, #d9534f, #c9302c);
            box-shadow: 0 4px 12px rgba(217, 83, 79, 0.25);
            display: none;
        }

        /* ── Shared Modal Base ── */
        .modal-overlay {
            display:none; position:fixed; inset:0;
            background:rgba(15,24,37,.45); backdrop-filter:blur(3px);
            z-index:9999; align-items:center; justify-content:center;
        }
        .modal-overlay.open { display:flex; }
        .modal-box {
            background:#fff; border-radius:18px; padding:32px 28px;
            max-width:400px; width:92%; text-align:center;
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
        .modal-box h3 { font-family:'Playfair Display',serif; color:var(--blue-deep); margin:0 0 8px; font-size:18px; }
        .modal-box p  { font-size:13px; color:#7a91b0; margin:0 0 24px; }
        .modal-box p strong { color:var(--danger); }
        .modal-actions { display:flex; gap:10px; justify-content:center; }
        .btn-modal-cancel {
            padding:10px 22px; border-radius:9px; border:1.5px solid var(--border);
            background:#fff; color:#5a6a80; font-size:13px; font-weight:600; cursor:pointer;
        }
        .btn-modal-confirm {
            padding:10px 22px; border-radius:9px; border:none;
            background:var(--danger); color:#fff; font-size:13px; font-weight:600; cursor:pointer;
            box-shadow:0 4px 12px rgba(214,48,49,.3);
        }

        @media(max-width:768px) {
            .filter-card { flex-direction:column; }
            .fg-search { min-width:100%; }
        }
    </style>
</head>
<body id="page-top">
<div class="container-fluid mt-4">

    <?php echo $delete_message; ?>

    <div class="page-heading">
        <div class="head-icon"><i class="fas fa-history"></i></div>
        <div>
            <h1>Audit &amp; Logs</h1>
            <p>Track all administrator actions and login events</p>
        </div>
    </div>

    <div class="tab-bar">
        <a href="admn_activity_logs.php"><i class="fas fa-tasks mr-1"></i> Activity Logs</a>
        <a href="admn_login_history.php" class="active"><i class="fas fa-sign-in-alt mr-1"></i> Login History</a>
    </div>

    <?php
    $all = $bmis->get_login_history([], 1, 999999);
    $count_login  = count(array_filter($all['rows'], fn($r) => $r['event']==='login'));
    $count_logout = count(array_filter($all['rows'], fn($r) => $r['event']==='logout'));
    $count_failed = count(array_filter($all['rows'], fn($r) => $r['event']==='failed'));
    ?>
    <div class="stat-row">
        <div class="stat-pill">
            <div class="s-icon" style="background:#e3f3e9;color:#1e6e3e;"><i class="fas fa-sign-in-alt"></i></div>
            <div>
                <div class="s-val"><?php echo number_format($count_login); ?></div>
                <div class="s-lbl">Successful Logins</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="s-icon" style="background:#eef1f6;color:#5a6a80;"><i class="fas fa-sign-out-alt"></i></div>
            <div>
                <div class="s-val"><?php echo number_format($count_logout); ?></div>
                <div class="s-lbl">Logouts</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="s-icon" style="background:#fde8e8;color:#a32d2d;"><i class="fas fa-exclamation-triangle"></i></div>
            <div>
                <div class="s-val"><?php echo number_format($count_failed); ?></div>
                <div class="s-lbl">Failed Attempts</div>
            </div>
        </div>
        <div class="stat-pill">
            <div class="s-icon" style="background:#dbe9ff;color:#185FA5;"><i class="fas fa-database"></i></div>
            <div>
                <div class="s-val"><?php echo number_format($all['total']); ?></div>
                <div class="s-lbl">Total Events</div>
            </div>
        </div>
    </div>

    <form method="GET" action="">
        <div class="filter-card">
            <div class="fg fg-search">
                <label>Search</label>
                <input type="text" name="search" placeholder="Name or email…" value="<?php echo htmlspecialchars($filters['search']); ?>">
            </div>
            <div class="fg">
                <label>Event</label>
                <select name="event">
                    <option value="">All Events</option>
                    <option value="login"  <?php echo $filters['event']==='login' ?'selected':''; ?>>Login</option>
                    <option value="logout" <?php echo $filters['event']==='logout'?'selected':''; ?>>Logout</option>
                    <option value="failed" <?php echo $filters['event']==='failed'?'selected':''; ?>>Failed</option>
                </select>
            </div>
            <div class="fg">
                <label>Date From</label>
                <input type="date" name="date_from" value="<?php echo htmlspecialchars($filters['date_from']); ?>">
            </div>
            <div class="fg">
                <label>Date To</label>
                <input type="date" name="date_to" value="<?php echo htmlspecialchars($filters['date_to']); ?>">
            </div>
            <div class="fg">
                <label>&nbsp;</label>
                <div style="display:flex;gap:8px;">
                    <button type="submit" class="btn-filter"><i class="fas fa-filter"></i> Filter</button>
                    <a href="admn_login_history.php" class="btn-reset">Clear</a>
                </div>
            </div>
        </div>
    </form>

    <form method="POST" action="" id="bulkDeleteForm">
        <input type="hidden" name="bulk_delete" value="1">
        <div class="main-card">
            <div class="card-header-strip">
                <h2><i class="fas fa-sign-in-alt mr-2"></i>Login History</h2>
                <div style="display:flex; align-items:center; gap:16px;">
                    <button type="button" id="btnBulkDelete" class="btn-filter btn-bulk-delete">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                    <span><?php echo number_format($total); ?> record<?php echo $total!=1?'s':''; ?></span>
                </div>
            </div>

            <?php if(empty($logs)): ?>
            <div class="empty-state">
                <i class="fas fa-user-clock"></i>
                <p>No login history found<?php echo array_filter($filters)?' matching your filters.':' yet.'; ?></p>
            </div>
            <?php else: ?>
            <div style="overflow-x:auto;">
            <table class="log-table">
                <thead>
                    <tr>
                        <th style="width: 45px; text-align: center;">
                            <input type="checkbox" id="selectAll" style="transform: scale(1.2); cursor: pointer;">
                        </th>
                        <th>#</th>
                        <th>Admin / User</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Event</th>
                        <th>IP Address</th>
                        <th>Date &amp; Time</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                $row_num = ($page - 1) * $per_page + 1;
                foreach($logs as $log):
                    $ev = $log['event'];
                    $ev_class = match($ev) { 'login'=>'ev-login','logout'=>'ev-logout',default=>'ev-failed' };
                    $ev_icon  = match($ev) { 'login'=>'fa-sign-in-alt','logout'=>'fa-sign-out-alt',default=>'fa-times-circle' };
                    
                    // FIXED: Maps to your actual primary database key column name
                    $log_id = $log['id_history'] ?? $log['id'] ?? $log['log_id'] ?? 0;
                ?>
                <tr>
                    <td style="text-align: center;">
                        <input type="checkbox" name="selected_ids[]" value="<?php echo $log_id; ?>" class="log-checkbox" style="transform: scale(1.1); cursor: pointer;">
                    </td>
                    <td class="muted"><?php echo $row_num++; ?></td>
                    <td>
                        <div style="font-weight:600;"><?php echo htmlspecialchars($log['admin_name']); ?></div>
                    </td>
                    <td class="muted" style="font-size:12px;"><?php echo htmlspecialchars($log['role'] ?: '—'); ?></td>
                    <td class="muted" style="font-size:12px;"><?php echo htmlspecialchars($log['email'] ?? '—'); ?></td>
                    <td>
                        <span class="ev-badge <?php echo $ev_class; ?>">
                            <i class="fas <?php echo $ev_icon; ?>"></i>
                            <?php echo ucfirst($ev); ?>
                        </span>
                    </td>
                    <td class="muted" style="font-size:12px;font-family:monospace;"><?php echo htmlspecialchars($log['ip_address'] ?? '—'); ?></td>
                    <td class="muted" style="white-space:nowrap;">
                        <?php
                            $dt = new DateTime($log['created_at']);
                            echo $dt->format('M j, Y') . '<br><span style="font-size:11px;">' . $dt->format('h:i A') . '</span>';
                        ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <div class="pagination-row">
                <span>Showing <?php echo min(($page-1)*$per_page+1,$total); ?>–<?php echo min($page*$per_page,$total); ?> of <?php echo number_format($total); ?></span>
                <div class="pagination-links">
                    <?php
                    $base_url = '?' . http_build_query(array_merge($filters, ['page'=>'']));
                    if($page > 1): ?>
                        <a href="<?php echo $base_url.($page-1); ?>"><i class="fas fa-chevron-left"></i></a>
                    <?php else: ?>
                        <span class="disabled"><i class="fas fa-chevron-left"></i></span>
                    <?php endif;

                    $start = max(1,$page-2); $end = min($total_pages,$page+2);
                    for($i=$start;$i<=$end;$i++):
                        if($i===$page): ?><span class="current"><?php echo $i; ?></span>
                        <?php else: ?><a href="<?php echo $base_url.$i; ?>"><?php echo $i; ?></a>
                        <?php endif;
                    endfor;

                    if($page < $total_pages): ?>
                        <a href="<?php echo $base_url.($page+1); ?>"><i class="fas fa-chevron-right"></i></a>
                    <?php else: ?>
                        <span class="disabled"><i class="fas fa-chevron-right"></i></span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </form>

</div>

<!-- ══════════════════════════════════════════════════════
     Modal: Bulk Delete (checkbox-selected rows)
════════════════════════════════════════════════════════ -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon"><i class="fas fa-trash-alt"></i></div>
        <h3>Delete Selected Entries?</h3>
        <p>You are about to permanently delete <strong id="modalCount">0</strong> login history record(s). This cannot be undone.</p>
        <div class="modal-actions">
            <button type="button" class="btn-modal-cancel" id="btnModalCancel">Cancel</button>
            <button type="button" class="btn-modal-confirm" id="btnModalConfirm"><i class="fas fa-trash-alt mr-1"></i> Yes, Delete</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectAllBox = document.getElementById('selectAll');
    const rowBoxes = document.querySelectorAll('.log-checkbox');
    const deleteBtn = document.getElementById('btnBulkDelete');
    const deleteForm = document.getElementById('bulkDeleteForm');
    const deleteModal = document.getElementById('deleteModal');
    const modalCount = document.getElementById('modalCount');
    const btnModalCancel = document.getElementById('btnModalCancel');
    const btnModalConfirm = document.getElementById('btnModalConfirm');

    deleteBtn.addEventListener('click', function (e) {
        e.preventDefault();
        const checkCount = document.querySelectorAll('.log-checkbox:checked').length;
        if (checkCount === 0) return;
        modalCount.textContent = checkCount;
        deleteModal.classList.add('open');
    });

    btnModalCancel.addEventListener('click', () => deleteModal.classList.remove('open'));
    deleteModal.addEventListener('click', e => { if (e.target === deleteModal) deleteModal.classList.remove('open'); });

    btnModalConfirm.addEventListener('click', () => {
        deleteModal.classList.remove('open');
        deleteForm.submit();
    });

    function refreshDeleteButtonState() {
        const checkCount = document.querySelectorAll('.log-checkbox:checked').length;
        if (checkCount > 0) {
            deleteBtn.style.display = 'inline-flex';
            deleteBtn.innerHTML = `<i class="fas fa-trash"></i> Delete Selected (${checkCount})`;
        } else {
            deleteBtn.style.display = 'none';
        }
    }

    if (selectAllBox) {
        selectAllBox.addEventListener('change', function () {
            rowBoxes.forEach(box => {
                box.checked = selectAllBox.checked;
            });
            refreshDeleteButtonState();
        });
    }

    rowBoxes.forEach(box => {
        box.addEventListener('change', function () {
            if (!this.checked) {
                selectAllBox.checked = false;
            } else {
                const totalRowsChecked = Array.from(rowBoxes).every(cb => cb.checked);
                selectAllBox.checked = totalRowsChecked;
            }
            refreshDeleteButtonState();
        });
    });
});
</script>
</body>
</html>