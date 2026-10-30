<?php
/* ============================================================
   SHARED RESIDENT NAVBAR
   Included by every resident-facing page (resident_*.php,
   services_*.php, setup_security_question.php).

   Edit THIS file only — the navbar links, icons, labels, and
   styling will update on every page that includes it.

   Requires $userdetails['id_resident'] to already be set by
   secure_header.php before this file is included.
   ============================================================ */

// Figure out which page we're on so we can highlight the active nav item.
$resident_nav_current = basename($_SERVER['PHP_SELF'] ?? '');

// Map each nav destination to the page(s) that should count as "active" for it.
$resident_nav_items = [
    [
        'match'  => ['resident_homepage.php'],
        'href'   => 'resident_homepage.php',
        'icon'   => 'bi-house-door-fill',
        'label'  => 'Home',
        'mobile' => 'Home',
    ],
    [
        'match'  => ['resident_announcement.php'],
        'href'   => 'resident_announcement.php',
        'icon'   => 'bi-megaphone-fill',
        'label'  => 'Announcements',
        'mobile' => 'News',
    ],
    [
        'match'  => ['resident_youth_profile.php'],
        'href'   => 'resident_youth_profile.php',
        'icon'   => 'bi-people',
        'label'  => 'Youth',
        'mobile' => 'Youth',
    ],
    [
        'match'  => ['resident_profile.php'],
        'href'   => 'resident_profile.php?id_resident=' . urlencode($userdetails['id_resident'] ?? ''),
        'icon'   => 'bi-person-badge',
        'label'  => 'Profile',
        'mobile' => 'Profile',
    ],
];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<?php include __DIR__ . '/resident_theme.php'; ?>

<style>
/* ===== SHARED RESIDENT NAVBAR STYLES ===== */

