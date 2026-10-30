<?php
require ROOT_PATH . '/classes/conn.php';
require_once ROOT_PATH . '/pagination_helper.php';

$page    = max(1, (int)($_GET['page'] ?? 1));
$perPage = 10;

function format_resident_address(array $row): string {
    $parts = [
        $row['houseno']   ?? '',
        $row['street']    ?? '',
        $row['brgy']      ?? '',
        $row['municipal'] ?? '',
        $row['province']  ?? '',
    ];
    $parts = array_filter(array_map('trim', $parts), fn($p) => $p !== '');

    if (!empty($parts)) {
        return implode(', ', $parts);
    }

    // Structured fields empty -> pre-PSGC-fix account, use legacy column
    return trim($row['address'] ?? '');
}

if (isset($_POST['search_resident'])) {
    $keyword = $_POST['keyword'];
    $kw = "%$keyword%";
    $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_resident WHERE
        lname LIKE :kw OR mi LIKE :kw OR fname LIKE :kw OR age LIKE :kw OR sex LIKE :kw OR
        status LIKE :kw OR address LIKE :kw OR contact LIKE :kw OR bdate LIKE :kw OR
        bplace LIKE :kw OR nationality LIKE :kw OR family_role LIKE :kw OR role LIKE :kw OR email LIKE :kw");
    $countStmt->execute([':kw' => $kw]);
    $total = (int)$countStmt->fetchColumn();
    $offset = ($page - 1) * $perPage;
    $stmnt = $conn->prepare("SELECT * FROM tbl_resident WHERE
        lname LIKE :kw OR mi LIKE :kw OR fname LIKE :kw OR age LIKE :kw OR sex LIKE :kw OR
        status LIKE :kw OR address LIKE :kw OR contact LIKE :kw OR bdate LIKE :kw OR
        bplace LIKE :kw OR nationality LIKE :kw OR family_role LIKE :kw OR role LIKE :kw OR email LIKE :kw
        LIMIT $perPage OFFSET $offset");
    $stmnt->execute([':kw' => $kw]);
    $rows = $stmnt->fetchAll();
    $paged = ['rows' => $rows, 'total' => $total, 'page' => $page, 'per_page' => $perPage, 'last_page' => (int)ceil($total / $perPage)];
} else {
    // $view is already the $paged array from view_resident_paginated()
    $rows  = $view['rows'] ?? [];
    $paged = $view;
}
?>

<?php
if (!function_exists('res_val')) {
    function res_val($v): string {
        $v = trim((string)$v);
        return $v === '' ? '—' : htmlspecialchars($v);
    }
    /** Age in whole years computed from the birth date (null if missing/invalid/future). */
    function res_age($bdate): ?int {
        $bdate = trim((string)$bdate);
        if ($bdate === '' || $bdate === '0000-00-00') return null;
        try {
            $tz   = new DateTimeZone('Asia/Manila');
            $born = new DateTime($bdate, $tz);
            $now  = new DateTime('now', $tz);
            if ($born > $now) return null;
            return (int)$born->diff($now)->y;
        } catch (Throwable $e) {
            return null;
        }
    }
    function res_full_name(array $r): string {
        $mi = trim((string)($r['mi'] ?? ''));
        return trim($r['lname'] . ', ' . $r['fname'] . ($mi !== '' ? ' ' . $mi : ''));
    }
}
?>
<style>
.res-grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
.res-card {
    position: relative; background: #fff; border: 1px solid #e8ecf0; border-radius: 14px;
    box-shadow: 0 2px 8px rgba(15,45,90,.07); padding: 16px; cursor: pointer;
    transition: box-shadow .2s ease, border-color .2s ease;
}
.res-card:hover { box-shadow: 0 6px 24px rgba(15,45,90,.12); border-color: #c9d6ea; }
.res-card-top { display: flex; align-items: center; gap: 12px; }
.res-avatar {
    width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0; color: #fff; font-weight: 700; font-size: 1.1rem;
    background: linear-gradient(135deg, #0f2d5a, #2b5ea7); display: flex; align-items: center; justify-content: center;
}
.res-avatar { overflow: hidden; }
.res-avatar.lg { width: 56px; height: 56px; font-size: 1.3rem; }
.res-id { min-width: 0; flex: 1; }
.res-name { font-weight: 700; color: #1a1a2e; font-size: .95rem; line-height: 1.25; word-break: break-word; }
.res-sub  { font-size: .75rem; color: #8894a8; margin-top: 2px; }
.res-actions { flex-shrink: 0; align-self: flex-start; }
.res-actions form { margin: 0 !important; }
.res-actions .dropdown > .btn {
    width: 32px !important; height: 32px !important; padding: 0 !important; border-radius: 50% !important;
    display: inline-flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #b8c4d6 !important;
}
.res-actions .dropdown > .btn i { font-size: 1rem; color: #25365a; }
.res-meta { margin-top: 12px; padding-top: 10px; border-top: 1px solid #e8ecf0; display: grid; gap: 6px; font-size: .8rem; color: #4a5568; }
.res-meta div { display: flex; gap: 8px; align-items: flex-start; }
.res-meta i { width: 14px; text-align: center; color: #8894a8; margin-top: 3px; flex-shrink: 0; }
.res-meta .res-text { min-width: 0; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.res-chip { display: inline-block; font-size: .68rem; font-weight: 700; padding: 2px 9px; border-radius: 999px; background: #e8eef7; color: #0f2d5a; margin-right: 4px; }
.res-chip.head { background: #fdf3e3; color: #b9832c; }
.res-empty { text-align: center; color: #718096; padding: 40px 0; font-style: italic; }

/* info modal */
.res-info-head { background: linear-gradient(135deg, #0f2d5a, #1a4480); color: #fff; padding: 18px 20px; display: flex; align-items: center; gap: 14px; }
.res-info-head .res-avatar { background: rgba(255,255,255,.18); }
.res-info-head h5 { margin: 0; color: #fff !important; font-weight: 700; }
.res-info-head small { opacity: .8; }
.res-info-section { font-size: .72rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #8894a8; margin: 4px 0 10px; }
.res-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 18px; margin-bottom: 18px; }
.res-info-grid .full { grid-column: 1 / -1; }
.res-info-grid .lbl { font-size: .72rem; color: #8894a8; margin-bottom: 1px; }
.res-info-grid .val { font-size: .9rem; color: #1a1a2e; font-weight: 600; word-break: break-word; }
@media (max-width: 480px) { .res-info-grid { grid-template-columns: 1fr; } }

/* Light / Dark theme (the admin theme blanks backgrounds & colours) */
html[data-theme] .res-card { background: var(--tm-surface) !important; border: 1px solid var(--tm-border) !important; box-shadow: var(--tm-shadow) !important; }
html[data-theme] .res-card:hover { background: var(--tm-hover) !important; }
html[data-theme] .res-name { color: var(--tm-head) !important; }
html[data-theme] .res-sub, html[data-theme] .res-meta i, html[data-theme] .res-info-grid .lbl, html[data-theme] .res-info-section { color: var(--tm-muted) !important; }
html[data-theme] .res-meta { border-color: var(--tm-border) !important; }
html[data-theme] .res-avatar { background: var(--tm-icon-active) !important; color: #fff !important; }
html[data-theme="dark"] .res-avatar { background: #4f46e5 !important; }
html[data-theme] .res-info-head { background: var(--tm-icon-active) !important; }
html[data-theme="dark"] .res-info-head { background: #312e81 !important; }
html[data-theme] .res-info-head h5, html[data-theme] .res-info-head small { color: #fff !important; }
html[data-theme] .res-info-head .res-avatar { background: rgba(255,255,255,.2) !important; }
html[data-theme] .res-info-grid .val { color: var(--tm-head) !important; }
html[data-theme] .res-chip { background: var(--tm-surface2) !important; color: var(--tm-head) !important; }
html[data-theme] .res-actions .dropdown > .btn { background: var(--tm-surface2) !important; border-color: var(--tm-border) !important; }
html[data-theme] .res-actions .dropdown > .btn i { color: var(--tm-head) !important; }
</style>

<div class="res-grid">
<?php if (!empty($rows)): ?>
    <?php foreach ($rows as $row):
        $rid  = (int)$row['id_resident'];
        $init = htmlspecialchars(strtoupper(mb_substr($row['fname'], 0, 1)));
        $isHead = stripos((string)($row['family_role'] ?? ''), 'yes') === 0 || stripos((string)($row['family_role'] ?? ''), 'head') !== false;
    ?>
    <div class="res-card" data-modal="viewModal<?= $rid ?>" tabindex="0" role="button" aria-label="View <?= htmlspecialchars($row['fname']) ?>'s information">
        <div class="res-card-top">
            <div class="res-avatar"><?= bmis_avatar_inner($row['profile_photo'] ?? null, $init) ?></div>
            <div class="res-id">
                <div class="res-name"><?= htmlspecialchars(res_full_name($row)) ?></div>
                <div class="res-sub">Resident ID: <?= $rid ?></div>
            </div>
            <div class="res-actions">
                <form action="" method="post" class="show-loader" data-loader-msg="Archiving resident...">
                    <input type="hidden" name="id_resident" value="<?= $rid ?>">
                    <input type="hidden" name="delete_resident" value="1">
                    <div class="dropdown">
                        <button type="button" class="btn btn-light btn-sm border" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $rid ?>">
                                    <i class="fa fa-eye mr-2 text-primary"></i> View
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#messageModal<?= $rid ?>">
                                    <i class="fas fa-comment-alt mr-2 text-info"></i> Message
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#promoteModal<?= $rid ?>">
                                    <i class="fas fa-user-tie mr-2 text-warning"></i> Promote
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="submit" name="delete_resident" class="dropdown-item text-danger">
                                    <i class="fas fa-archive mr-2"></i> Archive
                                </button>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
        <div class="res-meta">
            <div><i class="fas fa-location-dot"></i><span class="res-text"><?= htmlspecialchars(format_resident_address($row) ?: '—') ?></span></div>
            <div><i class="fas fa-tag"></i><span>
                <?php $age = res_age($row['bdate'] ?? ''); if ($age !== null): ?><span class="res-chip"><?= $age ?> yrs old</span><?php endif; ?>
                <?php if (trim((string)$row['sex']) !== ''): ?><span class="res-chip"><?= htmlspecialchars($row['sex']) ?></span><?php endif; ?>
                <?php if ($isHead): ?><span class="res-chip head">Household Head</span><?php endif; ?>
            </span></div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="res-empty" style="grid-column:1/-1;">No residents found.</div>
<?php endif; ?>
</div>

<?php render_pagination($paged); ?>

<!-- Modals (moved to <body> by the script below so they stack above the backdrop) -->
<div id="resModals">
<?php foreach ($rows as $row):
    $rid  = (int)$row['id_resident'];
    $init = htmlspecialchars(strtoupper(mb_substr($row['fname'], 0, 1)));
?>
    <!-- View Modal -->
    <div class="modal fade" id="viewModal<?= $rid ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:18px;overflow:hidden;">
                <div class="res-info-head">
                    <div class="res-avatar lg"><?= bmis_avatar_inner($row['profile_photo'] ?? null, $init) ?></div>
                    <div style="flex:1;min-width:0;">
                        <h5><?= htmlspecialchars(res_full_name($row)) ?></h5>
                        <small>Resident ID: <?= $rid ?></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-left">
                    <div class="res-info-section">Personal Information</div>
                    <div class="res-info-grid">
                        <div class="full"><div class="lbl">Full Name</div><div class="val"><?= htmlspecialchars(res_full_name($row)) ?></div></div>
                        <div><div class="lbl">Sex</div><div class="val"><?= res_val($row['sex'] ?? '') ?></div></div>
                        <div><div class="lbl">Age</div><div class="val"><?php $a = res_age($row['bdate'] ?? ''); echo $a !== null ? $a . ' years old' : res_val($row['age'] ?? ''); ?></div></div>
                        <div><div class="lbl">Civil Status</div><div class="val"><?= res_val($row['status'] ?? '') ?></div></div>
                        <div><div class="lbl">Nationality</div><div class="val"><?= res_val($row['nationality'] ?? '') ?></div></div>
                        <div><div class="lbl">Birth Date</div><div class="val"><?php $bt = strtotime((string)($row['bdate'] ?? '')); echo $bt ? date('F j, Y', $bt) : res_val($row['bdate'] ?? ''); ?></div></div>
                        <div><div class="lbl">Birth Place</div><div class="val"><?= res_val($row['bplace'] ?? '') ?></div></div>
                    </div>

                    <div class="res-info-section">Household &amp; Status</div>
                    <div class="res-info-grid">
                        <div><div class="lbl">Family Role</div><div class="val"><?= res_val($row['family_role'] ?? '') ?></div></div>
                        <div><div class="lbl">Registered Voter</div><div class="val"><?= res_val($row['voter'] ?? '') ?></div></div>
                        <div><div class="lbl">PWD</div><div class="val"><?= res_val($row['pwd'] ?? '') ?></div></div>
                    </div>

                    <div class="res-info-section">Contact &amp; Address</div>
                    <div class="res-info-grid">
                        <div><div class="lbl">Contact Number</div><div class="val"><?= res_val($row['contact'] ?? '') ?></div></div>
                        <div><div class="lbl">Email</div><div class="val"><?= res_val($row['email'] ?? '') ?></div></div>
                        <div class="full"><div class="lbl">Address</div><div class="val"><?= htmlspecialchars(format_resident_address($row) ?: '—') ?></div></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:30px;">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Modal -->
    <div class="modal fade" id="messageModal<?= $rid ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-paper-plane mr-2"></i> Send Message</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                </div>
                <form action="send_resident_msg.php" method="POST" class="show-loader" data-loader-msg="Sending message...">
                    <div class="modal-body text-left">
                        <div class="form-group">
                            <label><strong>Recipient:</strong></label>
                            <input type="text" class="form-control-plaintext border-bottom" value="<?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?>" readonly>
                        </div>
                        <div class="form-group mt-3">
                            <label><strong>Message Content:</strong></label>
                            <textarea name="message" class="form-control" rows="4" placeholder="Write your message here..." required></textarea>
                        </div>
                        <input type="hidden" name="id_resident" value="<?= $rid ?>">
                        <input type="hidden" name="send_msg" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                        <button type="submit" name="send_msg" class="btn btn-info text-white" style="border-radius:30px;width:120px;">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Promote Modal -->
    <div class="modal fade" id="promoteModal<?= $rid ?>" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="border-radius:20px;overflow:hidden;">
                <div class="modal-header text-white" style="background:linear-gradient(135deg,#b8860b,#daa520);">
                    <h5 class="modal-title"><i class="fas fa-user-tie mr-2"></i> Promote to Barangay Staff</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                </div>
                <form action="admn_resident_crud.php" method="POST" class="show-loader" data-loader-msg="Promoting resident...">
                    <div class="modal-body text-left">
                        <div class="alert alert-warning" style="border-radius:10px;">
                            <i class="fas fa-info-circle mr-1"></i>
                            You are about to promote <strong><?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['lname']) ?></strong> to a Barangay Staff member.
                        </div>
                        <div class="form-group">
                            <label><strong>Assign Position:</strong></label>
                            <select class="form-control" name="position" required style="border-radius:10px;">
                                <option value="">-- Choose Position --</option>
                                <option value="Punong Barangay">Punong Barangay</option>
                                <option value="Secretary">Secretary</option>
                                <option value="Treasurer">Treasurer</option>
                                <option value="Clerk">Clerk</option>
                                <option value="Book Keeper">Book Keeper</option>
                                <option value="Committee on Appropriation">Committee on Appropriation</option>
                                <option value="Committee on Health">Committee on Health</option>
                                <option value="Committee on Women and Children">Committee on Women and Children</option>
                                <option value="Committee on Education">Committee on Education</option>
                                <option value="Committee on Peace and Order">Committee on Peace and Order</option>
                                <option value="Committee on Infrastructure">Committee on Infrastructure</option>
                                <option value="Committee on Ways and Means">Committee on Ways and Means</option>
                                <option value="Committee on Agriculture">Committee on Agriculture</option>
                                <option value="Committee on Tourism">Committee on Tourism</option>
                                <option value="IPMRR Representative">IPMRR Representative</option>
                                <option value="Sk Chairperson">Sk Chairperson</option>
                            </select>
                        </div>
                        <input type="hidden" name="promote_id_resident" value="<?= $rid ?>">
                        <input type="hidden" name="promote_resident" value="1">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="border-radius:30px;">Cancel</button>
                        <button type="submit" name="promote_resident" class="btn text-white" style="border-radius:30px;width:140px;background:linear-gradient(135deg,#b8860b,#daa520);">
                            <i class="fas fa-level-up-alt mr-1"></i> Promote
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endforeach; ?>
</div>

<script>
(function () {
    // Move modals to <body>: inside the page's cards/containers they get trapped under the backdrop.
    document.querySelectorAll('#resModals .modal').forEach(function (m) {
        var old = document.getElementById(m.id);
        if (old && old !== m && !old.closest('#resModals')) old.remove();   // stale copy from a previous render
        document.body.appendChild(m);
    });

    // Tap a card -> open that resident's info (ignore taps on the ⋮ menu).
    function openCard(card) {
        var m = document.getElementById(card.getAttribute('data-modal'));
        if (m && window.bootstrap) bootstrap.Modal.getOrCreateInstance(m).show();
    }
    document.querySelectorAll('.res-card').forEach(function (card) {
        card.addEventListener('click', function (e) {
            if (e.target.closest('.res-actions')) return;
            openCard(card);
        });
        card.addEventListener('keydown', function (e) {
            if ((e.key === 'Enter' || e.key === ' ') && !e.target.closest('.res-actions')) { e.preventDefault(); openCard(card); }
        });
    });
})();
</script>

<!-- Loading Overlay (only create if not already on page) -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script>
(function () {
    if (typeof window.showLoader === 'function') return; // already defined by parent

    window.showLoader = function (msg) {
        const loader = document.getElementById('page-loader');
        const text   = document.getElementById('loader-msg');
        if (text) text.textContent = msg || 'Please wait...';
        if (loader) loader.classList.add('show');
    };
    window.hideLoader = function () {
        const loader = document.getElementById('page-loader');
        if (loader) loader.classList.remove('show');
    };

    window.addEventListener('pageshow', function () {
        window.hideLoader();
    });

    let loaderTimeout = null;
    document.querySelectorAll('form.show-loader').forEach(function (form) {
        form.addEventListener('submit', function () {
            const msg = this.dataset.loaderMsg || 'Please wait...';
            window.showLoader(msg);
            if (loaderTimeout) clearTimeout(loaderTimeout);
            loaderTimeout = setTimeout(window.hideLoader, 12000);
            const btn = this.querySelector('button[type="submit"], input[type="submit"]');
            if (btn) {
                btn.disabled = true;
                btn.dataset.originalHtml = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
            }
        });
    });
})();
</script>

<?php if (!empty($_SESSION['swal'])): ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire(<?= $_SESSION['swal'] ?>);
    });
</script>
<?php unset($_SESSION['swal']); endif; ?>
<?php $conn = null; ?>