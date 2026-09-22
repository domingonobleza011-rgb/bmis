
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration - Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    
    <style>
        body {
            background: #f0f2f5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            padding-bottom: 2rem;
        }
        .hero-registration {
            background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%);
            padding: 2.5rem 1.5rem;
            border-radius: 0 0 40px 40px;
            color: #fff;
            margin-bottom: 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .hero-registration::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 60%;
            height: 200%;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
            pointer-events: none;
        }
        .hero-registration .hero-content { position: relative; z-index: 1; }
        .hero-registration h1 {
            font-size: 2.2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }
        .hero-registration p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        .hero-registration .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            padding: 0.3rem 1.2rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
            letter-spacing: 0.5px;
        }
        @media (max-width: 576px) {
            .hero-registration {
                padding: 1.8rem 1.2rem;
                border-radius: 0 0 24px 24px;
            }
            .hero-registration h1 { font-size: 1.6rem; }
        }
        .form-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            padding: 2.5rem;
            max-width: 900px;
            margin: 0 auto;
        }
        @media (max-width: 576px) {
            .form-card {
                padding: 1.25rem 1rem;
                border-radius: 16px;
            }
        }
        .form-label {
            font-weight: 600;
            font-size: 0.72rem;
            color: #4b4f56;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.25rem;
        }
        .form-control, .form-select {
            border-radius: 10px;
            border: 1.5px solid #dee2e6;
            padding: 0.6rem 1rem;
            font-size: 0.92rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafbfc;
            height: auto;
        }
        .form-control:focus, .form-select:focus {
            border-color: #1b74e4;
            box-shadow: 0 0 0 3px rgba(27, 116, 228, 0.12);
            background: #fff;
        }
        .form-control.is-valid, .form-select.is-valid { border-color: #198754; }
        .form-control.is-invalid, .form-select.is-invalid { border-color: #dc3545; }
        /* Names display as uppercase while typing */
        .name-uppercase {
            text-transform: uppercase;
        }
        .section-title {
            font-size: 0.8rem;
            font-weight: 700;
            color: #1b74e4;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 0.5rem;
        }
        .section-title i { font-size: 1rem; color: #1b74e4; }
        .section-divider {
            border: 0;
            border-top: 2px solid #e4e6ea;
            margin: 0.25rem 0 1.25rem;
            opacity: 1;
        }
        .password-wrapper { position: relative; }
        .password-wrapper .toggle-password {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #8a8f9a;
            z-index: 10;
            background: none;
            border: none;
            font-size: 1rem;
        }
        .password-wrapper .toggle-password:hover { color: #1b74e4; }
        .strength-bar {
            height: 4px;
            border-radius: 4px;
            transition: width 0.3s ease;
        }
        .strength-text {
            font-size: 0.75rem;
            color: #8a8f9a;
        }
        .btn-submit {
            background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%);
            color: #fff;
            padding: 0.7rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.95rem;
            border: none;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(27, 116, 228, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(27, 116, 228, 0.35);
            color: #fff;
        }
        .btn-back {
            border-radius: 50px;
            padding: 0.7rem 1.8rem;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1.5px solid #dee2e6;
            color: #65676b;
            background: #fff;
            transition: all 0.2s ease;
        }
        .btn-back:hover {
            background: #f0f2f5;
            border-color: #bcc0c4;
            color: #1c1e21;
        }
        .btn-group-toggle .btn {
            border-radius: 8px;
            font-size: 0.85rem;
            padding: 0.4rem 1.2rem;
            font-weight: 600;
            border: 1.5px solid #dee2e6;
            color: #65676b;
            background: #fff;
        }
        .btn-group-toggle .btn.active {
            background: #1b74e4;
            color: #fff;
            border-color: #1b74e4;
        }
        #idCameraLive video {
            max-height: 360px;
            object-fit: cover;
            border-radius: 12px;
            background: #1a1a2e;
            width: 100%;
        }
        #idCameraPreview {
            max-height: 360px;
            object-fit: contain;
            border-radius: 12px;
            border: 1px solid #dee2e6;
            width: 100%;
        }
        .camera-error {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.5rem;
        }

        /* ----- UPLOAD AREA (dashed drop-zone style) ----- */
        .upload-area {
            position: relative;
            overflow: hidden;
            border: 2px dashed #dce0e4;
            border-radius: 14px;
            padding: 1.75rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.25s ease;
            background: #fafbfc;
        }
        .upload-area:hover {
            border-color: #1b74e4;
            background: #f0f7ff;
        }
        .upload-area i {
            font-size: 2.2rem;
            color: #8a8f9a;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.25s;
        }
        .upload-area:hover i {
            color: #1b74e4;
        }
        .upload-area .file-name {
            font-size: 0.85rem;
            color: #65676b;
            margin-bottom: 0;
        }
        .upload-area .file-name.has-file {
            color: #1b74e4;
            font-weight: 600;
        }
        /* Real file input stays "rendered" (opacity 0, not display:none) so
           native required-field validation still applies to it. */
        .upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        .modal-header-custom {
            background: linear-gradient(135deg, #1b74e4, #0a5ecf);
            color: #fff;
            border: none;
        }
        .modal-header-custom .btn-close { filter: invert(1); }
        @media (max-width: 576px) {
            .form-card { padding: 1rem 0.9rem; }
            .btn-submit, .btn-back {
                font-size: 0.85rem;
                padding: 0.6rem 1.2rem;
                width: 100%;
            }
        }
        @media (max-width: 768px) {
            .d-flex.justify-content-end {
                flex-direction: column-reverse;
                gap: 0.5rem;
            }
            .d-flex.justify-content-end .btn { width: 100%; }
        }
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
</head>
<body>
<div class="hero-registration">
    <div class="hero-content">
        <h1>Resident Registration</h1>
        <p>Create your account to access barangay services</p>
        <span class="hero-badge"><i class="bi bi-building me-1"></i> Barangay San Pedro, Iriga City</span>
    </div>
</div>

<div class="container">
    <div class="form-card">
        <form method="post" enctype="multipart/form-data" class="was-validated show-loader" data-loader-msg="Submitting registration..." id="registrationForm">
            <div class="section-title">
                <i class="bi bi-person-fill"></i> Personal Information
            </div>
            <hr class="section-divider">
            <div class="row g-3">
                <div class="col-md-4 col-12">
    <label class="form-label">Last Name</label>
    <input type="text"
           class="form-control name-uppercase"
           name="lname"
           id="lname"
           placeholder="Enter Last Name"
           required
           autocomplete="family-name"
           pattern="^[A-Za-zÑñ\s\-']+$"
           title="Letters only. No numbers or symbols."
           inputmode="text">
</div>
<div class="col-md-4 col-12">
    <label class="form-label">First Name</label>
    <input type="text"
           class="form-control name-uppercase"
           name="fname"
           id="fname"
           placeholder="Enter First Name"
           required
           autocomplete="given-name"
           pattern="^[A-Za-zÑñ\s\-']+$"
           title="Letters only. No numbers or symbols."
           inputmode="text">
</div>
<div class="col-md-4 col-12">
    <label class="form-label">Middle Name</label>
    <input type="text"
           class="form-control name-uppercase"
           name="mi"
           id="mi"
           placeholder="Enter Middle Name"
           required
           autocomplete="additional-name"
           pattern="^[A-Za-zÑñ\s\-']+$"
           title="Letters only. No numbers or symbols."
           inputmode="text">
</div>
            </div>

            <div class="section-title mt-4">
                <i class="bi bi-key-fill"></i> Account Credentials
            </div>
            <hr class="section-divider">
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label">Email Address</label>
                    <input type="email"
                           class="form-control"
                           name="login_identity"
                           id="login_identity"
                           required
                           pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
                           title="Please enter a valid email address (must include @)">
                    <div class="invalid-feedback">
                        Please enter a valid email address or phone number
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <label class="form-label">Password</label>
                    <div class="password-wrapper">
                        <input type="password" class="form-control" id="password-field" name="password" placeholder="Enter Password" required style="padding-right: 40px;">
                        <button type="button" class="toggle-password" toggle="#password-field">
                            <i class="fa-regular fa-eye"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <div class="strength-bar" id="strength-bar" style="width:0%; background:#dc3545;"></div>
                        <small id="strength-text" class="strength-text">Password strength</small>
                    </div>
                </div>
            </div>

            <div class="section-title mt-4">
                <i class="bi bi-geo-alt-fill"></i> Address Information
            </div>
            <hr class="section-divider">
            <div class="row g-3">
                <div class="col-md-6 col-12">
                    <label class="form-label">House No.</label>
                    <input type="text" class="form-control" name="houseno" placeholder="Enter House No." required>
                </div>
                <div class="col-md-6 col-12">
                    <label class="form-label">Street</label>
                    <input type="text" class="form-control" name="street" placeholder="Enter Street" required>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-3 col-6">
                    <label class="form-label">Region</label>
                    <select class="form-select" id="regionSelect" name="region" required>
                        <option value="">Loading regions...</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Province</label>
                    <select class="form-select" id="provinceSelect" name="province" required disabled>
                        <option value="">Select Region first</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">City/Municipality</label>
                    <select class="form-select" id="citymunSelect" name="municipal" required disabled>
                        <option value="">Select Province first</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Barangay</label>
                    <select class="form-select" id="barangaySelect" name="brgy" required disabled>
                        <option value="">Select City first</option>
                    </select>
                </div>
                <input type="hidden" id="regionCode" name="region_code">
                <input type="hidden" id="provinceCode" name="province_code">
                <input type="hidden" id="citymunCode" name="municipal_code">
                <input type="hidden" id="barangayCode" name="brgy_code">
            </div>

            <div class="section-title mt-4">
                <i class="bi bi-calendar-event-fill"></i> Birth &amp; Demographics
            </div>
            <hr class="section-divider">
            <div class="row g-3 mt-1">
                <div class="col-md-3 col-6">
                    <label class="form-label">Birth Date</label>
                    <input type="date" class="form-control" name="bdate" required>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Birth Place</label>
                    <input type="text" class="form-control" name="bplace" placeholder="City, Province" required>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Nationality</label>
                    <input type="text" class="form-control" name="nationality" placeholder="e.g. Filipino" required>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Head of the Family?</label>
                    <select class="form-select" name="family_role" required>
                        <option value="">Choose...</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>
            <div class="row g-3 mt-1">
                <div class="col-md-3 col-6">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" required>
                        <option value="">Choose...</option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Widowed">Widowed</option>
                        <option value="Divorced">Divorced</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">PWD?</label>
                    <select class="form-select" name="pwd" required>
                        <option value="">Choose...</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Sex</label>
                    <select class="form-select" name="sex" required>
                        <option value="">Choose...</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Registered Voter?</label>
                    <select class="form-select" name="voter" required>
                        <option value="">Choose...</option>
                        <option value="Yes">Yes</option>
                        <option value="No">No</option>
                    </select>
                </div>
            </div>

            <div class="section-title mt-4">
                <i class="bi bi-card-image"></i> Valid ID Upload
            </div>
            <hr class="section-divider">
            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Upload Valid ID (Government-issued)</label>
                    <div class="btn-group btn-group-toggle mb-2" role="group">
                        <button type="button" id="idModeUploadBtn" class="btn active">
                            <i class="bi bi-upload me-1"></i> Upload File
                        </button>
                        <button type="button" id="idModeCameraBtn" class="btn">
                            <i class="bi bi-camera me-1"></i> Take Photo
                        </button>
                    </div>
                    <div id="idUploadPanel">
                        <div class="upload-area">
                            <input type="file" id="valid_id_file_input" name="valid_id_file"
                                   accept=".jpg,.jpeg,.png,.pdf" required onchange="updateIdFileName(this)">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="file-name" id="idFileName">Click to upload your valid ID</p>
                            <small class="text-muted">JPG, PNG, or PDF &bull; Max 5MB</small>
                        </div>
                    </div>
                    <div id="idCameraPanel" class="d-none">
                        <div id="idCameraLive">
                            <video id="idVideo" autoplay playsinline muted></video>
                            <div class="mt-2 d-flex gap-2">
                                <button type="button" id="idCaptureBtn" class="btn btn-primary btn-sm">
                                    <i class="bi bi-camera me-1"></i> Capture Photo
                                </button>
                            </div>
                            <div id="idCameraError" class="camera-error d-none"></div>
                        </div>
                        <div id="idCameraPreviewWrap" class="d-none">
                            <img id="idCameraPreview" alt="Captured ID preview">
                            <div class="mt-2">
                                <button type="button" id="idRetakeBtn" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Retake
                                </button>
                            </div>
                        </div>
                        <canvas id="idCanvas" class="d-none"></canvas>
                    </div>

                </div>
            </div>

            <hr class="section-divider mt-4">
            <div class="row g-3">
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="termsCheck" required>
                        <label class="form-check-label" for="termsCheck">
                            I agree to the 
                            <a href="#" data-bs-toggle="modal" data-bs-target="#termsModal" style="text-decoration:none; color:#1b74e4; font-weight:600;">
                                Terms and Conditions
                            </a>
                        </label>
                        <div class="invalid-feedback">You must agree before submitting.</div>
                    </div>
                </div>
            </div>

            <hr class="section-divider mt-3">
            <div class="d-flex justify-content-end align-items-center gap-3 flex-wrap">
                <input type="hidden" name="role" value="resident">
                <input type="hidden" name="add_resident" value="1">
                <a class="btn btn-back" href="login.php">
                    <i class="bi bi-arrow-left me-1"></i> Back to Login
                </a>
                <button class="btn-submit" type="submit" name="add_resident">
                    <i class="bi bi-check-circle me-1"></i> Submit Registration
                </button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title" id="termsModalLabel">
                    <i class="bi bi-file-text me-2"></i> Terms and Conditions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6><strong>1. Data Privacy Act of 2012</strong></h6>
                <p>By registering, you allow Barangay San Pedro to collect and process your personal information in accordance with the Data Privacy Act.</p>
                <h6><strong>2. Accuracy of Information</strong></h6>
                <p>You certify that all information provided is true and correct.</p>
                <h6><strong>3. Usage Policy</strong></h6>
                <p>This account is for the exclusive use of the registered resident.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="document.getElementById('termsCheck').checked = true;">
                    I Understand
                </button>
            </div>
        </div>
    </div>
</div>

<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
['lname', 'fname', 'mi'].forEach(function (id) {
    var el = document.getElementById(id);
    if (!el) return;

    el.addEventListener('input', function () {
        var start = this.selectionStart;
        var end = this.selectionEnd;
        // Strip numbers & symbols; keep letters, spaces, hyphen, apostrophe, Ñ/ñ
        var cleaned = this.value.replace(/[^A-Za-zÑñ\s\-']/g, '');
        this.value = cleaned.toUpperCase();
        // Keep cursor near the end after cleanup
        var pos = Math.min(start, this.value.length);
        this.setSelectionRange(pos, pos);
    });

    el.addEventListener('blur', function () {
        this.value = this.value.trim().replace(/\s+/g, ' ').toUpperCase();
        if (!/^[A-Za-zÑñ\s\-']+$/.test(this.value) && this.value !== '') {
            this.setCustomValidity('Letters only. No numbers or symbols.');
        } else {
            this.setCustomValidity('');
        }
    });
});

// ── Email (login_identity) must contain @ ────────────────────────────
var loginIdentity = document.getElementById('login_identity');
function validateEmailIdentity() {
    var val = (loginIdentity.value || '').trim();
    var ok = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val);
    if (!ok) {
        loginIdentity.setCustomValidity('Please enter a valid email address (must include @).');
        loginIdentity.classList.add('is-invalid');
        loginIdentity.classList.remove('is-valid');
    } else {
        loginIdentity.setCustomValidity('');
        loginIdentity.classList.remove('is-invalid');
        loginIdentity.classList.add('is-valid');
    }
    return ok;
}
if (loginIdentity) {
    loginIdentity.addEventListener('input', validateEmailIdentity);
    loginIdentity.addEventListener('blur', validateEmailIdentity);
}

// ── Loader ───────────────────────────────────────────────────────────
function showLoader(msg) {
    var loader = document.getElementById('page-loader');
    var text   = document.getElementById('loader-msg');
    if (text) text.textContent = msg || 'Please wait...';
    if (loader) loader.classList.add('show');
}
function hideLoader() {
    var loader = document.getElementById('page-loader');
    if (loader) loader.classList.remove('show');
}
window.addEventListener('pageshow', hideLoader);

var loaderTimeout = null;
document.querySelectorAll('form.show-loader').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        // Force uppercase names before submit
        ['lname', 'fname', 'mi'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el) el.value = (el.value || '').trim().toUpperCase();
        });
        validateEmailIdentity();
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
            return;
        }
        showLoader(form.dataset.loaderMsg || 'Submitting registration...');
        if (loaderTimeout) clearTimeout(loaderTimeout);
        loaderTimeout = setTimeout(hideLoader, 15000);
        var btn = form.querySelector('button[type="submit"], .btn-submit');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Submitting...';
        }
    });
});

