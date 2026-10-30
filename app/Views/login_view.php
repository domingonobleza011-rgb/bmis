
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Brgy San Pedro">
    <link rel="apple-touch-icon" href="/icons/pwa/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <link rel="manifest" href="/manifest.json">
    <title>Barangay San Pedro - Login</title>
    
    <!-- Bootstrap 5 + Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    
    <style>
        /* ----- GLOBAL RESETS ----- */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body, html {
            min-height: 100%;
            min-height: 100dvh;
            font-family: 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif;
            background: #f0f2f5;
            overflow-x: hidden;
        }

        /* ----- NAVBAR ----- */
        .navbar-custom {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: #ffffff;
            border-bottom: 1px solid #e4e6ea;
            padding: 0.6rem 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }
        .navbar-custom .navbar-brand {
            font-weight: 700;
            color: #1b74e4 !important;
            font-size: 1.1rem;
            letter-spacing: -0.3px;
        }
        .navbar-custom .navbar-brand i {
            color: #1b74e4;
            margin-right: 8px;
        }
        .navbar-custom .nav-link {
            color: #4b4f56 !important;
            font-weight: 500;
            font-size: 0.9rem;
            padding: 0.4rem 1rem;
            border-radius: 40px;
            transition: all 0.2s ease;
        }
.navbar-brand-avatar {
    display: inline-flex;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    background: #fff;
    vertical-align: middle;
    margin-right: 4px;
}
.navbar-brand-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
        .navbar-custom .nav-link:hover {
            background: #f0f2f5;
            color: #1b74e4 !important;
        }
        .navbar-custom .nav-link i {
            margin-right: 6px;
        }
        .navbar-custom .nav-link-text {
            display: inline;
        }
        @media (max-width: 576px) {
            .navbar-custom { padding: 0.4rem 0.8rem; }
            .navbar-custom .navbar-brand { font-size: 0.85rem; }
            .navbar-custom .nav-link { font-size: 0.75rem; padding: 0.3rem 0.6rem; }
            .navbar-custom .nav-link-text { display: none; }
            .navbar-custom .nav-link i { margin-right: 0; font-size: 1rem; }
        }

        /* ----- MAIN CONTAINER ----- */
        .main-container {
            display: flex;
            min-height: 100vh;
            min-height: 100dvh;
            width: 100%;
            padding-top: 62px;
        }
        @media (max-width: 576px) {
            .main-container { padding-top: 54px; }
        }

        /* ----- LEFT PANEL (Branding - Clean White) ----- */
        .brand-panel {
            flex: 1;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 5% 6%;
            text-align: center;
            color: #1c1e21;
            border-right: 1px solid #e4e6ea;
        }
        .brand-panel .logo-wrapper {
            width: 160px;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            padding: 10px;
        }
        .brand-panel .logo-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }
        .brand-panel .logo-wrapper .logo-placeholder {
            font-size: 5rem;
            color: #1b74e4;
        }
        .brand-panel h1 {
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            color: #1c1e21;
            letter-spacing: -0.5px;
        }
        .brand-panel h1 span {
            color: #1b74e4;
        }
        .brand-panel .tagline {
            font-size: 1rem;
            color: #65676b;
            max-width: 400px;
            margin-bottom: 2rem;
            line-height: 1.6;
        }
        .brand-panel .features {
            display: flex;
            gap: 2.5rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .brand-panel .features .feature-item {
            text-align: center;
            min-width: 100px;
        }
        .brand-panel .features .feature-item i {
            font-size: 2rem;
            color: #1b74e4;
            display: block;
            margin-bottom: 0.5rem;
            background: #e7f3ff;
            width: 60px;
            height: 60px;
            line-height: 60px;
            border-radius: 50%;
            margin: 0 auto 0.5rem;
        }
        .brand-panel .features .feature-item span {
            font-size: 0.8rem;
            color: #65676b;
            font-weight: 600;
        }
        @media (max-width: 992px) {
            .brand-panel { display: none; }
        }
        @media (max-width: 1200px) {
            .brand-panel h1 { font-size: 1.8rem; }
            .brand-panel .features { gap: 1.5rem; }
            .brand-panel .logo-wrapper {
                width: 130px;
                height: 130px;
                padding: 8px;
            }
            .brand-panel .features .feature-item i {
                width: 50px;
                height: 50px;
                line-height: 50px;
                font-size: 1.5rem;
            }
        }

        /* ----- RIGHT PANEL (Login) ----- */
        .login-panel {
            flex: 1;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            min-height: calc(100vh - 62px);
            min-height: calc(100dvh - 62px);
        }
        @media (max-width: 992px) {
            .login-panel {
                justify-content: flex-start;
                min-height: auto;
                padding-top: 2rem;
                padding-bottom: 3rem;
            }
        }
        @media (max-width: 576px) {
            .login-panel {
                padding: 1.5rem 1.25rem 2.5rem;
            }
        }

        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-container .welcome-text h2 {
            font-weight: 700;
            font-size: 1.8rem;
            color: #1c1e21;
            margin-bottom: 0.25rem;
        }
        .login-container .welcome-text p {
            color: #65676b;
            font-size: 0.95rem;
            margin-bottom: 1.5rem;
        }
        .login-container .form-label {
            font-weight: 600;
            font-size: 0.8rem;
            color: #4b4f56;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        .login-container .input-group-custom {
            display: flex;
            align-items: center;
            border: 1.5px solid #dee2e6;
            border-radius: 12px;
            overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
            background: #fafbfc;
            margin-bottom: 1rem;
        }
        .login-container .input-group-custom:focus-within {
            border-color: #1b74e4;
            box-shadow: 0 0 0 3px rgba(27, 116, 228, 0.12);
            background: #fff;
        }
        .login-container .input-group-custom .input-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 46px;
            color: #8a8f9a;
            font-size: 0.95rem;
            flex-shrink: 0;
        }
        .login-container .input-group-custom .form-control {
            border: none;
            padding: 0.7rem 0.7rem 0.7rem 0;
            font-size: 0.95rem;
            background: transparent;
            outline: none;
            flex: 1;
            min-width: 0;
        }
        .login-container .input-group-custom .form-control::placeholder {
            color: #bcc0c4;
        }
        .login-container .form-check {
            margin-bottom: 1.25rem;
        }
        .login-container .form-check-label {
            font-size: 0.85rem;
            color: #65676b;
        }
        .login-container .btn-signin {
            background: #1b74e4;
            border: none;
            padding: 0.8rem;
            font-weight: 700;
            font-size: 1rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            color: #fff;
            width: 100%;
        }
        .login-container .btn-signin:hover {
            background: #0a5ecf;
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(27, 116, 228, 0.3);
            color: #fff;
        }
        .login-container .btn-signin:active {
            transform: translateY(0);
        }
        .login-container .forgot-link {
            font-size: 0.82rem;
            font-weight: 600;
            color: #1b74e4;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 12px;
            border-radius: 20px;
            transition: all 0.2s ease;
        }
        .login-container .forgot-link:hover {
            background: #e7f3ff;
            color: #0a5ecf;
        }
        .login-container .register-text {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #65676b;
        }
        .login-container .register-text a {
            color: #1b74e4;
            text-decoration: none;
            font-weight: 700;
        }
        .login-container .register-text a:hover {
            text-decoration: underline;
        }
        .login-container .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.25rem 0;
        }
        .login-container .divider::before,
        .login-container .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #dee2e6;
        }
        .login-container .divider span {
            padding: 0 1rem;
            color: #8a8f9a;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ----- ERROR ALERT ----- */
        .login-error-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 1.25rem;
            animation: shakeError 0.4s ease, fadeInDown 0.3s ease;
        }
        .login-error-alert .error-icon {
            color: #ef4444;
            flex-shrink: 0;
            font-size: 1.2rem;
            margin-top: 1px;
        }
        .login-error-alert .error-content strong {
            font-size: 0.9rem;
            color: #991b1b;
            display: block;
        }
        .login-error-alert .error-content span {
            font-size: 0.85rem;
            color: #7f1d1d;
        }
        .login-error-alert .error-close {
            background: none;
            border: none;
            font-size: 1.2rem;
            color: #ef4444;
            cursor: pointer;
            padding: 0 4px;
            opacity: 0.6;
            transition: opacity 0.2s;
            margin-left: auto;
            flex-shrink: 0;
        }
        .login-error-alert .error-close:hover { opacity: 1; }

        /* ----- PENDING / REJECTED ALERT VARIANTS ----- */
        .login-error-alert.alert-pending {
            background: #fffbeb;
            border-color: #fde68a;
            border-left-color: #f59e0b;
        }
        .login-error-alert.alert-pending .error-icon { color: #f59e0b; }
        .login-error-alert.alert-pending .error-content strong { color: #92400e; }
        .login-error-alert.alert-pending .error-content span { color: #78350f; }
        .login-error-alert.alert-pending .error-close { color: #f59e0b; }

        .login-error-alert.alert-rejected {
            background: #fef2f2;
            border-color: #fecaca;
            border-left-color: #dc2626;
        }
        .login-error-alert.alert-rejected .error-icon { color: #dc2626; }
        .login-error-alert.alert-rejected .error-content strong { color: #7f1d1d; }
        .login-error-alert.alert-rejected .error-content span { color: #7f1d1d; }
        .login-error-alert.alert-rejected .error-close { color: #dc2626; }
        @keyframes shakeError {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ----- OFFICIALS MODAL ----- */
        .modal-content {
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }
        .modal-header-custom {
            background: #1b74e4;
            color: #fff;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        .modal-header-custom .btn-close {
            filter: invert(1);
        }
        .official-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.25rem 0.75rem;
            text-align: center;
            border: 1.5px solid #e9ecef;
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
            cursor: pointer;
            height: 100%;
        }
        .official-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.08);
            border-color: #1b74e4;
        }
        .official-card .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #f0f2f5;
            margin: 0 auto 0.75rem;
        }
        .official-card .avatar-placeholder {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: #f0f2f5;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 2.2rem;
            color: #8a8f9a;
            border: 3px solid #e9ecef;
        }
        .official-card .official-name {
            font-weight: 700;
            font-size: 0.95rem;
            color: #1c1e21;
            margin-bottom: 0.15rem;
        }
        .official-card .official-position {
            font-size: 0.7rem;
            font-weight: 600;
            color: #1b74e4;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            background: #e7f3ff;
            padding: 0.2rem 0.8rem;
            border-radius: 20px;
            display: inline-block;
        }
        .official-card .view-hint {
            font-size: 0.68rem;
            color: #8a8f9a;
            margin-top: 0.5rem;
            display: block;
        }

        /* ----- ROLE DETAIL MODAL ----- */
        .role-modal-header {
            background: #1b74e4;
            color: #fff;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        .role-modal-header .btn-close {
            filter: invert(1);
        }
        .role-modal-header .role-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid rgba(255,255,255,0.3);
        }
        .role-modal-header .role-avatar-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: #fff;
        }
        .role-list-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 0.6rem 0;
            border-bottom: 1px solid #f0f2f5;
        }
        .role-list-item:last-child {
            border-bottom: none;
        }
        .role-list-item .check-icon {
            color: #1b74e4;
            font-size: 1rem;
            margin-top: 2px;
            flex-shrink: 0;
        }
        .role-list-item .role-text {
            font-size: 0.9rem;
            color: #4b4f56;
            line-height: 1.5;
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
    border-top-color: #c9943a;          /* gold accent */
    border-radius: 50%;
    animation: spin 0.85s linear infinite;
}
#page-loader .loader-text {
    color: #fff;
    font-weight: 600;
    font-size: 1.05rem;
    letter-spacing: 0.3px;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}

        @media (max-width: 576px) {
            .official-card .avatar,
            .official-card .avatar-placeholder {
                width: 60px;
                height: 60px;
            }
            .official-card .official-name { font-size: 0.85rem; }
            .official-card .official-position { font-size: 0.65rem; }
            .modal-header-custom h5 { font-size: 1rem; }
            .role-modal-header .role-avatar { width: 48px; height: 48px; }
            .role-list-item .role-text { font-size: 0.82rem; }
        }
    </style>
    <link rel="stylesheet" href="css/bg-blur.css?v=20261026b">
    <link rel="stylesheet" href="css/skeleton-public.css?v=20261026c">
</head>
<body>

<!-- ===== NAVBAR ===== -->
<nav class="navbar navbar-expand navbar-custom">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">
            <span class="navbar-brand-avatar"><img src="icons/logo.png" alt="Barangay San Pedro"></span> SAN PEDRO IRIGA
        </a>
    </div>
</nav>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-container">
    
    <!-- Brand Panel (Desktop) -->
    <div class="brand-panel">
        <div class="logo-wrapper">
            <?php if (file_exists('icons/logo.png')): ?>
                <img src="icons/logo.png" alt="Barangay Logo">
            <?php else: ?>
                <div class="logo-placeholder">
                    <i class="fas fa-landmark"></i>
                </div>
            <?php endif; ?>
        </div>
        <h1>Barangay <span>San Pedro</span></h1>
        <p class="tagline">A digitally integrated community where every resident has seamless access to services.</p>
        <div class="features">
            <div class="feature-item">
                <i class="fas fa-shield-alt"></i>
                <span>Transparent Governance</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-users-cog"></i>
                <span>Proactive Administration</span>
            </div>
            <div class="feature-item">
                <i class="fas fa-mobile-alt"></i>
                <span>Tech-Driven Services</span>
            </div>
        </div>
    </div>

    <!-- Login Panel -->
    <div class="login-panel">
        <div class="login-container">

            <div class="welcome-text">
                <h2>Welcome Back</h2>
                <p>Please enter your credentials to log in.</p>
            </div>

            <?php if (!empty($_SESSION['login_error'])):
                $loginStatus = $_SESSION['login_status'] ?? 'error';
                $alertModifier = $loginStatus === 'pending' ? 'alert-pending' : ($loginStatus === 'rejected' ? 'alert-rejected' : '');
                $alertIcon = $loginStatus === 'pending' ? 'bi-hourglass-split' : ($loginStatus === 'rejected' ? 'bi-x-octagon-fill' : 'bi-exclamation-circle-fill');
                $alertHeading = $loginStatus === 'pending' ? 'Account Pending Approval' : ($loginStatus === 'rejected' ? 'Registration Not Approved' : 'Login Failed');
            ?>
            <div class="login-error-alert <?= $alertModifier ?>" id="loginErrorAlert">
                <div class="error-icon"><i class="bi <?= $alertIcon ?>"></i></div>
                <div class="error-content">
                    <strong><?= $alertHeading ?></strong>
                    <span><?= htmlspecialchars($_SESSION['login_error']) ?></span>
                </div>
                <button class="error-close" onclick="document.getElementById('loginErrorAlert').remove()">&times;</button>
            </div>
            <?php unset($_SESSION['login_error'], $_SESSION['login_status']); ?>
            <?php endif; ?>

            <form method="post" class="show-loader" data-loader-msg="Signing you in...">
                <label class="form-label">Username or Phone Number</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="fas fa-user"></i></span>
                    <input type="text" class="form-control" placeholder="Enter Email or Phone Number" 
                           name="login_identity" required>
                </div>

                <label class="form-label">Password</label>
                <div class="input-group-custom">
                    <span class="input-icon"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="passInput" 
                           placeholder="Enter password" name="password" required>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="showCheck" onclick="togglePass()">
                    <label class="form-check-label" for="showCheck">Show Password</label>
                </div>
<div class="cf-turnstile" data-sitekey="0x4AAAAAAEHzfWAU9-zKulev"></div>
                <button type="submit" name="login" class="btn-signin">
                    <i class="fas fa-sign-in-alt me-2"></i> SIGN IN
                </button>
                <input type="hidden" name="login" value="1">
                <p class="text-center mt-2 mb-0" style="font-size:.78rem;opacity:.85;">
                    By signing in you acknowledge our <a href="/privacy" target="_blank" rel="noopener">Privacy Notice</a>
                    and <a href="/terms" target="_blank" rel="noopener">Terms</a>.
                </p>
            </form>

            <div class="d-flex justify-content-end mt-2">
                <a href="forgot_password.php" class="forgot-link">
                    <i class="fas fa-key me-1"></i> Forgot Password?
                </a>
            </div>

            <div class="divider"><span>or</span></div>

            <p class="register-text">
                Don't have an account? <a href="resident_registration.php">Register here</a>
            </p>
        </div>
    </div>
</div>

<!-- ===== OFFICIALS MODAL ===== -->
<div class="modal fade" id="officialsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-custom">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-users-cog fa-2x" style="color: #ffc107;"></i>
                    <div>
                        <h5 class="modal-title fw-bold mb-0">Sangguniang Barangay Members</h5>
                        <small class="opacity-75">San Pedro, Iriga City</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3 justify-content-center">
                    <?php 
                    if (!empty($view) && is_array($view)) { 
                        $positionOrder = [
                            "Punong Barangay", "Secretary", "Treasurer", "Clerk", "Book Keeper",
                            "Committee on Appropriation", "Committee on Health", "Committee on Women and Children",
                            "Committee on Education", "Committee on Peace and Order", "Committee on Infrastructure",
                            "Committee on Ways and Means", "Committee on Agriculture", "Committee on Tourism",
                            "IPMRR Representative", "Sk Chairperson"
                        ];
                        usort($view, function($a, $b) use ($positionOrder) {
                            $posA = array_search($a['position'], $positionOrder);
                            $posB = array_search($b['position'], $positionOrder);
                            $posA = ($posA === false) ? 999 : $posA;
                            $posB = ($posB === false) ? 999 : $posB;
                            return $posA <=> $posB;
                        });

                        foreach($view as $row) { 
                            $photo = !empty($row['photo']) && file_exists($row['photo']) ? $row['photo'] : null;
                    ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="official-card" onclick="showOfficialRole(
                            '<?= htmlspecialchars(addslashes($row['fname'] . ' ' . ($row['mi'] ? $row['mi'].'. ' : '') . $row['lname']), ENT_QUOTES) ?>',
                            '<?= htmlspecialchars(addslashes($row['position']), ENT_QUOTES) ?>',
                            '<?= $photo ? htmlspecialchars(addslashes($photo), ENT_QUOTES) : '' ?>'
                        )">
                            <?php if ($photo): ?>
                                <img src="<?= htmlspecialchars($photo) ?>" class="avatar" alt="Official Photo">
                            <?php else: ?>
                                <div class="avatar-placeholder"><i class="fas fa-user"></i></div>
                            <?php endif; ?>
                            <div class="official-name"><?= htmlspecialchars($row['fname'] . ' ' . ($row['mi'] ? $row['mi'].'. ' : '') . $row['lname']) ?></div>
                            <span class="official-position"><?= htmlspecialchars($row['position']) ?></span>
                            <span class="view-hint"><i class="fas fa-eye me-1"></i>Tap to view roles</span>
                        </div>
                    </div>
                    <?php 
                        } 
                    } else { 
                    ?>
                    <div class="col-12 text-center py-4">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No officials currently listed.</p>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- ===== ROLE DETAIL MODAL ===== -->
<div class="modal fade" id="officialRoleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 480px;">
        <div class="modal-content">
            <div class="role-modal-header" id="roleModalHeader">
                <div class="d-flex align-items-center gap-3">
                    <div id="roleAvatarWrap"></div>
                    <div>
                        <h5 class="fw-bold mb-0" id="roleModalName">—</h5>
                        <small class="opacity-75" id="roleModalPosition">—</small>
                    
                    </div>
                </div>
                
            </div>

            <div class="modal-body px-4 py-3">
                <p class="fw-semibold text-uppercase mb-3" style="font-size: 0.75rem; letter-spacing: 0.8px; color: #8a8f9a;">
                    <i class="fas fa-tasks me-1"></i> Assigned Roles &amp; Responsibilities
                </p>
                <div id="roleModalList"></div>
            </div>

            <div class="modal-footer border-0 bg-light">
                <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
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

// Attach to any form that has class "show-loader"
document.querySelectorAll('form.show-loader').forEach(form => {
    form.addEventListener('submit', function () {
        // Optional: different message per form
        const msg = this.dataset.loaderMsg || 'Please wait...';
        showLoader(msg);

        // Disable the submit button so user can't double-click
        const btn = this.querySelector('button[type="submit"], input[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
        }
    });
});
</script>
<!-- SCRIPTS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
<script>
    // ── Toggle Password Visibility ──────────────────────────────────────────
    function togglePass() {
        const passField = document.getElementById("passInput");
        passField.type = passField.type === "password" ? "text" : "password";
    }

    // ── Role Data from PHP ──────────────────────────────────────────────────
    const ROLE_DATA = <?php echo json_encode($roleDescriptions); ?>;

    // ── Show Official Role Modal ───────────────────────────────────────────
    function showOfficialRole(name, position, photoSrc) {
        const data = ROLE_DATA[position] || null;
        const header = document.getElementById('roleModalHeader');
        const nameEl = document.getElementById('roleModalName');
        const posEl = document.getElementById('roleModalPosition');
        const listEl = document.getElementById('roleModalList');
        const avatarWrap = document.getElementById('roleAvatarWrap');

        // Set name & position
        nameEl.textContent = name;
        posEl.textContent = position;

        // Header color
        const accentColor = data ? data.color : '#1b74e4';
        header.style.background = `linear-gradient(135deg, ${accentColor}, ${accentColor}dd)`;

        // Avatar
        if (photoSrc) {
            avatarWrap.innerHTML = `<img src="${photoSrc}" class="role-avatar" alt="Official Photo">`;
        } else {
            avatarWrap.innerHTML = `<div class="role-avatar-placeholder"><i class="fas fa-user"></i></div>`;
        }

        // Roles list
        if (data && data.roles && data.roles.length) {
            listEl.innerHTML = data.roles.map(r => `
                <div class="role-list-item">
                    <span class="check-icon"><i class="fas fa-check-circle"></i></span>
                    <span class="role-text">${r}</span>
                </div>
            `).join('');
        } else {
            listEl.innerHTML = `<p class="text-muted text-center py-3">No specific roles listed for this position.</p>`;
        }

        // Show the role modal
        const roleModal = new bootstrap.Modal(document.getElementById('officialRoleModal'));
        roleModal.show();
    }

    // ── Restore officials modal backdrop when role modal closes ────────────
    document.getElementById('officialRoleModal').addEventListener('hidden.bs.modal', function () {
        const officialsModalEl = document.getElementById('officialsModal');
        if (officialsModalEl.classList.contains('show')) {
            document.body.classList.add('modal-open');
            if (!document.querySelector('.modal-backdrop')) {
                const backdrop = document.createElement('div');
                backdrop.classList.add('modal-backdrop', 'fade', 'show');
                document.body.appendChild(backdrop);
            }
        }
    });
</script>
<script src="/js/pwa.js"></script>
<script src="js/skeleton-pages.js?v=20261026c"></script>
<script src="js/skeleton-public.js?v=20261026c"></script>
<?php $bmis_legal_links = true; include __DIR__ . '/partials/legal_widgets.php'; ?>
</body>
</html>