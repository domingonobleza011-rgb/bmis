<?php
/* ============================================================
   ADMIN TOPBAR NOTIFICATIONS
   Surfaces 3 things the admin needs to act on:
     1) New certificate / document requests
     2) New (unread) resident messages
     3) New pending requests (registrations + password resets)
   Wrapped defensively so a missing table never breaks a page
   that includes this sidebar.
   ============================================================ */
if (!isset($conn) || !($conn instanceof PDO)) {
    try {
        require_once __DIR__ . '/classes/conn.php';
    } catch (Throwable $e) {
        $conn = null;
    }
}

$notif_cert_total    = 0;
$notif_cert_items    = [];
$notif_msg_total     = 0;
$notif_msg_items     = [];
$notif_pending_total = 0;
$notif_pending_items = [];

if (isset($conn) && $conn instanceof PDO) {

    // ── 1) New certificate / document requests ──────────────────
    // A row in these tables IS the pending request; it's removed
    // once the admin processes/archives it. For document types that
    // support generation tracking, only rows that haven't been
    // generated yet (is_generated = 0/NULL) count as "new" here —
    // once a certificate is generated it drops out of this
    // notification, even if the row itself hasn't been archived.
    // Complaints have no generation step, so they still use 1=1.
    $not_generated_where = "(is_generated IS NULL OR is_generated = 0)";
    $cert_sources = [
        ['label' => 'Certificate of Residency', 'table' => 'tbl_rescert',    'where' => $not_generated_where, 'page' => 'admn_certofres.php',       'icon' => 'fa-file-word'],
        ['label' => 'Certificate of Good Moral Character', 'table' => 'tbl_goodmoral', 'where' => $not_generated_where, 'page' => 'admn_certofgoodmoral.php', 'icon' => 'fa-user-check'],
        ['label' => 'Certificate of Livestock Ownership', 'table' => 'tbl_livestock', 'where' => $not_generated_where, 'page' => 'admn_certoflivestock.php', 'icon' => 'fa-horse'],
        ['label' => 'Certificate of Guardianship', 'table' => 'tbl_guardianship', 'where' => $not_generated_where,        'page' => 'admn_certofguardianship.php', 'icon' => 'fa-people-arrows'],
        ['label' => 'Solo Parent Certification', 'table' => 'tbl_soloparent', 'where' => $not_generated_where,            'page' => 'admn_soloparent.php',      'icon' => 'fa-child'],
        ['label' => 'Certificate of Indigency',  'table' => 'tbl_indigency', 'where' => $not_generated_where,             'page' => 'admn_certofindigency.php', 'icon' => 'fa-table'],
        ['label' => 'Business Permit',           'table' => 'tbl_bspermit', 'where' => $not_generated_where,             'page' => 'admn_bspermit.php',        'icon' => 'fa-file-contract'],
        ['label' => 'Barangay ID',               'table' => 'tbl_brgyid',   'where' => $not_generated_where, 'page' => 'admn_brgyid.php',          'icon' => 'fa-id-card'],
        ['label' => 'Barangay Clearance',        'table' => 'tbl_clearance','where' => $not_generated_where,             'page' => 'admn_brgyclearance.php',   'icon' => 'fa-file'],
        ['label' => 'Complaints',                'table' => 'tbl_complaints', 'where' => "status = 'pending'", 'page' => 'admn_complaints.php',      'icon' => 'fa-file'],
    ];

    foreach ($cert_sources as $src) {
        try {
            $cnt = (int) $conn->query("SELECT COUNT(*) FROM {$src['table']} WHERE {$src['where']}")->fetchColumn();
            if ($cnt > 0) {
                $notif_cert_total += $cnt;
                $notif_cert_items[] = ['label' => $src['label'], 'count' => $cnt, 'page' => $src['page'], 'icon' => $src['icon']];
            }
        } catch (Throwable $e) { /* table may not exist yet - skip */ }
    }

    // ── 2) New (unread) messages from residents ──────────────────
    try {
        $notif_msg_total = (int) $conn->query("SELECT COUNT(*) FROM admin_messages WHERE status = 'unread'")->fetchColumn();
        if ($notif_msg_total > 0) {
            $stmt = $conn->query("SELECT m.id_admin_msg, m.message_text, m.date_sent, r.fname, r.lname
                                   FROM admin_messages m
                                   LEFT JOIN tbl_resident r ON m.id_resident = r.id_resident
                                   WHERE m.status = 'unread'
                                   ORDER BY m.date_sent DESC
                                   LIMIT 5");
            $notif_msg_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    } catch (Throwable $e) { /* table may not exist yet - skip */ }

    // ── 3) New pending requests (registrations + password resets) ─
    try {
        $reg_count = (int) $conn->query("SELECT COUNT(*) FROM tbl_resident_pending WHERE application_status = 'pending'")->fetchColumn();
        if ($reg_count > 0) {
            $notif_pending_total += $reg_count;
            $notif_pending_items[] = ['label' => 'New Resident Registration', 'count' => $reg_count, 'page' => 'admn_resident_pending.php', 'icon' => 'fa-user-clock'];
        }
    } catch (Throwable $e) { /* table may not exist yet - skip */ }

    try {
        $pw_count = (int) $conn->query("SELECT COUNT(*) FROM tbl_password_reset_requests WHERE status = 'pending'")->fetchColumn();
        if ($pw_count > 0) {
            $notif_pending_total += $pw_count;
            $notif_pending_items[] = ['label' => 'Password Reset Request', 'count' => $pw_count, 'page' => 'admn_password_reset_requests.php', 'icon' => 'fa-key'];
        }
    } catch (Throwable $e) { /* table may not exist yet - skip */ }
}

$notif_grand_total = $notif_cert_total + $notif_msg_total + $notif_pending_total;

// Current page + query string, used so "mark as read" actions redirect back here
$notif_current_page = basename($_SERVER['PHP_SELF']);
if (!empty($_SERVER['QUERY_STRING'])) {
    $notif_current_page .= '?' . $_SERVER['QUERY_STRING'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Barangay San Pedro</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>

    <!-- Custom styles for this template-->
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
<style>

/* ============================================================
   BARANGAY SAN PEDRO — ADMIN DASHBOARD
   Design system: warm navy + antique gold, "civic hall" feel
   ============================================================ */

:root {
    /* core palette */
    --navy-900:   #0a1e3d;
    --navy-800:   #0f2d5a;
    --navy-700:   #163f78;
    --navy-500:   #2b5ea7;
    --navy-100:   #e9f0fb;
    --navy-50:    #f4f8fd;

    --gold-600:   #b9832c;
    --gold-500:   #c9943a;
    --gold-400:   #dfae5b;
    --gold-200:   #efd8a9;
    --gold-100:   #fbf1de;

    --teal-600:   #0f8c7f;
    --teal-100:   #e2f6f3;

    --danger:     #d94b41;
    --danger-100: #fdeceb;
    --warning:    #c9821a;
    --warning-100:#fdf1de;
    --success:    #1f9d63;
    --success-100:#e6f8ef;

    --ink:        #16213f;
    --ink-soft:   #4c5872;
    --ink-faint:  #8894a8;

    --paper:      #ffffff;
    --canvas:     #f3f6fb;
    --line:       #e5eaf3;

    --shadow-1:   0 1px 3px rgba(15,45,90,0.06), 0 1px 2px rgba(15,45,90,0.04);
    --shadow-2:   0 8px 24px rgba(15,45,90,0.10);
    --shadow-3:   0 18px 44px rgba(15,45,90,0.16);

    --r-lg: 16px;
    --r-md: 12px;
    --r-sm: 8px;

    --ease: cubic-bezier(.4,0,.2,1);
}

* { box-sizing: border-box; }

html, body {
    width: 100%;
    max-width: 100%;
    overflow-x: hidden !important;
}

body {
    font-family: 'DM Sans', -apple-system, sans-serif !important;
    background: var(--canvas) !important;
    color: var(--ink) !important;
    -webkit-font-smoothing: antialiased;
}

h1, h2, h3, h4, h5, h6 { font-family: 'Outfit', 'DM Sans', sans-serif !important; }

h4 {
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    color: var(--navy-800) !important;
    letter-spacing: 0.1px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 0.9rem;
}
h4::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 20px;
    background: linear-gradient(to bottom, var(--gold-500), var(--gold-400));
    border-radius: 4px;
    flex-shrink: 0;
}

hr { border-color: var(--line) !important; opacity: 1 !important; margin: 0.5rem 0 !important; }

#wrapper { width: 100%; max-width: 100%; overflow-x: hidden; }

/* ── Scrollbars (webkit) ─────────────────────────────────── */
::-webkit-scrollbar { width: 8px; height: 8px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: #c7d2e3; border-radius: 8px; }
::-webkit-scrollbar-thumb:hover { background: var(--gold-500); }

/* ============================================================
   SIDEBAR
   ============================================================ */
.sidebar {
    background:
        radial-gradient(120% 140% at 0% 0%, rgba(201,148,58,0.14) 0%, rgba(201,148,58,0) 45%),
        linear-gradient(190deg, var(--navy-900) 0%, var(--navy-800) 55%, #102a52 100%) !important;
    border-right: none !important;
    box-shadow: 4px 0 28px rgba(10,30,61,0.22);
    width: 290px !important;
    min-width: 290px !important;
    max-width: 290px !important;
    overflow-x: hidden !important;
    -webkit-overflow-scrolling: touch;
    scroll-behavior: smooth;
    display: flex;
    flex-direction: column;
}

.sidebar .collapse.show { overflow: visible !important; }
.sidebar .collapse { width: 100% !important; }

/* Brand */
.sidebar-brand {
    padding: 1.5rem 1.15rem 1.3rem !important;
    background: rgba(0,0,0,0.16) !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
    display: flex !important;
    align-items: center;
    gap: 12px;
}
.sidebar-brand-logo {
    width: 54px; height: 54px;
    border-radius: 12px;
    object-fit: contain;
    background: #fff;
    padding: 4px;
    flex-shrink: 0;
    box-shadow: 0 4px 12px rgba(0,0,0,0.25);
}
.sidebar-brand-text {
    font-family: 'Outfit', sans-serif !important;
    font-size: 1.05rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.2px !important;
    color: rgba(255,255,255,0.96) !important;
    text-transform: none !important;
    line-height: 1.3;
}

.sidebar-divider {
    border-top-color: rgba(255,255,255,0.08) !important;
    margin: 0.4rem 1.1rem !important;
}

.sidebar-heading {
    font-size: 0.78rem !important;
    font-weight: 700 !important;
    letter-spacing: 1.6px !important;
    text-transform: uppercase !important;
    color: rgba(255,255,255,0.32) !important;
    padding: 0.8rem 1.2rem 0.35rem !important;
}

/* Nav links */
.sidebar .nav-item { padding: 0 10px; }
.sidebar .nav-item .nav-link {
    color: rgba(255,255,255,0.78) !important;
    font-size: 1.05rem !important;
    font-weight: 450 !important;
    padding: 12px 14px !important;
    border-radius: 10px !important;
    margin: 2px 0;
    transition: background-color .18s var(--ease), color .18s var(--ease), transform .12s var(--ease) !important;
    display: flex !important;
    flex-direction: row !important;
    flex-wrap: nowrap !important;
    align-items: center !important;
    gap: 10px !important;
    line-height: 1.2 !important;
    border-left: none;
    position: relative;
}
.sidebar .nav-item .nav-link span { line-height: 1.2 !important; }

.sidebar .nav-item .nav-link i,
.sidebar .nav-item .nav-link .bi {
    font-size: 1.15rem;
    width: 26px;
    text-align: center;
    flex-shrink: 0;
    color: rgba(255,255,255,0.45);
    transition: color .18s var(--ease);
}

.sidebar .nav-item .nav-link:hover {
    color: var(--paper) !important;
    background: rgba(255,255,255,0.08) !important;
}
.sidebar .nav-item .nav-link:hover i,
.sidebar .nav-item .nav-link:hover .bi { color: var(--gold-400); }

.sidebar .nav-item.active .nav-link,
.sidebar .nav-item .nav-link.active {
    color: var(--navy-900) !important;
    background: linear-gradient(135deg, var(--gold-400), var(--gold-500)) !important;
    font-weight: 600 !important;
    box-shadow: 0 6px 16px rgba(201,148,58,0.32);
}
.sidebar .nav-item.active .nav-link i,
.sidebar .nav-item .nav-link.active i { color: var(--navy-900) !important; }

/* Submenus */
.sidebar .collapse-inner {
    width: calc(100% - 4px) !important;
    max-width: 100%;
    margin: 6px auto 4px !important;
    box-sizing: border-box;
    padding: 6px !important;
    background: rgba(255,255,255,0.97) !important;
    border-radius: var(--r-md) !important;
    box-shadow: var(--shadow-2), inset 0 0 0 1px rgba(255,255,255,0.6);
    overflow: hidden;
}

.sidebar .collapse-item {
    width: 100% !important;
    max-width: 100% !important;
    white-space: normal !important;
    word-break: normal;
    overflow-wrap: anywhere;
    padding: 11px 13px !important;
    font-size: 0.95rem !important;
    line-height: 1.3 !important;
    display: flex !important;
    align-items: center !important;
    gap: 9px !important;
    color: var(--ink-soft) !important;
    background: transparent !important;
    border-radius: 8px !important;
    text-decoration: none !important;
    transition: background-color .15s var(--ease), color .15s var(--ease);
}
.sidebar .collapse-item i,
.sidebar .collapse-item .bi {
    width: 22px !important;
    min-width: 22px !important;
    font-size: 1.05rem !important;
    text-align: center !important;
    color: var(--navy-700) !important;
    flex-shrink: 0 !important;
}
.sidebar .collapse-item:hover {
    background: var(--navy-100) !important;
    color: var(--navy-800) !important;
}
.sidebar .collapse-item.active {
    background: var(--gold-100) !important;
    color: var(--navy-900) !important;
    font-weight: 600 !important;
}

#collapseBarangayServices .collapse-inner {
    max-height: 420px;
    overflow-y: auto;
    overflow-x: hidden;
}
#collapseBarangayServices .collapse-inner::-webkit-scrollbar { width: 5px; }
#collapseBarangayServices .collapse-inner::-webkit-scrollbar-thumb { background: var(--gold-500); border-radius: 10px; }
#collapseBarangayServices .collapse-inner { scrollbar-width: thin; scrollbar-color: var(--gold-500) transparent; }

.sidebar #collapseUserMgmt,
.sidebar #collapseBarangayServices { width: 100%; max-width: 100%; }

/* Sidebar collapse toggle circle (desktop) */
#sidebarToggle {
    width: 34px; height: 34px;
    background: rgba(255,255,255,0.1);
    color: rgba(255,255,255,0.7);
    transition: background-color .15s var(--ease);
}
#sidebarToggle:hover { background: rgba(255,255,255,0.18); }

/* ============================================================
   TOPBAR
   ============================================================ */
.topbar {
    background: var(--paper) !important;
    box-shadow: var(--shadow-1) !important;
    border-bottom: 1px solid var(--line) !important;
    padding: 0 22px !important;
    height: 64px;
    align-items: center;
}

.topbar .nav-item .nav-link {
    color: var(--ink-soft) !important;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 9px 14px !important;
    border-radius: var(--r-sm);
    transition: background-color .18s var(--ease), color .18s var(--ease);
    display: flex;
    align-items: center;
    gap: 8px;
}
.topbar .nav-item .nav-link:hover { background: var(--navy-50); color: var(--navy-800) !important; }

.topbar .text-gray-800 { color: var(--ink) !important; font-weight: 500; }

#sidebarToggleTop {
    width: 40px; height: 40px;
    display: flex; align-items: center; justify-content: center;
    background: var(--navy-50);
    color: var(--navy-800) !important;
    transition: background-color .15s var(--ease);
}
#sidebarToggleTop:hover { background: var(--navy-100); }

.topbar-divider { border-left: 1px solid var(--line) !important; opacity: 1 !important; }

/* ── Notification Bell Dropdown ── */
#notifDropdown {
    position: relative;
    color: var(--ink-soft) !important;
}
#notifDropdown i { font-size: 1.05rem; }
#notifDropdown .badge-counter {
    background: linear-gradient(135deg, var(--danger), #ef6b62);
    color: #fff;
    border-radius: 50px;
    font-size: 0.64rem;
    font-weight: 700;
    padding: 2px 5px;
    min-width: 16px;
    text-align: center;
    line-height: 1.2;
    box-shadow: 0 0 0 2px #fff;
    position: absolute;
    top: -8px;
    right: -10px;
    transform: none;
    margin: 0;
    pointer-events: none;
}
/* Count sits ON the bell icon: anchor it to a wrapper around the icon only */
#notifDropdown .notif-bell-wrap { position: relative; display: inline-flex; align-items: center; justify-content: center; }
#notifDropdown::after { display: none !important; }

.notif-dropdown-menu {
    width: 388px;
    max-width: 90vw;
    max-height: 500px;
    overflow-y: auto;
    overflow-x: hidden;
    scrollbar-gutter: stable;
    padding: 0;
    border: none;
    border-radius: 18px;
    box-shadow: var(--shadow-3);
    margin-top: 10px !important;
}

.notif-dropdown-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding: 18px 24px 16px 20px;
    background: linear-gradient(135deg, var(--navy-900), var(--navy-700));
    position: sticky;
    top: 0;
    z-index: 2;
}
.notif-dropdown-header .notif-header-title {
    font-family: 'Outfit', sans-serif;
    font-weight: 700;
    font-size: 1.02rem;
    color: #fff;
    letter-spacing: 0.1px;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 9px;
}
.notif-dropdown-header .notif-header-title i { color: var(--gold-400); font-size: 0.9rem; }
.notif-dropdown-header .notif-header-count {
    background: var(--gold-500);
    color: var(--navy-900);
    font-weight: 700;
    font-size: 0.7rem;
    padding: 3px 11px;
    border-radius: 20px;
    white-space: nowrap;
}