// ── Password Toggle ──────────────────────────────────────────────────
document.querySelectorAll('.toggle-password').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var input = document.querySelector(this.getAttribute('toggle'));
        var icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-regular fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fa-regular fa-eye';
        }
    });
});

// ── Password Strength ────────────────────────────────────────────────
document.getElementById('password-field').addEventListener('input', function () {
    var password = this.value;
    var bar = document.getElementById('strength-bar');
    var text = document.getElementById('strength-text');
    var strength = 0;
    if (password.length >= 8) strength++;
    if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
    if (password.match(/\d/)) strength++;
    if (password.match(/[^a-zA-Z\d]/)) strength++;
    var colors = ['#dc3545', '#dc3545', '#f59f00', '#fd7e14', '#198754', '#198754'];
    var labels = ['', 'Weak', 'Fair', 'Good', 'Strong', 'Very Strong'];
    var pct = [0, 25, 50, 75, 100, 100];
    bar.style.width = pct[strength] + '%';
    bar.style.background = colors[strength];
    text.innerHTML = strength > 0
        ? 'Strength: <span style="color:' + colors[strength] + ';font-weight:600;">' + labels[strength] + '</span>'
        : 'Password strength';
});

// ── PSGC Cascading Address ───────────────────────────────────────────
(function () {
    var PSGC_API = 'https://psgc.cloud/api/v2';
    var regionSelect = document.getElementById('regionSelect');
    var provinceSelect = document.getElementById('provinceSelect');
    var citymunSelect = document.getElementById('citymunSelect');
    var barangaySelect = document.getElementById('barangaySelect');
    var regionCode = document.getElementById('regionCode');
    var provinceCode = document.getElementById('provinceCode');
    var citymunCode = document.getElementById('citymunCode');
    var barangayCode = document.getElementById('barangayCode');
    function resetSelect(select, placeholder, disabled) {
        if (disabled === undefined) disabled = true;
        select.innerHTML = '<option value="">' + placeholder + '</option>';
        select.disabled = disabled;
    }
    function fillSelect(select, items, placeholder) {
        select.innerHTML = '<option value="">' + placeholder + '</option>';
        items.sort(function (a, b) { return a.name.localeCompare(b.name); }).forEach(function (item) {
            var opt = document.createElement('option');
            opt.value = item.name;
            opt.dataset.code = item.code;
            opt.textContent = item.name;
            select.appendChild(opt);
        });
        select.disabled = false;
    }
    async function fetchJSON(url) {
        var res = await fetch(url);
        if (!res.ok) throw new Error('PSGC API request failed');
        var data = await res.json();
        return Array.isArray(data) ? data : (data.data || []);
    }
    fetchJSON(PSGC_API + '/regions').then(function (regions) {
        fillSelect(regionSelect, regions, 'Select Region');
    }).catch(function () {
        resetSelect(regionSelect, 'Unable to load regions');
    });
    regionSelect.addEventListener('change', function () {
        var opt = this.selectedOptions[0];
        regionCode.value = opt ? (opt.dataset.code || '') : '';
        resetSelect(provinceSelect, 'Select Region first');
        resetSelect(citymunSelect, 'Select Province first');
        resetSelect(barangaySelect, 'Select City first');
        provinceCode.value = '';
        citymunCode.value = '';
        barangayCode.value = '';
        if (!regionCode.value) return;
        fetchJSON(PSGC_API + '/regions/' + regionCode.value + '/provinces').then(function (provinces) {
            if (provinces.length > 0) {
                fillSelect(provinceSelect, provinces, 'Select Province');
            } else {
                resetSelect(provinceSelect, 'Not applicable', true);
                fetchJSON(PSGC_API + '/regions/' + regionCode.value + '/cities-municipalities')
                    .then(function (citymuns) { fillSelect(citymunSelect, citymuns, 'Select City/Municipality'); });
            }
        }).catch(function () { resetSelect(provinceSelect, 'Unable to load provinces'); });
    });
    provinceSelect.addEventListener('change', function () {
        var opt = this.selectedOptions[0];
        provinceCode.value = opt ? (opt.dataset.code || '') : '';
        resetSelect(citymunSelect, 'Select Province first');
        resetSelect(barangaySelect, 'Select City first');
        citymunCode.value = '';
        barangayCode.value = '';
        if (!provinceCode.value) return;
        fetchJSON(PSGC_API + '/provinces/' + provinceCode.value + '/cities-municipalities')
            .then(function (citymuns) { fillSelect(citymunSelect, citymuns, 'Select City/Municipality'); })
            .catch(function () { resetSelect(citymunSelect, 'Unable to load cities'); });
    });
    citymunSelect.addEventListener('change', function () {
        var opt = this.selectedOptions[0];
        citymunCode.value = opt ? (opt.dataset.code || '') : '';
        resetSelect(barangaySelect, 'Select City first');
        barangayCode.value = '';
        if (!citymunCode.value) return;
        fetchJSON(PSGC_API + '/cities-municipalities/' + citymunCode.value + '/barangays')
            .then(function (barangays) { fillSelect(barangaySelect, barangays, 'Select Barangay'); })
            .catch(function () { resetSelect(barangaySelect, 'Unable to load barangays'); });
    });
    barangaySelect.addEventListener('change', function () {
        var opt = this.selectedOptions[0];
        barangayCode.value = opt ? (opt.dataset.code || '') : '';
    });
})();

