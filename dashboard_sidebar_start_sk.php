<?php
// dashboard_sidebar_start_sk.php — SK Portal Sidebar
// Usage: define('BMIS_ROLE_REQUIRED', 'sk'); require('secure_header.php'); include('dashboard_sidebar_start_sk.php');
//
// Uses the SAME design system as the admin portal (css/portal-theme.css is the
// admin sidebar's stylesheet): navy/gold look, Default / Light / Dark theme
// switcher, and the Text size + Font menu. Preferences are shared with the
// admin portal through the same localStorage keys (bmis_theme, bmis_fontsize,
// bmis_font).
$__portal_css_v = @filemtime(__DIR__ . '/css/portal-theme.css') ?: 1;
$__sk_mobile_v  = @filemtime(__DIR__ . '/css/sk-mobile.css') ?: 1;
$__sk_mobile_js = @filemtime(__DIR__ . '/js/sk-mobile.js') ?: 1;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover, shrink-to-fit=no">
    <meta name="theme-color" content="#0b2b5c">
    <title>SK Portal — Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">

    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Font choices offered in the Text & Font menu -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Open+Sans:wght@400;600;700&family=Lato:wght@400;700&family=Montserrat:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <!-- Text size + font — applied before paint to avoid flash -->
    <script>
    try {
        var __fs = localStorage.getItem('bmis_fontsize');
        if (__fs === 'small' || __fs === 'large') document.documentElement.setAttribute('data-fontsize', __fs);
        var __ff = localStorage.getItem('bmis_font');
        if (["nunito", "inter", "poppins", "roboto", "opensans", "lato", "montserrat", "merriweather", "serif", "verdana", "mono"].indexOf(__ff) > -1) document.documentElement.setAttribute('data-font', __ff);
    } catch (e) {}
    </script>
    <!-- Theme (default / light / dark) — applied before paint to avoid flash -->
    <script>
    try {
        var __t = localStorage.getItem('bmis_theme');
        if (__t === 'light' || __t === 'dark') document.documentElement.setAttribute('data-theme', __t);
    } catch (e) {}
    </script>
</head>
<link href="css/portal-theme.css?v=<?= (int)$__portal_css_v ?>" rel="stylesheet">
<style>
/* ============================================================
   SK PORTAL — page classes that the shared admin theme doesn't know about.
   Default mode keeps each page's own colours; Light/Dark map them onto
   the same --tm-* tokens the admin portal uses.
   ============================================================ */
