
<?php include(ROOT_PATH . '/dashboard_sidebar_start.php'); ?>

<style>
    .input-icons i {
        position: absolute;
    }
    .input-icons {
        width: 30%;
        margin-bottom: 10px;
        margin-left: 34%;
    }
    .icon {
        padding: 10px;
        min-width: 40px;
    }
    .form-control {
        text-align: center;
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
    #page-loader.show { display: flex; }
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

<!-- Begin Page Content -->
<div class="container-fluid">
    <div class="row">
        <div class="col text-center">
            <h1>Barangay Household Table</h1>
        </div>
    </div>
    <hr>
    <br><br>

    <div class="row">
        <div class="col">
            <form method="POST" action="" class="show-loader" data-loader-msg="Searching households...">
                <div class="input-icons">
                    <i class="fa fa-search icon"></i>
                    <input type="search" class="form-control" name="keyword" value="" required style="border-radius: 30px;"/>
                </div>
                <input type="hidden" name="search_household" value="1">
                <button class="btn btn-success" name="search_household" style="width: 90px; font-size: 18px; border-radius:30px; margin-left:41.5%;">Search</button>
                <a href="admn_table_totalhouse.php" class="btn btn-info" style="width: 90px; font-size: 18px; border-radius:30px;">Reload</a>
            </form>
            <br>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <?php include(ROOT_PATH . '/admn_table_totalhouse_search.php'); ?>
        </div>
        <div class="col-md-3"></div>
    </div>
</div>

<!-- FAMILY MEMBERS VIEW MODAL -->
<div class="modal fade" id="familyMembersModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-users mr-2"></i>Family Members — <span id="familyModalHouseholdName"></span></h5>
                <button type="button" class="btn-close" onclick="closeFamilyModal()"></button>
            </div>
            <div class="modal-body" id="familyModalBody" style="min-height:120px;">
                <div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin"></i> Loading...</div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script>
function showLoader(msg) {
    const loader = document.getElementById('page-loader');
    const text   = document.getElementById('loader-msg');
    if (text) text.textContent = msg || 'Please wait...';
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
document.querySelectorAll('form.show-loader').forEach(function (form) {
    form.addEventListener('submit', function () {
        const msg = this.dataset.loaderMsg || 'Please wait...';
        showLoader(msg);
        if (loaderTimeout) clearTimeout(loaderTimeout);
        loaderTimeout = setTimeout(hideLoader, 12000);
        const btn = this.querySelector('button[type="submit"], input[type="submit"], button.btn-success');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = 'Searching...';
        }
    });
});

function openFamilyModal(idResident, name) {
    document.getElementById('familyModalHouseholdName').textContent = name;
    var body = document.getElementById('familyModalBody');
    body.innerHTML = '<div class="text-center text-muted py-4"><i class="fas fa-spinner fa-spin"></i> Loading...</div>';
    var modalEl = document.getElementById('familyMembersModal');
    modalEl.style.display = 'block';
    modalEl.classList.add('show');
    modalEl.removeAttribute('aria-hidden');
    modalEl.setAttribute('aria-modal', 'true');
    document.body.classList.add('modal-open');
    if (!document.getElementById('familyModalBackdrop')) {
        var backdrop = document.createElement('div');
        backdrop.className = 'modal-backdrop fade show';
        backdrop.id = 'familyModalBackdrop';
        backdrop.onclick = closeFamilyModal;
        document.body.appendChild(backdrop);
    }
    fetch('ajax_view_family_members.php?id_resident=' + encodeURIComponent(idResident))
        .then(function(res) { return res.text(); })
        .then(function(html) { body.innerHTML = html; })
        .catch(function() {
            body.innerHTML = '<div class="text-danger text-center py-3">Failed to load family members. Please try again.</div>';
        });
}
function closeFamilyModal() {
    var modalEl = document.getElementById('familyMembersModal');
    modalEl.classList.remove('show');
    modalEl.style.display = 'none';
    modalEl.setAttribute('aria-hidden', 'true');
    modalEl.removeAttribute('aria-modal');
    document.body.classList.remove('modal-open');
    var backdrop = document.getElementById('familyModalBackdrop');
    if (backdrop) backdrop.remove();
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeFamilyModal();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>