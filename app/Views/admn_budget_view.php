
<?php include(ROOT_PATH . '/dashboard_sidebar_start.php'); ?>

<style>
/* Match announcement delete button */
.post-delete-btn {
    border: none;
    background: #f7f8fc;
    color: #718096;
    width: 34px;
    height: 34px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    flex-shrink: 0;
    vertical-align: middle;
}
.post-delete-btn:hover {
    background: #fef2f2;
    color: #dc2626;
}

/* Confirmation modal (same as announcements) */
.bmis-modal-backdrop {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0,0,0,0.45);
    align-items: center;
    justify-content: center;
}
.bmis-modal-backdrop.open {
    display: flex;
}
.bmis-modal-card {
    background: #fff;
    border-radius: 14px;
    padding: 28px 32px;
    width: 100%;
    max-width: 430px;
    margin: 0 16px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
}
.bmis-modal-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}
.bmis-modal-title {
    font-size: 15px;
    font-weight: 600;
    margin: 0;
    color: #0f2d5a;
}
.bmis-modal-sub {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
}
.bmis-modal-info {
    border-radius: 8px;
    padding: 12px 16px;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 12px;
}
.bmis-modal-info p { margin: 0; }
.bmis-btn-cancel {
    padding: 8px 18px;
    font-size: 13px;
    border-radius: 8px;
    cursor: pointer;
    border: 1px solid #d1d5db;
    background: #fff;
    color: #6b7280;
}
.bmis-btn-confirm {
    padding: 8px 20px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    cursor: pointer;
    border: none;
    color: #fff;
}

/* ===== LOADING OVERLAY ===== */
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
#page-loader.show {
    display: flex;
}
#page-loader .spinner {
    width: 56px;
    height: 56px;
    border: 4px solid rgba(255,255,255,0.25);
    border-top-color: #c9943a;
    border-radius: 50%;
    animation: spin 0.85s linear infinite;
}
#page-loader .loader-text {
    color: #fff;
    font-weight: 600;
    font-size: 1.05rem;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<div class="container-fluid">
    <!-- PAGE HEADER -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-wallet me-2"></i>Budget Transparency
        </h1>
        <button type="button"
                class="btn btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#addBudgetModal">
            <i class="fas fa-plus"></i> Add Budget Item
        </button>
    </div>

    <!-- FILTER + TOTAL -->
    <div class="row mb-4">
        <div class="col-md-4">
            <form method="GET">
                <select name="year"
                        class="form-select"
                        onchange="this.form.submit()">
                    <option value="">All Years</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?= htmlspecialchars($y) ?>"
                            <?= $filter_year == $y ? 'selected' : '' ?>>
                            <?= htmlspecialchars($y) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        </div>
        <div class="col-md-8 text-end">
            <div class="card border-left-success shadow h-100 py-2 d-inline-block px-4">
                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                    Total Budget
                </div>
                <div class="h4 mb-0 font-weight-bold text-gray-800">
                    ₱ <?= number_format($total, 2) ?>
                </div>
            </div>
        </div>
    </div>

    <!-- BUDGET TABLE -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                Budget Items
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Fiscal Year</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date Added</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($view)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                No budget items yet.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($view as $row): ?>
                            <tr>
                                <!-- PHOTO -->
                                <td class="text-center">
                                    <?php if (!empty($row['image']) && file_exists('uploads/' . $row['image'])): ?>
                                        <img src="uploads/<?= htmlspecialchars($row['image']) ?>"
                                             alt="Budget photo"
                                             style="width:60px;height:60px;object-fit:cover;border-radius:8px;cursor:pointer"
                                             onclick='openImageViewer(
                                                <?= json_encode("uploads/" . $row["image"]) ?>,
                                                <?= json_encode($row["category"] . " - " . $row["fiscal_year"]) ?>
                                             )'>
                                    <?php else: ?>
                                        <span class="text-muted small">No photo</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($row['fiscal_year']) ?></td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td><?= htmlspecialchars($row['description'] ?? '') ?></td>
                                <td class="text-end">₱ <?= number_format($row['amount'], 2) ?></td>
                                <td>
                                    <?php
                                    $badge = match ($row['status']) {
                                        'Approved' => 'success',
                                        'Spent'    => 'secondary',
                                        'Ongoing'  => 'info',
                                        default    => 'warning'
                                    };
                                    ?>
                                    <span class="badge bg-<?= $badge ?>">
                                        <?= htmlspecialchars($row['status']) ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($row['date_added'])) ?></td>
                                <td>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            onclick='editBudget(<?= json_encode(
                                                $row,
                                                JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
                                            ) ?>)'>
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button"
                                            class="post-delete-btn"
                                            title="Delete budget item"
                                            data-id="<?= htmlspecialchars($row['id_budget'], ENT_QUOTES) ?>"
                                            data-preview="<?= htmlspecialchars($row['category'] . ' — ₱' . number_format($row['amount'], 2) . ' (' . $row['fiscal_year'] . ')', ENT_QUOTES) ?>"
                                            onclick="openDeleteBudgetModal(this.dataset.id, this.dataset.preview)">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- IMAGE VIEWER MODAL -->
