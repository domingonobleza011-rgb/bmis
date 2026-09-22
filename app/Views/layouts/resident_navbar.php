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
        'match'  => ['resident_budget.php'],
        'href'   => 'resident_budget.php',
        'icon'   => 'bi-wallet2',
        'label'  => 'Budget',
        'mobile' => 'Budget',
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
            <a href="#" class="btn-nav-logout" data-bs-toggle="modal" data-bs-target="#residentLogoutModal">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

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
                <a href="logout.php" class="btn btn-danger">Yes, log out</a>
            </div>
        </div>
    </div>
</div>