// ── Camera / Upload Toggle ───────────────────────────────────────────
(function () {
    function updateIdFileName(input) {
        var fileName = document.getElementById('idFileName');
        if (!fileName) return;
        if (input.files.length > 0) {
            fileName.innerText = '📎 ' + input.files[0].name;
            fileName.classList.add('has-file');
        } else {
            fileName.innerText = 'Click to upload your valid ID';
            fileName.classList.remove('has-file');
        }
    }
    window.updateIdFileName = updateIdFileName;

    var uploadBtn = document.getElementById('idModeUploadBtn');
    var cameraBtn = document.getElementById('idModeCameraBtn');
    var uploadPanel = document.getElementById('idUploadPanel');
    var cameraPanel = document.getElementById('idCameraPanel');
    var fileInput = document.getElementById('valid_id_file_input');
    var video = document.getElementById('idVideo');
    var canvas = document.getElementById('idCanvas');
    var liveWrap = document.getElementById('idCameraLive');
    var previewWrap = document.getElementById('idCameraPreviewWrap');
    var previewImg = document.getElementById('idCameraPreview');
    var captureBtn = document.getElementById('idCaptureBtn');
    var retakeBtn = document.getElementById('idRetakeBtn');
    var cameraError = document.getElementById('idCameraError');
    var stream = null;
    function stopStream() {
        if (stream) { stream.getTracks().forEach(function (t) { t.stop(); }); stream = null; }
    }
    function showError(msg) {
        cameraError.textContent = msg;
        cameraError.classList.remove('d-none');
    }
    async function startCamera() {
        cameraError.classList.add('d-none');
        previewWrap.classList.add('d-none');
        liveWrap.classList.remove('d-none');
        if (!window.isSecureContext) {
            showError('Camera access requires HTTPS. Please use Upload File instead.');
            return;
        }
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            showError('Camera not supported. Please use Upload File.');
            return;
        }
        try {
            stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: 'environment' } } });
            video.srcObject = stream;
        } catch (err) {
            if (err.name === 'NotAllowedError') {
                showError('Camera permission denied. Please allow camera access or use Upload File.');
            } else if (err.name === 'NotFoundError') {
                showError('No camera found. Please use Upload File.');
            } else {
                showError('Could not access camera. Please use Upload File.');
            }
        }
    }
    uploadBtn.addEventListener('click', function () {
        uploadBtn.classList.add('active');
        cameraBtn.classList.remove('active');
        uploadPanel.classList.remove('d-none');
        cameraPanel.classList.add('d-none');
        stopStream();
    });
    cameraBtn.addEventListener('click', function () {
        cameraBtn.classList.add('active');
        uploadBtn.classList.remove('active');
        cameraPanel.classList.remove('d-none');
        uploadPanel.classList.add('d-none');
        startCamera();
    });
    captureBtn.addEventListener('click', function () {
        if (!stream) return;
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);
        canvas.toBlob(function (blob) {
            if (!blob) return;
            var file = new File([blob], 'valid_id_capture_' + Date.now() + '.jpg', { type: 'image/jpeg' });
            var dt = new DataTransfer();
            dt.items.add(file);
            fileInput.files = dt.files;
            updateIdFileName(fileInput);
            previewImg.src = URL.createObjectURL(blob);
            liveWrap.classList.add('d-none');
            previewWrap.classList.remove('d-none');
            stopStream();
        }, 'image/jpeg', 0.92);
    });
    retakeBtn.addEventListener('click', function () {
        fileInput.value = '';
        updateIdFileName(fileInput);
        previewWrap.classList.add('d-none');
        liveWrap.classList.remove('d-none');
        startCamera();
    });
    window.addEventListener('beforeunload', stopStream);
})();

