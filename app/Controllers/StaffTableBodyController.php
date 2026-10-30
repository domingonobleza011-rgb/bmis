<?php
/**
 * staff_table_body.php
 * Renders the Barangay Staff as an organizational chart (like the barangay
 * tarpaulin): Punong Barangay + officials on the left, SB Members (committees,
 * SK Chairperson, IPMR Representative) branching off on the right.
 * Expects $view (from StaffClass::view_staff_paginated()) set by the caller.
 *
 * Included by admn_staff_crud.php.
 */
$staffRows = $view['rows'] ?? [];

$officialOrder = ['Secretary', 'Treasurer', 'Clerk', 'Book Keeper'];
$memberOrder = [
    'Committee on Appropriation', 'Committee on Health', 'Committee on Women and Children',
    'Committee on Agriculture', 'Committee on Infrastructure', 'Committee on Education',
    'Committee on Ways and Means', 'Committee on Peace and Order', 'Committee on Tourism',
    'Sk Chairperson', 'IPMRR Representative',
];
$pillLabels = ['Sk Chairperson' => 'SK Chairperson', 'IPMRR Representative' => 'IPMR Representative'];

$pbList = []; $officials = []; $members = [];
foreach ($staffRows as $st) {
    $pos = trim((string)($st['position'] ?? ''));
    if (strcasecmp($pos, 'Punong Barangay') === 0)      $pbList[] = $st;
    elseif (in_array($pos, $officialOrder, true))       $officials[] = $st;
    else                                                $members[] = $st;
}
$rank = function ($pos, $order) { $i = array_search($pos, $order, true); return $i === false ? 999 : $i; };
usort($officials, function ($x, $y) use ($rank, $officialOrder) {
    return $rank($x['position'], $officialOrder) <=> $rank($y['position'], $officialOrder) ?: strcasecmp($x['lname'], $y['lname']);
});
usort($members, function ($x, $y) use ($rank, $memberOrder) {
    return $rank($x['position'], $memberOrder) <=> $rank($y['position'], $memberOrder) ?: strcasecmp($x['lname'], $y['lname']);
});

// Fill every standard seat that has nobody yet with a "Vacant" placeholder.
$taken = [];
foreach ($staffRows as $st) { $taken[strtolower(trim((string)$st['position']))] = true; }
$vacant = function ($pos) { return ['_vacant' => true, 'position' => $pos, 'fname' => '', 'lname' => '', 'mi' => '', 'photo' => '']; };
if (empty($pbList)) $pbList[] = $vacant('Punong Barangay');
foreach ($officialOrder as $pos) { if (!isset($taken[strtolower($pos)])) $officials[] = $vacant($pos); }
foreach ($memberOrder   as $pos) { if (!isset($taken[strtolower($pos)])) $members[]   = $vacant($pos); }
usort($officials, function ($x, $y) use ($rank, $officialOrder) {
    return $rank($x['position'], $officialOrder) <=> $rank($y['position'], $officialOrder);
});
usort($members, function ($x, $y) use ($rank, $memberOrder) {
    return $rank($x['position'], $memberOrder) <=> $rank($y['position'], $memberOrder);
});

if (!function_exists('org_person_name')) {
    function org_person_name(array $st, bool $hon = false): string {
        if (!empty($st['_vacant'])) return 'Vacant';
        $n = trim($st['fname'] . ' ' . (trim((string)$st['mi']) !== '' ? (mb_strlen(rtrim(trim($st['mi']), '.')) === 1 ? rtrim(trim($st['mi']), '.') . '.' : trim($st['mi'])) : '') . ' ' . $st['lname']);
        return ($hon ? 'Hon. ' : '') . $n;
    }
    function org_photo(array $st): string {
        if (!empty($st['_vacant'])) return '<div class="org-photo org-vacant-photo"><i class="fas fa-user"></i></div>';
        $photo = trim((string)($st['photo'] ?? ''));
        if ($photo !== '' && is_file(ROOT_PATH . '/' . ltrim($photo, '/'))) {
            return '<div class="org-photo"><img src="' . htmlspecialchars($photo) . '" alt=""></div>';
        }
        return '<div class="org-photo"><span class="org-initial">' . htmlspecialchars(strtoupper(mb_substr($st['fname'], 0, 1))) . '</span></div>';
    }
    function org_actions(array $staff, bool $inline = false): void { if (!empty($staff['_vacant'])) return; ?>
        <div class="org-actions<?= $inline ? ' inline' : '' ?>">
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
        </div>
    <?php }
}