.notif-section-title {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 11px 24px 11px 20px;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: var(--navy-700);
    background: var(--navy-50);
    flex-wrap: wrap;
    row-gap: 4px;
    border-bottom: 1px solid var(--line);
}
.notif-section-title i {
    width: 22px; height: 22px;
    border-radius: 50%;
    background: #fff;
    color: var(--gold-500);
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.7rem;
    flex-shrink: 0;
    box-shadow: var(--shadow-1);
}
.notif-section-title .notif-section-count {
    margin-left: auto;
    background: var(--navy-900);
    color: #fff;
    border-radius: 20px;
    padding: 1px 9px;
    font-size: 0.68rem;
    font-weight: 700;
}

.notif-item {
    display: flex !important;
    align-items: flex-start;
    gap: 12px;
    padding: 13px 24px 13px 20px !important;
    font-size: 0.83rem;
    white-space: normal !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #f1f3f8 !important;
    transition: background-color .15s var(--ease);
}
.notif-item:hover, .notif-item-msg:hover { background: var(--navy-50) !important; }

.notif-icon-wrap {
    width: 32px; height: 32px;
    border-radius: 10px;
    background: var(--gold-100);
    color: var(--gold-600);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.84rem;
    flex-shrink: 0;
    margin-top: 1px;
}

.notif-item span { color: var(--ink); }
.notif-item .notif-item-badge {
    margin-left: auto;
    margin-right: 2px;
    background: var(--gold-100);
    color: var(--gold-600);
    border: 1px solid var(--gold-200);
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 0.68rem;
    font-weight: 700;
    flex-shrink: 0;
    align-self: center;
}

.notif-item small.notif-time {
    display: block;
    color: var(--ink-faint);
    font-size: 0.71rem;
    margin-top: 3px;
}

.notif-empty {
    padding: 18px 20px 22px;
    font-size: 0.8rem;
    color: var(--ink-faint);
    font-style: italic;
    display: flex;
    align-items: center;
    gap: 9px;
}
.notif-empty::before {
    content: '\f00c';
    font-family: 'Font Awesome 5 Free';
    font-weight: 900;
    color: var(--gold-400);
}

.notif-viewall {
    text-align: center;
    font-size: 0.78rem;
    font-weight: 700;
    color: var(--navy-800) !important;
    padding: 12px !important;
    background: var(--navy-50);
    border-top: 1px solid var(--line);
    transition: background-color .15s var(--ease);
}
.notif-viewall:hover { background: var(--navy-100); text-decoration: none !important; }

.notif-mark-all-form { margin-left: auto; }
.notif-mark-all {
    background: none;
    border: none;
    color: var(--navy-700);
    font-size: 0.68rem;
    font-weight: 700;
    cursor: pointer;
    padding: 4px 9px;
    border-radius: 20px;
    display: flex;
    align-items: center;
    gap: 4px;
    transition: background-color .15s var(--ease), color .15s var(--ease);
}
.notif-mark-all:hover { background: #fff; color: var(--gold-600); }

.notif-item-msg {
    display: flex !important;
    align-items: flex-start;
    padding: 0 !important;
    border-left: none !important;
    border-right: none !important;
    border-bottom: 1px solid #f1f3f8 !important;
    transition: background-color .15s var(--ease);
}
.notif-item-msg .notif-item-link {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    flex: 1;
    min-width: 0;
    padding: 13px 8px 13px 20px;
    font-size: 0.83rem;
    text-decoration: none;
}
.notif-avatar {
    width: 32px; height: 32px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--navy-800), var(--navy-500));
    color: #fff;
    font-weight: 700;
    font-size: 0.78rem;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
    margin-top: 1px;
}
.notif-item-msg .notif-item-link span { color: var(--ink); }
.notif-item-msg .notif-item-link small.notif-time { color: var(--ink-faint); }

.notif-mark-item-form { flex-shrink: 0; padding-right: 20px; padding-top: 11px; }
.notif-mark-item {
    background: var(--gold-100);
    border: 1px solid var(--gold-200);
    color: var(--gold-600);
    border-radius: 50%;
    width: 27px;
    height: 27px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    cursor: pointer;
    transition: background-color .15s var(--ease), color .15s var(--ease);
}
.notif-mark-item:hover { background: var(--navy-900); color: #fff; border-color: var(--navy-900); }

.notif-caught-up { text-align: center; padding: 30px 18px; color: var(--ink-faint); }
.notif-caught-up i { font-size: 1.7rem; color: var(--gold-500); display: block; margin-bottom: 8px; }
.notif-caught-up span { font-family: 'Outfit', sans-serif; font-weight: 700; color: var(--navy-900); font-size: 0.96rem; }

/* ============================================================
   PAGE CONTENT — shared components used across admin pages
   ============================================================ */

.page-header {
    background: linear-gradient(135deg, var(--navy-900) 0%, var(--navy-700) 100%);
    color: #fff;
    border-radius: var(--r-lg);
    padding: 26px 30px;
    margin-bottom: 26px;
    display: flex;
    align-items: center;
    gap: 18px;
    box-shadow: var(--shadow-2);
    position: relative;
    overflow: hidden;
}
.page-header::after {
    content: '';
    position: absolute;
    top: -40%; right: -8%;
    width: 220px; height: 220px;
    background: radial-gradient(circle, rgba(201,148,58,0.28) 0%, rgba(201,148,58,0) 70%);
    pointer-events: none;
}
.page-header .header-icon {
    width: 58px; height: 58px; border-radius: var(--r-md);
    background: rgba(255,255,255,0.14);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.6rem; flex-shrink: 0;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.14);
}
.page-header h2 { margin: 0; font-size: 1.55rem; font-weight: 700; }
.page-header p  { margin: 4px 0 0; opacity: 0.78; font-size: 0.88rem; }