// ── Draft Autosave ───────────────────────────────────────────────────
(function () {
    var DRAFT_KEY = 'bmis_resident_registration_draft';
    var form = document.querySelector('form');
    if (!form) return;
    var TEXT_FIELDS = ['lname', 'fname', 'mi', 'login_identity', 'houseno', 'street', 'bdate', 'bplace', 'nationality'];
    var SELECT_FIELDS = ['status', 'pwd', 'sex', 'voter', 'family_role'];
    var ADDRESS_SELECTS = [
        { select: 'regionSelect', code: 'regionCode' },
        { select: 'provinceSelect', code: 'provinceCode' },
        { select: 'citymunSelect', code: 'citymunCode' },
        { select: 'barangaySelect', code: 'barangayCode' },
    ];
    function readDraft() {
        try { return JSON.parse(localStorage.getItem(DRAFT_KEY) || 'null'); } catch (e) { return null; }
    }
    function saveDraft() {
        var draft = { fields: {}, selects: {}, address: {}, terms: false };
        TEXT_FIELDS.forEach(function (name) {
            var el = form.querySelector('[name="' + name + '"]');
            if (el) draft.fields[name] = el.value;
        });
        SELECT_FIELDS.forEach(function (name) {
            var el = form.querySelector('[name="' + name + '"]');
            if (el) draft.selects[name] = el.value;
        });
        ADDRESS_SELECTS.forEach(function (item) {
            var selEl = document.getElementById(item.select);
            var codeEl = document.getElementById(item.code);
            if (selEl) draft.address[item.select] = { value: selEl.value, code: codeEl ? codeEl.value : '' };
        });
        var terms = document.getElementById('termsCheck');
        draft.terms = terms ? terms.checked : false;
        try { localStorage.setItem(DRAFT_KEY, JSON.stringify(draft)); } catch (e) {}
    }
    function clearDraft() { try { localStorage.removeItem(DRAFT_KEY); } catch (e) {} }
    function restoreDraft() {
        var draft = readDraft();
        if (!draft) return;
        TEXT_FIELDS.forEach(function (name) {
            var el = form.querySelector('[name="' + name + '"]');
            if (el && draft.fields[name]) el.value = draft.fields[name];
        });
        SELECT_FIELDS.forEach(function (name) {
            var el = form.querySelector('[name="' + name + '"]');
            if (el && draft.selects[name]) el.value = draft.selects[name];
        });
        var terms = document.getElementById('termsCheck');
        if (terms) terms.checked = !!draft.terms;
        // Uppercase restored names
        ['lname', 'fname', 'mi'].forEach(function (id) {
            var el = document.getElementById(id);
            if (el && el.value) el.value = el.value.toUpperCase();
        });
    }
    var t;
    function debouncedSave() {
        clearTimeout(t);
        t = setTimeout(saveDraft, 400);
    }
    TEXT_FIELDS.concat(SELECT_FIELDS).forEach(function (name) {
        var el = form.querySelector('[name="' + name + '"]');
        if (el) el.addEventListener('input', debouncedSave);
    });
    ADDRESS_SELECTS.forEach(function (item) {
        var el = document.getElementById(item.select);
        if (el) el.addEventListener('change', debouncedSave);
    });
    var termsCheckbox = document.getElementById('termsCheck');
    if (termsCheckbox) termsCheckbox.addEventListener('change', saveDraft);
    restoreDraft();
})();
</script>
</body>
</html>