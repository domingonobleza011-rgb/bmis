<?php
if (!function_exists('hh_load_members')) {
    /** One query for every household on the page. Returns [map, error]. */
    function hh_load_members($conn, array $rows): array {
        $ids = array_values(array_unique(array_map(fn($r) => (int)$r['id_resident'], $rows)));
        $map = [];
        if (!$ids) return [$map, false];
        try {
            $in   = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $conn->prepare("SELECT id_household, lname, fname, mi, relationship, age, sex, occupation
                                    FROM tbl_family_members WHERE id_household IN ($in)
                                    ORDER BY date_added DESC");
            $stmt->execute($ids);
            foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $m) {
                $map[(int)$m['id_household']][] = [
                    'name'         => trim(($m['lname'] ?? '') . ', ' . ($m['fname'] ?? '') . ' ' . ($m['mi'] ?? '')),
                    'relationship' => $m['relationship'] ?? '',
                    'age'          => $m['age'] ?? '',
                    'sex'          => $m['sex'] ?? '',
                    'occupation'   => $m['occupation'] ?? '',
                ];
            }
            return [$map, false];
        } catch (Throwable $e) {
            error_log('household members: ' . $e->getMessage());
            return [[], true];
        }
    }
}
if (!defined('BMIS_ROLE_REQUIRED')) { define('BMIS_ROLE_REQUIRED', 'admin_dashboard'); }
require_once ROOT_PATH . '/secure_header.php';

require ROOT_PATH . '/classes/conn.php';
require_once ROOT_PATH . '/pagination_helper.php';

if (isset($_POST['search_totalhouse']) || isset($_POST['search_household'])) {
    $keyword = $_POST['keyword'];
    $kw      = "%$keyword%";
    $page    = max(1, (int)($_GET['page'] ?? 1));
    $perPage = 10;

    $countStmt = $conn->prepare("SELECT COUNT(*) FROM `tbl_resident` WHERE (family_role = 'Yes' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw)");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();

    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM `tbl_resident` WHERE (family_role = 'Yes' AND (is_archived = 0 OR is_archived IS NULL)) AND (`lname` LIKE :kw OR `mi` LIKE :kw OR `fname` LIKE :kw OR `age` LIKE :kw OR `sex` LIKE :kw OR `status` LIKE :kw OR `houseno` LIKE :kw OR `street` LIKE :kw OR `brgy` LIKE :kw OR `municipal` LIKE :kw OR `contact` LIKE :kw) LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();
    [$fm_map, $fm_err] = hh_load_members($conn, $rows);

    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
?>
<?php require_once ROOT_PATH . '/app/Views/resident_cards_style.php'; ?>
<div class="res-grid">
<?php foreach ($rows as $row):
    $rid  = (int)$row['id_resident'];
    $init = htmlspecialchars(strtoupper(mb_substr($row['fname'], 0, 1)));
    $mem  = $fm_map[$rid] ?? [];
    $cnt  = count($mem);
?>
    <div class="res-card hh-card-item" tabindex="0" role="button"
         data-name="<?= htmlspecialchars($row['lname'] . ', ' . $row['fname'], ENT_QUOTES) ?>"
         data-error="<?= $fm_err ? '1' : '0' ?>"
         data-members="<?= htmlspecialchars(json_encode($mem, JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>">
        <div class="res-card-top">
            <div class="res-avatar"><i class="fas fa-house-user" style="font-size:1rem;"></i></div>
            <div class="res-id">
                <div class="res-name"><?= htmlspecialchars(res_full_name($row)) ?></div>
                <div class="res-sub">Household Head</div>
            </div>
            <div class="res-actions">
                <div class="dropdown">
                    <button type="button" class="btn btn-light btn-sm border" data-bs-toggle="dropdown" aria-expanded="false" title="Actions"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item hh-open" href="#"><i class="fa fa-eye mr-2 text-primary"></i> View members</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="res-meta">
            <div><i class="fas fa-location-dot"></i><span class="res-text"><?= htmlspecialchars(format_resident_address($row) ?: '—') ?></span></div>
            <div><i class="fas fa-tag"></i><span>
                <span class="res-chip"><?= $cnt ?> member<?= $cnt === 1 ? '' : 's' ?></span>
                <?php if (trim((string)($row['sex'] ?? '')) !== ''): ?><span class="res-chip"><?= htmlspecialchars($row['sex']) ?></span><?php endif; ?>
                <?php if (trim((string)($row['status'] ?? '')) !== ''): ?><span class="res-chip"><?= htmlspecialchars($row['status']) ?></span><?php endif; ?>
            </span></div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($rows)): ?><div class="res-empty" style="grid-column:1/-1;">No households found.</div><?php endif; ?>