<div class="modal fade" id="imageViewerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content bg-transparent border-0">
            <div class="modal-body p-0 position-relative">
                <button type="button"
                        class="btn-close btn-close-white position-fixed top-0 end-0 m-3"
                        style="z-index:1050;font-size:2rem"
                        onclick="closeImageViewer()"
                        aria-label="Close">
                </button>
                <div class="d-flex align-items-center justify-content-center" style="min-height:80vh">
                    <button id="prevImageBtn"
                            type="button"
                            class="btn btn-light btn-lg rounded-circle position-fixed start-0 ms-3"
                            style="z-index:1050;opacity:.7;top:50%;transform:translateY(-50%)"
                            onclick="prevImage()">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <img id="viewerImage"
                         src=""
                         alt="Budget Image"
                         style="max-width:85vw;max-height:75vh;object-fit:contain;border-radius:12px;box-shadow:0 0 50px rgba(0,0,0,.5)">
                    <button id="nextImageBtn"
                            type="button"
                            class="btn btn-light btn-lg rounded-circle position-fixed end-0 me-3"
                            style="z-index:1050;opacity:.7;top:50%;transform:translateY(-50%)"
                            onclick="nextImage()">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div id="imageCounter"
                     class="text-center text-white"
                     style="position:fixed;bottom:20px;left:0;right:0">
                    <span class="badge bg-dark bg-opacity-50 px-4 py-2">
                        <span id="currentImageIndex">1</span> of <span id="totalImages">1</span>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ADD BUDGET MODAL -->
<div class="modal fade" id="addBudgetModal" tabindex="-1" aria-labelledby="addBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content show-loader" data-loader-msg="Saving budget item...">
            <div class="modal-header">
                <h5 class="modal-title" id="addBudgetModalLabel">Add Budget Item</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Fiscal Year</label>
                    <input type="text" name="fiscal_year" class="form-control" placeholder="e.g. 2026" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Infrastructure, Health, Education" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount (₱)</label>
                    <input type="number" step="0.01" min="0" name="amount" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="Proposed">Proposed</option>
                        <option value="Approved">Approved</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Spent">Spent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Photo (optional)</label>
                    <input type="file" name="budget_img" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="form-text">JPG, PNG, GIF, or WebP. Max 5MB.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" name="create_budget" class="btn btn-primary">Save</button>
            </div>
            <input type="hidden" name="create_budget" value="1">
        </form>
    </div>
</div>

<!-- EDIT BUDGET MODAL -->
<div class="modal fade" id="editBudgetModal" tabindex="-1" aria-labelledby="editBudgetModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" enctype="multipart/form-data" class="modal-content show-loader" data-loader-msg="Updating budget item...">
            <input type="hidden" name="id_budget" id="edit_id">
            <div class="modal-header">
                <h5 class="modal-title" id="editBudgetModalLabel">Edit Budget Item</h5>
                <button type="button" class="btn-close" id="editCloseButton" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center" id="edit_preview_wrap" style="display:none">
                    <img id="edit_preview" src="" alt="Current photo"
                         style="max-width:150px;max-height:120px;border-radius:8px;object-fit:cover">
                    <div class="form-text">Current photo</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fiscal Year</label>
                    <input type="text" name="fiscal_year" id="edit_year" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <input type="text" name="category" id="edit_category" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit_desc" class="form-control" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Amount (₱)</label>
                    <input type="number" step="0.01" min="0" name="amount" id="edit_amount" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" id="edit_status" class="form-select">
                        <option value="Proposed">Proposed</option>
                        <option value="Approved">Approved</option>
                        <option value="Ongoing">Ongoing</option>
                        <option value="Spent">Spent</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Change Photo (optional)</label>
                    <input type="file" name="budget_img" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                    <div class="form-text">Leave empty to keep current photo.</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="editCancelButton">Cancel</button>
                <button type="submit" name="update_budget" class="btn btn-primary">Update</button>
            </div>
            <input type="hidden" name="update_budget" value="1">
        </form>
    </div>
</div>

<!-- HIDDEN DELETE FORM -->
<form id="deleteBudgetForm" method="POST" action="admn_budget.php" class="show-loader" data-loader-msg="Deleting budget item..." style="display:none;">
    <input type="hidden" name="id_budget" id="deleteBudgetId">
    <input type="hidden" name="delete_budget" value="1">
