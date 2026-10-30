<?php
/**
 * Read-only resident cards (shared by the admin resident table pages).
 * Expects: $rows (resident rows), $paged (pagination array), optional $res_empty_text.
 */
require_once ROOT_PATH . '/pagination_helper.php';
if (!function_exists('format_resident_address')) {
    function format_resident_address(array $row): string {
        $parts = array_filter(array_map('trim', [$row['houseno'] ?? '', $row['street'] ?? '', $row['brgy'] ?? '', $row['municipal'] ?? '', $row['province'] ?? '']), fn($p) => $p !== '');
        return !empty($parts) ? implode(', ', $parts) : trim($row['address'] ?? '');
    }
}
$rows = $rows ?? [];
$res_empty_text = $res_empty_text ?? 'No residents found.';
?>
<?php require_once ROOT_PATH . '/app/Views/resident_cards_style.php'; ?>

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
                <div class="dropdown">
                    <button type="button" class="btn btn-light btn-sm border" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                        <i class="fas fa-ellipsis-v"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $rid ?>"><i class="fa fa-eye mr-2 text-primary"></i> View</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="res-meta">
            <div><i class="fas fa-location-dot"></i><span class="res-text"><?= htmlspecialchars(format_resident_address($row) ?: '—') ?></span></div>
            <div><i class="fas fa-tag"></i><span>
                <?php $age = res_age($row['bdate'] ?? ''); if ($age !== null): ?><span class="res-chip"><?= $age ?> yrs old</span><?php endif; ?>
                <?php if (trim((string)($row['sex'] ?? '')) !== ''): ?><span class="res-chip"><?= htmlspecialchars($row['sex']) ?></span><?php endif; ?>
                <?php if ($isHead): ?><span class="res-chip head">Household Head</span><?php endif; ?>
            </span></div>
        </div>
    </div>
    <?php endforeach; ?>
<?php else: ?>
    <div class="res-empty" style="grid-column:1/-1;"><?= htmlspecialchars($res_empty_text) ?></div>
<?php endif; ?>
</div>

<?php if (!empty($paged)) render_pagination($paged); ?>

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
<?php endforeach; ?>
</div>

<script>
(function () {
    document.querySelectorAll('#resModals .modal').forEach(function (m) {
        var old = document.getElementById(m.id);
        if (old && old !== m && !old.closest('#resModals')) old.remove();
        document.body.appendChild(m);
    });
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
