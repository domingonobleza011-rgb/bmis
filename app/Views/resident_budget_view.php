
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Budget Transparency | Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> 
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.2/firebase-messaging-compat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body { background:#f0f2f5; color:#1c1e21; }
.budget-container { max-width:760px; margin:auto; }
.page-header { text-align:center; margin-bottom:25px; }
.page-header h2 { font-weight:700; color:#1877f2; }
.page-header p { color:#65676b; }
.filter-card { background:#fff; border-radius:12px; padding:15px; margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,.12); }
.total-box { background:#fff; border-radius:12px; padding:18px; margin-bottom:25px; box-shadow:0 1px 3px rgba(0,0,0,.12); border-left:5px solid #198754; }
.total-label { color:#65676b; font-size:12px; font-weight:700; text-transform:uppercase; }
.total-amount { color:#198754; font-size:28px; font-weight:700; }

/* POST */
.budget-post { background:#fff; border-radius:12px; margin-bottom:20px; box-shadow:0 1px 3px rgba(0,0,0,.18); overflow:hidden; }
.post-header { display:flex; align-items:center; padding:14px 16px; }
.post-avatar { width:44px; height:44px; border-radius:50%; overflow:hidden; background:#fff; border:1px solid #eee; display:flex; align-items:center; justify-content:center; margin-right:10px; flex-shrink:0; }
.post-avatar img { width:100%; height:100%; object-fit:cover; }
.post-user { flex:1; }
.post-user-name { font-weight:700; font-size:15px; }
.post-time { font-size:12px; color:#65676b; }
.post-menu { color:#65676b; font-size:18px; }
.post-content { padding:0 16px 15px; }
.post-content p { margin:0; line-height:1.5; }
.post-category { font-size:20px; font-weight:700; color:#1877f2; margin-bottom:5px; }

/* PHOTO — desktop/tablet: full-width banner */
.budget-post-image { width:100%; max-height:320px; background:#f0f2f5; display:flex; align-items:center; justify-content:center; overflow:hidden; border-top:1px solid #eee; border-bottom:1px solid #eee; }
.budget-post-image img { width:100%; max-height:320px; object-fit:contain; display:block; cursor:pointer; }
.budget-post-image-tap-hint { display:none; text-align:center; font-size:11px; color:#65676b; padding:4px 0; background:#f8f9fa; border-bottom:1px solid #eee; }

/* DATA */
.budget-details { padding:15px 16px 10px; }
.detail-row { display:flex; align-items:flex-start; padding:9px 0; border-bottom:1px solid #eee; }
.detail-row:last-child { border-bottom:0; }
.detail-icon { width:34px; color:#1877f2; font-size:16px; padding-top:2px; }
.detail-label { width:125px; color:#65676b; font-size:14px; }
.detail-value { flex:1; font-size:14px; font-weight:600; word-break:break-word; }
.amount-value { color:#198754; font-size:18px; font-weight:700; }

/* STATUS */
.status-badge { display:inline-block; padding:5px 12px; border-radius:15px; font-size:12px; font-weight:700; }
.status-approved { background:#d1e7dd; color:#0f5132; }
.status-spent { background:#e2e3e5; color:#41464b; }
.status-ongoing { background:#cff4fc; color:#055160; }
.status-proposed { background:#fff3cd; color:#664d03; }

/* FOOTER */
.post-stats { padding:10px 16px; color:#65676b; font-size:13px; border-top:1px solid #eee; }
.post-actions { display:flex; border-top:1px solid #eee; margin:0 12px; }
.post-action { flex:1; text-align:center; padding:11px 5px; color:#65676b; font-size:14px; }
.post-action i { margin-right:5px; }
.post-action:hover { background:#f2f2f2; border-radius:5px; }

/* NO DATA */
.no-budget { background:#fff; border-radius:12px; padding:50px 20px; text-align:center; box-shadow:0 1px 3px rgba(0,0,0,.12); color:#65676b; }
.no-budget i { font-size:45px; margin-bottom:15px; color:#adb5bd; }

/* IMAGE VIEWER */
#budgetViewerModal .modal-content { background:transparent; border:0; }
#budgetViewerModal .modal-body { min-height:85vh; display:flex; align-items:center; justify-content:center; position:relative; }
#viewerImage { max-width:85vw; max-height:75vh; object-fit:contain; border-radius:10px; box-shadow:0 0 40px rgba(0,0,0,.6); }
.viewer-btn { position:fixed; top:50%; transform:translateY(-50%); width:50px; height:50px; border:0; border-radius:50%; background:rgba(255,255,255,.9); color:#333; z-index:10; }
.viewer-btn:hover { background:#fff; }
.viewer-prev { left:20px; }
.viewer-next { right:20px; }
.viewer-close { position:fixed; top:76px; right:20px; width:42px; height:42px; border:0; border-radius:50%; background:rgba(255,255,255,.9); color:#333; z-index:20; font-size:18px; }
.viewer-close:hover { background:#fff; }
.viewer-info { position:fixed; bottom:20px; left:0; right:0; text-align:center; color:#fff; }
.viewer-counter { display:inline-block; background:rgba(0,0,0,.65); padding:6px 15px; border-radius:20px; font-size:13px; }
.viewer-title { margin-top:7px; font-size:14px; }

/* MOBILE */
@media(max-width:576px) {
    .budget-container { width:100%; }
    .page-header h2 { font-size:24px; }
    .detail-label { width:105px; }
    .detail-value { font-size:13px; }
    .amount-value { font-size:16px; }
    .viewer-btn { width:42px; height:42px; }
    .viewer-prev { left:8px; }
    .viewer-next { right:8px; }
    .viewer-close { top:15px; }
    .viewer-info { bottom:78px; }
    #viewerImage { max-width:92vw; max-height:70vh; }

    /* PHOTO — minimal compact thumbnail instead of a big banner */
    .budget-post-image { max-height:110px; margin:0 12px 8px; width:calc(100% - 24px); border-radius:10px; border:1px solid #eee; }
    .budget-post-image img { max-height:110px; object-fit:cover; }
    .budget-post-image-tap-hint { display:block; }
}
</style>
</head>
<body>

<?php include(ROOT_PATH . '/resident_navbar.php'); ?>

<div class="container-md px-3 px-sm-4 py-3 py-md-4">
<div class="budget-container">

<div class="page-header">
    <h2><i class="fas fa-wallet me-2"></i>Budget Transparency</h2>
    <p>Open and transparent barangay budget for residents of San Pedro</p>
</div>

<div class="filter-card">
    <div class="row g-2 align-items-center">
        <div class="col-12 col-sm-8 col-md-6">
            <label class="form-label fw-semibold mb-1"><i class="fas fa-filter me-1"></i>Filter by Fiscal Year</label>
            <form method="GET">
                <select name="year" class="form-select" onchange="this.form.submit()">
                    <option value="">All Years</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?= htmlspecialchars($y) ?>" <?= $filter_year == $y ? 'selected' : '' ?>><?= htmlspecialchars($y) ?></option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="total-box">
    <div class="total-label"><i class="fas fa-chart-pie me-1"></i>Total Budget</div>
    <div class="total-amount">₱ <?= number_format($total, 2) ?></div>
    <small class="text-muted"><?= $filter_year ? 'Fiscal Year ' . htmlspecialchars($filter_year) : 'All Fiscal Years' ?></small>
</div>

<?php if (empty($view)): ?>
<div class="no-budget">
    <i class="fas fa-folder-open"></i>
    <h5>No Budget Information</h5>
    <p class="mb-0">No budget information is available for the selected year.</p>
</div>
<?php else: foreach ($view as $row):
    $statusClass = match ($row['status']) {
        'Approved' => 'status-approved',
        'Spent'    => 'status-spent',
        'Ongoing'  => 'status-ongoing',
        default    => 'status-proposed',
    };

    $imageIndex = -1;
    if (!empty($row['image']) && file_exists('uploads/' . $row['image'])) {
        foreach ($all_images as $index => $galleryImage) {
            if ($galleryImage['src'] === 'uploads/' . $row['image']) { $imageIndex = $index; break; }
        }
    }
?>
<div class="budget-post">
    <div class="post-header">
        <div class="post-avatar"><img src="icons/logo.png" alt="Barangay San Pedro"></div>
        <div class="post-user">
            <div class="post-user-name">Barangay San Pedro</div>
            <div class="post-time"><i class="fas fa-globe-asia me-1"></i>Public &middot; Budget Transparency</div>
        </div>
        <div class="post-menu"><i class="fas fa-ellipsis-h"></i></div>
    </div>

    <div class="post-content">
        <div class="post-category"><?= htmlspecialchars($row['category']) ?></div>
        <?php if (!empty($row['description'])): ?>
            <p><?= nl2br(htmlspecialchars($row['description'])) ?></p>
        <?php else: ?>
            <p class="text-muted">No description provided.</p>
        <?php endif; ?>
    </div>

    <?php if (!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
        <div class="budget-post-image">
            <img src="uploads/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['category']) ?>" onclick="openBudgetImage(<?= $imageIndex >= 0 ? $imageIndex : 0 ?>)" loading="lazy">
        </div>
        <div class="budget-post-image-tap-hint"><i class="fas fa-expand me-1"></i>Tap photo to view full size</div>
    <?php endif; ?>

    <div class="budget-details">
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-calendar-alt"></i></div>
            <div class="detail-label">Fiscal Year</div>
            <div class="detail-value"><?= htmlspecialchars($row['fiscal_year']) ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-tag"></i></div>
            <div class="detail-label">Category</div>
            <div class="detail-value"><?= htmlspecialchars($row['category']) ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-align-left"></i></div>
            <div class="detail-label">Description</div>
            <div class="detail-value"><?= nl2br(htmlspecialchars($row['description'] ?? '—')) ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-money-bill-wave"></i></div>
            <div class="detail-label">Amount</div>
            <div class="detail-value amount-value">₱ <?= number_format($row['amount'], 2) ?></div>
        </div>
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-circle-check"></i></div>
            <div class="detail-label">Status</div>
            <div class="detail-value"><span class="status-badge <?= $statusClass ?>"><?= htmlspecialchars($row['status']) ?></span></div>
        </div>
        <?php if (!empty($row['date_added'])): ?>
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-clock"></i></div>
            <div class="detail-label">Date Added</div>
            <div class="detail-value"><?= date('M d, Y', strtotime($row['date_added'])) ?></div>
        </div>
        <?php endif; ?>
        <div class="detail-row">
            <div class="detail-icon"><i class="fas fa-image"></i></div>
            <div class="detail-label">Photo</div>
            <div class="detail-value">
                <?php if ($imageIndex >= 0): ?>
                    <span class="text-success"><i class="fas fa-check-circle me-1"></i>Available</span>
                <?php else: ?>
                    <span class="text-muted">No photo</span>
                <?php endif; ?>
            </div>
        </div>
    </div>



</div>
<?php endforeach; endif; ?>

</div>
</div>

<!-- IMAGE VIEWER -->
<div class="modal fade" id="budgetViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="viewer-close" data-bs-dismiss="modal"><i class="fas fa-times"></i></button>
                <button type="button" id="viewerPrev" class="viewer-btn viewer-prev" onclick="previousBudgetImage()"><i class="fas fa-chevron-left"></i></button>
                <img id="viewerImage" src="" alt="Budget Photo">
                <button type="button" id="viewerNext" class="viewer-btn viewer-next" onclick="nextBudgetImage()"><i class="fas fa-chevron-right"></i></button>
                <div class="viewer-info">
                    <div class="viewer-counter"><span id="viewerCurrent">1</span> of <span id="viewerTotal">1</span></div>
                    <div id="viewerImageTitle" class="viewer-title"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const budgetImages = <?= json_encode($all_images, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
let currentBudgetImage = 0;
let budgetViewer = null;

function openBudgetImage(index) {
    if (!budgetImages.length) return;
    currentBudgetImage = index;
    budgetViewer = bootstrap.Modal.getOrCreateInstance(document.getElementById('budgetViewerModal'));
    updateBudgetViewer();
    budgetViewer.show();
}

function updateBudgetViewer() {
    if (!budgetImages.length) return;
    const image = budgetImages[currentBudgetImage];
    document.getElementById('viewerImage').src = image.src;
    document.getElementById('viewerImage').alt = image.title;
    document.getElementById('viewerImageTitle').textContent = image.title;
    document.getElementById('viewerCurrent').textContent = currentBudgetImage + 1;
    document.getElementById('viewerTotal').textContent = budgetImages.length;
    const prev = document.getElementById('viewerPrev'), next = document.getElementById('viewerNext');
    if (budgetImages.length <= 1) {
        prev.style.display = 'none';
        next.style.display = 'none';
    } else {
        prev.style.display = currentBudgetImage === 0 ? 'none' : 'block';
        next.style.display = currentBudgetImage === budgetImages.length - 1 ? 'none' : 'block';
    }
}

function nextBudgetImage() { if (currentBudgetImage < budgetImages.length - 1) { currentBudgetImage++; updateBudgetViewer(); } }
function previousBudgetImage() { if (currentBudgetImage > 0) { currentBudgetImage--; updateBudgetViewer(); } }

document.addEventListener('keydown', function (e) {
    if (!document.getElementById('budgetViewerModal').classList.contains('show')) return;
    if (e.key === 'ArrowRight') nextBudgetImage();
    if (e.key === 'ArrowLeft') previousBudgetImage();
});

document.getElementById('budgetViewerModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('viewerImage').src = '';
    document.getElementById('viewerImageTitle').textContent = '';
});
</script>
</body>
</html>