</form>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteBudgetModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
      <div class="bmis-modal-icon" style="background:#fee2e2;">
        <i class="fas fa-trash-alt" style="color:#dc2626; font-size:18px;"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Delete budget item</p>
        <p class="bmis-modal-sub">This action cannot be undone.</p>
      </div>
    </div>
    <hr style="margin:16px 0; border-color:#e5e7eb;">
    <div class="bmis-modal-info" style="background:#fef2f2; border:1.5px solid #fecaca;">
      <i class="fas fa-exclamation-triangle" style="color:#dc2626; font-size:20px; flex-shrink:0;"></i>
      <div>
        <p id="deleteBudgetPreview" style="font-size:13px; font-weight:600; color:#991b1b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:280px;"></p>
        <p style="font-size:12px; color:#b91c1c; margin-top:2px;">This budget item will be permanently removed.</p>
      </div>
    </div>
    <div style="display:flex; gap:8px; justify-content:flex-end;">
      <button type="button" class="bmis-btn-cancel" onclick="closeDeleteBudgetModal()">Cancel</button>
      <button type="button" class="bmis-btn-confirm" style="background:linear-gradient(135deg,#dc2626,#ef4444);"
        onclick="showLoader('Deleting budget item...'); document.getElementById('deleteBudgetForm').submit();">
  <i class="fas fa-trash-alt" style="margin-right:5px;"></i> Yes, delete
</button>
    </div>
  </div>
</div>

<!-- Loading Overlay -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script>
/* =========================================================
   LOADING OVERLAY
========================================================= */
function showLoader(msg = 'Please wait...') {
    const loader = document.getElementById('page-loader');
    const text   = document.getElementById('loader-msg');
    if (text) text.textContent = msg;
    if (loader) loader.classList.add('show');
}
function hideLoader() {
    const loader = document.getElementById('page-loader');
    if (loader) loader.classList.remove('show');
}
window.addEventListener('pageshow', function () {
    hideLoader();
});
let loaderTimeout = null;
document.querySelectorAll('form.show-loader').forEach(form => {
    form.addEventListener('submit', function () {
        const msg = this.dataset.loaderMsg || 'Please wait...';
        showLoader(msg);
        if (loaderTimeout) clearTimeout(loaderTimeout);
        loaderTimeout = setTimeout(hideLoader, 12000);
        const btn = this.querySelector('button[type="submit"], input[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Processing...';
        }
    });
});

/* =========================================================
   DATA
========================================================= */
let imageGallery = <?= !empty($all_images) ? json_encode($all_images, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) : '[]' ?>;
let currentIndex = 0;
let editModalInstance = null;
let imageModalInstance = null;

/* =========================================================
   EDIT MODAL
========================================================= */
function editBudget(row) {
    document.getElementById('edit_id').value = row.id_budget || '';
    document.getElementById('edit_year').value = row.fiscal_year || '';
    document.getElementById('edit_category').value = row.category || '';
    document.getElementById('edit_desc').value = row.description || '';
    document.getElementById('edit_amount').value = row.amount || '';
    document.getElementById('edit_status').value = row.status || 'Proposed';

    const wrap = document.getElementById('edit_preview_wrap');
    const img = document.getElementById('edit_preview');
    if (row.image && String(row.image).trim() !== '') {
        img.src = 'uploads/' + row.image;
        wrap.style.display = 'block';
    } else {
        img.src = '';
        wrap.style.display = 'none';
    }

    const modalElement = document.getElementById('editBudgetModal');
    if (typeof bootstrap === 'undefined' || !bootstrap.Modal) {
        console.error('Bootstrap Modal is not available.');
        return;
    }
    try {
        if (editModalInstance) {
            editModalInstance.hide();
            editModalInstance = null;
        }
        editModalInstance = new bootstrap.Modal(modalElement, {
            backdrop: 'static',
            keyboard: true
        });
        editModalInstance.show();
    } catch (error) {
        console.error('Could not open edit modal:', error);
        modalElement.classList.add('show');
        modalElement.style.display = 'block';
        modalElement.removeAttribute('aria-hidden');
        modalElement.setAttribute('aria-modal', 'true');
        modalElement.setAttribute('role', 'dialog');
        document.body.classList.add('modal-open');
        let backdrop = document.getElementById('editModalFallbackBackdrop');
        if (!backdrop) {
            backdrop = document.createElement('div');
            backdrop.id = 'editModalFallbackBackdrop';
            backdrop.className = 'modal-backdrop fade show';
            document.body.appendChild(backdrop);
        }
    }
}