/* Stat cards */
.stat-card {
    border-radius: var(--r-md);
    padding: 20px 22px;
    display: flex; align-items: center; gap: 16px;
    box-shadow: var(--shadow-1);
    border: 1px solid var(--line);
    transition: transform .18s var(--ease), box-shadow .18s var(--ease);
    background: var(--paper);
}
.stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-2); }
.stat-card .stat-icon {
    width: 50px; height: 50px; border-radius: var(--r-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem; flex-shrink: 0;
}
.stat-card .stat-val { font-size: 1.85rem; font-weight: 800; line-height: 1; font-family: 'Outfit', sans-serif; }
.stat-card .stat-lbl { font-size: 0.75rem; text-transform: uppercase; letter-spacing: .06em; font-weight: 600; opacity: 0.6; margin-top: 4px; }

.stat-all      { border-color: var(--line); }
.stat-all      .stat-icon { background: var(--navy-100); color: var(--navy-800); }
.stat-all      .stat-val  { color: var(--navy-800); }

.stat-pending  { background: var(--warning-100); border-color: var(--gold-200); }
.stat-pending  .stat-icon { background: #ffe6b3; color: var(--warning); }
.stat-pending  .stat-val  { color: var(--warning); }

.stat-resolved { background: var(--success-100); border-color: #bfe9d3; }
.stat-resolved .stat-icon { background: #c3f2da; color: var(--success); }
.stat-resolved .stat-val  { color: var(--success); }

/* Filters */
.filter-bar {
    background: var(--paper); border-radius: var(--r-md);
    padding: 18px 22px; margin-bottom: 22px;
    box-shadow: var(--shadow-1);
    border: 1px solid var(--line);
    display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end;
}
.filter-bar .form-control,
.filter-bar .form-select {
    border-radius: var(--r-sm); font-size: 0.875rem;
    border: 1.5px solid var(--line);
}
.filter-bar .form-control:focus,
.filter-bar .form-select:focus {
    border-color: var(--navy-700);
    box-shadow: 0 0 0 3px rgba(22,63,120,0.12);
}
.btn-filter {
    background: var(--navy-800); color: #fff;
    border-radius: var(--r-sm); border: none;
    padding: 9px 20px; font-size: 0.875rem; font-weight: 600;
    transition: background-color .15s var(--ease);
}
.btn-filter:hover { background: var(--navy-900); color: #fff; }
.btn-filter-clear {
    background: var(--navy-50); color: var(--ink-soft);
    border-radius: var(--r-sm); border: none;
    padding: 9px 16px; font-size: 0.875rem;
}
.btn-filter-clear:hover { background: var(--navy-100); }

/* Filter tabs */
.filter-tabs { display: flex; gap: 8px; margin-bottom: 0; flex-wrap: wrap; }
.filter-tab {
    padding: 8px 18px; border-radius: var(--r-sm); border: 1.5px solid var(--line);
    font-size: 0.85rem; font-weight: 600; cursor: pointer; background: var(--paper);
    color: var(--ink-soft); text-decoration: none; transition: all .15s var(--ease);
}
.filter-tab:hover, .filter-tab.active { border-color: var(--navy-800); background: var(--navy-800); color: #fff; }
.filter-tab.tab-pending.active  { border-color: var(--gold-500); background: var(--warning-100); color: var(--warning); }
.filter-tab.tab-resolved.active { border-color: var(--success); background: var(--success-100); color: var(--success); }

/* Complaint / record cards */
.complaint-list { display: flex; flex-direction: column; gap: 16px; }

.c-card {
    background: var(--paper); border-radius: var(--r-lg);
    padding: 22px 24px;
    box-shadow: var(--shadow-1);
    border: 1px solid var(--line);
    border-left: 5px solid var(--line);
    transition: box-shadow .2s var(--ease), border-color .2s var(--ease);
}
.c-card:hover { box-shadow: var(--shadow-2); }
.c-card.c-pending  { border-left-color: var(--gold-500); }
.c-card.c-resolved { border-left-color: var(--success); }

.c-card-header {
    display: flex; flex-wrap: wrap;
    align-items: flex-start; justify-content: space-between;
    gap: 10px; margin-bottom: 14px;
}
.c-id   { font-size: 0.7rem; color: var(--ink-faint); font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
.c-name { font-size: 1.05rem; font-weight: 700; color: var(--navy-800); font-family: 'Outfit', sans-serif; }
.c-contact { font-size: 0.82rem; color: var(--ink-faint); }

.badge-pending  { background: var(--warning-100); color: var(--warning); border: 1px solid var(--gold-200); border-radius: var(--r-sm); padding: 4px 10px; font-size: 0.75rem; font-weight: 700; }
.badge-resolved { background: var(--success-100); color: var(--success); border: 1px solid #bfe9d3; border-radius: var(--r-sm); padding: 4px 10px; font-size: 0.75rem; font-weight: 700; }

.c-meta { display: flex; flex-wrap: wrap; gap: 16px; margin-bottom: 12px; font-size: 0.85rem; color: var(--ink-soft); }
.c-meta span { display: flex; align-items: center; gap: 5px; }

.c-category-tag {
    display: inline-block;
    background: var(--navy-100); color: var(--navy-800);
    border-radius: var(--r-sm); padding: 3px 10px;
    font-size: 0.78rem; font-weight: 600;
}
.c-description {
    font-size: 0.9rem; color: var(--ink);
    border-left: 3px solid var(--line);
    padding-left: 12px; margin: 12px 0;
    line-height: 1.55;
}
.c-remarks {
    background: var(--canvas); border-radius: var(--r-sm);
    padding: 10px 14px; font-size: 0.85rem;
    color: var(--ink-soft); margin-top: 10px;
    border: 1px solid var(--line);
}
.c-photo { margin: 12px 0; }
.c-photo img { max-height: 160px; border-radius: var(--r-sm); border: 1px solid var(--line); cursor: pointer; }

.c-actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 16px; }
.btn-resolve {
    background: var(--success); color: #fff; border: none;
    border-radius: var(--r-sm); padding: 8px 18px; font-size: 0.85rem; font-weight: 600;
    transition: background-color .15s var(--ease);
}
.btn-resolve:hover { background: #17825253; background: #17824f; color: #fff; }
.btn-set-pending {
    background: var(--gold-500); color: #fff; border: none;
    border-radius: var(--r-sm); padding: 8px 18px; font-size: 0.85rem; font-weight: 600;
}
.btn-set-pending:hover { background: var(--gold-600); color: #fff; }
.btn-delete-card {
    background: var(--danger); color: #fff; border: none;
    border-radius: var(--r-sm); padding: 8px 18px; font-size: 0.85rem; font-weight: 600;
}
.btn-delete-card:hover { background: #bb3a31; color: #fff; }
.btn-view-detail {
    background: var(--navy-50); color: var(--ink-soft); border: 1px solid var(--line);
    border-radius: var(--r-sm); padding: 8px 16px; font-size: 0.85rem; font-weight: 600;
}
.btn-view-detail:hover { background: var(--navy-100); }

/* Empty state */
.empty-state { text-align: center; padding: 60px 20px; color: var(--ink-faint); }
.empty-state i { font-size: 4rem; display: block; margin-bottom: 16px; color: var(--gold-200); }

/* Modal tweaks */
.modal-content { border-radius: var(--r-lg); overflow: hidden; border: none; }
.modal-header { background: linear-gradient(135deg, var(--navy-900), var(--navy-700)); color: #fff; border-radius: 0; }
.modal-header .btn-close { filter: invert(1); }
.modal-footer { border-top: 1px solid var(--line); }

/* ============================================================
   UNIFORM COMPACT DATA TABLE — applies site-wide to every
   admin listing table (certificates, records, requests, logs).
   Modeled on the admn_certofres table, but row height/padding
   is compressed Excel-style so more records fit in first view.
   Since this lives in the shared sidebar include, it cascades
   to every admin page automatically — no per-page edits needed.
   ============================================================ */
.table-responsive {
    border-radius: var(--r-md);
    box-shadow: var(--shadow-1);
    border: 1px solid var(--line);
    background: var(--paper);
}
table.table {
    margin-bottom: 0 !important;
    font-size: 0.78rem !important;
    border-collapse: separate !important;
    border-spacing: 0 !important;
}

/* Header — normalizes alert-info / bg-info / table-dark variants
   used across different pages into one navy/gold header */
table.table thead th,
table.table thead.alert-info th,
table.table thead.table-dark th,
table.table thead.bg-info th,
table.table thead tr th {
    background: linear-gradient(135deg, var(--navy-800), var(--navy-700)) !important;
    color: #fff !important;
    font-size: 0.7rem !important;
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    padding: 8px 10px !important;
    border: none !important;
    border-bottom: 2px solid var(--gold-500) !important;
    white-space: nowrap;
}

/* Body rows — compact like a spreadsheet, zebra-striped, clear hover */
table.table tbody td,
table.table tbody th {
    padding: 5px 10px !important;
    font-size: 0.78rem !important;
    vertical-align: middle !important;
    border-color: var(--line) !important;
    line-height: 1.35 !important;
}
table.table tbody tr:nth-child(even) { background: var(--navy-50); }
table.table tbody tr:hover { background: var(--gold-100) !important; }
table.table.table-dark tbody tr:nth-child(even) { background: rgba(255,255,255,0.04); }
table.table.table-dark tbody tr:hover { background: rgba(201,148,58,0.18) !important; }

/* Action buttons/links inside table cells — overrides oversized
   inline pill buttons (width/font-size/border-radius) site-wide,
   since an !important rule in this stylesheet outranks a plain
   inline style attribute without !important. */
table.table td .btn,
table.table td a.btn {
    width: auto !important;
    min-width: 0 !important;
    height: auto !important;
    font-size: 0.68rem !important;
    padding: 3px 9px !important;
    border-radius: 6px !important;
    margin: 1px 2px !important;
    line-height: 1.5 !important;
    box-shadow: none !important;
}
table.table td form {
    display: flex;
    flex-wrap: wrap;
    gap: 3px;
    justify-content: center;
    align-items: center;
}

/* Status pill <span>s inside cells (Generated / Pending / etc.) */
table.table td span[style*="border-radius:6px"],
table.table td span[style*="border-radius: 6px"] {
    padding: 2px 8px !important;
    font-size: 0.68rem !important;
    white-space: nowrap;
}

@media (max-width: 767.98px) {
    table.table thead th { padding: 7px 8px !important; font-size: 0.66rem !important; }
    table.table tbody td, table.table tbody th { padding: 5px 8px !important; font-size: 0.74rem !important; }
    table.table td .btn, table.table td a.btn { font-size: 0.64rem !important; padding: 3px 7px !important; }
}

/* ============================================================
   MOBILE RESPONSIVE
   ============================================================ */

@media (max-width: 991.98px) {
    .sidebar {
        width: 290px !important;
        min-width: 290px !important;
        max-width: 290px !important;
    }
    #content-wrapper { width: calc(100% - 290px); max-width: calc(100% - 290px); }
    .topbar { padding: 0 14px !important; }
    .page-header { padding: 22px; }
    .page-header h2 { font-size: 1.4rem; }
    .stat-card { padding: 16px; }
    .stat-card .stat-val { font-size: 1.6rem; }
    .filter-bar { padding: 15px; }
}

@media (max-width: 767.98px) {
    body { font-size: 14px !important; }
    #wrapper { width: 100%; overflow-x: hidden !important; }

    .sidebar {
        position: fixed !important;
        top: 0; left: 0; bottom: 0;
        z-index: 1050;
        width: 78vw !important;
        min-width: 78vw !important;
        max-width: 330px !important;
        overflow-y: auto !important;
        overflow-x: hidden !important;
        transform: translateX(-100%);
        transition: transform 0.28s var(--ease);
    }
    .sidebar.toggled { transform: translateX(0); width: 78vw !important; max-width: 330px !important; }

    #content-wrapper { width: 100% !important; max-width: 100% !important; margin-left: 0 !important; }
    #content { width: 100%; max-width: 100%; overflow-x: hidden; }

    .sidebar .sidebar-brand { min-height: 70px; padding: 15px 14px !important; }
    .sidebar-brand-text { font-size: 0.95rem !important; }
    .sidebar .nav-item .nav-link { padding: 12px 14px !important; font-size: 0.95rem !important; gap: 10px !important; }
    .sidebar .nav-item .nav-link i, .sidebar .nav-item .nav-link .bi { width: 17px; font-size: 0.85rem; }
    .sidebar .collapse-item { padding: 9px 10px !important; font-size: 0.75rem !important; }

    .topbar { height: 60px !important; min-height: 60px !important; padding: 0 8px !important; margin-bottom: 15px !important; width: 100% !important; }
    #sidebarToggleTop { width: 40px; height: 40px; margin-right: 4px !important; flex-shrink: 0; border-radius: 10px !important; }
    .topbar .navbar-nav { margin-left: auto !important; display: flex; align-items: center; }
    .topbar .nav-item { flex-shrink: 0; }
    .topbar .nav-link { padding: 8px !important; }
    .topbar-divider { display: none !important; }
    .topbar .d-none.d-lg-inline { display: none !important; }
    #notifDropdown { padding: 9px !important; }
    #notifDropdown i { font-size: 1rem; }
    #notifDropdown .badge-counter { font-size: 0.58rem; min-width: 15px; padding: 2px 4px; }

    .notif-dropdown-menu {
        position: fixed !important;
        top: 60px !important; right: 8px !important; left: 8px !important;
        width: auto !important; max-width: none !important;
        max-height: calc(100vh - 75px) !important;
        border-radius: 16px !important;
    }
    .notif-dropdown-header { padding: 14px 16px !important; }
    .notif-dropdown-header .notif-header-title { font-size: 0.95rem; }
    .notif-section-title { padding: 10px 15px 8px !important; font-size: 0.65rem; }
    .notif-item { padding: 11px 15px !important; gap: 9px; font-size: 0.78rem; }
    .notif-icon-wrap { width: 28px; height: 28px; border-radius: 8px; font-size: 0.72rem; }
    .notif-item .notif-item-badge { font-size: 0.62rem; padding: 2px 7px; }
    .notif-item-msg .notif-item-link { gap: 9px; padding: 11px 5px 11px 15px; min-width: 0; }
    .notif-item-msg .notif-item-link > span:last-child { min-width: 0; overflow-wrap: anywhere; }
    .notif-avatar { width: 28px; height: 28px; font-size: 0.7rem; }
    .notif-mark-item-form { padding-right: 12px; padding-top: 9px; }
    .notif-mark-item { width: 25px; height: 25px; }
    .notif-empty { padding: 14px; }
    .notif-caught-up { padding: 22px 14px; }

    .page-header { padding: 18px !important; margin: 0 10px 18px !important; border-radius: var(--r-md); gap: 12px; align-items: center; }
    .page-header .header-icon { width: 45px; height: 45px; border-radius: var(--r-sm); font-size: 1.2rem; }
    .page-header h2 { font-size: 1.15rem !important; line-height: 1.25; }
    .page-header p { font-size: 0.75rem; line-height: 1.4; }

    .stat-card { padding: 14px !important; border-radius: var(--r-sm); gap: 11px; }
    .stat-card .stat-icon { width: 42px; height: 42px; border-radius: var(--r-sm); font-size: 1.15rem; }
    .stat-card .stat-val { font-size: 1.35rem; }
    .stat-card .stat-lbl { font-size: 0.65rem; letter-spacing: 0.03em; }

    .filter-bar { margin: 0 10px 18px !important; padding: 13px !important; flex-direction: column; align-items: stretch !important; gap: 9px !important; border-radius: var(--r-sm); }
    .filter-bar > * { width: 100% !important; max-width: 100% !important; }
    .filter-bar .form-control, .filter-bar .form-select { min-height: 42px; width: 100% !important; }
    .btn-filter, .btn-filter-clear { width: 100% !important; min-height: 42px; }
    .filter-tabs { width: 100%; display: flex; flex-wrap: wrap; gap: 6px; }
    .filter-tab { flex: 1 1 auto; text-align: center; padding: 8px 10px; font-size: 0.75rem; }

    .complaint-list { padding: 0 10px; gap: 12px; }
    .c-card { padding: 15px !important; border-radius: var(--r-md); border-left-width: 4px; }
    .c-card-header { flex-direction: column; gap: 7px; margin-bottom: 11px; }
    .c-name { font-size: 0.95rem; line-height: 1.35; }
    .c-contact { font-size: 0.75rem; overflow-wrap: anywhere; }
    .c-meta { flex-direction: column; gap: 6px; font-size: 0.78rem; }
    .c-description { font-size: 0.82rem; line-height: 1.5; padding-left: 9px; }
    .c-remarks { font-size: 0.78rem; padding: 9px 11px; }
    .c-photo img { width: 100%; height: auto; max-height: none; }

    .c-actions { display: grid; grid-template-columns: 1fr; gap: 7px; }
    .c-actions button, .c-actions a { width: 100% !important; text-align: center; min-height: 40px; display: flex; align-items: center; justify-content: center; }

    .modal-dialog { width: calc(100% - 20px) !important; max-width: none !important; margin: 10px auto !important; }
    .modal-header { padding: 13px 15px; }
    .modal-body { padding: 15px !important; max-height: 70vh; overflow-y: auto; }
    .modal-footer { padding: 12px 15px; }

    .empty-state { padding: 40px 15px; }
    .empty-state i { font-size: 3rem; }

    h4 { font-size: 0.95rem !important; }
    h4::before { height: 17px; width: 3px; }

    /* Submenu on mobile becomes a normal in-flow full-width block
       (sb-admin-2's desktop fly-out rule would otherwise pin it
       to a floating box near the sidebar edge). */
    .sidebar.toggled .nav-item .collapse,
    .sidebar.toggled .nav-item .collapsing {
        position: static !important;
        left: auto !important; top: auto !important; right: auto !important;
        z-index: auto !important;
        width: 100% !important;
    }
    .sidebar.toggled .nav-item .collapse-inner {
        position: static !important;
        width: calc(100% - 12px) !important;
        max-width: 100% !important;
        margin: 6px auto !important;
        max-height: 60vh !important;
        overflow-y: auto !important;
        -webkit-overflow-scrolling: touch;
    }
    .sidebar.toggled .nav-item:hover .collapse:not(.show) { display: none !important; }
}

@media (max-width: 480px) {
    .topbar { padding: 0 5px !important; }
    #sidebarToggleTop { width: 36px; height: 36px; }
    .topbar .nav-link { padding: 6px !important; }

    .page-header { margin-left: 7px !important; margin-right: 7px !important; padding: 15px !important; }
    .page-header .header-icon { width: 40px; height: 40px; font-size: 1rem; }
    .page-header h2 { font-size: 1rem !important; }
    .page-header p { font-size: 0.7rem; }

    .stat-card { padding: 12px !important; }
    .stat-card .stat-icon { width: 38px; height: 38px; font-size: 1rem; }
    .stat-card .stat-val { font-size: 1.2rem; }
    .stat-card .stat-lbl { font-size: 0.58rem; }

    .filter-bar, .complaint-list { margin-left: 7px !important; margin-right: 7px !important; }
    .c-card { padding: 13px !important; }
    .badge-pending, .badge-resolved { font-size: 0.68rem; padding: 4px 8px; }

    .notif-dropdown-menu { top: 58px !important; left: 5px !important; right: 5px !important; border-radius: 14px !important; }
    .notif-dropdown-header { padding: 12px !important; }
    .notif-section-title { padding-left: 12px !important; padding-right: 12px !important; }
    .notif-item { padding-left: 12px !important; padding-right: 12px !important; }
}

@media (max-width: 360px) {
    .sidebar, .sidebar.toggled { width: 82vw !important; min-width: 82vw !important; max-width: 300px !important; }
    .page-header { gap: 9px; }
    .page-header .header-icon { width: 36px; height: 36px; }
    .page-header h2 { font-size: 0.92rem !important; }
    .stat-card { gap: 8px; }
    .stat-card .stat-icon { width: 34px; height: 34px; }
    .stat-card .stat-val { font-size: 1.05rem; }
    .filter-tab { font-size: 0.68rem; padding: 7px 6px; }
}

</style>
<style>
/* ============================================================
   RESPONSIVE LAYER — whole admin portal (phones, tablets, laptops)
   Additive: nothing here changes the desktop layout (>= 992px).
   ============================================================ */

/* Never allow sideways page scroll; media never overflows */
html, body { max-width: 100%; overflow-x: hidden; }
img, video, iframe { max-width: 100%; }
img, video { height: auto; }
canvas { max-width: 100%; }
input, select, textarea { max-width: 100%; }
.tbl-scroll { max-width: 100%; }

/* ---- Tablets & small laptops (768–991px): sidebar becomes a slide-in drawer ---- */
@media (min-width: 768px) and (max-width: 991.98px) {
    .sidebar {
        position: fixed !important; top: 0; left: 0; bottom: 0; z-index: 1050;
        width: 290px !important; min-width: 290px !important; max-width: 290px !important;
        overflow-y: auto !important; overflow-x: hidden !important;
        transform: translateX(-100%); transition: transform .28s ease;
    }
    .sidebar.toggled { transform: translateX(0); width: 290px !important; min-width: 290px !important; max-width: 290px !important; }
    .sidebar.toggled .nav-item .nav-link span,
    .sidebar.toggled .sidebar-brand-text { display: inline !important; }
    .sidebar.toggled .nav-item .nav-link { text-align: left !important; width: auto !important; }
    .sidebar.toggled .sidebar-heading { text-align: left !important; }
    .sidebar.toggled .nav-item .collapse,
    .sidebar.toggled .nav-item .collapsing {
        position: static !important; left: auto !important; top: auto !important; right: auto !important;
        z-index: auto !important; width: 100% !important;
    }
    .sidebar.toggled .nav-item .collapse-inner { position: static !important; width: calc(100% - 12px) !important; margin: 6px auto !important; }
    #content-wrapper { width: 100% !important; max-width: 100% !important; margin-left: 0 !important; }
    #sidebarToggleTop { display: inline-flex !important; align-items: center; justify-content: center; }
    #sidebarToggle { display: none !important; }
    .topbar-divider { display: none !important; }
}

/* ---- Tablets and down (<= 991px) ---- */
@media (max-width: 991.98px) {
    .container-fluid { padding-left: 14px !important; padding-right: 14px !important; }
    h1, .h1 { font-size: clamp(1.35rem, 4.6vw, 1.9rem) !important; line-height: 1.25; overflow-wrap: anywhere; }
    h2 { font-size: clamp(1.15rem, 3.8vw, 1.5rem) !important; overflow-wrap: anywhere; }

    /* Report/service toolbars that use inline % margins and 30%-wide search boxes */
    .input-icons { width: min(460px, 100%) !important; margin-left: auto !important; margin-right: auto !important; }
    form .btn[style*="margin-left"] { margin-left: 0 !important; }
    form > .btn, form > a.btn { margin-top: 6px; }

    /* Tables: scroll sideways inside their own box, never the page */
    .table-responsive, .dataTables_wrapper, .tbl-scroll {
        width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch;
    }
    .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_paginate { float: none !important; text-align: center !important; }
    .dataTables_wrapper .dataTables_filter input { width: min(260px, 100%); margin-left: 0 !important; }
    .pagination { flex-wrap: wrap; justify-content: center; }

    /* Common flex rows wrap instead of overflowing */
    .d-flex.justify-content-between, .card-header.d-flex { flex-wrap: wrap; gap: .5rem; }
    .btn-toolbar, .btn-group-toolbar { flex-wrap: wrap; gap: 6px; }

    /* Dropdown menus never wider than the screen */
    .dropdown-menu { max-width: calc(100vw - 16px); }
    .dropdown-menu-right, .dropdown-menu-end { right: 0; left: auto; }
}

/* ---- Phones (<= 767px) ---- */
@media (max-width: 767.98px) {
    .container-fluid { padding-left: 10px !important; padding-right: 10px !important; }
    .row { margin-left: -6px; margin-right: -6px; }
    .row > [class*="col"] { padding-left: 6px; padding-right: 6px; }

    /* Search + action buttons stack neatly */
    .input-icons { width: 100% !important; }
    form .btn { min-height: 42px; }

    /* Modals: fit the screen, scroll inside */
    .modal-dialog { margin: 8px auto !important; max-width: calc(100vw - 16px) !important; }
    .modal-content { max-height: calc(100vh - 16px); overflow-y: auto; }
    .modal-body { overflow-x: hidden; }
    .modal-footer { flex-wrap: wrap; gap: 6px; }
    .modal-footer .btn { flex: 1 1 auto; }

    /* Forms: single-column feel */
    .form-row > [class*="col"], .form-row > .form-group { flex: 0 0 100%; max-width: 100%; }
    label { font-size: .85rem; }
    .form-control, .form-select, select, textarea, input:not([type="checkbox"]):not([type="radio"]) { font-size: 16px; } /* no iOS zoom-on-focus */

    /* Cards / KPI / charts */
    .card-body { padding: .9rem; }
    .chart-area, .chart-pie, .chart-bar, [class*="chart-container"], .chart-wrap { max-width: 100%; }
    canvas { max-height: 320px; }

    /* Custom confirm dialogs (Archive, Logs, History, Announcements) */
    .modal-box, .bmis-modal-card, #restoreModal > div, #deleteModal > div, #bulkRestoreModal > div, #bulkDeleteModal > div {
        width: calc(100vw - 24px) !important; max-width: 420px !important; padding: 20px 16px !important; max-height: 90vh; overflow-y: auto;
    }
    .modal-actions, .bmis-modal-card [style*="justify-content:flex-end"] { flex-wrap: wrap; }

    /* Bulk toolbars wrap */
    .bulk-toolbar, .bulk-bar { flex-wrap: wrap; gap: 8px; }
    .bulk-toolbar .btn-bulk-restore, .bulk-toolbar .btn-bulk-delete, .bulk-toolbar .btn-bulk-clear,
    .bulk-bar .btn-delete-bulk, .bulk-bar .btn-deselect { flex: 1 1 auto; justify-content: center; }

    /* Footer + long text */
    .sticky-footer { padding: 14px 0 !important; }
    td, th, .card-title, .modal-title { overflow-wrap: anywhere; }
}

/* ---- Small phones (<= 400px) ---- */
@media (max-width: 400px) {
    .container-fluid { padding-left: 8px !important; padding-right: 8px !important; }
    .topbar .nav-link { padding: 6px !important; }
    .btn { padding-left: .6rem; padding-right: .6rem; }
    h1, .h1 { font-size: 1.25rem !important; }
}

/* ---- Touch devices: comfortable tap targets ---- */
@media (hover: none) and (pointer: coarse) {
    .sidebar .nav-link, .sidebar .collapse-item, .dropdown-item { min-height: 44px; }
    .btn { min-height: 40px; }
    input[type="checkbox"], input[type="radio"] { min-width: 18px; min-height: 18px; }
}

/* ---- Landscape phones: keep the topbar compact ---- */
@media (max-height: 480px) and (orientation: landscape) {
    .topbar { height: 52px !important; min-height: 52px !important; margin-bottom: 8px !important; }
    .modal-content { max-height: calc(100vh - 12px); }
}
</style>

<style>
/* ============================================================
   MOBILE FIXES — stat cards side by side + slide-in sidebar
   ============================================================ */

/* ---- Stat / KPI cards: two per row on phones (4 boxes = 2 x 2) ---- */
@media (max-width: 767.98px) {
    .row > [class*="col"]:has(> .card[class*="border-left-"]),
    .row > .col-xl-3.col-md-6,
    .row > .col-md-3.col-sm-6 { flex: 0 0 50% !important; max-width: 50% !important; }

    .card[class*="border-left-"].py-2, .card[class*="border-left-"].py-3 { padding-top: 0 !important; padding-bottom: 0 !important; }
    .card[class*="border-left-"] .card-body { padding: .7rem .65rem !important; }
    .card[class*="border-left-"] .row.no-gutters { flex-wrap: nowrap; }
    .card[class*="border-left-"] .text-xs { font-size: .6rem !important; letter-spacing: .3px !important; line-height: 1.25; margin-bottom: .25rem !important; }
    .card[class*="border-left-"] .h4, .card[class*="border-left-"] .h5 { font-size: 1.2rem !important; line-height: 1.15; }
    .card[class*="border-left-"] .col-auto { padding-left: 4px; }
    .card[class*="border-left-"] .col-auto i { font-size: 1.3rem !important; }
    .card[class*="border-left-"] a { font-size: .7rem; white-space: nowrap; }
}
@media (max-width: 360px) {
    .card[class*="border-left-"] .col-auto { display: none; }
}

/* ---- Slide-in sidebar (phones + tablets): reset SB-Admin's icon-rail rules ---- */
@media (max-width: 991.98px) {
    #accordionSidebar .sidebar-brand { justify-content: flex-start !important; text-align: left !important; }
    #accordionSidebar .sidebar-brand .sidebar-brand-text { display: block !important; }

    #accordionSidebar .nav-item .nav-link {
        display: flex !important; align-items: center !important; justify-content: flex-start !important;
        text-align: left !important; width: auto !important;
    }
    #accordionSidebar .nav-item .nav-link span {
        display: inline !important; font-size: inherit !important; text-align: left !important; white-space: normal;
    }
    #accordionSidebar .nav-item .nav-link[data-bs-toggle="collapse"]::after,
    #accordionSidebar .nav-item .nav-link[data-toggle="collapse"]::after { margin-left: auto !important; }
    #accordionSidebar .sidebar-heading { text-align: left !important; }
    #accordionSidebar .collapse-item { text-align: left !important; }

    /* dim the page behind the open drawer; tapping it closes the drawer */
    #accordionSidebar.toggled ~ #content-wrapper::after {
        content: ''; position: fixed; inset: 0; z-index: 1040;
        background: rgba(0, 0, 0, .5);
    }
}
</style>

<style>
/* ============================================================
   BARANGAY SERVICES toolbar: search box + Search / Reload / Add
   centered, with all three buttons the same size.
   ============================================================ */
.svc-toolbar .input-icons {
    width: min(460px, 100%) !important;
    margin: 0 auto 14px !important;
}
.svc-actions {
    display: flex; flex-wrap: wrap; justify-content: center; align-items: center;
    gap: 12px; margin: 0 auto;
}
.svc-actions .svc-btn {
    flex: 0 0 170px; width: 170px; height: 44px;
    display: inline-flex; align-items: center; justify-content: center;
    padding: 0 12px !important; margin: 0 !important;
    font-size: 16px !important; font-weight: 600; line-height: 1;
    border-radius: 30px !important; white-space: nowrap; text-align: center;
}
/* Phones: keep Search / Reload / Add side by side, equal width, in one row */
@media (max-width: 575.98px) {
    .svc-actions { flex-wrap: nowrap; gap: 6px; width: 100%; }
    .svc-actions .svc-btn {
        flex: 1 1 0; width: auto; min-width: 0; max-width: none;
        height: 44px; padding: 0 6px !important;
        font-size: 13px !important; white-space: normal; line-height: 1.1;
    }
}
@media (max-width: 380px) {
    .svc-actions { gap: 4px; }
    .svc-actions .svc-btn { font-size: 12px !important; padding: 0 4px !important; }
}
</style>

<style>
/* ============================================================
   GLOBAL SEARCH (topbar)
   ============================================================ */
.topbar .navbar-nav { align-items: center; }
.gs-item { position: relative; display: flex; align-items: center; align-self: center; top: 3px; }
.gs-toggle { display: none !important; background: none; border: 0; cursor: pointer; }
.gs-form { position: relative; display: flex; align-items: center; }
.gs-icon { position: absolute; left: 13px; font-size: .82rem; color: #1f3b66; pointer-events: none; }
.gs-input {
    width: 260px; height: 40px; padding: 0 36px 0 36px;
    border: 1.5px solid #8795ad; border-radius: 999px;
    background: #ffffff; color: #111827;
    box-shadow: 0 1px 4px rgba(15,45,90,.14);
    font-size: .85rem; outline: none;
    transition: width .2s ease, border-color .15s ease, background-color .15s ease, box-shadow .15s ease;
    -webkit-appearance: none; appearance: none;
}
.gs-input::-webkit-search-cancel-button { -webkit-appearance: none; }
.gs-input::placeholder { color: #5b6678; opacity: 1; }
.gs-input:hover { border-color: #5f7192; }
.gs-input:focus { width: 340px; background: #fff; border-color: #1f4f9a; box-shadow: 0 0 0 3px rgba(31,79,154,.22); }
.gs-kbd { position: absolute; right: 12px; font-size: .7rem; padding: 1px 6px; border: 1px solid #8795ad; border-radius: 4px; color: #4a5568; background: transparent; pointer-events: none; }
.gs-input:focus + .gs-kbd, .gs-input:not(:placeholder-shown) + .gs-kbd { display: none; }

.gs-panel {
    position: absolute; top: calc(100% + 8px); right: 0; width: 460px; max-width: 92vw;
    max-height: 70vh; overflow-y: auto; z-index: 1060;
    background: var(--paper, #fff); border: 1px solid var(--line, #dfe3ec); border-radius: 12px;
    box-shadow: 0 12px 32px rgba(15,45,90,.18); padding: 6px 0;
}
.gs-panel[hidden] { display: none; }
.gs-group-h { display: flex; align-items: center; gap: 8px; padding: 10px 14px 4px; font-size: .68rem; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--ink-faint, #8a94a6); }
.gs-group-h .gs-count { margin-left: auto; font-weight: 600; text-transform: none; letter-spacing: 0; }
.gs-row { display: block; padding: 8px 14px; text-decoration: none !important; color: inherit; border-left: 3px solid transparent; cursor: pointer; }
.gs-row:hover, .gs-row.gs-active { background: var(--navy-50, #f4f6fb); border-left-color: var(--navy-500, #4a6fa5); }
.gs-title { display: block; font-size: .88rem; font-weight: 600; color: var(--ink, #1a202c); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.gs-snip  { display: block; font-size: .76rem; color: var(--ink-faint, #8a94a6); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.gs-title mark, .gs-snip mark { background: rgba(250,204,21,.35); color: inherit; padding: 0 1px; border-radius: 2px; }
.gs-more { display: block; padding: 2px 14px 6px; font-size: .74rem; }
.gs-state { padding: 22px 16px; text-align: center; font-size: .85rem; color: var(--ink-faint, #8a94a6); }

html[data-theme] .gs-input { background: var(--tm-input) !important; color: var(--tm-head) !important; border: 1.5px solid var(--tm-input-border) !important; box-shadow: none !important; }
html[data-theme] .gs-input:hover { border-color: var(--tm-muted) !important; }
html[data-theme] .gs-input:focus { background: var(--tm-surface) !important; border-color: var(--tm-icon-active) !important; box-shadow: 0 0 0 3px rgba(var(--tm-focus-rgb), .3) !important; }
html[data-theme] .gs-input::placeholder { color: var(--tm-muted) !important; }
html[data-theme] .gs-icon { color: var(--tm-icon) !important; }
html[data-theme="light"] .gs-input { background: #ffffff !important; border-color: #7b8aa3 !important; box-shadow: 0 1px 4px rgba(15,23,42,.12) !important; }
html[data-theme="dark"] .gs-input { background: #1b1b1b !important; border-color: rgba(255,255,255,.45) !important; color: #ffffff !important; }
html[data-theme="dark"] .gs-input::placeholder { color: #b4bccb !important; }
html[data-theme="dark"] .gs-input:hover { border-color: rgba(255,255,255,.75) !important; }
html[data-theme="dark"] .gs-kbd { border-color: rgba(255,255,255,.4) !important; color: #d7dbe2 !important; }
html[data-theme] .gs-panel { background: var(--tm-surface) !important; border: 1px solid var(--tm-border) !important; box-shadow: var(--tm-pop-shadow) !important; }
html[data-theme] .gs-row:hover, html[data-theme] .gs-row.gs-active { background: var(--tm-hover) !important; }
html[data-theme] .gs-title { color: var(--tm-head) !important; }
html[data-theme] .gs-snip, html[data-theme] .gs-group-h, html[data-theme] .gs-state { color: var(--tm-muted) !important; }
html[data-theme] .gs-title mark, html[data-theme] .gs-snip mark { background: rgba(250,204,21,.35) !important; }

.gs-banner { display: flex; align-items: center; gap: 10px; margin: 0 24px 14px; padding: 10px 14px; border: 1px solid #8795ad; border-left: 4px solid #1f4f9a; border-radius: 8px; background: #eef3fb; color: #1a202c; font-size: .86rem; }
.gs-banner i { color: #1f4f9a; }
.gs-banner a { margin-left: auto; font-weight: 600; white-space: nowrap; color: #1f4f9a; }
html[data-theme] .gs-banner { background: var(--tm-surface2) !important; border-color: var(--tm-border) !important; border-left-color: var(--tm-icon-active) !important; }
html[data-theme] .gs-banner a, html[data-theme] .gs-banner i { color: var(--tm-icon-active) !important; }
.gs-detail-backdrop { position: fixed; inset: 0; z-index: 2000; background: rgba(15,23,42,.5); display: flex; align-items: center; justify-content: center; padding: 16px; }
.gs-detail { width: 560px; max-width: 100%; max-height: 86vh; display: flex; flex-direction: column; background: #fff; color: #111827; border: 1px solid #8795ad; border-radius: 14px; box-shadow: 0 20px 50px rgba(0,0,0,.35); }
.gs-detail-h { display: flex; align-items: center; gap: 10px; padding: 14px 18px; border-bottom: 1px solid #d5dbe6; }
.gs-detail-t { font-size: 1rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.gs-detail-x { margin-left: auto; border: 0; background: none; font-size: 1.6rem; line-height: 1; cursor: pointer; color: inherit; }
.gs-detail-b { padding: 8px 18px 16px; overflow-y: auto; }
.gs-fields { display: grid; grid-template-columns: 170px 1fr; gap: 0; margin: 0; }
.gs-fields dt, .gs-fields dd { padding: 8px 0; margin: 0; border-bottom: 1px solid #e6eaf1; font-size: .86rem; }
.gs-fields dt { font-weight: 600; color: #4a5568; padding-right: 12px; }
.gs-fields dd { word-break: break-word; }
.gs-detail-open { display: inline-block; margin-top: 14px; font-weight: 600; color: #1f4f9a; }
html[data-theme] .gs-detail { background: var(--tm-surface) !important; color: var(--tm-text) !important; border: 1px solid var(--tm-border) !important; }
html[data-theme] .gs-detail-h, html[data-theme] .gs-fields dt, html[data-theme] .gs-fields dd { border-color: var(--tm-border) !important; }
html[data-theme] .gs-fields dt { color: var(--tm-muted) !important; }
html[data-theme] .gs-detail-open { color: var(--tm-icon-active) !important; }
@media (max-width: 575.98px) { .gs-fields { grid-template-columns: 1fr; } .gs-fields dt { border-bottom: 0; padding-bottom: 0; } }
@media (max-width: 991.98px) { .gs-input { width: 190px; } .gs-input:focus { width: 250px; } }
@media (max-width: 767.98px) {
    .gs-item { top: 0; }
    .gs-toggle { display: flex !important; }
    .gs-form { display: none; }
    .gs-item.gs-open .gs-form { display: flex; position: fixed; top: 8px; left: 8px; right: 8px; z-index: 1070; }
    .gs-item.gs-open .gs-input { width: 100%; height: 44px; background: var(--paper, #fff); }
    html[data-theme] .gs-item.gs-open .gs-input { background: var(--tm-surface) !important; }
    .gs-panel { position: fixed; top: 60px; left: 8px; right: 8px; width: auto; max-width: none; max-height: calc(100vh - 80px); }
}

/* ============================================================
   UNIFORM SIDEBAR — many admin pages declare their own `.sidebar {...}`
   (lighter navy gradient, smaller brand text, etc.) that overrides this
   file. An #id-scoped copy of the shared look always wins, so every page
   shows the same sidebar. Default theme only (Light/Dark have own rules).
   Literal colours are used on purpose: pages redefine --navy-* variables.
   ============================================================ */
html:not([data-theme]) #accordionSidebar.sidebar {
    background:
        radial-gradient(120% 140% at 0% 0%, rgba(201,148,58,0.14) 0%, rgba(201,148,58,0) 45%),
        linear-gradient(190deg, #0a1e3d 0%, #0f2d5a 55%, #102a52 100%) !important;
    box-shadow: 4px 0 28px rgba(10,30,61,0.22) !important;
}
html:not([data-theme]) #accordionSidebar .sidebar-brand {
    padding: 1.5rem 1.15rem 1.3rem !important;
    background: rgba(0,0,0,0.16) !important;
    border-bottom: 1px solid rgba(255,255,255,0.07) !important;
    gap: 12px;
}
html:not([data-theme]) #accordionSidebar .sidebar-brand-text {
    font-family: 'Outfit', sans-serif !important;
    font-size: 1.05rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.2px !important;
}
html:not([data-theme]) #accordionSidebar .sidebar-divider {
    border-top-color: rgba(255,255,255,0.08) !important;
    margin: 0.4rem 1.1rem !important;
}
html:not([data-theme]) #accordionSidebar .nav-item .nav-link {
    font-size: 1.05rem !important;
    font-weight: 450 !important;
    padding: 12px 14px !important;
}

/* ============================================================
   THEMES — Default (no attribute) = original BMIS design, untouched.
   Light / Dark copy the Eusebia admin design (same tokens).
   ============================================================ */
html[data-theme="light"] {
    --tm-page:#f8f9fc; --tm-surface:#ffffff; --tm-surface2:#f1f4f9; --tm-input:#f8fafc; --tm-thead:#f8f9fc;
    --tm-border:#c9d1de; --tm-row-border:#d9dfe9; --tm-input-border:#aab4c3; --tm-side-border:#c5cddb; --tm-top-border:#cfd6e2;
    --tm-text:#0f172a; --tm-head:#0f172a; --tm-muted:#64748b; --tm-thead-text:#5a5c69;
    --tm-nav:#1a1f2b; --tm-brand:#0b2b5c; --tm-brand-sub:#374151; --tm-side-head:#374151;
    --tm-icon:#374151; --tm-icon-active:#1f5a9e; --tm-hover:#f1f4f9; --tm-active-bg:#e8effa;
    --tm-top-link:#4a5568; --tm-top-user:#1a202c; --tm-top-hover:rgba(0,0,0,.05); --tm-logout:#a0aec0;
    --tm-btn-bg:#374151; --tm-btn-fg:#ffffff; --tm-focus-rgb:55,65,81;
    --tm-shadow:0 2px 10px rgba(0,0,0,.06); --tm-top-shadow:0 2px 12px rgba(0,0,0,.04);
    --tm-side-shadow:0 0 24px rgba(11,43,92,.07); --tm-pop-shadow:0 8px 24px rgba(0,0,0,.10);
    --tm-hr:#cfd6e2; --tm-thumb:#c7d2e3; --tm-track:transparent; --tm-progress-track:#a1a5ac; --tm-chart1:#374151;
    --tm-toggle-bg:#f1f4f9; --tm-toggle-border:#e3e6ec; --tm-toggle-fg:#0b2b5c; --tm-toggle-hover:#e2e8f2;
    --tm-active-menu:#0b2b5c; --tm-red:#dc2626; --tm-link:#0f172a;
    --tm-mid:#4a5568; --tm-head2:#374151; --tm-line:#9ca3af; --tm-label:#475569;
    color-scheme: light;
}
html[data-theme="dark"] {
    --tm-page:#0b0b0b; --tm-surface:#000000; --tm-surface2:#141414; --tm-input:#141414; --tm-thead:#0d0d0d;
    --tm-border:rgba(255,255,255,.16); --tm-row-border:rgba(255,255,255,.12); --tm-input-border:rgba(255,255,255,.20);
    --tm-side-border:rgba(255,255,255,.16); --tm-top-border:rgba(255,255,255,.14);
    --tm-text:#d7dbe2; --tm-head:#eef0f4; --tm-muted:#9aa3b5; --tm-thead-text:#d7dbe2;
    --tm-nav:#ffffff; --tm-brand:#ffffff; --tm-brand-sub:#ffffff; --tm-side-head:#ffffff;
    --tm-icon:#ffffff; --tm-icon-active:#818cf8; --tm-hover:rgba(255,255,255,.06); --tm-active-bg:rgba(255,255,255,.10);
    --tm-top-link:#d7dbe2; --tm-top-user:#eef0f4; --tm-top-hover:rgba(255,255,255,.08); --tm-logout:#a0aec0;
    --tm-btn-bg:#d1d5db; --tm-btn-fg:#111827; --tm-focus-rgb:209,213,219;
    --tm-shadow:none; --tm-top-shadow:none; --tm-side-shadow:none; --tm-pop-shadow:0 8px 24px rgba(0,0,0,.40);
    --tm-hr:rgba(255,255,255,.10); --tm-thumb:#333333; --tm-track:#0b0b0b; --tm-progress-track:#71757f; --tm-chart1:#d1d5db;
    --tm-toggle-bg:rgba(255,255,255,.08); --tm-toggle-border:rgba(255,255,255,.10); --tm-toggle-fg:#ffffff; --tm-toggle-hover:rgba(255,255,255,.18);
    --tm-active-menu:#a5b4fc; --tm-red:#dc2626; --tm-link:#818cf8;
    --tm-mid:#b4bccb; --tm-head2:#d7dbe2; --tm-line:#6b7280; --tm-label:#b8c0cf;
    color-scheme: dark;
}


/* Re-point the pages' own colour variables at the theme, so page-level CSS
   (dashboard view + sidebar :root) follows Light/Dark instead of hard-coded navy/gold. */
html[data-theme] {
    /* dashboard view (:root in admn_dashboard_view.php) */
    --white: var(--tm-surface);  --cream: var(--tm-page);
    --text-dark: var(--tm-text); --text-mid: var(--tm-mid); --text-light: var(--tm-muted);
    --navy: var(--tm-head);      --navy-mid: var(--tm-head2); --navy-light: var(--tm-line); --navy-pale: var(--tm-surface2);
    --gold: var(--tm-line);      --gold-light: var(--tm-muted); --gold-pale: var(--tm-surface2);
    --teal: var(--tm-label);     --teal-pale: var(--tm-surface2);
    --danger: var(--tm-label);   --danger-pale: var(--tm-surface2);
    --warning: var(--tm-label);  --warning-pale: var(--tm-surface2);
    --success: var(--tm-label);  --success-pale: var(--tm-surface2);
    --border: var(--tm-border);
    --shadow-sm: var(--tm-shadow); --shadow-md: var(--tm-shadow);
    /* sidebar file (:root in dashboard_sidebar_start.php) */
    --paper: var(--tm-surface);  --canvas: var(--tm-page);
    --ink: var(--tm-text);       --ink-soft: var(--tm-mid);   --ink-faint: var(--tm-muted);
    --line: var(--tm-border);
    --navy-900: var(--tm-head);  --navy-800: var(--tm-head);  --navy-700: var(--tm-head2); --navy-500: var(--tm-muted);
    --navy-100: var(--tm-surface2); --navy-50: var(--tm-surface2);
    --gold-600: var(--tm-line);  --gold-500: var(--tm-line);  --gold-400: var(--tm-muted);
    --gold-200: var(--tm-surface2); --gold-100: var(--tm-surface2);
    --teal-600: var(--tm-label); --teal-100: var(--tm-surface2);
    --danger-100: var(--tm-surface2); --warning-100: var(--tm-surface2); --success-100: var(--tm-surface2);
    --shadow-1: var(--tm-shadow); --shadow-2: var(--tm-shadow); --shadow-3: var(--tm-shadow);
}

html[data-theme] body { background: var(--tm-page) !important; color: var(--tm-text) !important; }

/* Base reset (zero specificity so every rule below overrides it) */
html[data-theme] body :where(*:not(img):not(canvas):not(.modal-backdrop):not(.swal2-container):not(.form-check-input):not(#page-nav-loader):not(#page-nav-loader *)) {
    background: transparent !important;
    color: var(--tm-text) !important;
    border-color: var(--tm-border) !important;
    box-shadow: none !important;
    text-shadow: none !important;
}
html[data-theme] body *::before,
html[data-theme] body *::after { background-image: none !important; }
html[data-theme] body h4::before { background: var(--tm-muted) !important; }
html[data-theme] ::placeholder { color: var(--tm-muted) !important; opacity: 1; }
html[data-theme] ::-webkit-scrollbar-track { background: var(--tm-track) !important; }
html[data-theme] ::-webkit-scrollbar-thumb { background: var(--tm-thumb) !important; }
html[data-theme] hr { border-top: 1px solid var(--tm-hr) !important; }
html[data-theme] .form-check-input { border-color: var(--tm-input-border) !important; }

/* Headings & text */
html[data-theme] h1, html[data-theme] h2, html[data-theme] h3, html[data-theme] h4,
html[data-theme] h5, html[data-theme] h6,
html[data-theme] .text-gray-800, html[data-theme] .text-gray-900 { color: var(--tm-head) !important; }
html[data-theme] .text-muted, html[data-theme] .text-secondary, html[data-theme] .text-xs,
html[data-theme] small, html[data-theme] .small, html[data-theme] label, html[data-theme] .form-label,
html[data-theme] .text-primary, html[data-theme] .text-danger, html[data-theme] .text-warning,
html[data-theme] .text-success, html[data-theme] .text-info,
html[data-theme] .text-gray-500, html[data-theme] .text-gray-600 { color: var(--tm-muted) !important; }
html[data-theme="dark"] a:not(.btn):not(.nav-link):not(.dropdown-item):not(.collapse-item):not(.sidebar-brand):not(.page-link) { color: var(--tm-link) !important; }

/* Surfaces */
html[data-theme] #accordionSidebar,
html[data-theme] .topbar,
html[data-theme] .card,
html[data-theme] [class*="card"]:not([class*="cards"]),
html[data-theme] [class*="panel"],
html[data-theme] .modal-content,
html[data-theme] .dropdown-menu,
html[data-theme] .notif-dropdown,
html[data-theme] .swal2-popup,
html[data-theme] .stat-card,
html[data-theme] .page-header,
html[data-theme] .sticky-footer,
html[data-theme] .filter-bar { background: var(--tm-surface) !important; }

html[data-theme] .card,
html[data-theme] .stat-card,
html[data-theme] .page-header,
html[data-theme] .filter-bar,
html[data-theme] .modal-content,
html[data-theme] .swal2-popup {
    border: 1px solid var(--tm-border) !important;
    box-shadow: var(--tm-shadow) !important;
}
html[data-theme] .dropdown-menu,
html[data-theme] .notif-dropdown {
    border: 1px solid var(--tm-border) !important;
    box-shadow: var(--tm-pop-shadow) !important;
}
html[data-theme] .card[class*="border-left-"] { border-left: 4px solid var(--tm-muted) !important; }
html[data-theme] .card-header, html[data-theme] .card-footer { background: var(--tm-thead) !important; border-color: var(--tm-border) !important; }
html[data-theme] .modal-header, html[data-theme] .modal-footer { border-color: var(--tm-border) !important; }
html[data-theme] .dropdown-divider { border-color: var(--tm-border) !important; }
html[data-theme] .dropdown-header { color: var(--tm-muted) !important; }


/* Beat page-level rules that hard-code light backgrounds */
html[data-theme] #content-wrapper,
html[data-theme] #content,
html[data-theme] #wrapper { background: var(--tm-page) !important; }
html[data-theme] .card .card-body,
html[data-theme] .card[class*="border-left-"] .card-body { background: transparent !important; }
html[data-theme] .equal-height-cards,
html[data-theme] .container-fluid { background: transparent !important; }
html[data-theme] .sticky-footer { border-top: 1px solid var(--tm-border) !important; }


/* KPI cards & card labels: outrank the view's highly specific colour rules */
html[data-theme] .card .card-body .text-xs,
html[data-theme] .card .card-body .text-xs.font-weight-bold { color: var(--tm-label) !important; }
html[data-theme] .card .card-body .h5,
html[data-theme] .card .card-body .h5.font-weight-bold.text-dark { color: var(--tm-head) !important; }
html[data-theme] .card .card-body .col-auto i { color: var(--tm-muted) !important; opacity: 1 !important; }
html[data-theme] .card .card-body a { color: var(--tm-head2) !important; }

/* Icon tiles, pills, badges */
html[data-theme] [class*="icon"]:not(.swal2-icon):not(i):not(svg),
html[data-theme] .badge,
html[data-theme] [class*="pill"],
html[data-theme] #accordionSidebar .collapse-inner { background: var(--tm-surface2) !important; }
html[data-theme] .badge, html[data-theme] [class*="pill"] { border: 1px solid var(--tm-border) !important; }
html[data-theme] .badge-counter { background: var(--tm-red) !important; color: #fff !important; border: 0 !important; }
html[data-theme] #notifDropdown .badge-counter { box-shadow: 0 0 0 2px var(--tm-surface) !important; }
html[data-theme] #accordionSidebar .collapse-inner { border: 1px solid var(--tm-border) !important; }

/* ── Sidebar (Eusebia design) ─────────────────────────────── */
html[data-theme] #accordionSidebar {
    border-right: 1px solid var(--tm-side-border) !important;
    box-shadow: var(--tm-side-shadow) !important;
}
html[data-theme] #accordionSidebar .sidebar-brand {
    background: transparent !important;
    border-bottom: 1px solid var(--tm-hr) !important;
    position: relative !important;
}
html[data-theme] #accordionSidebar .sidebar-brand::after {
    content: ''; position: absolute; bottom: 0; left: 20%; width: 60%; height: 2px;
    background-image: linear-gradient(90deg, transparent, rgba(99,102,241,.4), transparent) !important;
}
html[data-theme] #accordionSidebar .sidebar-brand-logo {
    background: var(--tm-surface2) !important;
    border: 1px solid var(--tm-border) !important;
    box-shadow: none !important;
}
html[data-theme] #accordionSidebar .sidebar-brand:hover .sidebar-brand-logo { border-color: var(--tm-line) !important; }
html[data-theme] #accordionSidebar .sidebar-brand-text {
    color: var(--tm-brand) !important; font-weight: 800 !important; text-transform: uppercase !important; text-shadow: none !important;
}
html[data-theme] #accordionSidebar .sidebar-heading {
    color: var(--tm-side-head) !important; position: relative !important;
}
html[data-theme] #accordionSidebar .sidebar-heading::before {
    content: ''; position: absolute; left: 20px; top: 0; width: 30px; height: 2px;
    background-image: linear-gradient(90deg, rgba(99,102,241,.5), transparent) !important;
}
html[data-theme] #accordionSidebar hr.sidebar-divider { border-top: 1px solid var(--tm-hr) !important; }

html[data-theme] #accordionSidebar .nav-item .nav-link,
html[data-theme] #accordionSidebar .collapse-item {
    color: var(--tm-nav) !important; border-radius: 12px !important; font-weight: 500 !important;
}
html[data-theme] #accordionSidebar .nav-item .nav-link i,
html[data-theme] #accordionSidebar .nav-item .nav-link .bi,
html[data-theme] #accordionSidebar .collapse-item i { color: var(--tm-icon) !important; }
html[data-theme] #accordionSidebar .nav-item .nav-link:hover,
html[data-theme] #accordionSidebar .collapse-item:hover {
    background: var(--tm-hover) !important; transform: translateX(4px) !important;
}
html[data-theme="light"] #accordionSidebar .nav-item .nav-link:hover { color: var(--tm-brand) !important; }
html[data-theme] #accordionSidebar .nav-item .nav-link:hover i,
html[data-theme] #accordionSidebar .nav-item .nav-link:hover .bi { color: var(--tm-icon-active) !important; }
html[data-theme] #accordionSidebar .nav-item.active .nav-link,
html[data-theme] #accordionSidebar .nav-item .nav-link.active,
html[data-theme] #accordionSidebar .collapse-item.active {
    background: var(--tm-active-bg) !important; box-shadow: none !important; border: none !important; font-weight: 600 !important;
}
html[data-theme="light"] #accordionSidebar .nav-item.active .nav-link { color: var(--tm-brand) !important; }
html[data-theme] #accordionSidebar .nav-item.active .nav-link i,
html[data-theme] #accordionSidebar .nav-item .nav-link.active i,
html[data-theme] #accordionSidebar .collapse-item.active i { color: var(--tm-icon-active) !important; }
html[data-theme] #accordionSidebar .nav-item.active .nav-link::before {
    content: ''; position: absolute; left: -2px; top: 50%; transform: translateY(-50%);
    width: 4px; height: 24px; border-radius: 0 4px 4px 0;
    background-image: linear-gradient(180deg, #818cf8, #6366f1) !important;
}
html[data-theme] #sidebarToggle {
    background: var(--tm-toggle-bg) !important; border: 1px solid var(--tm-toggle-border) !important; color: var(--tm-toggle-fg) !important;
}
html[data-theme] #sidebarToggle:hover { background: var(--tm-toggle-hover) !important; }


html[data-theme] #accordionSidebar .nav-item .nav-link { border-color: transparent !important; }

/* ── Topbar ───────────────────────────────────────────────── */
html[data-theme] .topbar {
    border-bottom: 1px solid var(--tm-top-border) !important; box-shadow: var(--tm-top-shadow) !important;
}
html[data-theme] .topbar-divider { border-left: 1px solid var(--tm-top-border) !important; }
html[data-theme] .topbar .nav-link, html[data-theme] .topbar .nav-link i { color: var(--tm-top-link) !important; }
html[data-theme] .topbar .nav-link .text-gray-800 { color: var(--tm-top-user) !important; font-weight: 600 !important; }
html[data-theme] .topbar .nav-link .fa-sign-out-alt { color: var(--tm-logout) !important; }
html[data-theme] .topbar .nav-link:hover { background: var(--tm-top-hover) !important; }
html[data-theme] .topbar .nav-link:hover .fa-sign-out-alt { color: #ef4444 !important; }

/* ── Forms ────────────────────────────────────────────────── */
html[data-theme] input:not([type="checkbox"]):not([type="radio"]),
html[data-theme] select,
html[data-theme] textarea,
html[data-theme] .form-control,
html[data-theme] .form-select,
html[data-theme] .custom-select { background: var(--tm-input) !important; border: 1px solid var(--tm-input-border) !important; }
html[data-theme] .form-control:focus,
html[data-theme] .form-select:focus,
html[data-theme] input:focus, html[data-theme] textarea:focus, html[data-theme] select:focus {
    border-color: rgba(var(--tm-focus-rgb), .6) !important;
    box-shadow: 0 0 0 3px rgba(var(--tm-focus-rgb), .14) !important;
}
html[data-theme] select { -webkit-appearance: menulist; appearance: auto; }
html[data-theme] input[type="checkbox"], html[data-theme] input[type="radio"] { accent-color: var(--tm-accent); }

/* ── Buttons (Bootstrap "primary" -> neutral, as in Eusebia) ── */
html[data-theme] .btn { background: var(--tm-surface2) !important; border: 1px solid var(--tm-border) !important; }
html[data-theme] .btn:hover { background: var(--tm-hover) !important; }
html[data-theme] .btn-primary, html[data-theme] .btn-success, html[data-theme] .btn-info, html[data-theme] .btn-dark {
    background: var(--tm-btn-bg) !important; color: var(--tm-btn-fg) !important; border-color: var(--tm-btn-bg) !important;
}
html[data-theme] .btn-primary:hover, html[data-theme] .btn-success:hover, html[data-theme] .btn-info:hover, html[data-theme] .btn-dark:hover { opacity: .88; background: var(--tm-btn-bg) !important; }
html[data-theme] .btn-primary *, html[data-theme] .btn-success *, html[data-theme] .btn-info *, html[data-theme] .btn-dark * { color: var(--tm-btn-fg) !important; }
html[data-theme] .btn-outline-primary { background: transparent !important; border-color: rgba(var(--tm-focus-rgb), .45) !important; color: rgb(var(--tm-focus-rgb)) !important; }
html[data-theme] .btn-danger { background: var(--tm-red) !important; color: #fff !important; border-color: var(--tm-red) !important; }
html[data-theme] .btn-danger * { color: #fff !important; }

/* ── Tables ───────────────────────────────────────────────── */
html[data-theme] .table, html[data-theme] table { color: var(--tm-text) !important; }
html[data-theme] .table thead th, html[data-theme] table thead th {
    background: var(--tm-thead) !important; color: var(--tm-thead-text) !important; border-color: var(--tm-side-border) !important;
}
html[data-theme] .table td, html[data-theme] .table th,
html[data-theme] table td, html[data-theme] table th,
html[data-theme] .table-bordered, html[data-theme] .table-bordered td, html[data-theme] .table-bordered th { border-color: var(--tm-row-border) !important; }
html[data-theme] .table-hover tbody tr:hover, html[data-theme] table tbody tr:hover { background: var(--tm-hover) !important; }

/* ── Dropdown items / notifications ───────────────────────── */
html[data-theme] .dropdown-item:hover, html[data-theme] .dropdown-item:focus { background: var(--tm-hover) !important; }
html[data-theme="dark"] .dropdown-item:hover, html[data-theme="dark"] .dropdown-item:focus { color: #ffffff !important; }


/* ── Custom confirm modals (delete / restore / purge) ─────────
   Activity Logs, Archive, Login History, Announcements use their own
   overlay + box markup instead of Bootstrap modals. */
html[data-theme] .modal-overlay,
html[data-theme] .bmis-modal-backdrop,
html[data-theme] #restoreModal, html[data-theme] #deleteModal,
html[data-theme] #bulkRestoreModal, html[data-theme] #bulkDeleteModal { background: rgba(0,0,0,.55) !important; }
html[data-theme="dark"] .modal-overlay,
html[data-theme="dark"] .bmis-modal-backdrop,
html[data-theme="dark"] #restoreModal, html[data-theme="dark"] #deleteModal,
html[data-theme="dark"] #bulkRestoreModal, html[data-theme="dark"] #bulkDeleteModal { background: rgba(0,0,0,.72) !important; }

html[data-theme] .modal-box,
html[data-theme] .bmis-modal-card,
html[data-theme] #restoreModal > div, html[data-theme] #deleteModal > div,
html[data-theme] #bulkRestoreModal > div, html[data-theme] #bulkDeleteModal > div {
    background: var(--tm-surface) !important;
    border: 1px solid var(--tm-input-border) !important;
    box-shadow: var(--tm-pop-shadow) !important;
}
html[data-theme] .modal-box h3, html[data-theme] .bmis-modal-title { color: var(--tm-head) !important; }
html[data-theme] .modal-box p, html[data-theme] .bmis-modal-sub { color: var(--tm-mid) !important; }
html[data-theme] .modal-box p strong { color: var(--tm-head) !important; }
html[data-theme] .bmis-modal-info,
html[data-theme] :is(#restoreModal, #deleteModal, #bulkRestoreModal, #bulkDeleteModal) div[style*="border-radius:8px"] {
    background: var(--tm-surface2) !important; border: 1px solid var(--tm-border) !important;
}
html[data-theme] .modal-icon, html[data-theme] .bmis-modal-icon,
html[data-theme] :is(#restoreModal, #deleteModal, #bulkRestoreModal, #bulkDeleteModal) div[style*="width:40px"] {
    background: var(--tm-surface2) !important; border: 1px solid var(--tm-border) !important;
}
html[data-theme] :is(.modal-overlay, .bmis-modal-backdrop, #restoreModal, #deleteModal, #bulkRestoreModal, #bulkDeleteModal) hr {
    border-top: 1px solid var(--tm-border) !important;
}
/* Cancel buttons: outlined */
html[data-theme] .btn-modal-cancel, html[data-theme] .bmis-btn-cancel,
html[data-theme] :is(#restoreModal, #deleteModal, #bulkRestoreModal, #bulkDeleteModal) button:not([id$="ConfirmBtn"]) {
    background: var(--tm-surface2) !important; color: var(--tm-text) !important;
    border: 1px solid var(--tm-input-border) !important;
}
html[data-theme] .btn-modal-cancel:hover, html[data-theme] .bmis-btn-cancel:hover,
html[data-theme] :is(#restoreModal, #deleteModal, #bulkRestoreModal, #bulkDeleteModal) button:not([id$="ConfirmBtn"]):hover { background: var(--tm-hover) !important; }
/* Confirm buttons: restore = neutral solid, delete = red solid */
html[data-theme] .btn-modal-confirm.orange, html[data-theme] #restoreConfirmBtn, html[data-theme] #bulkRestoreConfirmBtn {
    background: var(--tm-btn-bg) !important; color: var(--tm-btn-fg) !important; border: 1px solid var(--tm-btn-bg) !important;
}
html[data-theme] .btn-modal-confirm:not(.orange), html[data-theme] .bmis-btn-confirm,
html[data-theme] #deleteConfirmBtn, html[data-theme] #bulkDeleteConfirmBtn {
    background: var(--tm-red) !important; color: #ffffff !important; border: 1px solid var(--tm-red) !important;
}
html[data-theme] .btn-modal-confirm *, html[data-theme] .bmis-btn-confirm *,
html[data-theme] #deleteConfirmBtn *, html[data-theme] #bulkDeleteConfirmBtn * { color: #ffffff !important; background: transparent !important; }
html[data-theme] #restoreConfirmBtn *, html[data-theme] #bulkRestoreConfirmBtn *,
html[data-theme] .btn-modal-confirm.orange * { color: var(--tm-btn-fg) !important; background: transparent !important; }



/* ── Barangay Services toolbar buttons ── */
html[data-theme] .svc-actions .svc-search,
html[data-theme] .svc-actions .svc-add {
    background: var(--tm-btn-bg) !important; color: var(--tm-btn-fg) !important; border: 1.5px solid var(--tm-btn-bg) !important;
}
html[data-theme] .svc-actions .svc-reload {
    background: var(--tm-surface) !important; color: var(--tm-text) !important; border: 1.5px solid var(--tm-input-border) !important;
}
html[data-theme] .svc-actions .svc-reload:hover { background: var(--tm-hover) !important; }
html[data-theme] .svc-actions .svc-search:hover,
html[data-theme] .svc-actions .svc-add:hover { opacity: .88; }

/* ── Bulk action buttons: Restore Selected / Delete Selected / Clear ──
   Archive, Activity Logs and Login History. Boxed so they stay visible. */
html[data-theme] .bulk-toolbar,
html[data-theme] .bulk-bar {
    background: var(--tm-surface2) !important; border: 1px solid var(--tm-input-border) !important;
}
html[data-theme] .bulk-count { color: var(--tm-head) !important; font-weight: 600 !important; }

html[data-theme] .btn-bulk-restore,
html[data-theme] .btn-bulk-delete,
html[data-theme] .btn-delete-bulk,
html[data-theme] .btn-bulk-clear,
html[data-theme] .btn-deselect,
html[data-theme] .btn-purge {
    border-style: solid !important; border-width: 1.5px !important;
    box-shadow: none !important; opacity: 1 !important; cursor: pointer;
}
/* Restore Selected: solid neutral box */
html[data-theme] .btn-bulk-restore {
    background: var(--tm-btn-bg) !important; color: var(--tm-btn-fg) !important; border-color: var(--tm-btn-bg) !important;
}
/* Delete Selected: solid red box */
html[data-theme] .btn-bulk-delete,
html[data-theme] .btn-delete-bulk {
    background: var(--tm-red) !important; color: #ffffff !important; border-color: var(--tm-red) !important;
}
/* Clear / Deselect / Purge: outlined box */
html[data-theme] .btn-bulk-clear,
html[data-theme] .btn-deselect,
html[data-theme] .btn-purge {
    background: var(--tm-surface) !important; color: var(--tm-text) !important; border-color: var(--tm-input-border) !important;
}
html[data-theme] .btn-bulk-restore *  { color: var(--tm-btn-fg) !important; background: transparent !important; }
html[data-theme] .btn-bulk-delete *, html[data-theme] .btn-delete-bulk * { color: #ffffff !important; background: transparent !important; }
html[data-theme] .btn-bulk-clear *, html[data-theme] .btn-deselect *, html[data-theme] .btn-purge * { color: var(--tm-text) !important; background: transparent !important; }
html[data-theme] .btn-bulk-restore:hover, html[data-theme] .btn-bulk-delete:hover, html[data-theme] .btn-delete-bulk:hover { opacity: .88 !important; }
html[data-theme] .btn-bulk-clear:hover, html[data-theme] .btn-deselect:hover, html[data-theme] .btn-purge:hover { background: var(--tm-hover) !important; }


/* ── Row selection (Activity Logs, Login History): keep it themed, never white ── */
html[data-theme] .log-table tbody tr:hover { background: var(--tm-hover) !important; }
html[data-theme] .log-table tbody tr.row-selected,
html[data-theme] .log-table tbody tr:has(input[type="checkbox"]:checked) { background: var(--tm-active-bg) !important; }
html[data-theme] .log-table tbody tr.row-selected:hover,
html[data-theme] .log-table tbody tr:has(input[type="checkbox"]:checked):hover { background: var(--tm-active-bg) !important; }
html[data-theme] .log-table tbody tr td { border-bottom-color: var(--tm-row-border) !important; }

/* Activity Logs custom checkbox (.cb-wrap) */
html[data-theme] .cb-wrap label {
    background: var(--tm-input) !important; border: 2px solid var(--tm-input-border) !important;
}
html[data-theme] .cb-wrap input:checked + label {
    background: var(--tm-accent) !important; border-color: var(--tm-accent) !important;
}
html[data-theme] .cb-wrap input:checked + label::after {
    border-right-color: #ffffff !important; border-bottom-color: #ffffff !important;
}
/* "N row(s) selected" count pill */
html[data-theme] .bulk-count span { background: var(--tm-accent) !important; color: #ffffff !important; }

/* Announcement image viewer (sits on a dark overlay in both modes) */
html[data-theme] #imageViewerModal { background: rgba(0,0,0,.88) !important; }
html[data-theme] #imageViewerModal, html[data-theme] #imageViewerModal * { color: #ffffff !important; }
html[data-theme] #imageViewerModal .iv-close, html[data-theme] #imageViewerModal .iv-nav,
html[data-theme] #imageViewerModal .iv-counter { background: rgba(255,255,255,.16) !important; border-color: rgba(255,255,255,.35) !important; }

/* ── Progress bars ────────────────────────────────────────── */
html[data-theme] .progress { background: var(--tm-progress-track) !important; }
html[data-theme] .progress-bar { background: var(--tm-chart1) !important; }

/* ── Theme switcher (topbar dropdown, Eusebia style) ──────── */
#themeSwitchDropdown i { font-size: 1.05rem; }
.theme-switch-menu { min-width: 180px; }
.theme-switch-menu .dropdown-item { display: flex; align-items: center; gap: 10px; cursor: pointer; }
.theme-switch-menu .dropdown-item i.fa-check { display: none; margin-left: auto; }
.theme-switch-menu .dropdown-item.active-theme { font-weight: 700; color: #0b2b5c; }
.theme-switch-menu .dropdown-item.active-theme i.fa-check { display: inline-block !important; }
html[data-theme] .theme-switch-menu .dropdown-item.active-theme { color: var(--tm-active-menu) !important; }

/* ============================================================
   TEXT SIZE + FONT (independent of Light/Dark; Medium + Default font = original)
   ============================================================ */
html[data-fontsize="small"] { zoom: 0.875; }
html[data-fontsize="large"] { zoom: 1.15; }
/* vh units ignore zoom, so give the layout columns the height that fills the real screen */
html[data-fontsize="small"] #wrapper,
html[data-fontsize="small"] #accordionSidebar,
html[data-fontsize="small"] #content-wrapper { min-height: calc(100vh / 0.875); }
/* keep modal dimming covering the whole screen when zoomed */
html[data-fontsize] .modal-backdrop { width: auto; height: auto; top: 0; right: 0; bottom: 0; left: 0; }

html[data-font="nunito"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Nunito', sans-serif !important; }
html[data-font="inter"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Inter', sans-serif !important; }
html[data-font="poppins"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Poppins', sans-serif !important; }
html[data-font="roboto"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Roboto', sans-serif !important; }
html[data-font="opensans"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Open Sans', sans-serif !important; }
html[data-font="lato"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Lato', sans-serif !important; }
html[data-font="montserrat"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Montserrat', sans-serif !important; }
html[data-font="merriweather"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: 'Merriweather', serif !important; }
html[data-font="serif"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: Georgia, 'Times New Roman', serif !important; }
html[data-font="verdana"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: Verdana, Geneva, sans-serif !important; }
html[data-font="mono"] body *:not(i):not(svg):not(.fas):not(.far):not(.fab):not(.fa):not(.bi):not([class*="fa-"]):not([class*="bi-"]) { font-family: ui-monospace, 'Cascadia Mono', Consolas, 'Courier New', monospace !important; }

/* Text & Font menu */
.textfont-menu { min-width: 210px; max-height: 75vh; overflow-y: auto; }
.textfont-menu .dropdown-item { display: flex; align-items: center; gap: 10px; cursor: pointer; }
.textfont-menu .dropdown-item i.fa-check { display: none; margin-left: auto; }
.textfont-menu .dropdown-item.active-theme { font-weight: 700; }
.textfont-menu .dropdown-item.active-theme i.fa-check { display: inline-block !important; }
.textfont-menu .size-a { width: 1.4em; text-align: center; font-weight: 700; }
#textFontDropdown i { font-size: 1.05rem; }
</style>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center" href="admn_dashboard.php">
                <img src="icons/logo.png" alt="Barangay San Pedro" class="sidebar-brand-logo">
                <div class="sidebar-brand-text">Administrator<br>Dashboard</div>
            </a>

            <hr class="sidebar-divider my-0">

            <!-- Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="admn_dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="admn_budget.php">
                    <i class="fas fa-fw fa-wallet"></i>
                    <span>Budget Transparency</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- ▼ Barangay Records DROPDOWN -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUserMgmt"
                    aria-expanded="false" aria-controls="collapseUserMgmt">
                    <i class="fas fa-users-cog"></i>
                    <span>Barangay Records</span>
                </a>
                <div id="collapseUserMgmt" class="collapse" data-bs-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="admn_staff_crud.php">
                            <i class="fas fa-user-tie"></i>Barangay Officials/Staffs
                        </a>
                        <a class="collapse-item" href="admn_resident_crud.php">
                            <i class="fas fa-users"></i>Barangay Residents
                        </a>
                        <a class="collapse-item" href="admn_resident_pending.php">
                            <i class="fas fa-user-clock"></i>Pending Registrations
                        </a>
                        <a class="collapse-item" href="admn_password_reset_requests.php">
                            <i class="fas fa-key"></i>Password Reset Requests
                        </a>
                    </div>
                </div>
            </li>

            <!-- Messages (flat) -->
            <li class="nav-item">
                <a class="nav-link" href="admn_messages.php">
                    <i class="fas fa-comments"></i>
                    <span>Messages</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Announcements (flat) -->
            <li class="nav-item">
                <a class="nav-link" href="admn_announcement_crud.php">
                    <i class="fas fa-bullhorn"></i>
                    <span>Announcements</span>
                </a>
            </li>

            <!-- ▼ Barangay Services DROPDOWN -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseBarangayServices"
                    aria-expanded="false" aria-controls="collapseBarangayServices">
                    <i class="fas fa-landmark"></i>
                    <span>Barangay Services</span>
                </a>
                <div id="collapseBarangayServices" class="collapse" data-bs-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="admn_certofres.php">
                            <i class="fas fa-file-word"></i>Certificate of Residency
                        </a>
                        <a class="collapse-item" href="admn_certofgoodmoral.php">
                            <i class="fas fa-user-check"></i>Certificate of Good Moral
                        </a>
                        <a class="collapse-item" href="admn_certoflivestock.php">
                            <i class="fas fa-horse"></i>Certificate of Livestock
                        </a>
                        <a class="collapse-item" href="admn_certofguardianship.php">
                            <i class="fas fa-fw fa-people-arrows"></i>Certificate of Guardianship
                        </a>
                        <a class="collapse-item" href="admn_soloparent.php">
                            <i class="fas fa-fw fa-child"></i>Solo Parent Certification
                        </a>
                        <a class="collapse-item" href="admn_brgyid.php">
                            <i class="fas fa-id-card"></i>Barangay ID
                        </a>
                        <a class="collapse-item" href="admn_bspermit.php">
                            <i class="fas fa-file-contract"></i>Business Permit
                        </a>
                        <a class="collapse-item" href="admn_brgyclearance.php">
                            <i class="fas fa-file"></i>Barangay Clearance
                        </a>
                        <a class="collapse-item" href="admn_certofindigency.php">
                            <i class="fas fa-fw fa-table"></i>Certificate of Indigency
                        </a>
                        <a class="collapse-item" href="admn_complaints.php">
                            <i class="bi bi-people-fill"></i>Complaints
                        </a>
                    </div>
                </div>
            </li>

            <hr class="sidebar-divider">

            <!-- Audit & Logs -->
            <li class="nav-item">
                <a class="nav-link" href="admn_activity_logs.php">
                    <i class="fas fa-history"></i>
                    <span>Audit &amp; Logs</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="admn_archive.php">
                    <i class="fas fa-archive"></i>
                    <span>Archive</span>
                </a>
            </li>

            <hr class="sidebar-divider">

           

        </ul>
        <!-- End of Sidebar -->

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

                        <!-- Global search (searches the whole database via global_search.php) -->
                        <li class="nav-item gs-item mx-1" id="gsItem">
                            <button type="button" class="nav-link gs-toggle" id="gsToggle" aria-label="Search" title="Search">
                                <i class="fas fa-search fa-fw"></i>
                            </button>
                            <form class="gs-form" id="gsForm" role="search" autocomplete="off" onsubmit="return false;">
                                <i class="fas fa-search gs-icon" aria-hidden="true"></i>
                                <input type="search" id="gsInput" class="gs-input" placeholder="Search everything…"
                                       aria-label="Search the whole system" aria-controls="gsPanel" maxlength="100">
                                <kbd class="gs-kbd" aria-hidden="true">/</kbd>
                            </form>
                            <div class="gs-panel" id="gsPanel" role="listbox" hidden></div>
                        </li>

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

                        <!-- Nav Item - Notifications -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle"
                                href="#"
                                id="notifDropdown"
                                role="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span class="notif-bell-wrap">
                                    <i class="fas fa-bell fa-fw"></i>
                                    <?php if ($notif_grand_total > 0): ?>
                                    <span class="badge badge-counter"><?= $notif_grand_total > 99 ? '99+' : $notif_grand_total; ?></span>
                                    <?php endif; ?>
                                </span>
                            </a>
                            <!-- Dropdown - Notifications -->
                            <div class="dropdown-menu dropdown-menu-end notif-dropdown-menu shadow animated--grow-in"
                                aria-labelledby="notifDropdown">
                                <div class="notif-dropdown-header">
                                    <p class="notif-header-title"><i class="fas fa-bell"></i> Notifications</p>
                                    <?php if ($notif_grand_total > 0): ?>
                                    <span class="notif-header-count"><?= $notif_grand_total ?> new</span>
                                    <?php endif; ?>
                                </div>

                                <!-- Certificate Requests -->
                                <div class="notif-section-title">
                                    <i class="fas fa-file-alt"></i> Barangay Services Requests
                                    <span class="notif-section-count"><?= $notif_cert_total ?></span>
                                </div>
                                <?php if ($notif_cert_total === 0): ?>
                                    <div class="notif-empty">No new certificate requests</div>
                                <?php else: foreach ($notif_cert_items as $item): ?>
                                    <a class="dropdown-item notif-item" href="<?= htmlspecialchars($item['page']) ?>">
                                        <span class="notif-icon-wrap"><i class="fas <?= htmlspecialchars($item['icon']) ?>"></i></span>
                                        <span><?= htmlspecialchars($item['label']) ?></span>
                                        <span class="notif-item-badge"><?= (int)$item['count'] ?> new</span>
                                    </a>
                                <?php endforeach; endif; ?>

                                <!-- Messages -->
                                <div class="notif-section-title">
                                    <i class="bi bi-chat-left-text"></i> Messages
                                    <span class="notif-section-count"><?= $notif_msg_total ?></span>
                                    <?php if ($notif_msg_total > 0): ?>
                                    <form method="post" action="mark_notification_read.php" class="notif-mark-all-form">
                                        <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($notif_current_page) ?>">
                                        <button type="submit" name="mark_all_read" value="1" class="notif-mark-all" title="Mark all messages as read">
                                            <i class="fas fa-check-double"></i> Mark all read
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php if ($notif_msg_total === 0): ?>
                                    <div class="notif-empty">No new messages</div>
                                <?php else: foreach ($notif_msg_items as $m):
                                        $sender = trim(($m['fname'] ?? '') . ' ' . ($m['lname'] ?? ''));
                                        $sender = $sender !== '' ? $sender : 'Resident';
                                        $snippet = mb_strimwidth(strip_tags($m['message_text']), 0, 55, '…');
                                ?>
                                    <div class="notif-item notif-item-msg">
                                        <a class="notif-item-link" href="admn_messages.php">
                                            <span class="notif-avatar"><?= htmlspecialchars(mb_strtoupper(mb_substr($sender, 0, 1))) ?></span>
                                            <span>
                                                <strong><?= htmlspecialchars($sender) ?></strong>: <?= htmlspecialchars($snippet) ?>
                                                <small class="notif-time"><?= htmlspecialchars(date('M j, Y g:i A', strtotime($m['date_sent']))) ?></small>
                                            </span>
                                        </a>
                                        <form method="post" action="mark_notification_read.php" class="notif-mark-item-form">
                                            <input type="hidden" name="redirect_to" value="<?= htmlspecialchars($notif_current_page) ?>">
                                            <input type="hidden" name="mark_read_id" value="<?= (int)$m['id_admin_msg'] ?>">
                                            <button type="submit" class="notif-mark-item" title="Mark as read">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    </div>
                                <?php endforeach; ?>
                                    <a class="dropdown-item notif-viewall" href="admn_messages.php">View all messages <i class="fas fa-arrow-right ms-1"></i></a>
                                <?php endif; ?>

                                <!-- Pending Requests -->
                                <div class="notif-section-title">
                                    <i class="fas fa-user-clock"></i> Pending Requests
                                    <span class="notif-section-count"><?= $notif_pending_total ?></span>
                                </div>
                                <?php if ($notif_pending_total === 0): ?>
                                    <div class="notif-empty">No new pending requests</div>
                                <?php else: foreach ($notif_pending_items as $item): ?>
                                    <a class="dropdown-item notif-item" href="<?= htmlspecialchars($item['page']) ?>">
                                        <span class="notif-icon-wrap"><i class="fas <?= htmlspecialchars($item['icon']) ?>"></i></span>
                                        <span><?= htmlspecialchars($item['label']) ?></span>
                                        <span class="notif-item-badge"><?= (int)$item['count'] ?> new</span>
                                    </a>
                                <?php endforeach; endif; ?>

                                <?php if ($notif_grand_total === 0): ?>
                                    <div class="notif-caught-up">
                                        <i class="fas fa-check-circle"></i>
                                        <span>You're all caught up</span>
                                    </div>
                                <?php endif; ?>
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
                            <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutConfirmModal" title="Log out">
                                <i class="fas fa-sign-out-alt fa-fw"></i>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- End of Topbar -->

                <script>
                /* Global search: debounced fetch to global_search.php, grouped results, keyboard nav */
                (function () {
                    var item  = document.getElementById('gsItem');
                    var input = document.getElementById('gsInput');
                    var panel = document.getElementById('gsPanel');
                    var tgl   = document.getElementById('gsToggle');
                    if (!item || !input || !panel) return;

                    var timer = null, reqId = 0, ctrl = null;

                    function esc(s) {
                        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
                            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
                        });
                    }
                    function hi(text, terms) {
                        var out = esc(text);
                        terms.forEach(function (t) {
                            if (!t) return;
                            var re = new RegExp('(' + esc(t).replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + ')', 'gi');
                            out = out.replace(re, '<mark>$1</mark>');
                        });
                        return out;
                    }
                    function show(html) { panel.innerHTML = html; panel.hidden = false; }
                    function hide() { panel.hidden = true; }
                    function state(msg) { show('<div class="gs-state">' + msg + '</div>'); }

                    function render(data) {
                        var terms = (data.q || '').split(' ').filter(Boolean);
                        if (!data.groups || !data.groups.length) {
                            return state('No results for “' + esc(data.q) + '”');
                        }
                        var html = '';
                        data.groups.forEach(function (g) {
                            var href = g.url ? esc(g.url) : '#';
                            html += '<div class="gs-group-h"><i class="fas ' + esc(g.icon) + '"></i>' + esc(g.label) +
                                    '<span class="gs-count">' + g.items.length + (g.more ? '+' : '') + '</span></div>';
                            g.items.forEach(function (it) {
                                html += '<a class="gs-row" role="option" href="' + href + '" data-t="' + esc(g.table) + '" data-id="' + esc(it.id) + '">' +
                                        '<span class="gs-title">' + hi(it.title, terms) + '</span>' +
                                        (it.snippet ? '<span class="gs-snip">' + hi(it.snippet, terms) + '</span>' : '') +
                                        '</a>';
                            });
                        });
                        show(html);
                    }

                    function run() {
                        var q = input.value.trim();
                        if (q.length < 2) { return q.length ? state('Keep typing…') : hide(); }
                        var id = ++reqId;
                        if (ctrl) ctrl.abort();
                        ctrl = window.AbortController ? new AbortController() : null;
                        state('<i class="fas fa-spinner fa-spin"></i> Searching…');
                        fetch('global_search.php?q=' + encodeURIComponent(q), {
                            credentials: 'same-origin',
                            headers: { 'Accept': 'application/json' },
                            signal: ctrl ? ctrl.signal : undefined
                        }).then(function (r) {
                            if (!r.ok) throw new Error('HTTP ' + r.status);
                            return r.json();
                        }).then(function (d) {
                            if (id === reqId) render(d);
                        }).catch(function (e) {
                            if (e && e.name === 'AbortError') return;
                            if (id === reqId) state('Search failed. Your session may have expired — reload and try again.');
                        });
                    }

                    // ── Tap a result: show only that record, right here (one small request) ──
                    var dv = null;
                    function closeDetail() { if (dv) { dv.remove(); dv = null; document.removeEventListener('keydown', escClose); } }
                    function escClose(e) { if (e.key === 'Escape') closeDetail(); }
                    function openDetail(table, id, moduleUrl) {
                        closeDetail();
                        hide();
                        dv = document.createElement('div');
                        dv.className = 'gs-detail-backdrop';
                        dv.innerHTML = '<div class="gs-detail" role="dialog" aria-modal="true">' +
                            '<div class="gs-detail-h"><strong class="gs-detail-t">Loading…</strong>' +
                            '<button type="button" class="gs-detail-x" aria-label="Close">&times;</button></div>' +
                            '<div class="gs-detail-b"><div class="gs-state"><i class="fas fa-spinner fa-spin"></i></div></div></div>';
                        document.body.appendChild(dv);
                        document.addEventListener('keydown', escClose);
                        dv.addEventListener('click', function (e) { if (e.target === dv || e.target.closest('.gs-detail-x')) closeDetail(); });
                        fetch('global_search.php?view=' + encodeURIComponent(table) + '&id=' + encodeURIComponent(id), { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
                            .then(function (r) { if (!r.ok) throw new Error(); return r.json(); })
                            .then(function (d) {
                                if (!dv) return;
                                dv.querySelector('.gs-detail-t').textContent = d.title + '  ·  ' + d.label;
                                var h = '<dl class="gs-fields">';
                                d.fields.forEach(function (f) { h += '<dt>' + esc(f.label) + '</dt><dd>' + esc(f.value) + '</dd>'; });
                                h += '</dl>';
                                if (moduleUrl && moduleUrl !== '#') h += '<a class="gs-detail-open" href="' + esc(moduleUrl + '?gs_t=' + encodeURIComponent(table) + '&gs_id=' + encodeURIComponent(id) + '&gs_title=' + encodeURIComponent(d.title)) + '">Open ' + esc(d.label) + ' page <i class="fas fa-arrow-right"></i></a>';
                                dv.querySelector('.gs-detail-b').innerHTML = h;
                            })
                            .catch(function () {
                                if (dv) dv.querySelector('.gs-detail-b').innerHTML = '<div class="gs-state">Could not load this record.</div>';
                            });
                    }
                    panel.addEventListener('click', function (e) {
                        var row = e.target.closest('.gs-row');
                        if (!row) return;
                        e.preventDefault();
                        if (row.dataset.id && row.dataset.id !== 'null') openDetail(row.dataset.t, row.dataset.id, row.getAttribute('href'));
                        else if (row.getAttribute('href') && row.getAttribute('href') !== '#') window.location.href = row.getAttribute('href');
                    });

                    input.addEventListener('input', function () {
                        clearTimeout(timer);
                        timer = setTimeout(run, 250);
                    });
                    input.addEventListener('focus', function () { if (input.value.trim().length >= 2 && panel.innerHTML) panel.hidden = false; });

                    // keyboard: ↑ ↓ Enter Esc
                    input.addEventListener('keydown', function (e) {
                        var rows = Array.prototype.slice.call(panel.querySelectorAll('.gs-row'));
                        var cur = panel.querySelector('.gs-row.gs-active');
                        var idx = rows.indexOf(cur);
                        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                            if (!rows.length) return;
                            e.preventDefault();
                            if (cur) cur.classList.remove('gs-active');
                            idx = e.key === 'ArrowDown' ? (idx + 1) % rows.length : (idx - 1 + rows.length) % rows.length;
                            rows[idx].classList.add('gs-active');
                            rows[idx].scrollIntoView({ block: 'nearest' });
                        } else if (e.key === 'Enter') {
                            e.preventDefault();
                            var target = cur || rows[0];
                            if (target) target.click();
                        } else if (e.key === 'Escape') {
                            hide(); input.blur(); item.classList.remove('gs-open');
                        }
                    });

                    // "/" focuses the search (unless typing in a field)
                    document.addEventListener('keydown', function (e) {
                        var tag = (document.activeElement && document.activeElement.tagName) || '';
                        var editable = /INPUT|TEXTAREA|SELECT/.test(tag) || (document.activeElement && document.activeElement.isContentEditable);
                        if (e.key === '/' && !editable && !e.ctrlKey && !e.metaKey && !e.altKey) {
                            e.preventDefault();
                            item.classList.add('gs-open');
                            input.focus();
                        }
                    });

                    // click outside closes
                    document.addEventListener('click', function (e) {
                        if (!item.contains(e.target)) { hide(); item.classList.remove('gs-open'); }
                    });

                    // mobile: icon button opens the full-width search bar
                    if (tgl) tgl.addEventListener('click', function () {
                        item.classList.toggle('gs-open');
                        if (item.classList.contains('gs-open')) setTimeout(function () { input.focus(); }, 30);
                        else hide();
                    });
                })();
                </script>

                <script>
                /* Opened from a global-search record: show only that record (single page load, no re-search). */
                (function () {
                    var P = new URLSearchParams(window.location.search);
                    if (!P.get('gs_id')) return;
                    var title = P.get('gs_title') || '';
                    document.addEventListener('DOMContentLoaded', function () {
                        var content = document.getElementById('content') || document.body;
                        // Pages without a server-side filter: keep only table rows containing the record's name/title
                        var words = title.toLowerCase().split(/\s+/).filter(function (w) { return w.length > 1; });
                        if (words.length) {
                            var rows = Array.prototype.slice.call(content.querySelectorAll('table tbody tr')).filter(function (tr) {
                                return !tr.closest('.notif-dropdown-menu') && !tr.querySelector('td[colspan]');
                            });
                            var hits = rows.filter(function (tr) {
                                var t = tr.textContent.toLowerCase();
                                return words.every(function (w) { return t.indexOf(w) !== -1; });
                            });
                            if (hits.length && hits.length < rows.length) {
                                rows.forEach(function (tr) { if (hits.indexOf(tr) === -1) tr.style.display = 'none'; });
                                content.querySelectorAll('.pagination').forEach(function (n) { n.style.display = 'none'; });
                            }
                        }
                        var bar = document.createElement('div');
                        bar.className = 'gs-banner';
                        var back = window.location.pathname.split('/').pop() || window.location.pathname;
                        bar.innerHTML = '<i class="fas fa-search"></i><span>Showing only <strong></strong></span><a href="' + back + '">Show all</a>';
                        bar.querySelector('strong').textContent = '“' + title + '”';
                        var tb = content.querySelector('.topbar');
                        if (tb && tb.parentNode) tb.parentNode.insertBefore(bar, tb.nextSibling); else content.insertBefore(bar, content.firstChild);
                    });
                })();
                </script>


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

                <!-- FIX: auto-scroll an opened Barangay Records / Barangay
                     Services accordion into view inside the fixed mobile
                     sidebar, so it's never opened off-screen -->
                <script>
                document.addEventListener('DOMContentLoaded', function () {
                    ['collapseUserMgmt', 'collapseBarangayServices'].forEach(function (id) {
                        var panel = document.getElementById(id);
                        if (!panel) return;
                        panel.addEventListener('shown.bs.collapse', function () {
                            var trigger = document.querySelector('[data-bs-target="#' + id + '"]');
                            if (trigger) {
                                trigger.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        });
                    });
                });
                </script>

                <!-- FIX: iOS/Android can fire a spurious "click" event on
                     whatever element sits under your finger when a scroll
                     gesture ends, even though you only scrolled and never
                     tapped. Also, the site's own hamburger toggle script
                     (not shown in this file) may be bound to "touchstart"
                     instead of "click", which fires the instant a finger
                     lands on the button — before any scroll movement can
                     even be measured. To fix both at once, this: (1) waits
                     for the page to fully load, so any other script has
                     already attached its listeners, (2) clones the
                     hamburger button, which strips every JS listener
                     previously bound to it — regardless of which event
                     they used, (3) attaches one clean tap-only handler
                     that opens the sidebar, and only if the touch was a
                     genuine tap (little to no finger movement), not a
                     scroll. -->
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