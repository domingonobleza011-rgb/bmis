<?php
// Turn http(s):// and www. links in a post into tappable links (safe: text is escaped first)
if (!function_exists('sk_linkify')) {
    function sk_linkify($text) {
        $safe = htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
        $safe = preg_replace_callback(
            '~(?<![\w@/])((?:https?://|www\.)[^\s<]+)~iu',
            function ($m) {
                $url = $m[1]; $trail = '';
                // keep trailing punctuation outside the link
                while ($url !== '' && preg_match('~[.,;:!?)\]\'"]$|&quot;$|&#0?39;$~', $url)) {
                    if (preg_match('~(&quot;|&#0?39;)$~', $url, $q)) { $trail = $q[1] . $trail; $url = substr($url, 0, -strlen($q[1])); }
                    else { $trail = substr($url, -1) . $trail; $url = substr($url, 0, -1); }
                }
                if ($url === '') return $m[0];
                $href = preg_match('~^https?://~i', $url) ? $url : 'https://' . $url;
                return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" class="post-link">' . $url . '</a>' . $trail;
            },
            $safe
        );
        return nl2br($safe);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Youth Portal | Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <style>
        body {
            background: #f0f2f5;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
        }
        .youth-hero {
            background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%);
            padding: 2.5rem 1.5rem;
            border-radius: 0 0 40px 40px;
            color: #fff;
            margin-bottom: 2rem;
            text-align: center;
        }
        .youth-hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .youth-hero .hero-actions {
            display: flex;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 1.25rem;
        }
        .youth-hero .btn-hero {
            border-radius: 40px;
            padding: 0.6rem 1.8rem;
            font-weight: 600;
            font-size: 0.95rem;
            border: none;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .youth-hero .btn-hero:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 14px rgba(0,0,0,0.15);
        }
        .youth-hero .btn-hero-light {
            background: #fff;
            color: #1b74e4;
        }
        .youth-hero .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.6);
        }
        .youth-hero .btn-hero-outline:hover {
            background: rgba(255,255,255,0.15);
            border-color: #fff;
        }
        @media (max-width: 576px) {
            .youth-hero h1 { font-size: 1.8rem; }
            .youth-hero {
                padding: 1.8rem 1rem;
                border-radius: 0 0 24px 24px;
            }
            .youth-hero .btn-hero {
                font-size: 0.85rem;
                padding: 0.5rem 1.2rem;
                width: 100%;
            }
            .youth-hero .hero-actions {
                flex-direction: column;
                align-items: stretch;
            }
        }
        .portal-tabs {
            display: flex;
            gap: 6px;
            background: #fff;
            border-radius: 14px;
            padding: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            border: 1.5px solid #dce0e4;
            margin-bottom: 1.5rem;
        }
        .portal-tab {
            flex: 1;
            text-align: center;
            padding: 0.7rem 0.5rem;
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            color: #65676b;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            background: transparent;
            cursor: pointer;
        }
        .portal-tab i {
            display: block;
            font-size: 1.2rem;
            margin-bottom: 3px;
        }
        .portal-tab.active, .portal-tab:hover {
            background: #1b74e4;
            color: #fff;
        }
        @media (max-width: 576px) {
            .portal-tab { font-size: 0.7rem; padding: 0.5rem 0.3rem; }
            .portal-tab i { font-size: 1rem; }
        }
        .chip-filter {
            padding: 0.35rem 1rem;
            border-radius: 20px;
            font-size: 0.78rem;
            font-weight: 700;
            border: 1.5px solid #dce0e4;
            background: #fff;
            color: #65676b;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-block;
        }
        .chip-filter:hover,
        .chip-filter.active {
            border-color: #1b74e4;
            background: #1b74e4;
            color: #fff;
        }
        .section-panel {
            background: #fff;
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            border: 1.5px solid #dce0e4;
        }
        .bulletin-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border: 1.5px solid #dce0e4;
            position: relative;
            transition: transform 0.15s, box-shadow 0.15s;
            height: 100%;
        }
        .bulletin-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0,0,0,0.1);
        }
        .bulletin-card.pinned {
            border-color: #c9943a;
            background: linear-gradient(135deg, #fffdf7, #fff);
        }
        .pin-ribbon {
            position: absolute;
            top: -1px;
            right: 16px;
            background: #c9943a;
            color: #fff;
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.2rem 0.8rem 0.5rem;
            border-radius: 0 0 10px 10px;
            letter-spacing: 0.8px;
            text-transform: uppercase;
        }
        .b-type-badge {
            display: inline-block;
            border-radius: 8px;
            padding: 0.2rem 0.8rem;
            font-size: 0.72rem;
            font-weight: 800;
            margin-bottom: 0.6rem;
        }
        .t-announcement { background: #ebf5fb; color: #2471a3; }
        .t-opportunity   { background: #e8f0fe; color: #1967d2; }
        .t-reminder      { background: #fdf3e3; color: #c9943a; }
        .t-achievement   { background: #f0eafe; color: #6200ea; }
        .t-general       { background: #f0f4f8; color: #555; }
        .b-title { font-size: 1.05rem; font-weight: 800; color: #1b74e4; margin-bottom: 0.5rem; line-height: 1.4; }
        .b-content { font-size: 0.9rem; color: #444; line-height: 1.7; margin-bottom: 0.75rem; }
.b-content a.post-link { color:#1967d2;text-decoration:underline;word-break:break-all;cursor:pointer; }
.b-content { overflow-wrap:anywhere; }
        .b-meta { font-size: 0.75rem; color: #65676b; display: flex; flex-wrap: wrap; gap: 12px; }
        .b-meta span { display: flex; align-items: center; gap: 4px; }
        .prog-card {
            background: #fff;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 10px rgba(0,0,0,0.06);
            border-top: 1.5px solid #dce0e4;
            border-right: 1.5px solid #dce0e4;
            border-bottom: 1.5px solid #dce0e4;
            border-left: 5px solid #dce0e4;
            transition: transform 0.15s, box-shadow 0.15s;
            height: 100%;
        }
        .prog-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 28px rgba(0,0,0,0.1);
        }
        .prog-card.upcoming   { border-left-color: #1b74e4; }
        .prog-card.ongoing    { border-left-color: #27ae60; }
        .prog-card.completed  { border-left-color: #999; }
        .prog-card.cancelled  { border-left-color: #c0392b; }
        .p-title { font-size: 1.05rem; font-weight: 800; color: #1b74e4; margin: 0.4rem 0 0.3rem; }
        .p-meta { font-size: 0.79rem; color: #65676b; display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 0.6rem; }
        .p-meta span { display: flex; align-items: center; gap: 4px; }
        .p-desc { font-size: 0.875rem; color: #444; line-height: 1.65; margin-bottom: 0.75rem; }
        .p-req { font-size: 0.78rem; color: #888; margin-bottom: 0.75rem; }
        .badge-ptype {
            background: #ebf5fb;
            color: #1b74e4;
            border-radius: 7px;
            padding: 0.2rem 0.7rem;
            font-size: 0.72rem;
            font-weight: 800;
        }
        .badge-upcoming  { background: #ebf5fb; color: #2471a3; border-radius: 7px; padding: 0.2rem 0.7rem; font-size: 0.72rem; font-weight: 800; }
        .badge-ongoing   { background: #eafaf1; color: #27ae60; border-radius: 7px; padding: 0.2rem 0.7rem; font-size: 0.72rem; font-weight: 800; }
        .badge-completed { background: #f0f4f8; color: #555; border-radius: 7px; padding: 0.2rem 0.7rem; font-size: 0.72rem; font-weight: 800; }
        .badge-cancelled { background: #fde8e8; color: #c0392b; border-radius: 7px; padding: 0.2rem 0.7rem; font-size: 0.72rem; font-weight: 800; }
        .btn-primary-custom {
            background: #1b74e4;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 0.5rem 1.4rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .btn-primary-custom:hover {
            background: #0a5ecf;
            color: #fff;
            transform: translateY(-1px);
        }
        .btn-enrolled {
            background: #ebf5fb;
            color: #1b74e4;
            border: 1.5px solid #1b74e4;
            border-radius: 10px;
            padding: 0.5rem 1.4rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: default;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-closed {
            background: #f0f4f8;
            color: #999;
            border: 1.5px solid #ccc;
            border-radius: 10px;
            padding: 0.5rem 1.4rem;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: not-allowed;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .toast-alert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            padding: 0.875rem 1.5rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 700;
            box-shadow: 0 6px 24px rgba(0,0,0,0.15);
            animation: slideIn 0.3s ease;
        }
        .toast-success { background: #1b74e4; color: #fff; }
        .toast-warning { background: #c9943a; color: #fff; }
        .toast-danger  { background: #c0392b; color: #fff; }
        @keyframes slideIn {
            from { transform: translateX(80px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .empty-state { text-align: center; padding: 3rem 1.5rem; color: #65676b; }
        .empty-state i { font-size: 3rem; opacity: 0.3; margin-bottom: 0.75rem; display: block; }
        .modal-content {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }
        .modal-header-blue {
            background: linear-gradient(135deg, #1b74e4, #0a5ecf);
            color: #fff;
            border: none;
            padding: 1.25rem 1.5rem;
        }
        /* Modal header + close (X) button follow the resident theme:
           - Default (no theme chosen): original blue header, white X
           - Light mode: light header, dark title, BLACK X
           - Dark mode:  dark header, light title, WHITE X
           (resident_theme.css already sets the themed header background; the X/title rules below
           use higher specificity + !important so the theme's filter can't hide them.) */
        .modal-header-blue .btn-close { filter: invert(1); opacity: .9; }
        html[data-theme] body .modal-header.modal-header-blue { background: var(--tm-thead) !important; color: var(--tm-head) !important; }
        html[data-theme] body .modal-header-blue .modal-title,
        html[data-theme] body .modal-header-blue .modal-title i { color: var(--tm-head) !important; }
        /* resident_theme.css has a base reset (background: transparent !important) that wipes the
           close icon's background-image in light/dark mode, so the X is drawn explicitly here. */
        html[data-theme] body .modal-header-blue .btn-close {
            width: 1em; height: 1em; padding: .5em; box-sizing: content-box;
            filter: none !important; opacity: 1 !important;
            background-repeat: no-repeat !important; background-position: center !important; background-size: 1em auto !important;
        }
        html[data-theme="light"] body .modal-header-blue .btn-close { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23000'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") !important; }
        html[data-theme="dark"]  body .modal-header-blue .btn-close { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23fff'%3e%3cpath d='M.293.293a1 1 0 0 1 1.414 0L8 6.586 14.293.293a1 1 0 1 1 1.414 1.414L9.414 8l6.293 6.293a1 1 0 0 1-1.414 1.414L8 9.414l-6.293 6.293a1 1 0 0 1-1.414-1.414L6.586 8 .293 1.707a1 1 0 0 1 0-1.414z'/%3e%3c/svg%3e") !important; }
        /* Youth profile registration — compact multi-column layout on phones */
        @media (max-width: 576px) {
            #youthProfilingModal .modal-body { padding: .85rem !important; }
            #youthProfilingModal .form-label { font-size: .72rem; margin-bottom: 2px; line-height: 1.15; }
            #youthProfilingModal .form-control-sm,
            #youthProfilingModal .form-select-sm { font-size: .8rem; padding-left: .45rem; }
            #youthProfilingModal .form-select-sm { padding-right: 1.4rem; background-position: right .4rem center; }
            #youthProfilingModal #youthPhotoPreview { width: 88px !important; height: 88px !important; margin-bottom: 0 !important; }
            #youthProfilingModal small.text-muted { font-size: .68rem; }
            #youthProfilingModal textarea { min-height: 0; }
            /* Keep Save Profile always visible: body scrolls, footer stays pinned (dvh = real visible height on phones) */
            #youthProfilingModal .modal-footer { flex-shrink: 0; box-shadow: 0 -2px 8px rgba(0,0,0,.08); }
        }

        /* ---- Youth profile modal: tidy mobile layout ---- */
        @media (max-width: 767.98px) {
            #youthProfilingModal .modal-body { background: #f0f2f5; padding: .75rem !important; }
            #youthProfilingModal .modal-body > h6 {
                background: #fff; margin: .75rem 0 0 !important; padding: .6rem .85rem;
                font-size: .82rem; border-radius: 12px 12px 0 0; border-bottom: 1px solid #eef1f5;
            }
            #youthProfilingModal .modal-body > h6:first-child { margin-top: 0 !important; }
            #youthProfilingModal .modal-body > hr { display: none; }
            #youthProfilingModal .modal-body > .row {
                background: #fff; margin: 0 !important; padding: .75rem .5rem .85rem; border-radius: 0 0 12px 12px;
                --bs-gutter-x: .75rem; --bs-gutter-y: .65rem;
            }
            #youthProfilingModal .form-label { font-size: .7rem; margin-bottom: 3px; color: #65676b; }
            #youthProfilingModal .form-control-sm,
            #youthProfilingModal .form-select-sm { font-size: .9rem; padding: .5rem .65rem; min-height: 40px; }
            #youthProfilingModal .form-select-sm { padding-right: 1.8rem; }
            #youthProfilingModal textarea.form-control-sm { min-height: 40px; height: 40px; resize: none; overflow-y: hidden; line-height: 1.35; }
            #youthProfilingModal .yp-photo { display: flex; justify-content: center; }
            #youthProfilingModal #youthPhotoPreview { width: 96px !important; height: 96px !important; margin: 0 !important; }
            #youthProfilingModal small.text-muted { display: block; font-size: .7rem; margin-top: 4px; line-height: 1.3; }
            #youthProfilingModal .yp-view .yp-val { font-size: .85rem; padding: 6px 9px; }
            #youthProfilingModal .modal-footer { padding: .65rem .75rem !important; }
        }
        /* Keep the resident bottom navbar visible (and tappable) while the youth modal is open on mobile.
           The dialog is pinned to the screen edges (top:0 -> just above the nav) instead of using
           100vh/100dvh math, which breaks on phones when the browser toolbar shows/hides and pushed the
           Save button underneath the nav. Body scrolls; header + footer (Save) stay fixed in view. */
        @media (max-width: 767.98px) {
            :root { --rnav-h: 68px; }
            body.modal-open .mobile-bottom-nav { z-index: 2100; }
            #youthProfilingModal .modal-dialog {
                position: fixed;
                top: 0; left: 0; right: 0;
                bottom: var(--rnav-h);            /* nav height already includes its safe-area padding */
                width: auto; max-width: none;
                height: auto; min-height: 0; max-height: none;
                margin: 0;
            }
            #youthProfilingModal .modal-content { height: 100%; max-height: 100%; border-radius: 0; display: flex; flex-direction: column; }
            #youthProfilingModal .modal-content > .modal-header,
            #youthProfilingModal .modal-content > .alert { flex: 0 0 auto; }
            #youthProfilingModal .modal-content > form { display: flex; flex-direction: column; flex: 1 1 auto; min-height: 0; }
            #youthProfilingModal .modal-content > form > .modal-body { flex: 1 1 auto; min-height: 0; max-height: none !important; overflow-y: auto; -webkit-overflow-scrolling: touch; overscroll-behavior: contain; }
            #youthProfilingModal .modal-content > form > .modal-footer { flex: 0 0 auto; }
        }
        @media (max-width: 576px) { :root { --rnav-h: 62px; } }
        .modal-body { background: #f8f9fa; }
        .yp-view .yp-lbl { display:block; font-size:.68rem; font-weight:700; letter-spacing:.04em; text-transform:uppercase; color:#65676b; }
        .yp-view .yp-val { display:block; font-size:.9rem; color:#1c1e21; background:#fff; border:1px solid #e8ecf0; border-radius:8px; padding:7px 10px; margin-top:2px; word-break:break-word; }
        .modal-footer { background: #fff; border-top: 1px solid #dce0e4; }
        .form-control-sm, .form-select-sm {
            border-radius: 8px;
            border: 1px solid #dce0e4;
        }
        .form-control-sm:focus, .form-select-sm:focus {
            border-color: #1b74e4;
            box-shadow: 0 0 0 3px rgba(27, 116, 228, 0.15);
        }
        .form-label {
            font-weight: 600;
            font-size: 0.75rem;
            color: #4b4f56;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        /* Youth profile form: labels (LAST NAME, FIRST NAME, EMAIL, etc.) in bold; entered values stay normal */
        #youthProfilingModal .form-label,
        #youthProfilingModal .yp-view .yp-lbl { font-weight: 800; color: #1c1e21; }
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
        .top-link:hover { background: #0a5ecf; }

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
</head>
<body>

<?php include ROOT_PATH . '/resident_navbar.php'; ?>

<?php if (($_GET['profile'] ?? '') === 'updated'): ?>
<div class="toast-alert toast-success" id="toastAlert"><i class="fas fa-check-circle me-2"></i>Youth profile updated.</div>
<script>setTimeout(()=>{const t=document.getElementById('toastAlert');if(t){t.style.opacity='0';t.style.transition='opacity .5s';setTimeout(()=>t.remove(),500);}},3500);</script>
<?php endif; ?>
<?php if (isset($_GET['enroll'])):
    $tc = 'toast-success'; $ti = 'check-circle'; $tm = '';
    if ($_GET['enroll']==='success')   { $tm = 'Successfully enrolled in the program!'; }
    elseif($_GET['enroll']==='duplicate'){ $tc='toast-warning';$ti='exclamation-circle';$tm='You are already enrolled in this program.'; }
    elseif($_GET['enroll']==='noprofile'){ $tc='toast-danger';$ti='x-circle';$tm='Please complete your Youth Profile first before enrolling.'; }
    elseif($_GET['enroll']==='invalidprogram'){ $tc='toast-danger';$ti='x-circle';$tm='That program is no longer available. Please refresh and try again.'; }
    elseif($_GET['enroll']==='full'){ $tc='toast-danger';$ti='x-circle';$tm='Sorry, this program has reached its slot limit.'; }
?>
<div class="toast-alert <?= $tc ?>" id="toastAlert">
    <i class="fas fa-<?= $ti ?> me-2"></i><?= $tm ?>
</div>
<script>setTimeout(()=>{const t=document.getElementById('toastAlert');if(t){t.style.opacity='0';t.style.transition='opacity .5s';setTimeout(()=>t.remove(),500);}},3500);</script>
<?php endif; ?>

<div class="youth-hero">
    <div class="container">
        <h1><i class="bi bi-megaphone-fill me-2"></i>Youth Portal</h1>
        <p style="opacity:0.9; margin-top:0.25rem;">Your hub for SK announcements, programs, and youth activities</p>
        <div class="hero-actions">
            <button type="button" class="btn-hero btn-hero-light" data-bs-toggle="modal" data-bs-target="#youthProfilingModal">
                <i class="fas fa-id-card me-2"></i>Youth Profiling
            </button>
            <a href="?tab=programs" class="btn-hero btn-hero-outline">
                <i class="bi bi-calendar-event-fill me-2"></i>Browse Programs
            </a>
        </div>
    </div>
</div>

<div class="container py-2 pb-5">

    <div class="portal-tabs">
        <a href="?tab=announcements" class="portal-tab <?= $active_tab==='announcements'?'active':'' ?>">
            <i class="bi bi-megaphone-fill"></i> Bulletins
        </a>
        <a href="?tab=programs" class="portal-tab <?= $active_tab==='programs'?'active':'' ?>">
            <i class="bi bi-calendar-event-fill"></i> Programs
        </a>
        <a href="?tab=myenrollments" class="portal-tab <?= $active_tab==='myenrollments'?'active':'' ?>">
            <i class="bi bi-person-check-fill"></i> My Enrollments
        </a>
    </div>

    <?php if ($active_tab === 'announcements'): ?>

    <div class="section-panel mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="mb-0 fw-bold" style="color:#1b74e4;"><i class="bi bi-pin-angle-fill me-2"></i>SK Bulletins</h5>
                <small class="text-muted">Official announcements from your Sangguniang Kabataan</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="?tab=announcements" class="chip-filter <?= !$type_filter?'active':'' ?>">All</a>
                <?php foreach ($post_types as $pt): ?>
                <a href="?tab=announcements&type=<?= urlencode($pt) ?>" class="chip-filter <?= $type_filter===$pt?'active':'' ?>"><?= $pt ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (empty($bulletins)): ?>
        <div class="section-panel empty-state">
            <i class="bi bi-megaphone"></i>
            <p class="fw-semibold">No bulletins posted yet.</p>
            <small class="text-muted">Check back later for updates.</small>
        </div>
    <?php else:
        $pinned  = array_filter($bulletins, fn($b) => $b['is_pinned']);
        $regular = array_filter($bulletins, fn($b) => !$b['is_pinned']);
        $tc_map  = ['Announcement'=>'t-announcement','Opportunity'=>'t-opportunity','Reminder'=>'t-reminder','Achievement'=>'t-achievement','General'=>'t-general'];
    ?>

    <?php if ($pinned): ?>
    <div class="d-flex align-items-center gap-2 mb-3">
        <i class="bi bi-pin-fill" style="color:#c9943a;"></i>
        <span class="fw-bold" style="font-size:0.8rem;color:#c9943a;text-transform:uppercase;letter-spacing:0.08em;">Pinned</span>
    </div>
    <div class="row g-3 mb-4">
    <?php foreach ($pinned as $b): $tc = $tc_map[$b['post_type']] ?? 't-general'; ?>
    <div class="col-md-6 col-xl-4">
        <div class="bulletin-card pinned">
            <div class="pin-ribbon"><i class="bi bi-pin-fill me-1"></i>Pinned</div>
            <span class="b-type-badge <?= $tc ?>"><?= htmlspecialchars($b['post_type']) ?></span>
            <div class="b-title"><?= htmlspecialchars($b['post_title']) ?></div>
            <div class="b-content"><?= sk_linkify($b['post_content']) ?></div>
            <div class="b-meta">
                <span><i class="fas fa-user"></i><?= htmlspecialchars($b['posted_by']) ?></span>
                <span><i class="fas fa-calendar"></i><?= date('M d, Y', strtotime($b['date_posted'])) ?></span>
                <span><i class="fas fa-clock"></i><?= date('h:i A', strtotime($b['date_posted'])) ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if ($regular): ?>
    <?php if ($pinned): ?>
    <div class="d-flex align-items-center gap-2 mb-3">
        <span class="fw-bold" style="font-size:0.8rem;color:#65676b;text-transform:uppercase;letter-spacing:0.08em;">Other Posts</span>
    </div>
    <?php endif; ?>
    <div class="row g-3">
    <?php foreach ($regular as $b): $tc = $tc_map[$b['post_type']] ?? 't-general'; ?>
    <div class="col-md-6 col-xl-4">
        <div class="bulletin-card">
            <span class="b-type-badge <?= $tc ?>"><?= htmlspecialchars($b['post_type']) ?></span>
            <div class="b-title"><?= htmlspecialchars($b['post_title']) ?></div>
            <div class="b-content"><?= sk_linkify($b['post_content']) ?></div>
            <div class="b-meta">
                <span><i class="fas fa-user"></i><?= htmlspecialchars($b['posted_by']) ?></span>
                <span><i class="fas fa-calendar"></i><?= date('M d, Y', strtotime($b['date_posted'])) ?></span>
                <span><i class="fas fa-clock"></i><?= date('h:i A', strtotime($b['date_posted'])) ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

    <?php elseif ($active_tab === 'programs'): ?>

    <div class="section-panel mb-4">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="mb-0 fw-bold" style="color:#1b74e4;"><i class="bi bi-calendar-event-fill me-2"></i>Youth Programs</h5>
                <small class="text-muted">Browse and enroll in SK programs and activities</small>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="?tab=programs" class="chip-filter <?= !$status_filter?'active':'' ?>">All</a>
                <?php foreach ($prog_statuses as $st): ?>
                <a href="?tab=programs&pstatus=<?= urlencode($st) ?>" class="chip-filter <?= $status_filter===$st?'active':'' ?>"><?= $st ?></a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <?php if (empty($programs)): ?>
        <div class="section-panel empty-state">
            <i class="bi bi-calendar-x"></i>
            <p class="fw-semibold">No programs available at the moment.</p>
            <small class="text-muted">Check back later for upcoming activities.</small>
        </div>
    <?php else: ?>
    <div class="row g-3">
    <?php foreach ($programs as $p):
        $lcst = strtolower($p['status']);
        $badge_map = ['Upcoming'=>'badge-upcoming','Ongoing'=>'badge-ongoing','Completed'=>'badge-completed','Cancelled'=>'badge-cancelled'];
        $badge = $badge_map[$p['status']] ?? 'badge-completed';
        $is_enrolled = in_array($p['id_program'], $my_enrolled);
        $can_enroll  = in_array($p['status'], ['Upcoming','Ongoing']);
        $taken       = $prog_slot_counts[(int)$p['id_program']] ?? 0;
        $is_full     = !empty($p['slots']) && $taken >= (int)$p['slots'];
    ?>
    <div class="col-md-6 col-xl-4">
        <div class="prog-card <?= $lcst ?>">
            <div class="d-flex justify-content-between align-items-start">
                <span class="badge-ptype"><?= htmlspecialchars($p['program_type']) ?></span>
                <span class="<?= $badge ?>"><?= htmlspecialchars($p['status']) ?></span>
            </div>
            <div class="p-title"><?= htmlspecialchars($p['program_title']) ?></div>
            <div class="p-meta">
                <?php if ($p['venue']): ?><span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($p['venue']) ?></span><?php endif; ?>
                <?php if ($p['event_date']): ?><span><i class="fas fa-calendar"></i><?= date('M d, Y', strtotime($p['event_date'])) ?></span><?php endif; ?>
                <?php if ($p['event_time']): ?><span><i class="fas fa-clock"></i><?= date('h:i A', strtotime($p['event_time'])) ?></span><?php endif; ?>
                <?php if ($p['slots']): ?><span><i class="fas fa-users"></i><?= $taken ?> / <?= $p['slots'] ?> slots<?= $is_full ? ' — Full' : '' ?></span><?php endif; ?>
            </div>
            <?php if ($p['description']): ?><div class="p-desc"><?= htmlspecialchars(substr($p['description'],0,150)).(strlen($p['description'])>150?'…':'') ?></div><?php endif; ?>
            <?php if ($p['requirements']): ?><div class="p-req"><i class="fas fa-clipboard me-1"></i><em><?= htmlspecialchars($p['requirements']) ?></em></div><?php endif; ?>

            <?php if ($is_enrolled): ?>
                <span class="btn-enrolled"><i class="bi bi-check-circle-fill"></i> Enrolled</span>
            <?php elseif (!$can_enroll): ?>
                <span class="btn-closed"><i class="bi bi-x-circle"></i> <?= htmlspecialchars($p['status']) ?></span>
            <?php elseif ($is_full): ?>
                <span class="btn-closed"><i class="bi bi-slash-circle"></i> slots Reached Limit</span>
            <?php elseif (!$youth_id_resolved || $youth_profile_incomplete): ?>
                <button class="btn-closed"
                    data-bs-toggle="modal" data-bs-target="#youthProfilingModal"
                    style="cursor:pointer; border:1.5px solid #c9943a; background:#fdf3e3; color:#c9943a;">
                    <i class="bi bi-person-x-fill"></i> Complete Profile to Enroll
                </button>
            <?php else: ?>
                <button class="btn-primary-custom"
                    data-bs-toggle="modal" data-bs-target="#enrollModal"
                    data-id="<?= $p['id_program'] ?>"
                    data-title="<?= htmlspecialchars($p['program_title'],ENT_QUOTES) ?>"
                    data-date="<?= $p['event_date'] ? date('M d, Y', strtotime($p['event_date'])) : 'TBA' ?>"
                    data-venue="<?= htmlspecialchars($p['venue']??'TBA',ENT_QUOTES) ?>">
                    <i class="bi bi-person-plus-fill"></i> Enroll Now
                </button>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php elseif ($active_tab === 'myenrollments'): ?>
    <?php
    $me_stmt = $conn->prepare("
        SELECT e.*, p.program_title, p.program_type, p.event_date, p.event_time, p.venue, p.status AS prog_status
        FROM tbl_youth_enrollment e
        JOIN tbl_youth_programs p ON e.id_program = p.id_program
        WHERE e.id_youth = ? ORDER BY e.enrolled_at DESC
    ");
    $me_stmt->execute([$youth_id_resolved ?? 0]);
    $my_programs = $me_stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="section-panel mb-4">
        <h5 class="mb-0 fw-bold" style="color:#1b74e4;"><i class="bi bi-person-check-fill me-2"></i>My Program Enrollments</h5>
        <small class="text-muted">Track your participation in SK youth programs</small>
    </div>

    <?php if (empty($my_programs)): ?>
        <div class="section-panel empty-state">
            <i class="bi bi-clipboard-x"></i>
            <p class="fw-semibold">You haven't enrolled in any programs yet.</p>
            <a href="?tab=programs" class="btn-primary-custom" style="margin:auto;">
                <i class="bi bi-calendar-event-fill"></i> Browse Programs
            </a>
        </div>
    <?php else:
        $total_my   = count($my_programs);
        $attended_c = count(array_filter($my_programs, fn($e)=>$e['status']==='Attended'));
        $enrolled_c = count(array_filter($my_programs, fn($e)=>$e['status']==='Enrolled'));
        $dropped_c  = count(array_filter($my_programs, fn($e)=>$e['status']==='Dropped'));
    ?>
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="section-panel text-center py-3">
                <div style="font-size:2rem;font-weight:900;color:#1b74e4;"><?= $total_my ?></div>
                <div style="font-size:0.75rem;color:#65676b;font-weight:700;text-transform:uppercase;">Total</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="section-panel text-center py-3">
                <div style="font-size:2rem;font-weight:900;color:#2471a3;"><?= $enrolled_c ?></div>
                <div style="font-size:0.75rem;color:#65676b;font-weight:700;text-transform:uppercase;">Enrolled</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="section-panel text-center py-3">
                <div style="font-size:2rem;font-weight:900;color:#27ae60;"><?= $attended_c ?></div>
                <div style="font-size:0.75rem;color:#65676b;font-weight:700;text-transform:uppercase;">Attended</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="section-panel text-center py-3">
                <div style="font-size:2rem;font-weight:900;color:#c0392b;"><?= $dropped_c ?></div>
                <div style="font-size:0.75rem;color:#65676b;font-weight:700;text-transform:uppercase;">Dropped</div>
            </div>
        </div>
    </div>
    <div class="row g-3">
    <?php foreach ($my_programs as $ep):
        $ep_badge_map = ['Enrolled'=>'badge-upcoming','Attended'=>'badge-ongoing','Dropped'=>'badge-cancelled'];
        $ep_badge = $ep_badge_map[$ep['status']] ?? 'badge-upcoming';
        $ps_badge_map = ['Upcoming'=>'badge-upcoming','Ongoing'=>'badge-ongoing','Completed'=>'badge-completed','Cancelled'=>'badge-cancelled'];
        $ps_badge = $ps_badge_map[$ep['prog_status']] ?? 'badge-completed';
    ?>
    <div class="col-md-6">
        <div class="section-panel" style="border-left:5px solid #1b74e4;">
            <div class="d-flex justify-content-between align-items-start mb-2 flex-wrap gap-2">
                <span class="badge-ptype"><?= htmlspecialchars($ep['program_type']) ?></span>
                <div class="d-flex gap-2 flex-wrap">
                    <span class="<?= $ps_badge ?>"><?= htmlspecialchars($ep['prog_status']) ?></span>
                    <span class="<?= $ep_badge ?>">My Status: <?= htmlspecialchars($ep['status']) ?></span>
                </div>
            </div>
            <div class="p-title"><?= htmlspecialchars($ep['program_title']) ?></div>
            <div class="p-meta">
                <?php if ($ep['venue']): ?><span><i class="fas fa-map-marker-alt"></i><?= htmlspecialchars($ep['venue']) ?></span><?php endif; ?>
                <?php if ($ep['event_date']): ?><span><i class="fas fa-calendar"></i><?= date('M d, Y', strtotime($ep['event_date'])) ?></span><?php endif; ?>
                <?php if ($ep['event_time']): ?><span><i class="fas fa-clock"></i><?= date('h:i A', strtotime($ep['event_time'])) ?></span><?php endif; ?>
                <span><i class="fas fa-user-check"></i>Enrolled: <?= date('M d, Y', strtotime($ep['enrolled_at'])) ?></span>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <?php endif; ?>

</div>

<?php
// Inline SVG placeholder (external placeholder images are blocked by the CSP)
$yp_placeholder = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="#e9eef7"/><circle cx="50" cy="38" r="17" fill="#9db2d3"/><path d="M16 92c2-22 18-32 34-32s32 10 34 32z" fill="#9db2d3"/></svg>');
?>
<!-- YOUTH PROFILING MODAL -->
<div class="modal fade" id="youthProfilingModal" tabindex="-1" aria-labelledby="youthProfilingTitle" aria-hidden="true"
    <?php if ($youth_profile_incomplete): ?>data-bs-backdrop="static" data-bs-keyboard="false"<?php endif; ?>>
    <div class="modal-dialog modal-dialog-centered modal-lg modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header modal-header-blue">
                <h5 class="modal-title fw-bold" id="youthProfilingTitle">
                    <i class="fas fa-id-card me-2"></i> <?= $youth_profile_incomplete ? 'Youth Profile Registration' : 'My Youth Profile' ?>
                </h5>
                <?php if (!$youth_profile_incomplete): ?>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <?php endif; ?>
            </div>
            <?php if ($youth_profile_incomplete): ?>
            <div class="alert alert-info rounded-0 mb-0 px-3 px-md-4 py-2">
                <i class="bi bi-info-circle-fill me-1"></i>
                Welcome! Since you're registering as SK youth, please complete this profile first before you can use the rest of the resident portal.
            </div>
            <?php endif; ?>
<?php if ($youth_profile_incomplete): ?>
            <form method="post" class="was-validated show-loader" data-loader-msg="Saving youth profile..." enctype="multipart/form-data">
                <div class="modal-body p-3 p-md-4" style="max-height:75vh;overflow-y:auto;">

                    <h6 class="text-primary fw-bold mb-2"><i class="fas fa-user me-1"></i> Personal Information</h6>
                    <hr class="mt-0 mb-3">

                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-12 col-md-3 text-center yp-photo">
                            <img id="youthPhotoPreview"
                                src="<?= $existing_youth_photo ? 'uploads/youth_photos/' . htmlspecialchars($existing_youth_photo) : $yp_placeholder ?>"
                                alt="Youth photo" class="rounded-circle mb-2"
                                style="width:100px;height:100px;object-fit:cover;border:2px solid #e8ecf0;">
                        </div>
                        <div class="col-12 col-md-9">
                            <label class="form-label">Photo</label>
                            <input name="photo" type="file" accept="image/png,image/jpeg,image/gif,image/webp"
                                class="form-control form-control-sm" <?= $existing_youth_photo ? '' : 'required' ?>>
                            <small class="text-muted">JPG, PNG, GIF, or WebP — max 5 MB. <?= $existing_youth_photo ? 'Leave blank to keep your current photo.' : 'Required.' ?></small>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6 col-md-4">
                            <label class="form-label">Last Name</label>
                            <input name="lname" type="text" class="form-control form-control-sm" placeholder="Required"
                                value="<?= htmlspecialchars($youth_data['lname'] ?? $resident_row['lname'] ?? '') ?>" required>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">First Name</label>
                            <input name="fname" type="text" class="form-control form-control-sm" placeholder="Required"
                                value="<?= htmlspecialchars($youth_data['fname'] ?? $resident_row['fname'] ?? '') ?>" required>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input name="mi" type="text" class="form-control form-control-sm" placeholder="Required"
                                value="<?= htmlspecialchars($youth_data['mi'] ?? $resident_row['mi'] ?? '') ?>" required>
                        </div>
                                            <div class="col-6 col-md-4">
                            <label class="form-label">Age</label>
                            <?php
                                $computed_age = '';
                                if (!empty($userdetails['bdate'])) {
                                    $bdate_obj = new DateTime($userdetails['bdate']);
                                    $computed_age = (new DateTime())->diff($bdate_obj)->y;
                                } elseif (!empty($userdetails['age'])) {
                                    $computed_age = $userdetails['age'];
                                }
                            ?>
                            <input name="age" type="number" class="form-control form-control-sm"
                                value="<?= htmlspecialchars($computed_age) ?>" readonly tabindex="-1" style="background:#f4f7fb;">
                            <small class="text-muted d-none d-md-block">Based on your birthdate on file</small>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Sex</label>
                            <select name="sex" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Male"   <?= (isset($userdetails['sex']) && $userdetails['sex']==='Male')   ? 'selected':'' ?>>Male</option>
                                <option value="Female" <?= (isset($userdetails['sex']) && $userdetails['sex']==='Female') ? 'selected':'' ?>>Female</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Civil Status</label>
                            <select name="civil_status" class="form-select form-select-sm" required>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Solo Parent">Solo Parent</option>
                                <option value="Widowed">Widowed</option>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-3 mb-2"><i class="fas fa-at me-1"></i> Contact & Education</h6>
                    <hr class="mt-0 mb-3">
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-sm" name="email_address"
                                placeholder="name@example.com" required inputmode="email"
                                value="<?= htmlspecialchars($resident_row['email'] ?? '') ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Educational Attainment</label>
                            <select name="educ_attain" id="educSelect" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Elementary Level">Elementary Level</option>
                                <option value="Elementary Graduate">Elementary Graduate</option>
                                <option value="High School Level">High School Level</option>
                                <option value="High School Graduate">High School Graduate</option>
                                <option value="Senior High School Level">Senior High School Level</option>
                                <option value="Senior High School Graduate">Senior High School Graduate</option>
                                <option value="Vocational / Technical">Vocational / Technical</option>
                                <option value="College Level">College Level</option>
                                <option value="College Graduate">College Graduate</option>
                                <option value="Post Graduate">Post Graduate</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" name="educ_attain_other" id="educOther" class="form-control form-control-sm mt-1 d-none"
                                placeholder="Please specify" maxlength="100">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Employment Status</label>
                            <select name="emp_status" id="empSelect" class="form-select form-select-sm" required>
                                <option value="" disabled selected>Select</option>
                                <option value="Employed">Employed</option>
                                <option value="Unemployed">Unemployed</option>
                                <option value="Self-Employed">Self-Employed</option>
                                <option value="Student">Student</option>
                                <option value="Other">Other</option>
                            </select>
                            <input type="text" name="emp_status_other" id="empOther" class="form-control form-control-sm mt-1 d-none"
                                placeholder="Please specify" maxlength="100">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Special Skills / Interests</label>
                            <textarea class="form-control form-control-sm" name="skill_name" rows="1"
                                placeholder="e.g. Graphic Design, Public Speaking, Sports" required></textarea>
                        </div>
                    </div>
                <?php $bmis_consent_id = 'consent_youth'; $bmis_consent_purpose = 'create and manage my youth profile'; $bmis_consent_third = false; include __DIR__ . '/partials/consent_checkbox.php'; ?>
</div>
                <div class="modal-footer bg-light p-3 gap-2 flex-nowrap">
                    <input name="id_youth" type="hidden" value="<?= $userdetails['id_resident'] ?? '' ?>">
                    <input type="hidden" name="create_youth" value="1">
                    <?php if (!$youth_profile_incomplete): ?>
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <?php endif; ?>
                    <button name="create_youth" type="submit" class="btn btn-primary btn-sm px-4 fw-bold rounded-pill flex-grow-1 flex-md-grow-0">
                        <i class="fas fa-save me-1"></i> Save Profile
                    </button>
                </div>
            </form>
            <?php else: ?>
            <?php
                $yd = $youth_data; $rd = $resident_row;
                $bd_fmt = !empty($rd['bdate']) ? date('F j, Y', strtotime($rd['bdate'])) : '—';
                $ro_age = !empty($rd['bdate']) ? (new DateTime())->diff(new DateTime($rd['bdate']))->y : ($yd['age'] ?? '');
                $addr_parts = array_filter([trim(($rd['houseno'] ?? '') . ' ' . ($rd['street'] ?? '')), $rd['brgy'] ?? '', $rd['municipal'] ?? '', $rd['province'] ?? ''], function($v){ return trim((string)$v) !== ''; });
                $h = function($v) { return htmlspecialchars((string)$v); };
                $educ_opts = ['Elementary Level','Elementary Graduate','High School Level','High School Graduate','Senior High School Level','Senior High School Graduate','Vocational / Technical','College Level','College Graduate','Post Graduate'];
                $emp_opts  = ['Employed','Unemployed','Self-Employed','Student'];
                $civil_opts = ['Single','Married','Solo Parent','Widowed'];
                $educ_cur = trim((string)($yd['educ_attain'] ?? '')); $emp_cur = trim((string)($yd['emp_status'] ?? ''));
                $educ_is_other = $educ_cur !== '' && !in_array($educ_cur, $educ_opts, true);
                $emp_is_other  = $emp_cur  !== '' && !in_array($emp_cur,  $emp_opts,  true);
            ?>
            <form method="post" class="show-loader" data-loader-msg="Saving changes..." enctype="multipart/form-data" novalidate>
                <div class="modal-body p-3 p-md-4" style="max-height:75vh;overflow-y:auto;">

                    <h6 class="text-primary fw-bold mb-2"><i class="fas fa-user me-1"></i> Personal Information</h6>
                    <hr class="mt-0 mb-3">

                    <div class="row g-2 mb-2 align-items-center">
                        <div class="col-12 col-md-3 text-center yp-photo">
                            <img id="youthPhotoPreview"
                                src="<?= !empty($yd['photo']) ? 'uploads/youth_photos/' . $h($yd['photo']) : $yp_placeholder ?>"
                                alt="Youth photo" class="rounded-circle mb-2"
                                style="width:100px;height:100px;object-fit:cover;border:2px solid #e8ecf0;">
                        </div>
                        <div class="col-12 col-md-9">
                            <label class="form-label">Photo</label>
                            <input name="photo" type="file" accept="image/png,image/jpeg,image/gif,image/webp" class="form-control form-control-sm">
                            <small class="text-muted">Optional. JPG, PNG, GIF, or WebP — max 5 MB. Leave blank to keep your current photo.</small>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6 col-md-4">
                            <label class="form-label">Last Name</label>
                            <input name="lname" type="text" class="form-control form-control-sm" value="<?= $h($yd['lname'] ?? '') ?>">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">First Name</label>
                            <input name="fname" type="text" class="form-control form-control-sm" value="<?= $h($yd['fname'] ?? '') ?>">
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Middle Name</label>
                            <input name="mi" type="text" class="form-control form-control-sm" value="<?= $h($yd['mi'] ?? '') ?>">
                        </div>
                                            <div class="col-6 col-md-4">
                            <label class="form-label">Age</label>
                            <input type="number" class="form-control form-control-sm" value="<?= $h($ro_age) ?>" readonly tabindex="-1" style="background:#f4f7fb;">
                            <small class="text-muted d-none d-md-block">Based on your birthdate on file</small>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Sex</label>
                            <select name="sex" class="form-select form-select-sm">
                                <option value="" disabled <?= empty($yd['sex']) ? 'selected' : '' ?>>Select</option>
                                <?php foreach (['Male','Female'] as $o): ?>
                                <option value="<?= $o ?>" <?= ($yd['sex'] ?? '') === $o ? 'selected' : '' ?>><?= $o ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-6 col-md-4">
                            <label class="form-label">Civil Status</label>
                            <select name="civil_status" class="form-select form-select-sm">
                                <?php foreach ($civil_opts as $o): ?>
                                <option value="<?= $o ?>" <?= ($yd['civil_status'] ?? '') === $o ? 'selected' : '' ?>><?= $o ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-3 mb-2"><i class="fas fa-at me-1"></i> Contact &amp; Education</h6>
                    <hr class="mt-0 mb-3">
                    <div class="row g-2">
                        <div class="col-12 col-md-6">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control form-control-sm" name="email_address" placeholder="name@example.com"
                                inputmode="email" value="<?= $h($yd['email_address'] ?? '') ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Educational Attainment</label>
                            <select name="educ_attain" id="educSelect" class="form-select form-select-sm">
                                <option value="" <?= $educ_cur === '' ? 'selected' : '' ?>>Select</option>
                                <?php foreach ($educ_opts as $o): ?>
                                <option value="<?= $h($o) ?>" <?= $educ_cur === $o ? 'selected' : '' ?>><?= $h($o) ?></option>
                                <?php endforeach; ?>
                                <option value="Other" <?= $educ_is_other ? 'selected' : '' ?>>Other</option>
                            </select>
                            <input type="text" name="educ_attain_other" id="educOther" class="form-control form-control-sm mt-1 <?= $educ_is_other ? '' : 'd-none' ?>"
                                placeholder="Please specify" maxlength="100" value="<?= $educ_is_other ? $h($educ_cur) : '' ?>">
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label">Employment Status</label>
                            <select name="emp_status" id="empSelect" class="form-select form-select-sm">
                                <option value="" <?= $emp_cur === '' ? 'selected' : '' ?>>Select</option>
                                <?php foreach ($emp_opts as $o): ?>
                                <option value="<?= $h($o) ?>" <?= $emp_cur === $o ? 'selected' : '' ?>><?= $h($o) ?></option>
                                <?php endforeach; ?>
                                <option value="Other" <?= $emp_is_other ? 'selected' : '' ?>>Other</option>
                            </select>
                            <input type="text" name="emp_status_other" id="empOther" class="form-control form-control-sm mt-1 <?= $emp_is_other ? '' : 'd-none' ?>"
                                placeholder="Please specify" maxlength="100" value="<?= $emp_is_other ? $h($emp_cur) : '' ?>">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Special Skills / Interests</label>
                            <textarea class="form-control form-control-sm" name="skill_name" rows="1"
                                placeholder="e.g. Graphic Design, Public Speaking, Sports"><?= $h($yd['skill_name'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <h6 class="text-primary fw-bold mt-3 mb-2"><i class="fas fa-address-card me-1"></i> On File <small class="text-muted fw-normal">(from your resident record)</small></h6>
                    <hr class="mt-0 mb-2">
                    <div class="row g-2 yp-view">
                        <div class="col-6"><span class="yp-lbl">Birthdate</span><span class="yp-val"><?= $h($bd_fmt) ?></span></div>
                        <div class="col-6"><span class="yp-lbl">Contact Number</span><span class="yp-val"><?= $h(($rd['contact'] ?? '') !== '' ? $rd['contact'] : '—') ?></span></div>
                        <div class="col-12"><span class="yp-lbl">Address</span><span class="yp-val"><?= $h($addr_parts ? implode(', ', $addr_parts) : '—') ?></span></div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3 gap-2 flex-nowrap">
                    <input type="hidden" name="update_youth_profile" value="1">
                    <button type="button" class="btn btn-secondary btn-sm px-3 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm px-4 fw-bold rounded-pill flex-grow-1 flex-md-grow-0">
                        <i class="fas fa-save me-1"></i> Save Changes
                    </button>
                </div>
            </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ENROLL CONFIRMATION MODAL -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header modal-header-blue">
                <h5 class="modal-title"><i class="bi bi-person-plus-fill me-2"></i>Confirm Enrollment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <p class="mb-3">You are about to enroll in:</p>
                <div class="section-panel" style="border-left:4px solid #1b74e4;">
                    <div class="fw-bold" style="color:#1b74e4;font-size:1rem;" id="modal_prog_title">—</div>
                    <div class="p-meta mt-2">
                        <span><i class="fas fa-calendar"></i><span id="modal_prog_date">—</span></span>
                        <span><i class="fas fa-map-marker-alt"></i><span id="modal_prog_venue">—</span></span>
                    </div>
                </div>
                <p class="mt-3 mb-0" style="font-size:0.85rem;color:#65676b;">
                    <i class="bi bi-info-circle me-1"></i>Your profile info will be submitted. The SK admin will track your participation.
                </p>
            </div>
            <div class="modal-footer bg-light px-4 py-3">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" class="show-loader" data-loader-msg="Enrolling in program...">
                    <input type="hidden" name="id_program" id="modal_prog_id">
                    <input type="hidden" name="enroll_program" value="1">
                    <button type="submit" name="enroll_program" class="btn-primary-custom">
                        <i class="bi bi-check-circle-fill"></i> Confirm Enrollment
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
        if (form.classList.contains('was-validated') && !form.checkValidity()) return;
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

// Show the same loading overlay used for the forms above when tapping any
// tab, filter chip, or "See Programs"-style link on this page, since those
// reload the page with a new ?tab=... query string. Buttons that just open
// a modal (data-bs-toggle="modal") are left alone since they don't navigate.
document.querySelectorAll('.portal-tab, .chip-filter, .btn-hero-outline, .btn-primary-custom').forEach(function (link) {
    if (link.tagName !== 'A') return;
    link.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;
        const href = link.getAttribute('href');
        if (!href || href === '#' || href.indexOf('javascript:') === 0) return;
        showLoader('Loading...');
    });
});

<?php if (!$youth_profile_incomplete && !empty($photo_msg)): ?>
document.addEventListener('DOMContentLoaded', function() {
    const m = document.getElementById('youthProfilingModal');
    if (m) { new bootstrap.Modal(m).show(); }
});
<?php endif; ?>

<?php if ($youth_profile_incomplete): ?>
document.addEventListener('DOMContentLoaded', function() {
    const youthModalEl = document.getElementById('youthProfilingModal');
    if (youthModalEl) {
        const youthModal = new bootstrap.Modal(youthModalEl);
        youthModal.show();
    }
});
<?php endif; ?>

document.getElementById('enrollModal').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    document.getElementById('modal_prog_id').value          = b.dataset.id;
    document.getElementById('modal_prog_title').textContent = b.dataset.title;
    document.getElementById('modal_prog_date').textContent  = b.dataset.date;
    document.getElementById('modal_prog_venue').textContent = b.dataset.venue;
});
</script>
<script>
// "Other" → show a required "Please specify" box (educational attainment / employment status)
(function () {
    function wire(selId, otherId) {
        var sel = document.getElementById(selId), other = document.getElementById(otherId);
        if (!sel || !other) return;
        function sync() {
            var on = sel.value === 'Other';
            other.classList.toggle('d-none', !on);
            other.required = on;
            if (!on) other.value = '';
            else other.focus();
        }
        sel.addEventListener('change', sync);
    }
    wire('educSelect', 'educOther');
    wire('empSelect', 'empOther');
})();
</script>
<script>
// Live photo preview (FileReader → data: URL, works under the site CSP and on phones)
(function () {
    function wire(input, img) {
        if (!input || !img) return;
        input.addEventListener('change', function () {
            var f = input.files && input.files[0];
            if (!f) return;
            if (f.size > 5 * 1024 * 1024) { alert('Photo is too large (max 5 MB).'); input.value = ''; return; }
            var r = new FileReader();
            r.onload = function (e) {
                img.onerror = function () {
                    alert('That photo format can\'t be used. Please choose a JPG, PNG, GIF or WebP image.');
                    input.value = '';
                };
                img.src = e.target.result;
            };
            r.readAsDataURL(f);
        });
    }
    wire(document.querySelector('#youthProfilingModal input[name="photo"]'), document.getElementById('youthPhotoPreview'));
    var upd = document.querySelector('input[name="update_youth_photo"]');
    if (upd && upd.form) wire(upd.form.querySelector('input[name="photo"]'), document.getElementById('youthPhotoCurrent'));
})();
</script>
<script>
// Special Skills box: compact by default, grows with the text
(function () {
    var ta = document.querySelector('#youthProfilingModal textarea[name="skill_name"]');
    if (!ta) return;
    function fit() { ta.style.height = 'auto'; ta.style.height = Math.min(ta.scrollHeight + 2, 160) + 'px'; }
    ta.addEventListener('input', fit);
    var m = document.getElementById('youthProfilingModal');
    if (m) m.addEventListener('shown.bs.modal', fit);
})();
</script>
<?php include __DIR__ . '/partials/legal_widgets.php'; ?>
</body>
</html>