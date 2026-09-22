
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Resident Profile | Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* ----- GLOBAL RESETS ----- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        html, body {
            height: 100%;
            min-height: 100vh;
            background: #f0f2f5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        body {
            display: flex;
            flex-direction: column;
            padding-bottom: 85px;
        }
        @media (min-width: 768px) {
            body { padding-bottom: 0; }
        }
        /* ----- PAGE WRAPPER - FULL SCREEN ----- */
        .page-wrapper {
            flex: 1;
            width: 100%;
            max-width: 900px;
            margin: 0 auto;
            padding: 0 16px 2rem;
            display: flex;
            flex-direction: column;
        }
        /* ----- HERO BANNER ----- */
        .hero-profile {
            background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%);
            padding: 2rem 1.5rem;
            border-radius: 0 0 40px 40px;
            color: #fff;
            margin-bottom: 1.5rem;
            text-align: center;
            width: 100%;
            flex-shrink: 0;
        }
        .hero-profile i {
            font-size: 2.5rem;
            display: block;
            margin-bottom: 0.5rem;
        }
        .hero-profile h1 {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 0.25rem;
        }
        .hero-profile p {
            opacity: 0.9;
            margin-bottom: 0;
            font-size: 0.95rem;
        }
        @media (max-width: 576px) {
            .hero-profile {
                padding: 1.5rem 1rem;
                border-radius: 0 0 24px 24px;
            }
            .hero-profile h1 {
                font-size: 1.5rem;
            }
            .hero-profile i {
                font-size: 2rem;
            }
        }
        /* ----- VERIFICATION BANNER ----- */
        .verify-banner {
            border-left: 5px solid #ffc107;
            border-radius: 16px;
            padding: 0.75rem 1.25rem;
            margin-bottom: 1.25rem;
            background: #fff3cd;
            flex-shrink: 0;
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .verify-banner .btn-upload-sm {
            background: #ffc107;
            border-radius: 40px;
            font-weight: 600;
            padding: 0.3rem 1.2rem;
            font-size: 0.85rem;
            color: #1c1e21;
            border: none;
            transition: background 0.2s;
            text-decoration: none;
            white-space: nowrap;
        }
        .verify-banner .btn-upload-sm:hover {
            background: #e0a800;
        }
        .verify-banner .verify-icon {
            font-size: 1.8rem;
            flex-shrink: 0;
        }
        .verify-banner .verify-text h6 {
            font-weight: 700;
            margin-bottom: 0;
        }
        .verify-banner .verify-text p {
            margin-bottom: 0;
            font-size: 0.85rem;
            color: #856404;
        }
        .alert-verified {
            background: #d1e7dd;
            border: none;
            border-radius: 16px;
            padding: 0.6rem 1.25rem;
            color: #0f5132;
            margin-bottom: 1.25rem;
            flex-shrink: 0;
        }
        .alert-verified i {
            color: #198754;
            font-size: 1.1rem;
        }
        /* ----- FORM CARD ----- */
        .form-card {
            background: #fff;
            border-radius: 20px;
            border: 1px solid rgba(0,0,0,0.04);
            box-shadow: 0 4px 24px rgba(0,0,0,0.06);
            padding: 2rem 2rem 1.75rem;
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        @media (max-width: 576px) {
            .form-card {
                padding: 1.25rem 1rem;
                border-radius: 16px;
                margin-bottom: 4rem;
            }
        }
        .form-label {
            font-weight: 600;
            font-size: 0.7rem;
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
        .form-control.bg-light {
            background: #f0f2f5 !important;
            color: #1c1e21;
            cursor: default;
            font-weight: 500;
        }
        .form-control.bg-light:focus {
            box-shadow: none;
            border-color: #dee2e6;
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
        .section-title i {
            font-size: 1rem;
            color: #1b74e4;
        }
        .section-divider {
            border: 0;
            border-top: 2px solid #e4e6ea;
            margin: 0.25rem 0 1rem;
            opacity: 1;
        }
        /* ----- BUTTONS ----- */
        .btn-submit {
            background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%);
            color: #fff;
            padding: 0.75rem 2.5rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            border: none;
            transition: transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(27, 116, 228, 0.3);
            min-width: 200px;
            margin-top: 0.5rem;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(27, 116, 228, 0.35);
            color: #fff;
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .btn-wrapper {
            display: flex;
            justify-content: center;
            margin-top: 0.5rem;
        }
        @media (max-width: 576px) {
            .btn-submit {
                font-size: 0.9rem;
                padding: 0.65rem 1.5rem;
                width: 100%;
                min-width: auto;
            }
        }
        /* ----- BACK TO TOP ----- */
        .top-link {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #1b74e4;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: opacity 0.2s, transform 0.2s;
            z-index: 999;
            border: none;
            cursor: pointer;
        }
        .top-link.hide {
            opacity: 0;
            pointer-events: none;
            transform: scale(0.8);
        }
        .top-link svg {
            fill: #fff;
            width: 20px;
            height: 12px;
        }
        .top-link:hover {
            background: #0a5ecf;
        }
        /* ----- FAMILY MEMBERS TABLE & DELETE ----- */
        .family-table {
            margin-bottom: 0;
        }
        .family-table th {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            color: #6c757d;
            font-weight: 600;
            border-bottom-width: 1px;
        }
        .family-table td {
            vertical-align: middle;
            font-size: 0.9rem;
        }
        .btn-delete-fm {
            width: 36px;
            height: 36px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1.5px solid #dc3545;
            background: #fff;
            color: #dc3545;
            transition: all 0.2s ease;
        }
        .btn-delete-fm:hover {
            background: #dc3545;
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.25);
        }
        .btn-delete-fm:active {
            transform: translateY(0);
        }
        /* ----- ADD FAMILY MEMBER MODAL (responsive) ----- */
        #addFamilyMemberModal .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }
        #addFamilyMemberModal .modal-header {
            background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%);
            color: #fff;
            border-bottom: none;
            padding: 1.25rem 1.5rem;
        }
        #addFamilyMemberModal .modal-header .btn-close {
            filter: brightness(0) invert(1);
            opacity: 0.85;
        }
        #addFamilyMemberModal .modal-header .btn-close:hover {
            opacity: 1;
        }
        #addFamilyMemberModal .modal-title {
            font-weight: 700;
            font-size: 1.15rem;
        }
        #addFamilyMemberModal .modal-body {
            padding: 1.5rem;
        }
        #addFamilyMemberModal .modal-footer {
            border-top: 1px solid #eef0f3;
            padding: 1rem 1.5rem;
            gap: 0.75rem;
        }
        #addFamilyMemberModal .form-label {
            margin-bottom: 0.3rem;
        }
        #addFamilyMemberModal .form-control,
        #addFamilyMemberModal .form-select {
            border-radius: 10px;
        }
        /* Mobile-friendly modal */
        @media (max-width: 575.98px) {
            #addFamilyMemberModal .modal-dialog {
                margin: 0.5rem;
                max-width: calc(100% - 1rem);
            }
            #addFamilyMemberModal .modal-content {
                border-radius: 16px;
            }
            #addFamilyMemberModal .modal-header {
                padding: 1rem 1.15rem;
            }
            #addFamilyMemberModal .modal-body {
                padding: 1.15rem;
            }
            #addFamilyMemberModal .modal-footer {
                flex-direction: column;
                padding: 1rem 1.15rem;
            }
            #addFamilyMemberModal .modal-footer .btn,
            #addFamilyMemberModal .modal-footer .btn-submit {
                width: 100%;
                margin: 0;
            }
            #addFamilyMemberModal .btn-submit {
                min-width: auto;
            }
        }
        /* ----- RESPONSIVE ----- */
        @media (max-width: 576px) {
            .page-wrapper {
                padding: 0 10px 1rem;
            }
            .form-card {
                padding: 1rem 0.9rem;
            }
            .form-control, .form-select {
                font-size: 0.88rem;
                padding: 0.5rem 0.8rem;
            }
            .section-title {
                font-size: 0.7rem;
            }
            .verify-banner {
                padding: 0.6rem 1rem;
            }
            .verify-banner .verify-icon {
                font-size: 1.4rem;
            }
            .verify-banner .btn-upload-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.8rem;
            }
        }
        @media (min-width: 768px) {
            .form-card {
                padding: 2.5rem;
            }
        }
    </style>