html[data-theme] {
    --tm-accent: var(--tm-btn-bg);
    --sk: var(--tm-head);             --sk-mid: var(--tm-head2);
    --sk-green: var(--tm-head);       --sk-green-mid: var(--tm-head2);   --sk-green-light: var(--tm-muted);
    --sk-green-pale: var(--tm-surface2); --sk-pale: var(--tm-surface2);
    --sk-gold: var(--tm-line);        --sk-gold-light: var(--tm-muted);  --sk-gold-pale: var(--tm-surface2);
    --radius: 14px; --radius-sm: 10px;
}
/* surfaces */
html[data-theme] .sk-page-header,
html[data-theme] .panel,
html[data-theme] .post-card,
html[data-theme] .quick-link,
html[data-theme] .prog-table-wrap { background: var(--tm-surface) !important; border: 1px solid var(--tm-border) !important; box-shadow: var(--tm-shadow) !important; }
html[data-theme] .post-card.pinned { border-left: 4px solid var(--tm-muted) !important; }
html[data-theme] .quick-link:hover { background: var(--tm-hover) !important; }
/* stat tiles */
html[data-theme] .stat-icon { background: var(--tm-surface2) !important; border: 1px solid var(--tm-border) !important; }
html[data-theme] .stat-icon i, html[data-theme] .quick-link i, html[data-theme] .panel-title i { color: var(--tm-muted) !important; }
html[data-theme] .stat-val, html[data-theme] .panel-title, html[data-theme] .post-title,
html[data-theme] .post-card-title, html[data-theme] .prog-title-cell { color: var(--tm-head) !important; }
html[data-theme] .stat-lbl, html[data-theme] .post-meta, html[data-theme] .post-meta span,
html[data-theme] .post-card-meta, html[data-theme] .prog-desc-cell, html[data-theme] .prog-req-cell,
html[data-theme] .prog-meta-cell { color: var(--tm-muted) !important; }
/* buttons */
html[data-theme] .btn-sk { background: var(--tm-btn-bg) !important; color: var(--tm-btn-fg) !important; border: 1px solid var(--tm-btn-bg) !important; }
html[data-theme] .btn-sk *, html[data-theme] .btn-sk i { color: var(--tm-btn-fg) !important; }
html[data-theme] .btn-sk:hover { opacity: .88; }
html[data-theme] .btn-sk-outline, html[data-theme] .filter-tab { background: var(--tm-surface) !important; color: var(--tm-text) !important; border: 1px solid var(--tm-input-border) !important; }
html[data-theme] .btn-sk-outline:hover, html[data-theme] .filter-tab:hover { background: var(--tm-hover) !important; }
html[data-theme] .filter-tab.active { background: var(--tm-btn-bg) !important; color: var(--tm-btn-fg) !important; border-color: var(--tm-btn-bg) !important; }
html[data-theme] .action-btn, html[data-theme] .edit-btn, html[data-theme] .del-btn,
html[data-theme] .pin-btn, html[data-theme] .unpin-btn { background: var(--tm-surface2) !important; color: var(--tm-text) !important; border: 1px solid var(--tm-border) !important; }
html[data-theme] .action-btn:hover, html[data-theme] .edit-btn:hover, html[data-theme] .pin-btn:hover, html[data-theme] .unpin-btn:hover { background: var(--tm-hover) !important; }
html[data-theme] .del-btn, html[data-theme] .del-btn i { color: var(--tm-red) !important; }
html[data-theme] .del-btn:hover { background: var(--tm-red) !important; color: #fff !important; }
html[data-theme] .del-btn:hover i { color: #fff !important; }
/* search + form fields */
html[data-theme] .search-box { background: var(--tm-input) !important; color: var(--tm-text) !important; border: 1px solid var(--tm-input-border) !important; }
/* badges and pills */
html[data-theme] [class^="badge-"], html[data-theme] [class*=" badge-"],
html[data-theme] .post-type-badge, html[data-theme] .pinned-ribbon,
html[data-theme] [class^="type-"], html[data-theme] [class*=" type-"] { background: var(--tm-surface2) !important; color: var(--tm-text) !important; border: 1px solid var(--tm-border) !important; }
/* program table */
html[data-theme] .prog-table thead th { background: var(--tm-thead) !important; color: var(--tm-thead-text) !important; border-color: var(--tm-side-border) !important; }
html[data-theme] .prog-table tbody td { border-color: var(--tm-row-border) !important; }
html[data-theme] .prog-table tbody tr:hover td { background: var(--tm-hover) !important; }
html[data-theme] .prog-table tbody tr[class*="row-"] td:first-child { border-left: 3px solid var(--tm-muted) !important; }
html[data-theme] .alert-success-custom { background: var(--tm-surface2) !important; color: var(--tm-text) !important; border: 1px solid var(--tm-border) !important; }
html[data-theme] .modal-header .btn-close { filter: none; }
html[data-theme="dark"] .modal-header .btn-close { filter: invert(1) grayscale(1) brightness(1.6); }
</style>
<link href="css/sk-mobile.css?v=<?= (int)$__sk_mobile_v ?>" rel="stylesheet">

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- SK Sidebar -->
        <ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center" href="sk_dashboard.php">
                <img src="icons/logo.png" alt="Barangay San Pedro" class="sidebar-brand-logo">
                <div class="sidebar-brand-text">SK Portal<br>Youth Engagement</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="sk_dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>SK Dashboard</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Youth Records</div>

            <li class="nav-item">
                <a class="nav-link" href="sk_youth_records.php">
                    <i class="fas fa-users"></i>
                    <span>Youth Member Records</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Programs &amp; Activities</div>

            <li class="nav-item">
                <a class="nav-link" href="sk_programs.php">
                    <i class="fas fa-calendar-check"></i>
                    <span>Programs &amp; Activities</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="sk_enrollment.php">
                    <i class="fas fa-clipboard-list"></i>
                    <span>Participation Tracking</span>
                </a>
            </li>

            <hr class="sidebar-divider">
            <div class="sidebar-heading">Announcements</div>

            <li class="nav-item">
                <a class="nav-link" href="sk_announcements.php">
                    <i class="fas fa-bullhorn"></i>
                    <span>SK Announcements</span>
                </a>
            </li>

            <hr class="sidebar-divider">

        </ul>
        <!-- End of SK Sidebar -->
        <!-- Dim overlay behind the slide-in menu on phones/tablets -->
        <div class="sk-backdrop" aria-hidden="true"></div>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop"
                        class="btn btn-link d-md-none rounded-circle mr-3"
                        type="button"
                        aria-label="Open navigation">
                        <i class="fas fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Theme switcher: Light / Dark / Default (original design) -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="themeSwitchDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" title="Theme">
                                <i class="fas fa-adjust fa-fw" id="themeIcon"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-right shadow theme-switch-menu"
                                 aria-labelledby="themeSwitchDropdown">
                                <h6 class="dropdown-header">Theme</h6>
                                <a class="dropdown-item" href="#" data-theme-choice="light">
                                    <i class="fas fa-sun"></i> Light
                                    <i class="fas fa-check ml-auto"></i>
                                </a>
                                <a class="dropdown-item" href="#" data-theme-choice="dark">
                                    <i class="fas fa-moon"></i> Dark
                                    <i class="fas fa-check ml-auto"></i>
                                </a>
                                <a class="dropdown-item" href="#" data-theme-choice="default">
                                    <i class="fas fa-desktop"></i> Default
                                    <i class="fas fa-check ml-auto"></i>
                                </a>
                            </div>
                        </li>

                        <!-- Text size + font -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="textFontDropdown" role="button"
                               data-bs-toggle="dropdown" aria-expanded="false" title="Text size &amp; font">
                                <i class="fas fa-font fa-fw"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end dropdown-menu-right shadow theme-switch-menu textfont-menu"
                                 aria-labelledby="textFontDropdown">
                                <h6 class="dropdown-header">Text size</h6>
                                <a class="dropdown-item" href="#" data-size-choice="small"><span class="size-a" style="font-size:.8em">A</span> Small <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-size-choice="medium"><span class="size-a" style="font-size:1em">A</span> Medium <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-size-choice="large"><span class="size-a" style="font-size:1.25em">A</span> Large <i class="fas fa-check"></i></a>
                                <div class="dropdown-divider"></div>
                                <h6 class="dropdown-header">Font</h6>
                                <a class="dropdown-item" href="#" data-font-choice="default">Default <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="nunito"><span style="font-family:'Nunito', sans-serif">Nunito</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="inter"><span style="font-family:'Inter', sans-serif">Inter</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="poppins"><span style="font-family:'Poppins', sans-serif">Poppins</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="roboto"><span style="font-family:'Roboto', sans-serif">Roboto</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="opensans"><span style="font-family:'Open Sans', sans-serif">Open Sans</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="lato"><span style="font-family:'Lato', sans-serif">Lato</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="montserrat"><span style="font-family:'Montserrat', sans-serif">Montserrat</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="merriweather"><span style="font-family:'Merriweather', serif">Merriweather</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="serif"><span style="font-family:Georgia, 'Times New Roman', serif">Serif (Georgia)</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="verdana"><span style="font-family:Verdana, Geneva, sans-serif">Verdana</span> <i class="fas fa-check"></i></a>
                                <a class="dropdown-item" href="#" data-font-choice="mono"><span style="font-family:ui-monospace, 'Cascadia Mono', Consolas, 'Courier New', monospace">Monospace</span> <i class="fas fa-check"></i></a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- User Name -->
                        <li class="nav-item">
                            <a class="nav-link" href="javascript:void(0);">
                                <span class="mr-2 d-none d-lg-inline text-gray-800 small">
                                    <?= htmlspecialchars($userdetails['surname'] ?? '') ?>,
                                    <?= htmlspecialchars($userdetails['firstname'] ?? '') ?>
                                    <?= htmlspecialchars($userdetails['mname'] ?? '') ?>
                                </span>
                            </a>
                        </li>

                        <!-- Logout Icon Only -->
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal" onclick="return showLogoutConfirm(event);" title="Log out">
                                <i class="fas fa-sign-out-alt fa-fw"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->

                <script>
                (function () {
                    var KEY = 'bmis_theme';
                    var ICONS = { 'default': 'fa-adjust', light: 'fa-sun', dark: 'fa-moon' };

                    // Chart colours sampled from the reference light/dark screenshots
                    var CHART = {
                        light: { ramp: ['#374151', '#6b7280', '#9ca3af', '#d1d5db'], tick: '#64748b', grid: '#d9dee6', edge: '#ffffff' },
                        dark:  { ramp: ['#d1d5db', '#9ca3af', '#6b7280', '#4b5563'], tick: '#9aa3b5', grid: '#26292e', edge: '#000000' }
                    };

                    function getMode() {
                        try { var m = localStorage.getItem(KEY); return (m === 'light' || m === 'dark') ? m : 'default'; }
                        catch (e) { return 'default'; }
                    }

                    // ── Chart.js instance recolor ────────────────────────
                    function themeInstance(c) {
                        var mode = getMode(), P = CHART[mode], o = c.options || {}, type = c.config && c.config.type;
                        if (!c.$tm) c.$tm = { scales: {} };

                        (c.data.datasets || []).forEach(function (ds, i) {
                            if (!ds.$tm) ds.$tm = { bg: ds.backgroundColor, bd: ds.borderColor, bw: ds.borderWidth };
                            if (mode === 'default') {
                                ds.backgroundColor = ds.$tm.bg; ds.borderColor = ds.$tm.bd; ds.borderWidth = ds.$tm.bw;
                                return;
                            }
                            var n = P.ramp.length;
                            if (type === 'pie' || type === 'doughnut' || Array.isArray(ds.$tm.bg)) {
                                var cols = (ds.data || []).map(function (_, j) { return P.ramp[j % n]; });
                                ds.backgroundColor = cols;
                                ds.borderColor = (type === 'pie' || type === 'doughnut') ? P.edge : cols;
                            } else {
                                ds.backgroundColor = P.ramp[i % n];
                                ds.borderColor = P.ramp[i % n];
                            }
                        });

                        Object.keys(o.scales || {}).forEach(function (id) {
                            var sc = o.scales[id];
                            if (!c.$tm.scales[id]) {
                                c.$tm.scales[id] = { t: sc.ticks && sc.ticks.color, g: sc.grid && sc.grid.color, ti: sc.title && sc.title.color };
                            }
                            var orig = c.$tm.scales[id];
                            if (sc.ticks) sc.ticks.color = mode === 'default' ? orig.t : P.tick;
                            if (sc.grid)  sc.grid.color  = mode === 'default' ? orig.g : P.grid;
                            if (sc.title) sc.title.color = mode === 'default' ? orig.ti : P.tick;
                        });
                    }

                    function patchChart() {
                        if (!window.Chart || !Chart.prototype || Chart.prototype.$tmPatched) return;
                        var orig = Chart.prototype.update;
                        Chart.prototype.update = function () {
                            try { themeInstance(this); } catch (e) {}
                            return orig.apply(this, arguments);
                        };
                        Chart.prototype.$tmPatched = true;
                    }

                    function applyCharts() {
                        if (!window.Chart) return;
                        patchChart();
                        var mode = getMode(), P = CHART[mode];
                        Chart.defaults.color       = mode === 'default' ? '#666' : P.tick;
                        Chart.defaults.borderColor = mode === 'default' ? 'rgba(0,0,0,0.1)' : P.grid;
                        var inst = Chart.instances || {};
                        Object.keys(inst).forEach(function (k) { inst[k].update('none'); });
                    }

                    // ── HTML legend swatches (inline colours) ────────────
                    function recolorSwatches() {
                        var mode = getMode(), P = CHART[mode];
                        ['.chart-legend-grid', '.bar-legend-grid', '.cert-legend-grid'].forEach(function (sel) {
                            document.querySelectorAll(sel).forEach(function (grid) {
                                grid.querySelectorAll('[class$="legend-color"]').forEach(function (el, i) {
                                    if (!el.hasAttribute('data-tm-bg')) el.setAttribute('data-tm-bg', el.style.background || el.style.backgroundColor || '');
                                    if (mode === 'default') el.style.setProperty('background', el.getAttribute('data-tm-bg'));
                                    else el.style.setProperty('background', P.ramp[i % P.ramp.length], 'important');
                                });
                            });
                        });
                    }

                    function paint(mode) {
                        var root = document.documentElement;
                        if (mode === 'default') root.removeAttribute('data-theme');
                        else root.setAttribute('data-theme', mode);

                        var icon = document.getElementById('themeIcon');
                        if (icon) icon.className = 'fas fa-fw ' + ICONS[mode];
                        document.querySelectorAll('[data-theme-choice]').forEach(function (a) {
                            a.classList.toggle('active-theme', a.getAttribute('data-theme-choice') === mode);
                        });
                        applyCharts();
                        recolorSwatches();
                    }

                    document.querySelectorAll('[data-theme-choice]').forEach(function (a) {
                        a.addEventListener('click', function (e) {
                            e.preventDefault();
                            var mode = a.getAttribute('data-theme-choice');
                            try { if (mode === 'default') localStorage.removeItem(KEY); else localStorage.setItem(KEY, mode); } catch (err) {}
                            paint(mode);
                        });
                    });

                    paint(getMode());

                    // Charts/legends are built later in the page (some after a fetch) — re-apply
                    document.addEventListener('DOMContentLoaded', function () {
                        applyCharts(); recolorSwatches();
                        var lg = document.getElementById('certChartLegend');
                        if (lg && window.MutationObserver) new MutationObserver(recolorSwatches).observe(lg, { childList: true });
                    });
                    window.addEventListener('load', function () {
                        [0, 1500, 4000].forEach(function (d) { setTimeout(function () { applyCharts(); recolorSwatches(); }, d); });
                    });
                })();
                </script>

                <script>
                (function () {
                    var SIZE_KEY = 'bmis_fontsize', FONT_KEY = 'bmis_font';
                    var STACKS = {
                        nunito: "'Nunito', sans-serif",
                        inter: "'Inter', sans-serif",
                        poppins: "'Poppins', sans-serif",
                        roboto: "'Roboto', sans-serif",
                        opensans: "'Open Sans', sans-serif",
                        lato: "'Lato', sans-serif",
                        montserrat: "'Montserrat', sans-serif",
                        merriweather: "'Merriweather', serif",
                        serif: "Georgia, 'Times New Roman', serif",
                        verdana: "Verdana, Geneva, sans-serif",
                        mono: "ui-monospace, 'Cascadia Mono', Consolas, 'Courier New', monospace"
                    };
                    var FONT_LIST = ["default"].concat(Object.keys(STACKS));
                    var chartFontOrig = null;

                    function get(key, allowed, dflt) {
                        try { var v = localStorage.getItem(key); return allowed.indexOf(v) > -1 ? v : dflt; }
                        catch (e) { return dflt; }
                    }
                    function set(key, val, dflt) {
                        try { if (val === dflt) localStorage.removeItem(key); else localStorage.setItem(key, val); } catch (e) {}
                    }

                    function applyChartFont(font) {
                        if (!window.Chart || !Chart.defaults || !Chart.defaults.font) return;
                        if (chartFontOrig === null) chartFontOrig = Chart.defaults.font.family;
                        Chart.defaults.font.family = STACKS[font] || chartFontOrig;
                        var inst = Chart.instances || {};
                        Object.keys(inst).forEach(function (k) {
                            var c = inst[k], sc = (c.options && c.options.scales) || {};
                            Object.keys(sc).forEach(function (id) {
                                if (sc[id].ticks && sc[id].ticks.font) sc[id].ticks.font.family = STACKS[font] || chartFontOrig;
                            });
                            c.update('none');
                        });
                    }

                    function paint() {
                        var size = get(SIZE_KEY, ['small', 'medium', 'large'], 'medium');
                        var font = get(FONT_KEY, FONT_LIST, 'default');
                        var root = document.documentElement;
                        if (size === 'medium') root.removeAttribute('data-fontsize'); else root.setAttribute('data-fontsize', size);
                        if (font === 'default') root.removeAttribute('data-font'); else root.setAttribute('data-font', font);
                        document.querySelectorAll('[data-size-choice]').forEach(function (a) {
                            a.classList.toggle('active-theme', a.getAttribute('data-size-choice') === size);
                        });
                        document.querySelectorAll('[data-font-choice]').forEach(function (a) {
                            a.classList.toggle('active-theme', a.getAttribute('data-font-choice') === font);
                        });
                        applyChartFont(font);
                        window.dispatchEvent(new Event('resize'));   // let charts re-measure after zoom
                    }

                    document.querySelectorAll('[data-size-choice]').forEach(function (a) {
                        a.addEventListener('click', function (e) {
                            e.preventDefault(); set(SIZE_KEY, a.getAttribute('data-size-choice'), 'medium'); paint();
                        });
                    });
                    document.querySelectorAll('[data-font-choice]').forEach(function (a) {
                        a.addEventListener('click', function (e) {
                            e.preventDefault(); set(FONT_KEY, a.getAttribute('data-font-choice'), 'default'); paint();
                        });
                    });

                    paint();
                    document.addEventListener('DOMContentLoaded', function () { applyChartFont(get(FONT_KEY, FONT_LIST, 'default')); });
                    window.addEventListener('load', function () {
                        [0, 1500, 4000].forEach(function (d) {
                            setTimeout(function () { applyChartFont(get(FONT_KEY, FONT_LIST, 'default')); }, d);
                        });
                    });
                })();
                </script>

                <script>
                window.addEventListener('load', function () {
                    var touchStartX = 0, touchStartY = 0, wasScrollGesture = false;

                    document.addEventListener('touchstart', function (e) {
                        if (!e.touches || !e.touches.length) return;
                        touchStartX = e.touches[0].clientX;
                        touchStartY = e.touches[0].clientY;
                        wasScrollGesture = false;
                    }, { passive: true, capture: true });

                    document.addEventListener('touchmove', function (e) {
                        if (!e.touches || !e.touches.length) return;
                        var dx = Math.abs(e.touches[0].clientX - touchStartX);
                        var dy = Math.abs(e.touches[0].clientY - touchStartY);
                        if (dx > 10 || dy > 10) {
                            wasScrollGesture = true;
                        }
                    }, { passive: true, capture: true });

                    // Strip and rebind both possible toggle buttons so no
                    // stray touchstart/click/mousedown handler from
                    // elsewhere can open the sidebar on its own. The
                    // scroll-vs-tap check is scoped to these buttons only —
                    // it must never intercept clicks anywhere else on the
                    // page (that previously blocked taps on buttons like
                    // "View" right after scrolling to reach them).
                    ['sidebarToggleTop', 'sidebarToggle'].forEach(function (id) {
                        var el = document.getElementById(id);
                        if (!el) return;
                        var fresh = el.cloneNode(true);
                        el.parentNode.replaceChild(fresh, el);
                        fresh.addEventListener('click', function (e) {
                            if (wasScrollGesture) return; // ignore scroll-end taps
                            e.preventDefault();
                            var sidebar = document.querySelector('.sidebar');
                            if (sidebar) sidebar.classList.toggle('toggled');
                        });
                    });
                });
                </script>


                <script>
                /* Responsive helpers: (1) wrap tables that have no scroll container so they scroll
                   sideways inside a box instead of stretching the page; (2) close the drawer when
                   the window grows back to desktop width. */
                (function () {
                    function wrapTables() {
                        document.querySelectorAll('table').forEach(function (t) {
                            if (t.closest('.table-responsive, .tbl-scroll, .enr-scroll, .dataTables_wrapper, .dataTables_scroll, .swal2-container, .modal-backdrop')) return;
                            if (t.parentNode && t.parentNode.classList && t.parentNode.classList.contains('tbl-scroll')) return;
                            var box = document.createElement('div');
                            box.className = 'tbl-scroll';
                            t.parentNode.insertBefore(box, t);
                            box.appendChild(t);
                        });
                    }
                    document.addEventListener('DOMContentLoaded', wrapTables);
                    window.addEventListener('load', wrapTables);

                    var wasSmall = window.innerWidth <= 991.98;
                    window.addEventListener('resize', function () {
                        var small = window.innerWidth <= 991.98;
                        if (wasSmall && !small) {           // crossed from drawer mode to desktop: reset the drawer
                            var sb = document.querySelector('.sidebar');
                            if (sb) sb.classList.remove('toggled');
                        }
                        wasSmall = small;
                    });
                })();
                </script>

                <!-- FIX: tapping/clicking anywhere outside the open mobile
                     sidebar closes it. -->
                <script>
                document.addEventListener('click', function (e) {
                    if (window.innerWidth > 991.98) return; // desktop unaffected (drawer on phones + tablets)

                    var sidebar = document.querySelector('.sidebar');
                    var toggleBtn = document.getElementById('sidebarToggleTop');
                    if (!sidebar || !sidebar.classList.contains('toggled')) return;

                    var clickedInsideSidebar = sidebar.contains(e.target);
                    var clickedToggleBtn = toggleBtn && toggleBtn.contains(e.target);

                    if (!clickedInsideSidebar && !clickedToggleBtn) {
                        sidebar.classList.remove('toggled');
                    }
                }, true);
                </script>
<script src="js/sk-mobile.js?v=<?= (int)$__sk_mobile_js ?>" defer></script>