/* ----- DESKTOP NAVBAR ----- */
.resident-desktop-nav {
    background: linear-gradient(135deg, #1b74e4 0%, #0a5ecf 100%) !important;
    padding: 0.6rem 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.12);
}
.resident-desktop-nav .navbar-brand {
    font-weight: 700;
    font-size: 1.25rem;
    letter-spacing: -0.3px;
    color: #fff;
}
.resident-desktop-nav .navbar-brand i {
    font-size: 1.4rem;
    color: inherit; /* Remove color from brand icon */
}
.resident-desktop-nav .navbar-brand-avatar {
    display: inline-flex;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    overflow: hidden;
    background: #fff;
    vertical-align: middle;
    margin-right: 4px;
}
.resident-desktop-nav .navbar-brand-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}
.resident-desktop-nav .btn-nav {
    border-radius: 40px;
    padding: 0.45rem 1.2rem;
    font-weight: 600;
    font-size: 0.88rem;
    transition: all 0.2s ease;
    border: none;
    background: rgba(255,255,255,0.15);
    color: rgba(255,255,255,0.85);
    margin-right: 0.4rem;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.resident-desktop-nav .btn-nav i {
    color: inherit; /* Remove color from nav icons */
}
.resident-desktop-nav .btn-nav:hover {
    background: rgba(255,255,255,0.28);
    color: #fff;
    transform: translateY(-1px);
}
.resident-desktop-nav .btn-nav.active-nav {
    background: #ffffff;
    color: #1b74e4;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}
.resident-desktop-nav .btn-nav.active-nav i {
    color: #1b74e4; /* Keep color for active state if desired */
}
.resident-desktop-nav .btn-nav.active-nav:hover {
    background: #f0f4ff;
}
.resident-desktop-nav .btn-nav-logout {
    border-radius: 40px;
    padding: 0.45rem 1.4rem;
    font-weight: 600;
    font-size: 0.88rem;
    border: none;
    background: rgba(220, 53, 69, 0.85);
    color: #fff;
    transition: all 0.2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.resident-desktop-nav .btn-nav-logout i {
    color: inherit; /* Remove color from logout icon */
}
.resident-desktop-nav .btn-nav-logout:hover {
    background: #dc3545;
    transform: translateY(-1px);
    box-shadow: 0 2px 10px rgba(220, 53, 69, 0.35);
}

/* ----- MOBILE BOTTOM NAV ----- */
.mobile-bottom-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    right: 0;
    height: 68px;
    background: #ffffff;
    display: flex;
    justify-content: space-around;
    align-items: center;
    box-shadow: 0 -2px 16px rgba(0,0,0,0.08);
    z-index: 1050;
    border-top: 1px solid rgba(0,0,0,0.05);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding-bottom: env(safe-area-inset-bottom);
}

.mobile-bottom-nav .nav-item {
    text-decoration: none;
    color: #8a8f9a;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 0.65rem;
    font-weight: 600;
    padding: 0.3rem 0.6rem;
    border-radius: 12px;
    transition: all 0.2s ease;
    min-width: 52px;
    position: relative;
}

.mobile-bottom-nav .nav-item i {
    font-size: 1.4rem;
    margin-bottom: 1px;
    transition: transform 0.2s ease;
    color: inherit; /* Remove color from mobile nav icons */
}

.mobile-bottom-nav .nav-item:active {
    transform: scale(0.92);
}

.mobile-bottom-nav .nav-item.active-nav {
    color: #1b74e4;
}

.mobile-bottom-nav .nav-item.active-nav i {
    color: #1b74e4; /* Keep color for active state if desired */
    transform: translateY(-2px);
}

.mobile-bottom-nav .nav-item.active-nav::after {
    content: '';
    position: absolute;
    top: -1px;
    left: 30%;
    right: 30%;
    height: 3px;
    background: #1b74e4;
    border-radius: 0 0 4px 4px;
}

.mobile-bottom-nav .nav-item.logout-item {
    color: #dc3545;
}

.mobile-bottom-nav .nav-item.logout-item i {
    color: #dc3545; /* Keep color for logout item if desired */
}

.mobile-bottom-nav .nav-item.logout-item:active {
    color: #b02a37;
}

/* ----- PADDING FOR MOBILE (so content isn't hidden) ----- */
@media (max-width: 767px) {
    body {
        padding-bottom: 85px;
    }
}

/* ----- RESPONSIVE ADJUSTMENTS ----- */
@media (max-width: 576px) {
    .mobile-bottom-nav {
        height: 62px;
    }
    .mobile-bottom-nav .nav-item {
        font-size: 0.6rem;
        min-width: 44px;
        padding: 0.2rem 0.4rem;
    }
    .mobile-bottom-nav .nav-item i {
        font-size: 1.2rem;
    }
}

@media (min-width: 768px) and (max-width: 991px) {
    .resident-desktop-nav .btn-nav {
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
    }
    .resident-desktop-nav .btn-nav-logout {
        font-size: 0.8rem;
        padding: 0.4rem 1rem;
    }
}
</style>

<!-- ===== DESKTOP NAVBAR (Hidden on Mobile) ===== -->
<nav class="navbar navbar-expand-lg sticky-top d-none d-md-block resident-desktop-nav">
    <div class="container-fluid">
        <a class="navbar-brand" href="resident_homepage.php">
            <span class="navbar-brand-avatar"><img src="icons/logo.png" alt="Barangay San Pedro"></span> Barangay San Pedro
        </a>
        <div class="d-flex align-items-center ms-auto">
            <?php foreach ($resident_nav_items as $item): ?>
                <?php $isActive = in_array($resident_nav_current, $item['match'], true); ?>
                <a href="<?= htmlspecialchars($item['href']) ?>" class="btn-nav <?= $isActive ? 'active-nav' : '' ?>">
                    <i class="bi <?= htmlspecialchars($item['icon']) ?>"></i> <?= htmlspecialchars($item['label']) ?>
                </a>
            <?php endforeach; ?>
            <?php rtm_render_controls($rtm_fonts); ?>
            <a href="#" class="btn-nav-logout" data-bs-toggle="modal" data-bs-target="#residentLogoutModal">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<!-- ===== MOBILE TOP BAR (theme + text settings; hidden on desktop) ===== -->
<div class="resident-mobile-top d-md-none">
    <a class="rtm-brand" href="resident_homepage.php"><img src="icons/logo.png" alt=""> Barangay San Pedro</a>
    <?php rtm_render_controls($rtm_fonts); ?>
</div>

<!-- ===== MOBILE BOTTOM NAV (Hidden on Desktop) ===== -->
<div class="mobile-bottom-nav d-md-none">
    <?php foreach ($resident_nav_items as $item): ?>
        <?php $isActive = in_array($resident_nav_current, $item['match'], true); ?>
        <a href="<?= htmlspecialchars($item['href']) ?>" class="nav-item <?= $isActive ? 'active-nav' : '' ?>">
            <i class="bi <?= htmlspecialchars($item['icon']) ?>"></i>
            <span><?= htmlspecialchars($item['mobile']) ?></span>
        </a>
    <?php endforeach; ?>
    <a href="#" class="nav-item logout-item" data-bs-toggle="modal" data-bs-target="#residentLogoutModal">
        <i class="bi bi-box-arrow-right"></i>
        <span>Exit</span>
    </a>
</div>

<!-- Logout confirmation modal (shared by desktop nav + mobile bottom nav) -->
<div class="modal fade" id="residentLogoutModal" tabindex="-1" aria-labelledby="residentLogoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residentLogoutModalLabel">Log out?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to log out?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="logout.php" id="residentConfirmLogoutBtn" class="btn btn-danger">Yes, log out</a>
            </div>
        </div>
    </div>
</div>

<!-- Page loading screen — a skeleton screen (shimmering placeholders) that covers
     only the content area, between the top nav and the mobile bottom nav. Used for
     logout, every resident nav link click, and the pages' own #page-loader calls. -->
<style>
    #page-nav-loader, #page-loader {
        --sk-bg: #f0f2f5; --sk-card: #ffffff; --sk-line: #e4e6ea;
        --sk-base: #e4e8ef; --sk-shine: #f5f7fb;
        position: fixed !important; inset: auto !important;
        top: var(--sk-top, 64px) !important; left: 0 !important; right: 0 !important;
        bottom: var(--sk-bottom, 0px) !important;
        display: none; background: var(--sk-bg) !important;
        backdrop-filter: none !important; -webkit-backdrop-filter: none !important;
        z-index: 1040 !important; overflow: hidden; padding: 0 !important; gap: 0 !important;
    }
    html[data-theme="dark"] #page-nav-loader, html[data-theme="dark"] #page-loader {
        --sk-bg: #0b0b0b; --sk-card: #000000; --sk-line: rgba(255,255,255,.12);
        --sk-base: #1c1c1c; --sk-shine: #2b2b2b;
    }
    html[data-theme="light"] #page-nav-loader, html[data-theme="light"] #page-loader {
        --sk-bg: #f8f9fc; --sk-line: #d9dfe9;
    }
    #page-nav-loader.show, #page-loader.show { display: block !important; }
    #page-nav-loader > :not(.sk-wrap), #page-loader > :not(.sk-wrap) { display: none !important; }

    .sk-wrap { max-width: 1100px; margin: 0 auto; padding: 1.5rem 1rem; height: 100%; overflow: hidden; }
    /* !important: the resident theme blanks every background inside the page */
    .sk { display: block; border-radius: 8px; background-color: var(--sk-base) !important;
          background-image: linear-gradient(90deg, transparent 0, var(--sk-shine) 50%, transparent 100%) !important;
          background-size: 200px 100%; background-repeat: no-repeat; background-position: -200px 0;
          animation: sk-shimmer 1.3s ease-in-out infinite; }
    @keyframes sk-shimmer { to { background-position: calc(100% + 200px) 0; } }
    .sk-title { height: 28px; width: 240px; max-width: 70%; margin: 0 auto 10px; }
    .sk-sub   { height: 12px; width: 170px; max-width: 50%; margin: 0 auto 24px; }
    .sk-cards { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 20px; }
    .sk-card  { background: var(--sk-card) !important; border: 1px solid var(--sk-line) !important; border-radius: 14px; padding: 16px; margin-bottom: 14px; }
    .sk-cards .sk-card { margin-bottom: 0; }
    .sk-cards .sk:nth-child(1) { height: 36px; width: 36px; border-radius: 50%; margin-bottom: 12px; }
    .sk-cards .sk:nth-child(2) { height: 12px; width: 70%; margin-bottom: 8px; }
    .sk-cards .sk:nth-child(3) { height: 10px; width: 45%; }
    .sk-post-head { display: flex; align-items: center; gap: 12px; margin-bottom: 14px; }
    .sk-post-head .sk.sk-av { width: 42px; height: 42px; border-radius: 50%; flex-shrink: 0; }
    .sk-post-head .sk-lines { flex: 1; }
    .sk-post-head .sk-lines .sk { height: 11px; }
    .sk-post-head .sk-lines .sk:first-child { width: 40%; margin-bottom: 8px; }
    .sk-post-head .sk-lines .sk:last-child { width: 24%; }
    .sk-line { height: 11px; margin-bottom: 9px; }
    .sk-line.w80 { width: 80%; } .sk-line.w60 { width: 60%; }
    @media (max-width: 767.98px) { .sk-cards { grid-template-columns: 1fr; } .sk-wrap { padding: 1rem .75rem; } }
