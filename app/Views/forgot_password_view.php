
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
    <title>Forgot Password — Barangay San Pedro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <style>
        :root {
            --primary-color: #0f2d5a;
            --primary-hover: #1a4480;
            --bg-gradient-start: #e8edf5;
            --bg-gradient-end: #f0f4f8;
            --card-shadow: 0 8px 32px rgba(0,0,0,0.12);
            --border-radius-lg: 24px;
            --border-radius-sm: 12px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            background: linear-gradient(135deg, var(--bg-gradient-start) 0%, var(--bg-gradient-end) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            padding: 20px;
            margin: 0;
        }

        .card {
            border: none;
            border-radius: var(--border-radius-lg);
            box-shadow: var(--card-shadow);
            max-width: 460px;
            width: 100%;
            background: #ffffff;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Header ── */
        .card-header {
            background: linear-gradient(135deg, #f8faff 0%, #ffffff 100%);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            border-radius: var(--border-radius-lg) var(--border-radius-lg) 0 0 !important;
            text-align: center;
            padding: 2rem 1.5rem 1.5rem;
        }

        .logo-wrapper {
            position: relative;
            display: inline-block;
            margin-bottom: 0.75rem;
        }

        .logo-wrapper img {
            width: 85px;
            height: 85px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid #ffffff;
            box-shadow: 0 4px 12px rgba(15, 45, 90, 0.15);
            transition: transform 0.3s ease;
        }

        .logo-wrapper img:hover {
            transform: scale(1.05);
        }

        .logo-wrapper .logo-badge {
            position: absolute;
            bottom: -2px;
            right: -2px;
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            border: 2px solid white;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .card-header h5 {
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1.3rem;
            margin-bottom: 0.3rem;
            letter-spacing: -0.5px;
        }

        .card-header .subtitle {
            color: #6c757d;
            font-size: 0.85rem;
            font-weight: 400;
        }

        .step-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 20px;
            padding: 4px 16px 4px 12px;
            font-size: 0.72rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            margin-top: 0.3rem;
            text-transform: uppercase;
        }

        .step-badge .step-number {
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
        }

        /* ── Body ── */
        .card-body {
            padding: 2rem 1.75rem;
        }

        @media (max-width: 576px) {
            .card-body {
                padding: 1.5rem 1.25rem;
            }
        }

        .form-label {
            font-weight: 600;
            font-size: 0.88rem;
            color: #2d3748;
            margin-bottom: 0.4rem;
        }

        .input-group {
            border-radius: var(--border-radius-sm);
            overflow: hidden;
            transition: all 0.2s ease;
            border: 1px solid #e2e8f0;
            background: white;
        }

        .input-group:focus-within {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(15, 45, 90, 0.1);
        }

        .input-group-text {
            background: transparent;
            color: #6c757d;
            border: none;
            padding: 0.75rem 0 0.75rem 1rem;
            font-size: 0.95rem;
        }

        .form-control {
            border: none;
            padding: 0.75rem 1rem 0.75rem 0.5rem;
            background: transparent;
            font-size: 0.95rem;
        }

        .form-control:focus {
            outline: none;
            box-shadow: none;
            background: transparent;
        }

        .form-control::placeholder {
            color: #a0aec0;
            font-size: 0.9rem;
        }

        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            padding: 0.75rem 1.25rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
            letter-spacing: 0.2px;
        }

        .btn-primary {
            background: var(--primary-color);
            border: none;
            color: white;
            box-shadow: 0 4px 12px rgba(15, 45, 90, 0.25);
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(15, 45, 90, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-outline-secondary {
            border: 1px solid #e2e8f0;
            color: #4a5568;
            background: transparent;
        }

        .btn-outline-secondary:hover {
            background: #f7fafc;
            border-color: #cbd5e0;
            transform: translateY(-1px);
        }

        /* ── Question Box ── */
        .question-box {
            background: linear-gradient(135deg, #f0f4ff 0%, #f8faff 100%);
            border: 1px solid #d4e0f8;
            border-left: 4px solid var(--primary-color);
            border-radius: var(--border-radius-sm);
            padding: 14px 18px;
            font-size: 0.95rem;
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .question-box i {
            color: var(--primary-color);
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        /* ── Alerts ── */
        .error-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            background: #fff5f5;
            border: 1px solid #fecaca;
            border-left: 4px solid #ef4444;
            border-radius: var(--border-radius-sm);
            padding: 14px 16px;
            margin-bottom: 1.25rem;
            animation: shake 0.5s ease;
        }

        .error-alert i {
            color: #ef4444;
            margin-top: 2px;
            flex-shrink: 0;
        }

        .error-alert .error-title {
            font-size: 0.85rem;
            font-weight: 600;
            color: #991b1b;
        }

        .error-alert .error-message {
            font-size: 0.85rem;
            color: #7f1d1d;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            20% { transform: translateX(-6px); }
            40% { transform: translateX(6px); }
            60% { transform: translateX(-4px); }
            80% { transform: translateX(4px); }
        }

        .alert-success {
            border-radius: var(--border-radius-sm);
            border-left: 4px solid #22c55e;
            background: #f0fdf4;
            border-color: #bbf7d0;
            font-size: 0.9rem;
        }

        .alert-warning {
            border-radius: var(--border-radius-sm);
            border-left: 4px solid #f59e0b;
            background: #fffbeb;
            border-color: #fde68a;
            font-size: 0.9rem;
        }

        /* ── Success State ── */
        .success-circle {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            animation: pulse 1.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .success-circle i {
            font-size: 2rem;
            color: #059669;
        }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.25rem 0;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider span {
            padding: 0 0.75rem;
            color: #a0aec0;
            font-size: 0.8rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* ── Back Link ── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.2s ease;
        }

        .back-link:hover {
            color: var(--primary-color);
            transform: translateX(-3px);
        }

        /* ── Password Match Feedback ── */
        .password-match {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            padding: 4px 0;
        }

        .password-match i {
            margin-right: 6px;
        }

        /* ── Responsive Tweaks ── */
        @media (max-width: 480px) {
            .card {
                margin: 0 -10px;
                border-radius: 16px;
            }

            .card-header {
                padding: 1.5rem 1rem 1.2rem;
            }

            .logo-wrapper img {
                width: 70px;
                height: 70px;
            }

            .card-header h5 {
                font-size: 1.1rem;
            }

            .step-badge {
                font-size: 0.65rem;
                padding: 3px 12px 3px 8px;
            }

            .btn {
                font-size: 0.9rem;
                padding: 0.7rem 1rem;
            }

            .form-control {
                font-size: 0.9rem;
                padding: 0.65rem 0.75rem 0.65rem 0.4rem;
            }

            .question-box {
                font-size: 0.88rem;
                padding: 12px 14px;
            }
        }

        @media (max-width: 380px) {
            .card-body {
                padding: 1.2rem 1rem;
            }

            .card-header {
                padding: 1.2rem 0.75rem 1rem;
            }

            .logo-wrapper img {
                width: 60px;
                height: 60px;
            }

            .logo-wrapper .logo-badge {
                width: 24px;
                height: 24px;
                font-size: 10px;
            }
        }
    </style>
</head>
<body>
<div class="container px-0 px-sm-3">
    <div class="card mx-auto">

        <!-- Header -->
        <div class="card-header">
            <div class="logo-wrapper">
                <img src="icons/logo.png" alt="Barangay San Pedro">
                <span class="logo-badge"><i class="fas fa-key"></i></span>
            </div>
            <h5>Forgot Password</h5>
            <div class="subtitle">Recover access to your account</div>
            <span class="step-badge">
                <span class="step-number">
                    <?php
                    $stepNumbers = [
                        'identify' => '1',
                        'security_question' => '2',
                        'new_password' => '3',
                        'admin_request' => '!',
                        'done' => '✓'
                    ];
                    echo $stepNumbers[$step] ?? '1';
                    ?>
                </span>
                <?php
                $labels = [
                    'identify'          => 'Find Account',
                    'security_question' => 'Verify Identity',
                    'new_password'      => 'Reset Password',
                    'admin_request'     => 'Admin Reset',
                    'done'              => 'Complete',
                ];
                echo $labels[$step] ?? 'Step 1 of 3';
                ?>
            </span>
        </div>

        <!-- Body -->
        <div class="card-body">

            <?php if ($error): ?>
            <div class="error-alert">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    <div class="error-title">Oops!</div>
                    <div class="error-message"><?= htmlspecialchars($error) ?></div>
                </div>
            </div>
            <?php endif; ?>

            <?php if ($success && $step !== 'done'): ?>
            <div class="alert alert-success" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                <?= htmlspecialchars($success) ?>
            </div>
            <?php endif; ?>

            <!-- STEP 1: Identify -->
            <?php if ($step === 'identify'): ?>
                <p class="text-muted small mb-4">
                    <i class="fas fa-info-circle me-1"></i>
                    Enter the email address or phone number linked to your account.
                </p>
                <form method="post" class="show-loader" data-loader-msg="Searching for your account...">
                    <?= bmis_csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-address-card me-1"></i>Email or Phone Number
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" name="identity" class="form-control"
                                   placeholder="e.g. 09xxxxxxxxx or email@domain.com" 
                                   required autofocus>
                        </div>
                    </div>
                    <button type="submit" name="find_account" class="btn btn-primary w-100">
                        <i class="fas fa-search me-2"></i>Find My Account
                    </button>
                     <input type="hidden" name="find_account" value="1">

                </form>

            <!-- STEP 2: Security Question -->
            <?php elseif ($step === 'security_question'): ?>
                <p class="text-muted small mb-3">
                    <i class="fas fa-shield-alt me-1"></i>
                    Answer your security question to verify your identity.
                </p>
                <div class="question-box">
                    <i class="fas fa-question-circle"></i>
                    <span><?= htmlspecialchars($_SESSION['reset_question'] ?? '') ?></span>
                </div>
                <form method="post" class="show-loader" data-loader-msg="Verifying your answer...">
                    <?= bmis_csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-comment-dots me-1"></i>Your Answer
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-pen"></i></span>
                            <input type="text" name="security_answer" class="form-control"
                                   placeholder="Type your answer" required autofocus>
                        </div>
                    </div>
                    <button type="submit" name="verify_answer" class="btn btn-primary w-100">
                        <i class="fas fa-check-circle me-2"></i>Verify Answer
                    </button>
                    <input type="hidden" name="verify_answer" value="1">

                </form>

                <div class="divider"><span>or</span></div>

                <div class="text-center">
                    <p class="text-muted small mb-2">Don't remember your answer?</p>
                    <a href="forgot_password.php?step=admin_request"
                       class="btn btn-outline-secondary w-100">
                        <i class="fas fa-user-shield me-2"></i>Request Admin Reset
                    </a>
                </div>

            <!-- STEP 3: New Password -->
            <?php elseif ($step === 'new_password'): ?>
                <p class="text-muted small mb-3">
                    <i class="fas fa-lock me-1"></i>
                    Set your new password. Minimum <strong>8 characters</strong>.
                </p>
                <form method="post" id="passwordForm" class="show-loader" data-loader-msg="Saving your new password...">
                    <?= bmis_csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-key me-1"></i>New Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="new_password" id="newpw" 
                                   class="form-control" minlength="8" 
                                   placeholder="Minimum 8 characters" required autofocus>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-check me-1"></i>Confirm New Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" name="confirm_password" id="confpw" 
                                   class="form-control" minlength="8" 
                                   placeholder="Re-enter password" required>
                        </div>
                        <div id="pw-match" class="password-match"></div>
                    </div>
                    <button type="submit" name="set_password" class="btn btn-primary w-100">
                        <i class="fas fa-save me-2"></i>Save New Password
                    </button>
                       <input type="hidden" name="set_password" value="1">

                </form>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const newpw = document.getElementById('newpw');
                    const confpw = document.getElementById('confpw');
                    const matchDiv = document.getElementById('pw-match');

                    function checkMatch() {
                        if (confpw.value.length === 0) {
                            matchDiv.innerHTML = '';
                            return;
                        }
                        if (confpw.value === newpw.value) {
                            matchDiv.innerHTML = '<i class="fas fa-check-circle text-success"></i> <span class="text-success">Passwords match</span>';
                        } else {
                            matchDiv.innerHTML = '<i class="fas fa-times-circle text-danger"></i> <span class="text-danger">Passwords do not match</span>';
                        }
                    }

                    newpw.addEventListener('input', checkMatch);
                    confpw.addEventListener('input', checkMatch);
                });
                </script>

            <!-- Admin Request -->
            <?php elseif ($step === 'admin_request'): ?>
                <div class="alert alert-warning mb-3" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Important:</strong> Admin will set a <strong>temporary password</strong> 
                    for your account. You must change it upon next login.
                </div>
                <p class="text-muted small mb-4">
                    <i class="fas fa-building me-1"></i>
                    Submit a reset request to the barangay admin. 
                    You can visit the barangay hall to claim your temporary password.
                </p>
                <form method="post" class="show-loader" data-loader-msg="Submitting your request...">
                    <?= bmis_csrf_field() ?>
                    <button type="submit" name="request_admin_reset" class="btn btn-primary w-100">
                        <i class="fas fa-paper-plane me-2"></i>Submit Reset Request
                    </button>
                    <input type="hidden" name="request_admin_reset" value="1">

                </form>

                <div class="divider"><span>or</span></div>

                <div class="text-center">
                    <a href="forgot_password.php" class="back-link">
                        <i class="fas fa-arrow-left"></i>Try another way
                    </a>
                </div>

            <!-- Done -->
            <?php elseif ($step === 'done'): ?>
                <div class="text-center py-3">
                    <div class="success-circle">
                        <i class="fas fa-check"></i>
                    </div>
                    <h6 class="fw-bold text-success mb-2">Success!</h6>
                    <p class="text-muted small">
                        <?= htmlspecialchars($success ?: 'Your request has been submitted.') ?>
                    </p>
                    <div class="mt-4">
                        <a href="login.php" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Back to Login
                        </a>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Back to Login (except done) -->
            <?php if ($step !== 'done' && !in_array($step, ['admin_request'])): ?>
                <div class="text-center mt-4 pt-2 border-top">
                    <a href="login.php" class="back-link">
                        <i class="fas fa-arrow-left"></i>Back to Login
                    </a>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Loading Overlay — same design as the sign-in loader on login.php -->
<style>
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
    letter-spacing: 0.3px;
}
@keyframes spin { to { transform: rotate(360deg); } }
</style>
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

// Attach to any form that has class "show-loader"
document.querySelectorAll('form.show-loader').forEach(form => {
    form.addEventListener('submit', function (e) {
        // Let native HTML5 validation block submission first (required fields,
        // minlength, etc.) — only show the loader once the form actually submits.
        if (!this.checkValidity()) return;

        const msg = this.dataset.loaderMsg || 'Please wait...';
        showLoader(msg);

        const btn = this.querySelector('button[type="submit"], input[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
        }
    });
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>