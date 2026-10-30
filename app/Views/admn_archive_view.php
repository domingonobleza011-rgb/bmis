

<?php include(ROOT_PATH . '/dashboard_sidebar_start.php'); ?>

<link href="css/" rel="stylesheet">

<style>
/* Default-mode palette: these legacy variables are only defined by the
   sidebar when a theme (light/dark) is chosen, so without them the header
   line, search box and borders vanish. html[data-theme] still overrides. */
:root {
    --white:#ffffff; --cream:#f8f5ef; --border:#dfe4ee;
    --navy:#0f2d5a; --navy-mid:#163f78; --navy-light:#2b5ea7; --navy-pale:#e9f0fb;
    --text-dark:#16213f; --text-mid:#4c5872; --text-light:#8894a8;
    --gold:#c9943a; --gold-light:#dfae5b; --gold-pale:#fbf1de;
    --teal:#0f8c7f; --teal-pale:#e2f6f3;
    --danger-pale:#fdeceb; --success-pale:#e6f8ef;
    --shadow-sm:0 1px 3px rgba(15,45,90,0.06); --shadow-md:0 8px 24px rgba(15,45,90,0.10);
}
.archive-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 16px;
    padding-bottom: 1.4rem;
    border-bottom: 1px solid var(--border);
    margin-bottom: 1.6rem;
}
.archive-title-group { display: flex; align-items: center; gap: 14px; }
.archive-title-icon {
    width: 48px; height: 48px;
    border-radius: 12px;
    background: linear-gradient(135deg, #fdf3e3, #fce8c5);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; color: var(--gold);
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(201,148,58,0.2);
}
.archive-title-group h1 {
    font-size: 1.35rem !important;
    font-weight: 700 !important;
    color: var(--navy) !important;
    margin: 0 !important;
}
.archive-title-group h1::before { display: none !important; }
.archive-title-group .subtitle {
    font-size: 0.8rem;
    color: var(--text-light);
    margin: 2px 0 0;
}
.archive-stats { display: flex; flex-wrap: wrap; gap: 10px; }
.stat-chip {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 8px 14px;
    font-size: 0.8rem;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none !important;
    color: var(--text-mid) !important;
}
.stat-chip:hover, .stat-chip.active {
    border-color: var(--navy-light);
    background: var(--navy-pale);
    color: var(--navy) !important;
}
.stat-chip .chip-count { font-weight: 700; font-size: 0.95rem; color: var(--navy); }
.stat-chip .chip-label { font-size: 0.75rem; }
.filter-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
    background: var(--white);
    border: 1.5px solid var(--border);
    border-radius: 14px;
    padding: 12px 16px;
    margin-bottom: 1.4rem;
    box-shadow: 0 2px 8px rgba(15,45,90,0.05);
}
.filter-bar .filter-search { flex: 1; min-width: 200px; position: relative; }
.filter-bar .filter-search i {
    position: absolute; left: 12px; top: 50%;
    transform: translateY(-50%);
    color: var(--text-light); font-size: 0.85rem; pointer-events: none;
}
.filter-bar .filter-search input {
    width: 100%;
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 9px 12px 9px 36px;
    font-size: 0.875rem;
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
    transition: all 0.2s;
}
.filter-bar .filter-search input:focus {
    outline: none;
    border-color: var(--navy-light);
    box-shadow: 0 0 0 3px rgba(43,94,167,0.1);
    background: var(--white);
}
.filter-bar select {
    border: 1.5px solid var(--border);
    border-radius: 10px;
    padding: 9px 14px;
    font-size: 0.875rem;
    background: var(--cream);
    font-family: 'DM Sans', sans-serif;
    color: var(--text-dark);
    cursor: pointer;
    transition: all 0.2s;
}
.filter-bar select:focus {
    outline: none;
    border-color: var(--navy-light);
    box-shadow: 0 0 0 3px rgba(43,94,167,0.1);
}
.btn-filter-apply {
    background: linear-gradient(135deg, var(--navy), var(--navy-light));
    color: #fff; border: none; border-radius: 10px;
    padding: 9px 20px; font-size: 0.845rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s; white-space: nowrap;
}
.btn-filter-apply:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(15,45,90,0.25); }
.btn-clear-filter {
    background: var(--white); color: var(--text-mid);
    border: 1.5px solid var(--border); border-radius: 10px;
    padding: 9px 16px; font-size: 0.845rem; font-weight: 500;
    cursor: pointer; transition: all 0.2s; text-decoration: none; white-space: nowrap;
}
.btn-clear-filter:hover { background: var(--cream); color: var(--navy); }
.results-bar {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 0.8rem; flex-wrap: wrap; gap: 8px;
}
.results-count { font-size: 0.82rem; color: var(--text-light); font-weight: 500; }
.results-count strong { color: var(--navy); }
.archive-table-wrap {
    border-radius: 14px; overflow: hidden;
    box-shadow: 0 2px 16px rgba(15,45,90,0.08);
    border: 1px solid var(--border); background: var(--white);
}
.archive-table-wrap .table-responsive { border-radius: 0; box-shadow: none; border: none; }
.type-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.7rem; font-weight: 600; letter-spacing: 0.5px;
    text-transform: uppercase; padding: 4px 10px; border-radius: 20px; white-space: nowrap;
}
.type-badge.blue  { background: var(--navy-pale);   color: var(--navy-mid); }
.type-badge.teal  { background: var(--teal-pale);   color: var(--teal);     }
.type-badge.gold  { background: #fdf3e3;             color: var(--gold);     }
.type-badge.red   { background: var(--danger-pale); color: var(--danger);   }
.status-badge {
    display: inline-flex; align-items: center; gap: 5px;
    font-size: 0.7rem; font-weight: 600; padding: 4px 10px; border-radius: 20px;
}
.status-badge.archived { background: #fff3cd; color: #92400e; }
.status-badge.restored { background: var(--success-pale); color: var(--success); }
.btn-restore {
    background: linear-gradient(135deg, var(--success), #34d399) !important;
    color: #ffffff !important; border: none !important; border-radius: 8px !important;
    padding: 5px 14px !important; font-size: 0.78rem !important; font-weight: 600 !important;
    cursor: pointer; transition: all 0.18s !important;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-restore:hover { transform: translateY(-1px) !important; box-shadow: 0 3px 10px rgba(5,150,105,0.3) !important; }
.btn-perma-delete {
    background: var(--white) !important; color: var(--danger) !important;
    border: 1.5px solid rgba(220,38,38,0.3) !important; border-radius: 8px !important;
    padding: 5px 12px !important; font-size: 0.78rem !important; font-weight: 600 !important;
    cursor: pointer; transition: all 0.18s !important;
    display: inline-flex; align-items: center; gap: 4px;
}
.btn-perma-delete:hover { background: var(--danger-pale) !important; border-color: var(--danger) !important; }
.summary-text {
    font-size: 0.78rem; color: var(--text-light);
    max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.date-cell { font-size: 0.78rem; color: var(--text-mid); white-space: nowrap; }
.archive-empty { text-align: center; padding: 4rem 1rem; color: var(--text-light); }
.archive-empty .empty-icon {
    width: 72px; height: 72px; background: var(--cream); border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 2rem; color: var(--text-light); margin: 0 auto 1.2rem;
}
.archive-empty h5 { font-size: 1rem; font-weight: 600; color: var(--text-mid); margin-bottom: 0.4rem; }
.archive-empty p { font-size: 0.85rem; margin: 0; }
.json-detail pre {
    background: var(--cream); border: 1px solid var(--border); border-radius: 10px;
    padding: 12px 16px; font-size: 0.78rem; max-height: 220px; overflow-y: auto;
    color: var(--text-dark); text-align: left; margin: 0;
}
/* Bulk toolbar */
.bulk-toolbar {
    display: none; align-items: center; gap: 10px; flex-wrap: wrap;
    background: #fff5f5; border: 1.5px solid rgba(220,38,38,0.3);
    border-radius: 12px; padding: 10px 16px; margin-bottom: 1rem;
}
.bulk-toolbar .bulk-count { font-weight: 700; font-size: 0.875rem; color: var(--danger); margin-right: 4px; }
.btn-bulk-restore {
    background: linear-gradient(135deg, var(--success), #32a30f);
    color: #fff; border: none; border-radius: 8px;
    padding: 7px 18px; font-size: 0.82rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 6px;
}
.btn-bulk-restore:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(60, 255, 0, 0.3); }
.btn-bulk-delete {
    background: linear-gradient(135deg, var(--danger), #ef4444);
    color: #fff; border: none; border-radius: 8px;
    padding: 7px 18px; font-size: 0.82rem; font-weight: 600;
    cursor: pointer; transition: all 0.2s;
    display: inline-flex; align-items: center; gap: 6px;
}
.btn-bulk-delete:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(220,38,38,0.3); }
.btn-bulk-clear {
    background: var(--white); color: var(--text-mid);
    border: 1.5px solid var(--border); border-radius: 8px;
    padding: 7px 14px; font-size: 0.82rem; font-weight: 500; cursor: pointer; transition: all 0.2s;
}
.btn-bulk-clear:hover { background: var(--cream); color: var(--navy); }
.check-col { width: 44px; text-align: center !important; }
/* ============================================================
   MOBILE — filters become a tidy grid, table becomes cards
   ============================================================ */
@media (max-width: 768px) {
    .container-fluid { padding-left: 12px; padding-right: 12px; }

    /* header + stat chips (chips scroll sideways instead of stacking) */
    .archive-header { flex-direction: column; align-items: stretch; gap: 12px; padding-bottom: 1rem; margin-bottom: 1rem; }
    .archive-title-icon { width: 40px; height: 40px; font-size: 1.1rem; }
    .archive-title-group h1 { font-size: 1.15rem !important; }
    .archive-stats {
        flex-wrap: nowrap; overflow-x: auto; -webkit-overflow-scrolling: touch;
        scrollbar-width: none; padding-bottom: 2px; margin: 0 -12px; padding-left: 12px; padding-right: 12px;
    }
    .archive-stats::-webkit-scrollbar { display: none; }
    .stat-chip { flex: 0 0 auto; white-space: nowrap; padding: 8px 12px; }

    /* filters / search: search + type full width, buttons side by side */
    .filter-bar { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 14px; align-items: stretch; }
    .filter-bar .filter-search, .filter-bar select { grid-column: 1 / -1; min-width: 0; width: 100%; }
    .filter-bar .filter-search input, .filter-bar select { min-height: 42px; font-size: 16px; /* stops iOS zoom-on-focus */ }
    .btn-filter-apply, .btn-clear-filter {
        min-height: 42px; display: inline-flex; align-items: center; justify-content: center; gap: 6px;
    }
    .results-count { font-size: 0.78rem; line-height: 1.5; }

    /* bulk toolbar */
    .bulk-toolbar { gap: 8px; padding: 10px 12px; }
    .bulk-toolbar .bulk-count { flex: 1 0 100%; margin: 0; }
    .btn-bulk-restore, .btn-bulk-delete, .btn-bulk-clear { flex: 1; justify-content: center; min-height: 38px; }

    /* table -> stacked cards */
    .archive-table-wrap { border-radius: 12px; }
    .archive-table-wrap .table-responsive { overflow: visible; }
    #archiveTable, #archiveTable tbody, #archiveTable tr, #archiveTable td { display: block; width: 100%; }
    #archiveTable { border-collapse: separate; margin: 0; }
    #archiveTable thead { display: block; }
    #archiveTable thead tr { display: flex; align-items: center; padding: 10px 14px; background: var(--cream, #f8fafd); border-bottom: 2px solid var(--gold, #c9943a); }
    #archiveTable thead th { display: none; }
    #archiveTable thead th.check-col {
        display: flex; align-items: center; gap: 10px; width: auto; padding: 0; border: 0;
        background: none; text-align: left !important;
    }
    #archiveTable thead th.check-col::after { content: 'Select all'; font-size: 0.78rem; font-weight: 600; color: var(--text-mid, #5a6b85); }
    #archiveTable tbody { padding: 10px 12px 2px; }
    #archiveTable tbody tr:not([id^="detail-"]) {
        background: var(--white, #fff); border: 1px solid var(--border, #dfe6f1);
        border-radius: 12px; padding: 6px 12px; margin-bottom: 10px;
        box-shadow: 0 2px 8px rgba(15,45,90,0.06);
    }
    #archiveTable tbody tr:not([id^="detail-"]):has(.row-check:checked) {
        border-color: var(--navy-light, #2b5ea7); box-shadow: 0 0 0 2px rgba(43,94,167,0.15);
    }
    #archiveTable tbody td, #archiveTable tbody td.check-col {
        display: grid; grid-template-columns: 84px minmax(0, 1fr); gap: 10px; align-items: start;
        width: 100%; padding: 8px 0; text-align: left !important;
        border: 0; border-bottom: 1px dashed rgba(122,145,176,.3);
        background: none; box-shadow: none; word-break: break-word;
    }
    #archiveTable tbody td:last-child { border-bottom: 0; }
    #archiveTable tbody td[data-label]::before {
        content: attr(data-label); font-size: 0.65rem; font-weight: 700; letter-spacing: .04em;
        text-transform: uppercase; color: var(--text-light, #7a91b0); padding-top: 3px;
    }
    #archiveTable td .cv { min-width: 0; }
    .date-cell { white-space: normal; }
    .summary-text { max-width: 100%; white-space: normal; }
    #archiveTable td[data-label="Actions"] .cv > div { gap: 8px !important; }
    #archiveTable td[data-label="Actions"] .cv button { min-height: 36px; flex: 1 1 auto; justify-content: center; }

    /* expandable "Full Record Data" row: keep the JS toggle working in card mode */
    #archiveTable tr[id^="detail-"] { margin: -4px 0 10px; }
    #archiveTable tr[id^="detail-"][style*="table-row"] { display: block !important; }
    #archiveTable tr[id^="detail-"]:not([style*="table-row"]) { display: none !important; }
    #archiveTable tr[id^="detail-"] td { display: block; padding: 10px 12px; border-radius: 10px; }
    .json-detail pre { max-height: 200px; font-size: 0.72rem; white-space: pre-wrap; word-break: break-word; }

    /* pagination */
    .archive-table-wrap .d-flex.justify-content-between { flex-direction: column; align-items: center !important; text-align: center; padding: 10px 8px 14px; }
    .archive-table-wrap .pagination { flex-wrap: wrap; justify-content: center; gap: 4px; }
    .archive-table-wrap .page-link { min-width: 38px; text-align: center; padding: 8px 12px; }
}
@media (max-width: 380px) {
    .filter-bar { grid-template-columns: 1fr; }
    #archiveTable tbody td, #archiveTable tbody td.check-col { grid-template-columns: 72px minmax(0, 1fr); }
}
</style>

<!-- ─── BEGIN PAGE CONTENT ─────────────────────────────────── -->
<div class="container-fluid">

    <!-- Archive Header -->
    <div class="archive-header">
        <div class="archive-title-group">
            <div class="archive-title-icon"><i class="fas fa-archive"></i></div>
            <div>
                <h1>Archive</h1>
            </div>
        </div>
        <div class="archive-stats">
            <a href="admn_archive.php" class="stat-chip <?= $filter_type === '' ? 'active' : '' ?>">
                <span class="chip-count"><?= $total_archived ?></span>
                <span class="chip-label">All Archived</span>
            </a>
            <?php foreach ($type_meta as $key => $meta): if (!isset($counts[$key])) continue; ?>
            <a href="admn_archive.php?type=<?= $key ?>" class="stat-chip <?= $filter_type === $key ? 'active' : '' ?>">
                <span class="chip-count"><?= $counts[$key] ?></span>
                <span class="chip-label"><?= $meta['label'] ?></span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Filter Bar -->
    <form method="GET" action="admn_archive.php">
        <div class="filter-bar">
            <div class="filter-search">
                <i class="fas fa-search"></i>
                <input type="text" name="keyword" placeholder="Search by name or details…" value="<?= htmlspecialchars($filter_keyword) ?>">
            </div>
            <select name="type">
                <option value="">All Types</option>
                <?php foreach ($type_meta as $key => $meta): ?>
                <option value="<?= $key ?>" <?= $filter_type === $key ? 'selected' : '' ?>><?= $meta['label'] ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn-filter-apply"><i class="fas fa-filter"></i> Filter</button>
            <a href="admn_archive.php" class="btn-clear-filter"><i class="fas fa-times"></i> Clear</a>
        </div>
    </form>

    <!-- Results count -->
    <div class="results-bar">
        <span class="results-count">
            Showing <strong><?= count($records) ?></strong> of <strong><?= $paged['total'] ?></strong> record<?= $paged['total'] !== 1 ? 's' : '' ?>
            <?php if ($filter_type): ?> — filtered by <strong><?= htmlspecialchars($type_meta[$filter_type]['label'] ?? $filter_type) ?></strong><?php endif; ?>
            <?php if ($filter_keyword): ?> matching <strong>"<?= htmlspecialchars($filter_keyword) ?>"</strong><?php endif; ?>
        </span>
    </div>

    <!-- ══ Single-action form: lives OUTSIDE the bulk form ══ -->
    <form method="POST" action="admn_archive.php" id="singleActionForm">
        <input type="hidden" name="id_archive" id="singleActionId">
        <input type="hidden" id="singleActionName">
    </form>

    <!-- ══ Bulk form: wraps toolbar + table ══ -->
    <form method="POST" action="admn_archive.php" id="bulkForm">

        <!-- Bulk Toolbar (hidden until rows are checked) -->
        <div class="bulk-toolbar" id="bulkToolbar">
            <span class="bulk-count" id="bulkCount">0 selected</span>
            <button type="button" class="btn-bulk-restore" onclick="openBulkModal('restore')">
                <i class="fas fa-undo"></i> Restore Selected
            </button>
            <button type="button" class="btn-bulk-delete" onclick="openBulkModal('delete')">
                <i class="fas fa-trash"></i> Delete Selected
            </button>
            <button type="button" class="btn-bulk-clear" onclick="clearAllChecks()">
                <i class="fas fa-times"></i> Clear
            </button>
        </div>

        <!-- Archive Table -->
        <div class="archive-table-wrap">
            <?php if (empty($records)): ?>
                <div class="archive-empty">
                    <div class="empty-icon"><i class="fas fa-inbox"></i></div>
                    <h5>No archived records found</h5>
                    <p>Records appear here after they are deleted from any module.</p>
                </div>
            <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover" id="archiveTable">
                    <thead>
                        <tr>
                            <th class="check-col">
                                <input type="checkbox" id="checkAll" title="Select all"
                                    style="width:16px;height:16px;cursor:pointer;">
                            </th>
                            <th style="width:40px;">#</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Deleted</th>
                            <th>Deleted By</th>
                            <th style="min-width:180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($records as $i => $rec):
                            $meta = $type_meta[$rec['record_type']] ?? ['label' => $rec['record_type'], 'icon' => 'fa-file', 'color' => 'blue'];
                        ?>
                        <tr>
                            <td class="check-col" data-label="Select"><div class="cv">
                                <input type="checkbox" name="selected_archives[]"
                                    value="<?= $rec['id_archive'] ?>"
                                    class="row-check"
                                    style="width:16px;height:16px;cursor:pointer;">
                            </div></td>
                            <td class="date-cell text-muted" data-label="#"><div class="cv"><?= $i + 1 ?></div></td>

                            <td data-label="Type"><div class="cv">
                                <span class="type-badge <?= $meta['color'] ?>">
                                    <i class="fas <?= $meta['icon'] ?>"></i>
                                    <?= $meta['label'] ?>
                                </span>
                            </div></td>

                            <td data-label="Name"><div class="cv">
                                <div style="font-weight:600; font-size:0.875rem; color:var(--text-dark);">
                                    <?= htmlspecialchars($rec['full_name']) ?>
                                </div>
                                <div style="font-size:0.72rem; color:var(--text-light);">ID #<?= $rec['record_id'] ?></div>
                            </div></td>

                           

                            <td class="date-cell" data-label="Deleted"><div class="cv">
                                <?= date('M d, Y', strtotime($rec['deleted_at'])) ?><br>
                                <span style="color:var(--text-light); font-size:0.7rem;"><?= date('h:i A', strtotime($rec['deleted_at'])) ?></span>
                            </div></td>

                            <td class="date-cell" data-label="Deleted By"><div class="cv"><?= htmlspecialchars($rec['deleted_by'] ?? '—') ?></div></td>

                            <td data-label="Actions"><div class="cv">
                                <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">

                                    <!-- View Details modal -->
                                    <button type="button"
                                            class="btn-perma-delete"
                                            style="border-color:rgba(15,45,90,0.2) !important; color:var(--navy-mid) !important;"
                                            data-name="<?= htmlspecialchars($rec['full_name'], ENT_QUOTES) ?>"
                                            data-meta="<?= htmlspecialchars($meta['label'] . ' · ID #' . $rec['record_id'], ENT_QUOTES) ?>"
                                            data-deleted="<?= htmlspecialchars(date('M d, Y h:i A', strtotime($rec['deleted_at'])) . ' by ' . ($rec['deleted_by'] ?? '—'), ENT_QUOTES) ?>"
                                            data-record="<?= htmlspecialchars($rec['record_data'] ?? '{}', ENT_QUOTES) ?>"
                                            onclick="openViewModal(this)">
                                        <i class="fas fa-eye"></i> View
                                    </button>

                                    <!-- Restore button -->
                                    <button type="button" class="btn-restore"
                                        onclick="submitSingle('restore_record', <?= $rec['id_archive'] ?>,
                                        '<?= addslashes(htmlspecialchars($rec['full_name'])) ?>',
                                        '<?= addslashes($meta['label']) ?> · ID #<?= $rec['record_id'] ?>')">
                                        <i class="fas fa-undo"></i> Restore
                                    </button>

                                    <!-- Permanent delete button -->
                                    <button type="button" class="btn-perma-delete"
                                        onclick="submitSingle('permanent_delete', <?= $rec['id_archive'] ?>,
                                        '<?= addslashes(htmlspecialchars($rec['full_name'])) ?>',
                                        '<?= addslashes($meta['label']) ?> · ID #<?= $rec['record_id'] ?>')">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                </div>
                            </div></td>
                        </tr>

                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php render_pagination($paged); ?>
            <?php endif; ?>
        </div><!-- /.archive-table-wrap -->

    </form><!-- /#bulkForm -->

    <!-- ══ MODALS — declared ONCE, outside the loop and outside the table ══ -->

    <!-- View Details Modal -->
    <style>
    /* Default mode (no data-theme) */
    #viewModal { display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center; }
    #viewModal .vm-card { background:#fff; border:1px solid transparent; border-radius:14px; padding:24px 28px; width:100%; max-width:640px; max-height:88vh; margin:0 16px; box-shadow:0 8px 32px rgba(0,0,0,0.18); display:flex; flex-direction:column; }
    #viewModal .vm-head { display:flex; align-items:center; gap:12px; margin-bottom:8px; }
    #viewModal .vm-icon { width:40px; height:40px; border-radius:10px; background:#e0e7ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    #viewModal .vm-icon i { color:#0f2d5a; font-size:18px; }
    #viewModal .vm-title { font-size:15px; font-weight:600; margin:0; color:#0f2d5a; }
    #viewModal .vm-sub { font-size:12px; color:#6b7280; margin:0; }
    #viewModal .vm-deleted { font-size:12px; color:#9ca3af; margin:0 0 4px; }
    #viewModal .vm-hr { margin:12px 0; border:0; border-top:1px solid #e5e7eb; }
    #viewModal .vm-body { overflow-y:auto; flex:1; padding-right:4px; }
    #viewModal .vm-grid { display:grid; grid-template-columns:1fr 1fr; gap:10px 16px; }
    #viewModal .vm-cell { background:#f9fafb; border:1px solid transparent; border-radius:8px; padding:8px 12px; min-width:0; }
    #viewModal .vm-label { font-size:0.68rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#9ca3af; margin-bottom:2px; }
    #viewModal .vm-val { font-size:0.85rem; color:#111827; word-break:break-word; }
    #viewModal .vm-empty { font-size:13px; color:#6b7280; margin:0; }
    #viewModal .vm-foot { display:flex; justify-content:flex-end; margin-top:16px; }
    #viewModal .vm-close { padding:8px 18px; font-size:13px; border-radius:8px; cursor:pointer; border:1px solid #d1d5db; background:#fff; color:#6b7280; }
    #viewModal .vm-close:hover { background:#f3f4f6; }
    @media (max-width: 600px) { #viewModal .vm-grid { grid-template-columns:1fr; } #viewModal .vm-card { padding:20px 16px; } }

    /* Light + Dark theme (driven by sidebar --tm-* variables) */
    html[data-theme] #viewModal { background:rgba(0,0,0,.55) !important; }
    html[data-theme="dark"] #viewModal { background:rgba(0,0,0,.72) !important; }
    html[data-theme] #viewModal .vm-card { background:var(--tm-surface) !important; border:1px solid var(--tm-input-border) !important; box-shadow:var(--tm-pop-shadow) !important; }
    html[data-theme] #viewModal .vm-icon { background:var(--tm-surface2) !important; border:1px solid var(--tm-border) !important; }
    html[data-theme] #viewModal .vm-icon i { color:var(--tm-head) !important; }
    html[data-theme] #viewModal .vm-title { color:var(--tm-head) !important; }
    html[data-theme] #viewModal .vm-sub { color:var(--tm-mid) !important; }
    html[data-theme] #viewModal .vm-deleted { color:var(--tm-muted) !important; }
    html[data-theme] #viewModal .vm-hr { border-top:1px solid var(--tm-border) !important; }
    html[data-theme] #viewModal .vm-cell { background:var(--tm-surface2) !important; border:1px solid var(--tm-border) !important; }
    html[data-theme] #viewModal .vm-label { color:var(--tm-muted) !important; }
    html[data-theme] #viewModal .vm-val { color:var(--tm-text) !important; }
    html[data-theme] #viewModal .vm-empty { color:var(--tm-mid) !important; }
    html[data-theme] #viewModal .vm-close { background:var(--tm-surface2) !important; color:var(--tm-text) !important; border:1px solid var(--tm-input-border) !important; }
    html[data-theme] #viewModal .vm-close:hover { background:var(--tm-hover) !important; }
    </style>
    <div id="viewModal">
      <div class="vm-card">
        <div class="vm-head">
          <div class="vm-icon"><i class="fas fa-eye"></i></div>
          <div style="min-width:0;">
            <p id="viewRecordName" class="vm-title"></p>
            <p id="viewRecordMeta" class="vm-sub"></p>
          </div>
        </div>
        <p id="viewRecordDeleted" class="vm-deleted"></p>
        <hr class="vm-hr">
        <div id="viewRecordBody" class="vm-body"></div>
        <div class="vm-foot">
          <button type="button" class="vm-close" onclick="closeModal('viewModal')">Close</button>
        </div>
      </div>
    </div>

    <!-- Restore Modal -->
    <div id="restoreModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
      <div style="background:#fff; border-radius:14px; padding:28px 32px; width:100%; max-width:420px; margin:0 16px; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
          <div style="width:40px; height:40px; border-radius:10px; background:#d1fae5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <i class="fas fa-undo" style="color:#059669; font-size:18px;"></i>
          </div>
          <div>
            <p style="font-size:15px; font-weight:600; margin:0; color:#0f2d5a;">Restore record</p>
            <p style="font-size:13px; color:#6b7280; margin:0;">This will move the record back to its source table.</p>
          </div>
        </div>
        <hr style="margin:16px 0; border-color:#e5e7eb;">
        <div style="background:#f9fafb; border-radius:8px; padding:10px 14px; margin-bottom:18px; display:flex; align-items:center; gap:10px;">
          <i class="fas fa-user" style="color:#9ca3af; font-size:15px;"></i>
          <div>
            <p id="restoreRecordName" style="font-size:13px; font-weight:600; margin:0; color:#111827;"></p>
            <p id="restoreRecordMeta" style="font-size:12px; color:#6b7280; margin:0;"></p>
          </div>
        </div>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
          <button onclick="closeModal('restoreModal')" style="padding:8px 18px; font-size:13px; border-radius:8px; cursor:pointer; border:1px solid #d1d5db; background:#fff; color:#6b7280;">Cancel</button>
          <button id="restoreConfirmBtn" style="padding:8px 18px; font-size:13px; font-weight:600; border-radius:8px; cursor:pointer; border:none; background:#d1fae5; color:#065f46;">
            <i class="fas fa-undo" style="margin-right:5px;"></i>Yes, restore
          </button>
        </div>
      </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
      <div style="background:#fff; border-radius:14px; padding:28px 32px; width:100%; max-width:420px; margin:0 16px; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
          <div style="width:40px; height:40px; border-radius:10px; background:#fee2e2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <i class="fas fa-trash" style="color:#dc2626; font-size:18px;"></i>
          </div>
          <div>
            <p style="font-size:15px; font-weight:600; margin:0; color:#0f2d5a;">Permanently delete</p>
            <p style="font-size:13px; color:#6b7280; margin:0;">This action cannot be undone.</p>
          </div>
        </div>
        <hr style="margin:16px 0; border-color:#e5e7eb;">
        <div style="background:#f9fafb; border-radius:8px; padding:10px 14px; margin-bottom:18px; display:flex; align-items:center; gap:10px;">
          <i class="fas fa-user" style="color:#9ca3af; font-size:15px;"></i>
          <div>
            <p id="deleteRecordName" style="font-size:13px; font-weight:600; margin:0; color:#111827;"></p>
            <p id="deleteRecordMeta" style="font-size:12px; color:#6b7280; margin:0;"></p>
          </div>
        </div>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
          <button onclick="closeModal('deleteModal')" style="padding:8px 18px; font-size:13px; border-radius:8px; cursor:pointer; border:1px solid #d1d5db; background:#fff; color:#6b7280;">Cancel</button>
          <button id="deleteConfirmBtn" style="padding:8px 18px; font-size:13px; font-weight:600; border-radius:8px; cursor:pointer; border:none; background:#fee2e2; color:#991b1b;">
            <i class="fas fa-trash" style="margin-right:5px;"></i>Yes, delete
          </button>
        </div>
      </div>
    </div>

    <!-- Bulk Restore Modal -->
    <div id="bulkRestoreModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
      <div style="background:#fff; border-radius:14px; padding:28px 32px; width:100%; max-width:440px; margin:0 16px; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
          <div style="width:40px; height:40px; border-radius:10px; background:#d1fae5; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <i class="fas fa-undo" style="color:#059669; font-size:18px;"></i>
          </div>
          <div>
            <p style="font-size:15px; font-weight:600; margin:0; color:#0f2d5a;">Restore selected records</p>
            <p style="font-size:13px; color:#6b7280; margin:0;">All selected records will be moved back to their source tables.</p>
          </div>
        </div>
        <hr style="margin:16px 0; border-color:#e5e7eb;">
        <div style="background:#f0fdf4; border:1.5px solid #bbf7d0; border-radius:8px; padding:12px 16px; margin-bottom:18px; display:flex; align-items:center; gap:12px;">
          <i class="fas fa-layer-group" style="color:#059669; font-size:18px; flex-shrink:0;"></i>
          <div>
            <p id="bulkRestoreCount" style="font-size:14px; font-weight:700; margin:0; color:#065f46;"></p>
            <p style="font-size:12px; color:#047857; margin:2px 0 0;">Records will be restored to their original tables.</p>
          </div>
        </div>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
          <button onclick="closeModal('bulkRestoreModal')" style="padding:8px 18px; font-size:13px; border-radius:8px; cursor:pointer; border:1px solid #d1d5db; background:#fff; color:#6b7280;">Cancel</button>
          <button id="bulkRestoreConfirmBtn" style="padding:8px 20px; font-size:13px; font-weight:600; border-radius:8px; cursor:pointer; border:none; background:linear-gradient(135deg,#059669,#34d399); color:#fff;">
            <i class="fas fa-undo" style="margin-right:5px;"></i>Yes, restore all
          </button>
        </div>
      </div>
    </div>

    <!-- Bulk Delete Modal -->
    <div id="bulkDeleteModal" style="display:none; position:fixed; inset:0; z-index:9999; background:rgba(0,0,0,0.45); align-items:center; justify-content:center;">
      <div style="background:#fff; border-radius:14px; padding:28px 32px; width:100%; max-width:440px; margin:0 16px; box-shadow:0 8px 32px rgba(0,0,0,0.18);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
          <div style="width:40px; height:40px; border-radius:10px; background:#fee2e2; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
            <i class="fas fa-trash" style="color:#dc2626; font-size:18px;"></i>
          </div>
          <div>
            <p style="font-size:15px; font-weight:600; margin:0; color:#0f2d5a;">Permanently delete selected</p>
            <p style="font-size:13px; color:#6b7280; margin:0;">This action cannot be undone.</p>
          </div>
        </div>
        <hr style="margin:16px 0; border-color:#e5e7eb;">
        <div style="background:#fef2f2; border:1.5px solid #fecaca; border-radius:8px; padding:12px 16px; margin-bottom:18px; display:flex; align-items:center; gap:12px;">
          <i class="fas fa-exclamation-triangle" style="color:#dc2626; font-size:18px; flex-shrink:0;"></i>
          <div>
            <p id="bulkDeleteCount" style="font-size:14px; font-weight:700; margin:0; color:#991b1b;"></p>
            <p style="font-size:12px; color:#b91c1c; margin:2px 0 0;">These records will be gone forever — there is no way to recover them.</p>
          </div>
        </div>
        <div style="display:flex; gap:8px; justify-content:flex-end;">
          <button onclick="closeModal('bulkDeleteModal')" style="padding:8px 18px; font-size:13px; border-radius:8px; cursor:pointer; border:1px solid #d1d5db; background:#fff; color:#6b7280;">Cancel</button>
          <button id="bulkDeleteConfirmBtn" style="padding:8px 20px; font-size:13px; font-weight:600; border-radius:8px; cursor:pointer; border:none; background:linear-gradient(135deg,#dc2626,#ef4444); color:#fff;">
            <i class="fas fa-trash" style="margin-right:5px;"></i>Yes, delete all
          </button>
        </div>
      </div>
    </div>

    <!-- ══ SUCCESS TOAST MODAL ══ -->
    <div id="successToast" style="display:none; position:fixed; bottom:28px; right:28px; z-index:10000; min-width:300px; max-width:400px;">
      <div id="successToastInner" style="border-radius:14px; padding:16px 20px; box-shadow:0 8px 32px rgba(0,0,0,0.18); display:flex; align-items:flex-start; gap:14px; animation: toastSlideIn 0.3s ease;">
        <div id="successToastIcon" style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px;">
          <i id="successToastIconI" style="font-size:16px;"></i>
        </div>
        <div style="flex:1; min-width:0;">
          <p id="successToastTitle" style="font-size:13px; font-weight:700; margin:0 0 2px; color:#111827;"></p>
          <p id="successToastMsg"   style="font-size:12px; margin:0; color:#6b7280; line-height:1.4;"></p>
        </div>
        <button onclick="closeToast()" style="background:none; border:none; cursor:pointer; color:#9ca3af; font-size:16px; padding:0; flex-shrink:0; line-height:1;">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>

    <style>
    @keyframes toastSlideIn {
        from { opacity:0; transform: translateY(16px); }
        to   { opacity:1; transform: translateY(0); }
    }
    </style>

    <br><br>
</div>
<!-- End of Main Content -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>

<script>
// ── Success toast ────────────────────────────────────────────
function closeToast() {
    var t = document.getElementById('successToast');
    if (t) { t.style.opacity = '0'; t.style.transition = 'opacity 0.3s'; setTimeout(function(){ t.style.display = 'none'; }, 300); }
}

document.addEventListener('DOMContentLoaded', function () {
    var raw = sessionStorage.getItem('archiveToast');
    if (!raw) return;
    sessionStorage.removeItem('archiveToast');

    try {
        var data  = JSON.parse(raw);
        var isRed = data.type === 'delete';

        var bg        = isRed ? '#fef2f2'  : '#f0fdf4';
        var border    = isRed ? '#fecaca'  : '#bbf7d0';
        var iconBg    = isRed ? '#fee2e2'  : '#d1fae5';
        var iconColor = isRed ? '#dc2626'  : '#059669';
        var iconCls   = isRed ? 'fa-trash' : 'fa-check';
        var title     = isRed ? 'Deleted'  : 'Restored';

        var inner = document.getElementById('successToastInner');
        inner.style.background  = bg;
        inner.style.border      = '1.5px solid ' + border;

        var iconWrap = document.getElementById('successToastIcon');
        iconWrap.style.background = iconBg;

        var iconEl = document.getElementById('successToastIconI');
        iconEl.className    = 'fas ' + iconCls;
        iconEl.style.color  = iconColor;

        document.getElementById('successToastTitle').textContent = title;
        document.getElementById('successToastTitle').style.color = iconColor;
        document.getElementById('successToastMsg').textContent   = data.msg;

        document.getElementById('successToast').style.display = 'block';

        // auto-dismiss after 4 seconds
        setTimeout(closeToast, 4000);
    } catch(e) {}
});

// ── View record details modal ────────────────────────────────
function openViewModal(btn) {
    var data = {};
    try { data = JSON.parse(btn.getAttribute('data-record') || '{}') || {}; } catch (e) {}

    document.getElementById('viewRecordName').textContent    = btn.getAttribute('data-name');
    document.getElementById('viewRecordMeta').textContent    = btn.getAttribute('data-meta');
    document.getElementById('viewRecordDeleted').textContent = 'Deleted: ' + btn.getAttribute('data-deleted');

    var body = document.getElementById('viewRecordBody');
    body.innerHTML = '';
    var keys = Object.keys(data);

    if (!keys.length) {
        var empty = document.createElement('p');
        empty.className = 'vm-empty';
        empty.textContent = 'No record data available.';
        body.appendChild(empty);
    } else {
        var grid = document.createElement('div');
        grid.className = 'vm-grid';
        keys.forEach(function (k) {
            var v = data[k];
            if (v === null || v === '') v = '—';
            else if (typeof v === 'object') v = JSON.stringify(v);

            var cell  = document.createElement('div');
            cell.className = 'vm-cell';
            var label = document.createElement('div');
            label.className = 'vm-label';
            label.textContent = k.replace(/_/g, ' ');
            var val = document.createElement('div');
            val.className = 'vm-val';
            val.textContent = v;

            cell.appendChild(label);
            cell.appendChild(val);
            grid.appendChild(cell);
        });
        body.appendChild(grid);
    }
    document.getElementById('viewModal').style.display = 'flex';
}

// ── Close modal ──────────────────────────────────────────────
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

// ── Single-action modal trigger ──────────────────────────────
function submitSingle(actionName, archiveId, fullName, typeMeta) {
    if (actionName === 'restore_record') {
        document.getElementById('restoreRecordName').textContent = fullName;
        document.getElementById('restoreRecordMeta').textContent = typeMeta;
        document.getElementById('restoreModal').style.display    = 'flex';
        document.getElementById('restoreConfirmBtn').onclick = function () {
            document.getElementById('singleActionId').value  = archiveId;
            document.getElementById('singleActionName').name = 'restore_record';
            document.getElementById('singleActionForm').submit();
        };
    } else {
        document.getElementById('deleteRecordName').textContent = fullName;
        document.getElementById('deleteRecordMeta').textContent = typeMeta;
        document.getElementById('deleteModal').style.display    = 'flex';
        document.getElementById('deleteConfirmBtn').onclick = function () {
            document.getElementById('singleActionId').value  = archiveId;
            document.getElementById('singleActionName').name = 'permanent_delete';
            document.getElementById('singleActionForm').submit();
        };
    }
}

// ── Bulk modal trigger ───────────────────────────────────────
function openBulkModal(type) {
    var count = document.querySelectorAll('.row-check:checked').length;
    if (count === 0) return;
    var noun = count === 1 ? 'record' : 'records';

    if (type === 'restore') {
        document.getElementById('bulkRestoreCount').textContent = count + ' ' + noun + ' selected for restore';
        document.getElementById('bulkRestoreModal').style.display = 'flex';
        document.getElementById('bulkRestoreConfirmBtn').onclick = function () {
            // inject a hidden submit button into the bulk form and click it
            var btn = document.createElement('button');
            btn.type = 'submit';
            btn.name = 'bulk_restore';
            btn.style.display = 'none';
            document.getElementById('bulkForm').appendChild(btn);
            btn.click();
        };
    } else {
        document.getElementById('bulkDeleteCount').textContent = count + ' ' + noun + ' will be permanently deleted';
        document.getElementById('bulkDeleteModal').style.display = 'flex';
        document.getElementById('bulkDeleteConfirmBtn').onclick = function () {
            var btn = document.createElement('button');
            btn.type = 'submit';
            btn.name = 'bulk_permanent_delete';
            btn.style.display = 'none';
            document.getElementById('bulkForm').appendChild(btn);
            btn.click();
        };
    }
}

// ── Close modals by clicking backdrop ───────────────────────
window.addEventListener('click', function (e) {
    if (e.target.id === 'viewModal')        closeModal('viewModal');
    if (e.target.id === 'restoreModal')     closeModal('restoreModal');
    if (e.target.id === 'deleteModal')      closeModal('deleteModal');
    if (e.target.id === 'bulkRestoreModal') closeModal('bulkRestoreModal');
    if (e.target.id === 'bulkDeleteModal')  closeModal('bulkDeleteModal');
});

// ── Bulk checkbox logic ──────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    var checkAll = document.getElementById('checkAll');
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            document.querySelectorAll('.row-check').forEach(cb => cb.checked = checkAll.checked);
            updateToolbar();
        });
    }
    document.querySelectorAll('.row-check').forEach(cb => cb.addEventListener('change', updateToolbar));
});

function updateToolbar() {
    var checked = document.querySelectorAll('.row-check:checked');
    var toolbar  = document.getElementById('bulkToolbar');
    var counter  = document.getElementById('bulkCount');
    if (toolbar) toolbar.style.display = checked.length > 0 ? 'flex' : 'none';
    if (counter) counter.textContent   = checked.length + ' selected';
}

function clearAllChecks() {
    document.querySelectorAll('.row-check').forEach(cb => cb.checked = false);
    var ca = document.getElementById('checkAll');
    if (ca) ca.checked = false;
    updateToolbar();
}
</script>

<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>