</div>
<?php render_pagination($paged); ?>
<script>
(function () {
    document.querySelectorAll('.hh-card-item').forEach(function (card) {
        function open() { if (window.openFamilyModal) window.openFamilyModal(card); }
        card.addEventListener('click', function (e) { if (e.target.closest('.res-actions') && !e.target.closest('.hh-open')) return; e.preventDefault(); open(); });
        card.addEventListener('keydown', function (e) { if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.res-actions')) { e.preventDefault(); open(); } });
    });
})();
</script>

<?php } else {
    $rows  = $view['rows']  ?? [];
    [$fm_map, $fm_err] = hh_load_members($conn, $rows);
    $paged = $view;
?>
<?php require_once ROOT_PATH . '/app/Views/resident_cards_style.php'; ?>
<div class="res-grid">
<?php foreach ($rows as $row):
    $rid  = (int)$row['id_resident'];
    $init = htmlspecialchars(strtoupper(mb_substr($row['fname'], 0, 1)));
    $mem  = $fm_map[$rid] ?? [];
    $cnt  = count($mem);
?>
    <div class="res-card hh-card-item" tabindex="0" role="button"
         data-name="<?= htmlspecialchars($row['lname'] . ', ' . $row['fname'], ENT_QUOTES) ?>"
         data-error="<?= $fm_err ? '1' : '0' ?>"
         data-members="<?= htmlspecialchars(json_encode($mem, JSON_UNESCAPED_UNICODE), ENT_QUOTES) ?>">
        <div class="res-card-top">
            <div class="res-avatar"><i class="fas fa-house-user" style="font-size:1rem;"></i></div>
            <div class="res-id">
                <div class="res-name"><?= htmlspecialchars(res_full_name($row)) ?></div>
                <div class="res-sub">Household Head</div>
            </div>
            <div class="res-actions">
                <div class="dropdown">
                    <button type="button" class="btn btn-light btn-sm border" data-bs-toggle="dropdown" aria-expanded="false" title="Actions"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item hh-open" href="#"><i class="fa fa-eye mr-2 text-primary"></i> View members</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="res-meta">
            <div><i class="fas fa-location-dot"></i><span class="res-text"><?= htmlspecialchars(format_resident_address($row) ?: '—') ?></span></div>
            <div><i class="fas fa-tag"></i><span>
                <span class="res-chip"><?= $cnt ?> member<?= $cnt === 1 ? '' : 's' ?></span>
                <?php if (trim((string)($row['sex'] ?? '')) !== ''): ?><span class="res-chip"><?= htmlspecialchars($row['sex']) ?></span><?php endif; ?>
                <?php if (trim((string)($row['status'] ?? '')) !== ''): ?><span class="res-chip"><?= htmlspecialchars($row['status']) ?></span><?php endif; ?>
            </span></div>
        </div>
    </div>
<?php endforeach; ?>
<?php if (empty($rows)): ?><div class="res-empty" style="grid-column:1/-1;">No households found.</div><?php endif; ?>
</div>
<?php render_pagination($paged); ?>
<script>
(function () {
    document.querySelectorAll('.hh-card-item').forEach(function (card) {
        function open() { if (window.openFamilyModal) window.openFamilyModal(card); }
        card.addEventListener('click', function (e) { if (e.target.closest('.res-actions') && !e.target.closest('.hh-open')) return; e.preventDefault(); open(); });
        card.addEventListener('keydown', function (e) { if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.res-actions')) { e.preventDefault(); open(); } });
    });
})();
</script>

<?php } $conn = null; ?>

<?php /* ── Household Members "View" modal ──
   Fully self-contained (no Bootstrap modal JS needed) so it works on the
   household page, the report page and AJAX reloads. Rendered only once. */
if (empty($GLOBALS['__hh_modal_rendered'])): $GLOBALS['__hh_modal_rendered'] = true; ?>
<style>
#hhModal { display:none; position:fixed; inset:0; z-index:100000; background:rgba(0,0,0,0.45); align-items:center; justify-content:center; }
#hhModal.open { display:flex; }
#hhModal .hh-card { background:#fff; border:1px solid transparent; border-radius:14px; padding:22px 26px; width:100%; max-width:760px; max-height:88vh; margin:0 16px; box-shadow:0 8px 32px rgba(0,0,0,0.18); display:flex; flex-direction:column; text-align:left; }
#hhModal .hh-head { display:flex; align-items:center; gap:12px; margin-bottom:6px; }
#hhModal .hh-icon { width:40px; height:40px; border-radius:10px; background:#e0e7ff; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
#hhModal .hh-icon i { color:#0f2d5a; font-size:18px; }
#hhModal .hh-title { font-size:15px; font-weight:600; margin:0; color:#0f2d5a; }
#hhModal .hh-sub { font-size:12px; color:#6b7280; margin:0; }
#hhModal .hh-hr { margin:12px 0; border:0; border-top:1px solid #e5e7eb; }
#hhModal .hh-body { overflow:auto; flex:1; min-height:100px; }
#hhModal .hh-foot { display:flex; justify-content:flex-end; margin-top:14px; }
#hhModal .hh-close { padding:8px 18px; font-size:13px; border-radius:8px; cursor:pointer; border:1px solid #d1d5db; background:#fff; color:#6b7280; }
#hhModal .hh-close:hover { background:#f3f4f6; }
html[data-theme] #hhModal { background:rgba(0,0,0,.55) !important; }
html[data-theme="dark"] #hhModal { background:rgba(0,0,0,.72) !important; }
html[data-theme] #hhModal .hh-card { background:var(--tm-surface) !important; border:1px solid var(--tm-input-border) !important; box-shadow:var(--tm-pop-shadow) !important; }
html[data-theme] #hhModal .hh-icon { background:var(--tm-surface2) !important; border:1px solid var(--tm-border) !important; }
html[data-theme] #hhModal .hh-icon i, html[data-theme] #hhModal .hh-title { color:var(--tm-head) !important; }
html[data-theme] #hhModal .hh-sub { color:var(--tm-mid) !important; }
html[data-theme] #hhModal .hh-hr { border-top:1px solid var(--tm-border) !important; }
html[data-theme] #hhModal .hh-close { background:var(--tm-surface2) !important; color:var(--tm-text) !important; border:1px solid var(--tm-input-border) !important; }
html[data-theme] #hhModal table th { background:var(--tm-thead) !important; color:var(--tm-thead-text) !important; }
</style>
<div id="hhModal" aria-hidden="true">
    <div class="hh-card" role="dialog" aria-modal="true">
        <div class="hh-head">
            <div class="hh-icon"><i class="fas fa-users"></i></div>
            <div style="min-width:0;">
                <p class="hh-title">Household Members</p>
                <p class="hh-sub" id="hhModalName"></p>
            </div>
        </div>
        <hr class="hh-hr">
        <div class="hh-body" id="hhModalBody"></div>
        <div class="hh-foot"><button type="button" class="hh-close" onclick="closeFamilyModal()">Close</button></div>
    </div>
</div>
<script>
(function () {
    var modal = document.getElementById('hhModal');
    // Move to <body> so no parent wrapper/overflow can trap it.
    document.body.appendChild(modal);

    function box(cls, html) { return '<div class="' + cls + '" style="padding:24px 8px;text-align:center;">' + html + '</div>'; }

    window.openFamilyModal = function (btn) {
        document.getElementById('hhModalName').textContent = btn.getAttribute('data-name') || '';
        var body = document.getElementById('hhModalBody');
        body.innerHTML = '';

        var members = [];
        try { members = JSON.parse(btn.getAttribute('data-members') || '[]') || []; } catch (e) {}

        if (btn.getAttribute('data-error') === '1') {
            body.innerHTML = box('text-danger', 'Could not read the household members from the database. Check that the tbl_family_members table exists.');
        } else if (!members.length) {
            body.innerHTML = box('text-muted', '<i class="fas fa-users" style="font-size:2rem;"></i><p class="mb-0 mt-2">This household has no family members listed yet.</p>');
        } else {
            var table = document.createElement('table');
            table.className = 'table table-sm table-bordered text-center align-middle mb-0';
            var thead = table.createTHead().insertRow();
            thead.className = 'alert-info';
            ['Name', 'Relationship', 'Age', 'Sex', 'Occupation'].forEach(function (h) {
                var th = document.createElement('th'); th.textContent = h; thead.appendChild(th);
            });
            var tb = table.createTBody();
            members.forEach(function (m) {
                var tr = tb.insertRow();
                [m.name, m.relationship, m.age, m.sex, m.occupation].forEach(function (v) {
                    tr.insertCell().textContent = (v === null || v === undefined || v === '') ? '\u2014' : v;
                });
            });
            body.appendChild(table);
        }
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
    };
    window.closeFamilyModal = function () {
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
    };
    modal.addEventListener('click', function (e) { if (e.target === modal) window.closeFamilyModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') window.closeFamilyModal(); });
})();
</script>
<?php endif; ?>