function closeEditModal() {
    const modalElement = document.getElementById('editBudgetModal');
    if (editModalInstance) {
        editModalInstance.hide();
        editModalInstance = null;
    } else if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        try {
            const modal = new bootstrap.Modal(modalElement);
            modal.hide();
        } catch (e) {}
    }
    modalElement.classList.remove('show');
    modalElement.style.display = 'none';
    modalElement.setAttribute('aria-hidden', 'true');
    modalElement.removeAttribute('aria-modal');
    modalElement.removeAttribute('role');
    document.body.classList.remove('modal-open');
    document.body.style.removeProperty('padding-right');
    const backdrop = document.getElementById('editModalFallbackBackdrop');
    if (backdrop) backdrop.remove();
}

document.getElementById('editCloseButton').addEventListener('click', function (e) {
    e.preventDefault();
    closeEditModal();
});
document.getElementById('editCancelButton').addEventListener('click', function (e) {
    e.preventDefault();
    closeEditModal();
});
document.getElementById('editBudgetModal').addEventListener('hidden.bs.modal', function () {
    const form = this.querySelector('form');
    if (form) form.reset();
    document.getElementById('edit_preview').src = '';
    document.getElementById('edit_preview_wrap').style.display = 'none';
    editModalInstance = null;
});

/* =========================================================
   IMAGE VIEWER
========================================================= */
function openImageViewer(src, title) {
    const index = imageGallery.findIndex(function (img) {
        return img.src === src;
    });
    if (index !== -1) {
        currentIndex = index;
    } else {
        imageGallery = [{ src: src, title: title }];
        currentIndex = 0;
    }
    updateViewerImage();
    const modalElement = document.getElementById('imageViewerModal');
    if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
        try {
            imageModalInstance = new bootstrap.Modal(modalElement, {
                backdrop: true,
                keyboard: true
            });
            imageModalInstance.show();
        } catch (error) {
            console.error(error);
        }
    }
}
function closeImageViewer() {
    if (imageModalInstance) {
        imageModalInstance.hide();
        imageModalInstance = null;
    }
}
function updateViewerImage() {
    if (!imageGallery.length) return;
    const img = document.getElementById('viewerImage');
    const prev = document.getElementById('prevImageBtn');
    const next = document.getElementById('nextImageBtn');
    const counter = document.getElementById('imageCounter');
    img.src = imageGallery[currentIndex].src;
    img.alt = imageGallery[currentIndex].title;
    document.getElementById('currentImageIndex').textContent = currentIndex + 1;
    document.getElementById('totalImages').textContent = imageGallery.length;
    if (imageGallery.length <= 1) {
        prev.style.display = 'none';
        next.style.display = 'none';
        counter.style.display = 'none';
    } else {
        prev.style.display = currentIndex === 0 ? 'none' : 'block';
        next.style.display = currentIndex === imageGallery.length - 1 ? 'none' : 'block';
        counter.style.display = 'block';
    }
}
function nextImage() {
    if (currentIndex < imageGallery.length - 1) {
        currentIndex++;
        updateViewerImage();
    }
}
function prevImage() {
    if (currentIndex > 0) {
        currentIndex--;
        updateViewerImage();
    }
}

document.addEventListener('keydown', function (e) {
    const edit = document.getElementById('editBudgetModal');
    const viewer = document.getElementById('imageViewerModal');
    const del = document.getElementById('deleteBudgetModal');
    if (e.key === 'Escape') {
        if (del && del.classList.contains('open')) {
            closeDeleteBudgetModal();
            return;
        }
        if (edit.classList.contains('show')) {
            closeEditModal();
            return;
        }
        if (viewer.classList.contains('show')) {
            closeImageViewer();
            return;
        }
    }
    if (viewer.classList.contains('show')) {
        if (e.key === 'ArrowRight') nextImage();
        if (e.key === 'ArrowLeft') prevImage();
    }
});

document.getElementById('imageViewerModal').addEventListener('hidden.bs.modal', function () {
    document.getElementById('viewerImage').src = '';
    currentIndex = 0;
    imageModalInstance = null;
});
document.getElementById('imageViewerModal').addEventListener('click', function (e) {
    if (e.target === this) closeImageViewer();
});

/* =========================================================
   DELETE BUDGET MODAL
========================================================= */
function openDeleteBudgetModal(id, preview) {
    document.getElementById('deleteBudgetId').value = id;
    document.getElementById('deleteBudgetPreview').textContent = preview || 'Budget item';
    document.getElementById('deleteBudgetModal').classList.add('open');
}
function closeDeleteBudgetModal() {
    const modal = document.getElementById('deleteBudgetModal');
    modal.classList.remove('open');
    modal.style.display = '';
}
document.getElementById('deleteBudgetModal').addEventListener('click', function (e) {
    if (e.target === this) closeDeleteBudgetModal();
});
</script>

<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>