</head>
<body>
<!-- NAVBAR -->
<?php include ROOT_PATH . '/resident_navbar.php'; ?>

    <!-- HERO -->
    <div class="hero-profile">
        <i class="bi bi-person-badge"></i>
        <h1>Resident Profile</h1>
    </div>

<div class="page-wrapper">

    <!-- VERIFICATION NOTICE -->
    <?php if (!$is_verified): ?>
    <div class="verify-banner">
        <div class="d-flex align-items-center gap-3">
            <span class="verify-icon">&#x1F512;</span>
            <div class="verify-text">
                <h6>Account Not Yet Verified</h6>
                <p>Upload a valid ID for admin approval.</p>
            </div>
        </div>
        <a href="resident_messages.php?upload_id=1" class="btn-upload-sm">
            <i class="bi bi-upload me-1"></i> Upload ID
        </a>
    </div>
    <?php else: ?>
    <div class="alert-verified">
        <i class="bi bi-patch-check-fill me-2"></i> <strong>Account Verified</strong> &mdash; You have full access to all barangay services.
    </div>
    <?php endif; ?>

    <!-- PROFILE CARD -->
    <div class="form-card">
        <form method="post" class="d-flex flex-column flex-grow-1">
            <!-- Permanent Records -->
            <div class="section-title">
                <i class="bi bi-file-earmark-person"></i> Permanent Records
            </div>
            <hr class="section-divider">

            <div class="row g-3">
                <div class="col-md-4 col-12">
                    <label class="form-label">Last Name</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['lname'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-4 col-12">
                    <label class="form-label">First Name</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['fname'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-4 col-12">
                    <label class="form-label">Middle Name</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['mi'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6 col-12">
                    <label class="form-label">Email Address</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['email'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Sex</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['sex'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Nationality</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['nationality'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-6 col-6">
                    <label class="form-label">Date of Birth</label>
                    <input type="date" class="form-control bg-light" value="<?= $resident['bdate'] ?? ''; ?>" readonly>
                </div>
                <div class="col-md-6 col-6">
                    <label class="form-label">Civil Status</label>
                    <select class="form-control bg-light" name="status" readonly>
                        <option value="Single" <?= ($resident['status'] ?? '') == 'Single' ? 'selected' : ''; ?>>Single</option>
                        <option value="Married" <?= ($resident['status'] ?? '') == 'Married' ? 'selected' : ''; ?>>Married</option>
                        <option value="Widowed" <?= ($resident['status'] ?? '') == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                        <option value="Separated" <?= ($resident['status'] ?? '') == 'Separated' ? 'selected' : ''; ?>>Separated</option>
                    </select>
                </div>
                <div class="col-md-6 col-12">
                    <label class="form-label">Place of Birth</label>
                    <input class="form-control bg-light" value="<?= htmlspecialchars($resident['bplace'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Registered voter?</label>
                    <input class="form-control bg-light" type="text" name="voter"
                           value="<?= htmlspecialchars($resident['voter'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Household Head?</label>
                    <input class="form-control bg-light" type="text" name="family_role"
                           value="<?= htmlspecialchars($resident['family_role'] ?? ''); ?>" readonly>
                </div>
            </div>

            <br>

            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <label class="form-label">House No.</label>
                    <input class="form-control bg-light" type="text" name="houseno" value="<?= htmlspecialchars($resident['houseno'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Street</label>
                    <input class="form-control bg-light" type="text" name="street" value="<?= htmlspecialchars($resident['street'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Barangay</label>
                    <input class="form-control bg-light" type="text" name="brgy" value="<?= htmlspecialchars($resident['brgy'] ?? ''); ?>" readonly>
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label">Municipality</label>
                    <input class="form-control bg-light" type="text" name="municipal" value="<?= htmlspecialchars($resident['municipal'] ?? ''); ?>" readonly>
                </div>
            </div>

            <div class="btn-wrapper">
                <a href="resident_changepass.php?id_resident=<?= urlencode($userdetails['id_resident'] ?? ''); ?>" class="btn-submit text-center text-decoration-none d-inline-flex align-items-center justify-content-center gap-2">
                    <i class="bi bi-shield-lock"></i> Password Management
                </a>
            </div>
        </form>
    </div>

    <?php if ($is_household_head): ?>
    <!-- FAMILY MEMBERS CARD -->
    <div class="form-card" style="margin-top:1.5rem;">
        <div class="section-title">
            <i class="bi bi-people-fill"></i> Family Members
        </div>
        <hr class="section-divider">

        <?php if (empty($family_members)): ?>
            <div class="text-center text-muted py-4">
                <i class="bi bi-people" style="font-size:2rem;"></i>
                <p class="mb-0 mt-2">No family members added yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle family-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Relationship</th>
                            <th>Age</th>
                            <th>Sex</th>
                            <th>Occupation</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($family_members as $fm): 
                        $fullName = trim($fm['lname'].', '.$fm['fname'].' '.$fm['mi']);
                        $safeName = htmlspecialchars($fullName, ENT_QUOTES);
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($fullName) ?></td>
                            <td><?= htmlspecialchars($fm['relationship']) ?></td>
                            <td><?= htmlspecialchars($fm['age'] !== null ? $fm['age'] : '—') ?></td>
                            <td><?= htmlspecialchars($fm['sex'] ?: '—') ?></td>
                            <td><?= htmlspecialchars($fm['occupation'] ?: '—') ?></td>
                            <td class="text-end">
                                <form method="post" class="d-inline delete-fm-form"
                                      data-name="<?= $safeName ?>">
                                    <input type="hidden" name="id_family_member" value="<?= (int)$fm['id_family_member'] ?>">
                                    <button type="submit" name="delete_family_member" 
                                            class="btn-delete-fm" title="Remove member">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <button type="button" class="btn-submit mt-3" 
                data-bs-toggle="modal" data-bs-target="#addFamilyMemberModal">
            <i class="bi bi-person-plus-fill me-1"></i> Add Family Member
        </button>
    </div>

    <!-- ADD FAMILY MEMBER MODAL -->
    <div class="modal fade" id="addFamilyMemberModal" tabindex="-1" 
         aria-labelledby="addFamilyMemberModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addFamilyMemberModalLabel">
                            <i class="bi bi-person-plus-fill me-2"></i>Add Family Member
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <!-- Name fields -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="fm_lname" required
                                       placeholder="e.g. Dela Cruz">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">First Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="fm_fname" required
                                       placeholder="e.g. Juan">
                            </div>
                            <div class="col-12 col-sm-4">
                                <label class="form-label">Middle Initial</label>
                                <input type="text" class="form-control" name="fm_mi" maxlength="5"
                                       placeholder="e.g. A.">
                            </div>
                            <div class="col-12 col-sm-8">
                                <label class="form-label">Relationship to Head <span class="text-danger">*</span></label>
                                <select class="form-select" name="fm_relationship" required>
                                    <option value="">Choose...</option>
                                    <option value="Spouse">Spouse</option>
                                    <option value="Son">Son</option>
                                    <option value="Daughter">Daughter</option>
                                    <option value="Parent">Parent</option>
                                    <option value="Sibling">Sibling</option>
                                    <option value="Grandchild">Grandchild</option>
                                    <option value="Other Relative">Other Relative</option>
                                </select>
                            </div>

                            <!-- Demographics -->
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" name="fm_bdate">
                            </div>
                            <div class="col-12 col-sm-6">
                                <label class="form-label">Sex</label>
                                <select class="form-select" name="fm_sex">
                                    <option value="">Choose...</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Occupation <span class="text-muted">(optional)</span></label>
                                <input type="text" class="form-control" name="fm_occupation"
                                       placeholder="e.g. Student, Teacher, etc.">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" name="add_family_member" class="btn-submit">
                            <i class="bi bi-check-circle me-1"></i> Save Member
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endif; ?>

</div><!-- end page-wrapper -->

<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Back-to-top visibility (if element exists)
    const topBtn = document.getElementById('js-top');
    if (topBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                topBtn.classList.remove('hide');
            } else {
                topBtn.classList.add('hide');
            }
        });
        topBtn.addEventListener('click', (e) => {
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    // Tooltip initialization (if any)
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth' });
            }
        });
    });

    // Show the shared loading screen (defined in resident_navbar.php) for
    // every feature on this page that navigates away or saves data:
    // Upload ID, Password Management, Add Family Member, Delete Family
    // Member. The "Add Family Member" button that just opens the modal is
    // intentionally left alone since it doesn't leave the page.
    document.querySelectorAll('a[href^="resident_messages.php"], a[href^="resident_changepass.php"]').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;
            if (typeof showPageNavLoader === 'function') showPageNavLoader('Loading...');
        });
    });

    const addFmForm = document.querySelector('#addFamilyMemberModal form');
    if (addFmForm) {
        addFmForm.addEventListener('submit', function () {
            if (typeof showPageNavLoader === 'function') showPageNavLoader('Saving family member...');
        });
    }

    // SweetAlert confirmation for deleting family member
    document.querySelectorAll('.delete-fm-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const name = this.dataset.name || 'this member';
            const formEl = this;

            Swal.fire({
                title: 'Remove Family Member?',
                html: `Are you sure you want to remove <strong>${name}</strong> from your household?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="bi bi-trash me-1"></i> Yes, remove',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-4',
                    confirmButton: 'rounded-pill px-4',
                    cancelButton: 'rounded-pill px-4'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    if (typeof showPageNavLoader === 'function') showPageNavLoader('Removing family member...');
                    formEl.submit();
                }
            });
        });
    });
</script>

<?php if (!empty($_SESSION['swal'])): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire(<?= $_SESSION['swal'] ?>);
});
</script>
<?php unset($_SESSION['swal']); endif; ?>
</body>
</html>