$memberRows = max(1, (int)ceil(count($members) / 2));
?>
<style>
.org { --org-green:#1d7a62; --org-ring:#23806a; --org-ink:#25365a; --org-line:#4a5d7e; }
.org-title {
    text-align: center; font-weight: 800; letter-spacing: 1px; text-transform: uppercase;
    color: var(--org-green); font-size: clamp(1.2rem, 2.4vw, 1.9rem); margin: 0 0 28px;
}
.org-body { display: grid; grid-template-columns: minmax(280px, 340px) 1fr; gap: 0; align-items: start; }

/* people */
.org-person { position: relative; text-align: center; }
.org-photo {
    border: 5px solid var(--org-ring); border-radius: 50%; padding: 3px; background: #fff;
    aspect-ratio: 1; overflow: hidden; flex-shrink: 0;
}
.org-photo img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; display: block; }
.org-initial {
    width: 100%; height: 100%; border-radius: 50%; background: #e3ecf5; color: var(--org-ink);
    display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 2rem;
}
.org-name { font-weight: 800; color: var(--org-ink); text-transform: uppercase; letter-spacing: .3px; line-height: 1.2; }
.org-pill {
    display: inline-block; background: var(--org-ring); color: #fff; font-size: .68rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .5px; padding: 2px 12px; border-radius: 3px; margin-top: 4px;
}
.org-sub { padding-bottom: 2px; font-size: .8rem; color: var(--org-green); font-weight: 600; line-height: 1.25; margin-top: 4px; }

/* Punong Barangay */
.org-left { margin-right: 40px; display: flex; flex-direction: column; align-items: center; gap: 22px; }
.org-pb .org-photo { width: 210px; margin: 0 auto 10px; }
.org-pb .org-name { font-size: 1.3rem; }
.org-pb .org-pos { font-size: 1.1rem; color: var(--org-green); font-style: italic; font-weight: 700; }
.org-officials { display: grid; grid-template-columns: 1fr 1fr; gap: 20px 18px; width: 100%; }
.org-officials .org-photo { width: 120px; margin: 0 auto 6px; }
.org-officials .org-name { font-size: .82rem; }

/* SB Members */
.org-sb { display: grid; grid-template-columns: 120px 1fr; align-items: stretch; }
.org-mid {
    position: relative; align-self: stretch; display: flex; align-items: center; justify-content: flex-end;
    padding-right: 10px; text-align: right; font-weight: 800; font-size: 1.3rem; color: var(--org-ink); text-transform: uppercase;
}
.org-mid::after { content: ''; position: absolute; right: 0; top: 52px; bottom: 64px; width: 2px; background: var(--org-line); }
.org-members { display: grid; grid-auto-flow: column; grid-template-columns: max-content max-content; justify-content: start; gap: 24px 36px; padding-left: 28px; padding-bottom: 12px; }
.org-members .org-person { display: flex; align-items: center; gap: 12px; text-align: left; }
.org-members .org-photo { width: 104px; }
.org-members .org-name { font-size: .85rem; }
.org-name-row { display: flex; align-items: center; gap: 4px; }
.org-members .org-person.col1::before {
    content: ''; position: absolute; left: -28px; top: 52px; width: 28px; height: 2px; background: var(--org-line);
}