</style>
<div id="page-nav-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="page-nav-loader-text">Loading...</div>
</div>
<link rel="stylesheet" href="css/skeleton-pages.css?v=20261026c">
<script src="js/skeleton-pages.js?v=20261026c"></script>
<script>
    function placeSkeleton() {
        // between the desktop top nav and the mobile bottom nav
        var top = 0, bottom = 0;
        var dn = document.querySelector('.resident-desktop-nav');
        if (dn && dn.offsetParent !== null) top = Math.max(0, dn.getBoundingClientRect().bottom);
        var mb = document.querySelector('.mobile-bottom-nav');
        if (mb && mb.offsetParent !== null) bottom = Math.max(0, window.innerHeight - mb.getBoundingClientRect().top);
        document.documentElement.style.setProperty('--sk-top', top + 'px');
        document.documentElement.style.setProperty('--sk-bottom', bottom + 'px');
    }
    function initSkeletons() {
        // #page-loader is declared by each page (after this include), so wait for the DOM.
        ['page-nav-loader', 'page-loader'].forEach(function (id) {
            var el = document.getElementById(id);
            if (!el || el.__skReady) return;
            el.__skReady = true;
            SkeletonPages.render(el, location.href, 'resident');
            new MutationObserver(function () {
                if (!el.classList.contains('show')) return;
                if (!el.__skNav) SkeletonPages.render(el, location.href, 'resident');
                placeSkeleton();
            }).observe(el, { attributes: true, attributeFilter: ['class'] });
        });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', initSkeletons);
    else initSkeletons();

    function showPageNavLoader(msg, href) {
        var loader = document.getElementById('page-nav-loader');
        var text = document.getElementById('page-nav-loader-text');
        if (text) text.textContent = msg || 'Loading...';
        if (loader) {
            // skeleton of the page being opened (falls back to the current page)
            loader.__skNav = true;
            SkeletonPages.render(loader, href || location.href, 'resident');
            loader.classList.add('show');
        }
    }

    // Show the loading overlay the moment logout is confirmed, then let
    // the link's normal navigation to logout.php proceed underneath it.
    document.getElementById('residentConfirmLogoutBtn').addEventListener('click', function () {
        showPageNavLoader('Logging you out...', 'logout.php');
    });

    // Show the same loading screen whenever a resident nav link (desktop
    // top nav .btn-nav or mobile bottom nav .nav-item) is clicked and about
    // to navigate to another resident page. Skip clicks that don't actually
    // leave the page ("#" placeholders like the logout button, which is
    // handled above) and modified/middle clicks that open a new tab.
    document.querySelectorAll('.resident-desktop-nav .btn-nav, .mobile-bottom-nav .nav-item').forEach(function (link) {
        link.addEventListener('click', function (e) {
            if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;

            var href = link.getAttribute('href');
            if (!href || href === '#' || href.indexOf('javascript:') === 0) return;
            if (link.hasAttribute('data-bs-toggle')) return;
            if (link.target && link.target !== '' && link.target !== '_self') return;

            showPageNavLoader('Loading...', link.href);
        });
    });

    // If the page is restored from back/forward cache (e.g. the user hits
    // the browser Back button), make sure a stale loader doesn't stay stuck.
    window.addEventListener('pageshow', function (e) {
        if (e.persisted) {
            var loader = document.getElementById('page-nav-loader');
            if (loader) { loader.classList.remove('show'); loader.__skNav = false; }
        }
    });
</script>
