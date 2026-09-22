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

<style>
/* Loader styles (safe if parent already has them) */
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
    animation: bmis-spin 0.85s linear infinite;
}
#page-loader .loader-text {
    color: #fff;
    font-weight: 600;
    font-size: 1.05rem;
}
@keyframes bmis-spin {
    to { transform: rotate(360deg); }
}
</style>

<table class="table table-hover text-center table-bordered" style="min-width: 1000px;">
    <thead class="alert-info">
        <tr>
            <th>Actions</th>
            <th>Resident ID</th>
            <th>Full Name</th>
            <th>Address</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($rows)): ?>
            <?php foreach ($rows as $row): ?>
            <tr>
                <td>
                    <form action="" method="post" class="show-loader" data-loader-msg="Archiving resident...">
                        <input type="hidden" name="id_resident" value="<?= $row['id_resident'] ?>">
                        <input type="hidden" name="delete_resident" value="1">
                        <div class="dropdown">
                            <button type="button" class="btn btn-light btn-sm border" style="width:34px;height:34px;padding:0;border-radius:50%;" data-bs-toggle="dropdown" aria-expanded="false" title="Actions">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#viewModal<?= $row['id_resident'] ?>">
                                        <i class="fa fa-eye mr-2 text-primary"></i> View
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#messageModal<?= $row['id_resident'] ?>">
                                        <i class="fas fa-comment-alt mr-2 text-info"></i> Message
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#promoteModal<?= $row['id_resident'] ?>">
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
                </td>
                <td><?= $row['id_resident'] ?></td>
                <td><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?></td>
                <td><?= htmlspecialchars(format_resident_address($row)) ?></td>

                <!-- View Modal -->
                <div class="modal fade" id="viewModal<?= $row['id_resident'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title">Resident Information</h5>
                                <button type="button" class="close text-white" data-bs-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body text-left">
                                <p><strong>Resident ID:</strong> <?= $row['id_resident'] ?></p>
                                <hr>
                                <h5><strong>Personal Information</strong></h5>
                                <div class="row">
                                    <div class="col-6">
                                        <p><strong>Full Name:</strong><br><?= htmlspecialchars($row['lname']) ?>, <?= htmlspecialchars($row['fname']) ?> <?= htmlspecialchars($row['mi']) ?>.</p>
                                        <p><strong>Sex:</strong> <?= htmlspecialchars($row['sex']) ?></p>
                                        <p><strong>Civil Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
                                    </div>
                                    <div class="col-6">
                                        <p><strong>Nationality:</strong> <?= htmlspecialchars($row['nationality']) ?></p>
                                        <p><strong>Birth Date:</strong> <?= htmlspecialchars($row['bdate']) ?></p>
                                        <p><strong>Birth Place:</strong> <?= htmlspecialchars($row['bplace']) ?></p>
                                        <p><strong>Family Role:</strong> <?= htmlspecialchars($row['family_role']) ?></p>
                                    </div>
                                </div>
                                <hr>
                                <h5><strong>Contact & Address</strong></h5>
                                <p><strong>Address:</strong> <?= htmlspecialchars(format_resident_address($row)) ?></p>
                                <hr>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Message Modal -->
                <div class="modal fade" id="messageModal<?= $row['id_resident'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <input type="hidden" name="id_resident" value="<?= $row['id_resident'] ?>">
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
                <div class="modal fade" id="promoteModal<?= $row['id_resident'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                                    <input type="hidden" name="promote_id_resident" value="<?= $row['id_resident'] ?>">
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
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="4" class="text-muted py-3">No residents found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php render_pagination($paged); ?>

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