/* vacant seats */
.org-vacant-photo { border-style: dashed !important; border-color: #9fb3c8 !important; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: #9fb3c8; font-size: 2.2rem; }
.org-pb .org-vacant-photo { font-size: 5rem; }
.org-person.is-vacant .org-name { color: #8a97ad; font-weight: 700; font-style: italic; }
.org-person.is-vacant .org-pill { background: #9fb3c8; }
.org-person.is-vacant .org-sub { color: #8a97ad; }
.org-person.is-vacant .org-pos { color: #8a97ad; }

/* admin actions */
.org-actions { position: absolute; top: 0; right: 0; z-index: 3; }
.org-actions .dropdown > .btn { opacity: 1; background: #fff; border: 1px solid #b8c4d6 !important; color: var(--org-ink) !important; }
.org-actions .dropdown > .btn i { font-size: 1rem; color: var(--org-ink); }
.org-actions.inline { position: static; flex-shrink: 0; }
.org-actions form, .org-actions .dropdown { margin: 0 !important; }
.org-actions .dropdown > .btn { display: inline-flex; align-items: center; justify-content: center; width: 32px !important; height: 32px !important; font-size: 1rem; line-height: 1; }
.org-person:hover .org-actions .dropdown > .btn { border-color: var(--org-ring) !important; }
.org-pb .org-actions { right: calc(50% - 130px); }
.org-officials .org-actions { right: calc(50% - 85px); }
.org-empty { text-align: center; color: #718096; padding: 40px 0; font-style: italic; }

@media (max-width: 991.98px) {
    .org-body { grid-template-columns: 1fr; gap: 28px; }
    .org-left { margin-right: 0; }
    .org-sb { grid-template-columns: 1fr; }
    .org-mid { justify-content: center; padding: 0; }
    .org-mid::after, .org-members .org-person.col1::before { display: none; }
    .org-members { grid-auto-flow: row; grid-template-rows: none !important; padding-left: 0; }
}
@media (max-width: 575.98px) { .org-members { grid-template-columns: 1fr; } }

/* Light / Dark theme (theme blanks all backgrounds & colours, so re-declare) */
html[data-theme] .org { --org-green: var(--tm-icon-active); --org-ring: var(--tm-icon-active); --org-ink: var(--tm-head); --org-line: var(--tm-line); }
html[data-theme="dark"] .org-pill { background: #4f46e5 !important; }
html[data-theme] .org-pill { background: var(--tm-icon-active) !important; color: #fff !important; }
html[data-theme] .org-title, html[data-theme] .org-sub, html[data-theme] .org-pb .org-pos { color: var(--org-green) !important; }
html[data-theme] .org-name, html[data-theme] .org-mid { color: var(--org-ink) !important; }
html[data-theme] .org-actions .dropdown > .btn { background: var(--tm-surface2) !important; border-color: var(--tm-border) !important; color: var(--tm-head) !important; }
html[data-theme] .org-actions .dropdown > .btn i { color: var(--tm-head) !important; }
html[data-theme] .org-photo { background: var(--tm-surface) !important; border-color: var(--org-ring) !important; }
html[data-theme] .org-initial { background: var(--tm-surface2) !important; color: var(--tm-head) !important; }
html[data-theme] .org-mid::after, html[data-theme] .org-members .org-person.col1::before { background: var(--tm-line) !important; }
html[data-theme] .org-vacant-photo { background: var(--tm-surface2) !important; color: var(--tm-muted) !important; border-color: var(--tm-line) !important; }
html[data-theme] .org-person.is-vacant .org-name, html[data-theme] .org-person.is-vacant .org-sub, html[data-theme] .org-person.is-vacant .org-pos { color: var(--tm-muted) !important; }
html[data-theme] .org-person.is-vacant .org-pill { background: var(--tm-line) !important; }
</style>

<div class="org">
    <h2 class="org-title">The Sangguniang Barangay of San Pedro</h2>

    <?php if (empty($staffRows)) { ?>
        <div class="org-empty">No staff found.</div>
    <?php } else { ?>
    <div class="org-body">
        <!-- Left: Punong Barangay + officials -->
        <div class="org-left">
            <?php foreach ($pbList as $st) { ?>
                <div class="org-person org-pb<?= !empty($st['_vacant']) ? ' is-vacant' : '' ?>">
                    <?= org_photo($st) ?>
                    <div class="org-name"><?= htmlspecialchars(org_person_name($st)) ?></div>
                    <div class="org-pos">Punong Barangay</div>
                    <?php org_actions($st); ?>
                </div>
            <?php } ?>
            <?php if (!empty($officials)) { ?>
            <div class="org-officials">
                <?php foreach ($officials as $st) { ?>
                    <div class="org-person<?= !empty($st['_vacant']) ? ' is-vacant' : '' ?>">
                        <?= org_photo($st) ?>
                        <div class="org-name"><?= htmlspecialchars(org_person_name($st)) ?></div>
                        <span class="org-pill"><?= htmlspecialchars($st['position']) ?></span>
                        <?php org_actions($st); ?>
                    </div>
                <?php } ?>
            </div>
            <?php } ?>
        </div>

        <!-- Middle: SB MEMBER bracket label -->
        <?php if (!empty($members)) { ?>
        <div class="org-sb">
        <div class="org-mid">SB Member</div>

        <!-- Right: SB members -->
        <div class="org-members" style="grid-template-rows: repeat(<?= $memberRows ?>, auto);">
            <?php foreach ($members as $i => $st) {
                $pos   = trim((string)$st['position']);
                $isSB  = stripos($pos, 'Committee') === 0 || !isset($pillLabels[$pos]);
                $pill  = $isSB ? 'SB Member' : $pillLabels[$pos];
                $sub   = $isSB ? $pos : '';
            ?>
                <div class="org-person <?= $i < $memberRows ? 'col1' : '' ?><?= !empty($st['_vacant']) ? ' is-vacant' : '' ?>">
                    <?= org_photo($st) ?>
                    <div>
                        <div class="org-name-row">
                            <div class="org-name"><?= htmlspecialchars(org_person_name($st, true)) ?></div>
                            <?php org_actions($st, true); ?>
                        </div>
                        <span class="org-pill"><?= htmlspecialchars($pill) ?></span>
                        <?php if ($sub !== '') { ?><div class="org-sub"><?= htmlspecialchars($sub) ?></div><?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
        </div><!-- /.org-sb -->
        <?php } ?>
    </div>
    <?php } ?>
</div>

<script>
// Demote modals are rendered inside each person, but must live on <body> to sit
// above Bootstrap's backdrop (otherwise they get dimmed/overlapped by the chart).
document.querySelectorAll('.org .modal').forEach(function (m) { document.body.appendChild(m); });
</script>
