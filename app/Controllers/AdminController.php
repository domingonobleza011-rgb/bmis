<?php

class AdminController extends Controller
{
    public function dashboard()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);

// Must be logged in as Admin / allowed official — blocks URL bar access without login



$userdetails = $bmis->get_userdata();
// Double-check (defense in depth) — redirects guests to login, denies wrong roles
$bmis->validate_staff_or_admin();

   
    $rescountm = $residentbmis->count_male_resident();
    $rescountf = $residentbmis->count_female_resident();
    $rescountfh = $residentbmis->count_head_resident();
    $rescountfm = $residentbmis->count_member_resident();
    $rescountvoter = $residentbmis->count_voters();
    $rescountsenior = $residentbmis->count_resident_senior();
    $rescountpwd = $residentbmis->count_pwd();

    $staffcount = $staffbmis->count_staff();
    $staffcountm = $staffbmis->count_mstaff();
    $staffcountf = $staffbmis->count_fstaff();

    // ── Complaint counts (uses the shared $conn PDO from conn.php) ──
    $complaint_pending  = 0;
    $complaint_resolved = 0;
    $complaint_total    = 0;
    try {
        $complaint_pending  = (int)$conn->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='pending'")->fetchColumn();
        $complaint_resolved = (int)$conn->query("SELECT COUNT(*) FROM tbl_complaints WHERE status='resolved'")->fetchColumn();
        $complaint_total    = $complaint_pending + $complaint_resolved;
    } catch (Exception $e) { /* table may not exist yet */ }
?>

<style> 
/* ============================================================
   BARANGAY SAN PEDRO — ADMIN DASHBOARD — IMPROVED CSS
   Extends sb-admin-2 with a refined navy + gold civic theme
   ============================================================ */

@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

/* ─── THEME TOKENS ──────────────────────────────────────────── */
:root {
    --navy:          #0f2d5a;
    --navy-mid:      #1a4480;
    --navy-light:    #2b5ea7;
    --navy-pale:     #e8eef7;
    --gold:          #c9943a;
    --gold-light:    #e8b86d;
    --gold-pale:     #fdf3e3;
    --teal:          #0d9488;
    --teal-pale:     #e0f2f0;
    --danger:        #dc2626;
    --danger-pale:   #fef2f2;
    --warning:       #d97706;
    --warning-pale:  #fffbeb;
    --success:       #059669;
    --success-pale:  #ecfdf5;
    --cream:         #f7f8fc;
    --white:         #ffffff;
    --text-dark:     #1a1a2e;
    --text-mid:      #4a5568;
    --text-light:    #718096;
    --border:        #e8ecf0;
    --shadow-sm:     0 2px 8px rgba(15,45,90,0.07);
    --shadow-md:     0 6px 24px rgba(15,45,90,0.11);
    --radius:        14px;
    --radius-sm:     10px;
    --transition:    0.22s cubic-bezier(0.4,0,0.2,1);
}

/* ─── GLOBAL ────────────────────────────────────────────────── */
body {
    font-family: 'DM Sans', -apple-system, sans-serif !important;
    background: var(--cream) !important;
    color: var(--text-dark) !important;
}

h1, h2, h3, h4, h5, h6 {
    font-family: 'DM Sans', sans-serif !important;
}

/* Section headings */
h4 {
    font-weight: 700 !important;
    font-size: 1.05rem !important;
    color: var(--navy) !important;
    letter-spacing: 0.2px;
    display: flex;
    align-items: center;
    gap: 10px;
}

h4::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 20px;
    background: linear-gradient(to bottom, var(--gold), var(--gold-light));
    border-radius: 4px;
    flex-shrink: 0;
}

hr {
    border-color: var(--border) !important;
    opacity: 1 !important;
    margin: 0.5rem 0 !important;
}

/* ─── SIDEBAR ───────────────────────────────────────────────── */
.sidebar {
    background: linear-gradient(180deg, var(--navy) 0%, var(--navy-mid) 60%, #153560 100%) !important;
    border-right: none !important;
    box-shadow: 4px 0 24px rgba(15,45,90,0.18);
}

.sidebar-brand {
    padding: 1.6rem 1rem 1.4rem !important;
    background: rgba(0,0,0,0.12) !important;
    border-bottom: 1px solid rgba(255,255,255,0.08) !important;
    gap: 10px;
}

.sidebar-brand-text {
    font-family: 'DM Sans', sans-serif !important;
    font-size: 0.82rem !important;
    font-weight: 600 !important;
    letter-spacing: 0.3px !important;
    color: rgba(255,255,255,0.95) !important;
    text-transform: none !important;
    line-height: 1.3;
}

.sidebar-divider {
    border-top-color: rgba(255,255,255,0.08) !important;
    margin: 0.6rem 1rem !important;
}

.sidebar-heading {
    font-size: 0.65rem !important;
    font-weight: 700 !important;
    letter-spacing: 1.8px !important;
    text-transform: uppercase !important;
    color: rgba(255,255,255,0.35) !important;
    padding: 0.8rem 1.2rem 0.4rem !important;
}

/* Sidebar nav links */
.sidebar .nav-item .nav-link {
    color: rgba(255,255,255,0.72) !important;
    font-size: 0.875rem !important;
    font-weight: 400 !important;
    padding: 10px 20px !important;
    border-radius: 0 !important;
    transition: all var(--transition) !important;
    display: flex;
    align-items: center;
    gap: 10px;
    border-left: 3px solid transparent;
}

.sidebar .nav-item .nav-link i,
.sidebar .nav-item .nav-link .bi {
    font-size: 0.95rem;
    width: 18px;
    text-align: center;
    flex-shrink: 0;
    color: rgba(255,255,255,0.5);
    transition: color var(--transition);
}

.sidebar .nav-item .nav-link:hover {
    color: var(--white) !important;
    background: rgba(255,255,255,0.07) !important;
    border-left-color: rgba(201,148,58,0.5) !important;
}

.sidebar .nav-item .nav-link:hover i,
.sidebar .nav-item .nav-link:hover .bi {
    color: var(--gold-light);
}

.sidebar .nav-item.active .nav-link,
.sidebar .nav-item .nav-link.active {
    color: var(--white) !important;
    background: rgba(201,148,58,0.15) !important;
    border-left-color: var(--gold) !important;
    font-weight: 500 !important;
}

/* ─── TOPBAR ────────────────────────────────────────────────── */
.topbar {
    background: var(--white) !important;
    box-shadow: 0 2px 16px rgba(15,45,90,0.08) !important;
    border-bottom: 1px solid var(--border) !important;
    padding: 0 20px !important;
    height: 60px;
    align-items: center;
}

.topbar .nav-item .nav-link {
    color: var(--text-mid) !important;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 8px 14px !important;
    border-radius: 8px;
    transition: all var(--transition);
    display: flex;
    align-items: center;
    gap: 8px;
}

.topbar .nav-item .nav-link:hover {
    background: var(--cream);
    color: var(--navy) !important;
}

/* Username badge in topbar */
.topbar .text-gray-800 {
    color: var(--text-dark) !important;
    font-weight: 500;
}

/* ─── CONTENT WRAPPER ───────────────────────────────────────── */
#content-wrapper {
    background: var(--cream) !important;
}

#content {
    padding-bottom: 2rem;
}

.container-fluid {
    padding: 1.5rem 2rem !important;
}

/* ─── STAT CARDS ────────────────────────────────────────────── */
/* Override sb-admin2 border-left cards */
.card {
    border: none !important;
    border-radius: var(--radius) !important;
    box-shadow: var(--shadow-sm) !important;
    transition: all var(--transition) !important;
    overflow: hidden;
    background: var(--white) !important;
}

.card:hover {
    box-shadow: var(--shadow-md) !important;
    transform: translateY(-3px);
}

.card-body {
    padding: 1.4rem 1.6rem !important;
}

/* Colored top accent instead of left border */
.card.border-left-primary {
    border-top: 3px solid var(--navy-light) !important;
    border-left: none !important;
}

.card.border-left-info {
    border-top: 3px solid var(--teal) !important;
    border-left: none !important;
}

.card.border-left-danger {
    border-top: 3px solid var(--danger) !important;
    border-left: none !important;
}

.card.border-left-warning {
    border-top: 3px solid var(--warning) !important;
    border-left: none !important;
}

.card.border-left-success {
    border-top: 3px solid var(--success) !important;
    border-left: none !important;
}

/* Tinted card backgrounds */
.card.border-left-primary .card-body { background: linear-gradient(135deg, var(--white) 60%, var(--navy-pale)) !important; }
.card.border-left-info    .card-body { background: linear-gradient(135deg, var(--white) 60%, var(--teal-pale))  !important; }
.card.border-left-danger  .card-body { background: linear-gradient(135deg, var(--white) 60%, var(--danger-pale))!important; }
.card.border-left-warning .card-body { background: linear-gradient(135deg, var(--white) 60%, var(--warning-pale))!important; }
.card.border-left-success .card-body { background: linear-gradient(135deg, var(--white) 60%, var(--success-pale))!important; }

/* Card labels */
.text-xs.font-weight-bold.text-primary {
    color: var(--navy-mid) !important;
    font-size: 0.7rem !important;
    letter-spacing: 1.2px !important;
    font-weight: 700 !important;
}

.text-xs.font-weight-bold.text-info {
    color: var(--teal) !important;
    font-size: 0.7rem !important;
    letter-spacing: 1.2px !important;
    font-weight: 700 !important;
}

.text-xs.font-weight-bold.text-danger {
    color: var(--danger) !important;
    font-size: 0.7rem !important;
    letter-spacing: 1.2px !important;
    font-weight: 700 !important;
}

.text-xs.font-weight-bold.text-warning {
    color: var(--warning) !important;
    font-size: 0.7rem !important;
    letter-spacing: 1.2px !important;
    font-weight: 700 !important;
}

.text-xs.font-weight-bold.text-success {
    color: var(--success) !important;
    font-size: 0.7rem !important;
    letter-spacing: 1.2px !important;
    font-weight: 700 !important;
}

/* Big number */
.h5.mb-0.font-weight-bold.text-dark {
    font-size: 1.8rem !important;
    font-weight: 800 !important;
    color: var(--text-dark) !important;
    line-height: 1.1;
    font-family: 'DM Sans', sans-serif !important;
}

/* View records link */
.card-body a {
    font-size: 0.78rem !important;
    font-weight: 600 !important;
    color: var(--navy-mid) !important;
    text-decoration: none !important;
    letter-spacing: 0.3px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: gap var(--transition), opacity var(--transition);
}

.card.border-left-info .card-body a    { color: var(--teal)    !important; }
.card.border-left-danger .card-body a  { color: var(--danger)  !important; }
.card.border-left-warning .card-body a { color: var(--warning) !important; }
.card.border-left-success .card-body a { color: var(--success) !important; }

.card-body a:hover { opacity: 0.75; gap: 8px; }

.card-body a::after {
    content: '→';
    font-size: 0.85em;
}

/* Card icon */
.card-body .col-auto i,
.card-body .col-auto .bi {
    opacity: 0.18;
    font-size: 2.4rem !important;
    color: var(--text-dark) !important;
}

.card:hover .card-body .col-auto i,
.card:hover .card-body .col-auto .bi {
    opacity: 0.28;
}

/* ─── CARD SPACING ──────────────────────────────────────────── */
.card-upper-space {
    margin-top: 24px !important;
}

.card-row-gap {
    margin-top: 24px !important;
}

.row {
    row-gap: 0;
}

/* ─── SECTION SEPARATORS ────────────────────────────────────── */
.container-fluid > br + hr {
    border: none !important;
    height: 1px !important;
    background: linear-gradient(to right, transparent, var(--border), transparent) !important;
    margin: 1.5rem 0 !important;
}

/* ─── RESPONSIVE TABLES (other pages) ──────────────────────── */
.table {
    font-size: 0.875rem;
}

.table thead th {
    background: var(--navy);
    color: var(--white);
    font-weight: 600;
    letter-spacing: 0.5px;
    font-size: 0.78rem;
    text-transform: uppercase;
    border: none;
    padding: 12px 16px;
}

.table tbody tr:hover {
    background: var(--navy-pale);
}

.table td, .table th {
    border-color: var(--border);
    vertical-align: middle;
    padding: 10px 16px;
}

/* ─── BUTTONS ───────────────────────────────────────────────── */
.btn-primary {
    background: linear-gradient(135deg, var(--navy), var(--navy-light)) !important;
    border: none !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    letter-spacing: 0.3px;
    box-shadow: 0 3px 10px rgba(15,45,90,0.25) !important;
    transition: all var(--transition) !important;
}

.btn-primary:hover {
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 18px rgba(15,45,90,0.3) !important;
}

/* ─── PAGE HEADER (optional) ────────────────────────────────── */
.page-header {
    padding: 1.2rem 0 1.5rem;
    border-bottom: 1px solid var(--border);
    margin-bottom: 1.5rem;
}

.page-header h4 {
    font-size: 1.35rem !important;
}

/* ─── SECTION HEADER CHIPS ──────────────────────────────────── */
.section-label {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 1.5px;
    text-transform: uppercase;
    padding: 4px 12px;
    border-radius: 20px;
    margin-bottom: 1rem;
}

.section-label.resident { background: var(--navy-pale); color: var(--navy-mid); }
.section-label.staff    { background: var(--teal-pale);  color: var(--teal);     }
.section-label.complaint{ background: var(--danger-pale);color: var(--danger);   }

/* ─── FOOTER ────────────────────────────────────────────────── */
.sticky-footer {
    background: var(--white) !important;
    border-top: 1px solid var(--border) !important;
    font-size: 0.8rem !important;
    color: var(--text-light) !important;
    padding: 16px 24px !important;
}

/* ─── SCROLLBAR (Webkit) ────────────────────────────────────── */
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(15,45,90,0.15); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: rgba(15,45,90,0.28); }

/* ─── ALERTS & BADGES ───────────────────────────────────────── */
.badge-primary { background-color: var(--navy-light) !important; }
.badge-info    { background-color: var(--teal)       !important; }
.badge-danger  { background-color: var(--danger)     !important; }
.badge-warning { background-color: var(--warning)    !important; color: var(--white) !important; }
.badge-success { background-color: var(--success)    !important; }

/* ─── RESPONSIVE ────────────────────────────────────────────── */
@media (max-width: 768px) {
    .container-fluid {
        padding: 1rem 1.2rem !important;
    }

    .h5.mb-0.font-weight-bold.text-dark {
        font-size: 1.5rem !important;
    }

    .card:hover {
        transform: none;
    }
}

/* ─── CHART CONTAINERS ────────────────────────────────────── */
.chart-wrapper {
    position: relative;
    margin: 0 auto;
    max-width: 260px;
    height: 220px; /* Fixed height to match bar chart */
}

.chart-wrapper canvas {
    width: 100% !important;
    height: 100% !important;
}

.chart-legend-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.75rem 1.5rem;
    margin-top: 1rem;
}

.chart-legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-mid);
    cursor: pointer;
    transition: all var(--transition);
    padding: 0.25rem 0.5rem;
    border-radius: 6px;
}

.chart-legend-item:hover {
    background: rgba(15,45,90,0.05);
    color: var(--text-dark);
}

.chart-legend-color {
    width: 14px;
    height: 14px;
    border-radius: 4px;
    flex-shrink: 0;
}

.chart-legend-value {
    font-weight: 700;
    color: var(--text-dark);
}

.click-hint {
    font-size: 0.65rem;
    color: var(--text-light);
    text-align: center;
    margin-top: 0.5rem;
    opacity: 0.7;
}

/* ─── HORIZONTAL BAR CHART SPECIFIC ────────────────────────── */
.bar-chart-container {
    height: 220px; /* Fixed height to match pie chart */
    position: relative;
}

.bar-chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

.bar-legend-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem 1rem;
    margin-top: 0.75rem;
}

.bar-legend-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.7rem;
    font-weight: 600;
    color: var(--text-mid);
    padding: 0.15rem 0.5rem;
    border-radius: 4px;
    background: rgba(0,0,0,0.03);
}

.bar-legend-color {
    width: 12px;
    height: 12px;
    border-radius: 3px;
    flex-shrink: 0;
}

/* ─── EQUAL HEIGHT CARDS ────────────────────────────────────── */
.equal-height-cards {
    display: flex;
    align-items: stretch;
}

.equal-height-cards .card {
    height: 100%;
}

.equal-height-cards .card-body {
    display: flex;
    flex-direction: column;
    height: 100%;
}

.chart-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* ─── CARD BODY FLEX ────────────────────────────────────────── */
.card-body-flex {
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 400px;
}

.card-body-flex .chart-section {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

/* ─── CERTIFICATES ISSUED CHART ─────────────────────────────── */
.cert-chart-card .card-body {
    padding: 1.5rem 1.75rem;
}

.cert-chart-header {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    margin-bottom: 0.25rem;
}

.cert-chart-title {
    display: flex;
    align-items: center;
    gap: 10px;
}

.cert-chart-title .bi {
    font-size: 1.1rem;
    color: var(--gold);
    background: var(--gold-pale);
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    flex-shrink: 0;
}

.cert-chart-title-text .label {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1.3;
}

.cert-chart-title-text .sub {
    font-size: 0.75rem;
    color: var(--text-light);
    font-weight: 500;
}

.cert-chart-total {
    text-align: right;
}

.cert-chart-total .num {
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--navy);
    line-height: 1;
}

.cert-chart-total .lbl {
    font-size: 0.68rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-light);
}

/* Period toggle pills */
.period-toggle {
    display: inline-flex;
    background: var(--navy-pale);
    border-radius: 999px;
    padding: 4px;
    gap: 2px;
}

.period-toggle button {
    border: none;
    background: transparent;
    color: var(--navy);
    font-size: 0.78rem;
    font-weight: 600;
    padding: 6px 16px;
    border-radius: 999px;
    cursor: pointer;
    transition: all var(--transition);
    white-space: nowrap;
}

.period-toggle button:hover {
    color: var(--navy);
    background: rgba(15,45,90,0.08);
}

.period-toggle button.active {
    background: var(--navy);
    color: var(--white);
    box-shadow: 0 2px 8px rgba(15,45,90,0.25);
}

.cert-chart-container {
    position: relative;
    height: 320px;
    margin-top: 1.25rem;
}

.cert-chart-container canvas {
    width: 100% !important;
    height: 100% !important;
}

.cert-chart-container.is-loading canvas {
    opacity: 0.25;
    transition: opacity var(--transition);
}

.cert-chart-spinner {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 34px;
    height: 34px;
    border: 3px solid var(--navy-pale);
    border-top-color: var(--gold);
    border-radius: 50%;
    animation: cert-spin 0.75s linear infinite;
    display: none;
}

.cert-chart-container.is-loading .cert-chart-spinner {
    display: block;
}

@keyframes cert-spin {
    to { transform: translate(-50%, -50%) rotate(360deg); }
}

.cert-chart-empty {
    position: absolute;
    inset: 0;
    display: none;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 6px;
    color: var(--text-light);
    text-align: center;
}

.cert-chart-empty.show { display: flex; }

.cert-chart-empty .bi { font-size: 1.8rem; color: var(--border); }
.cert-chart-empty .msg { font-size: 0.85rem; font-weight: 500; }

.cert-legend-grid {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 0.5rem 1.25rem;
    margin-top: 1rem;
    padding-top: 1rem;
    border-top: 1px dashed var(--border);
}

.cert-legend-item {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-mid);
}

.cert-legend-color {
    width: 11px;
    height: 11px;
    border-radius: 3px;
    flex-shrink: 0;
}

@media (max-width: 576px) {
    .cert-chart-header { flex-direction: column; align-items: stretch; }
    .cert-chart-total { text-align: left; }
}
</style>

<?php 
    
        $this->view('admin/dashboard.php');

    }

    public function settings()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $bmis->create_admin(); 
    $userdetails = $bmis->get_userdata();

    
        $this->view('admin/settings.php');

    }

    public function archive()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $userdetails = $bmis->get_userdata();
    $bmis->validate_staff_or_admin();

    $type_meta = [
        'resident'        => ['label' => 'Resident',              'icon' => 'fa-users',          'color' => 'blue'],
        'certofres'       => ['label' => 'Cert. of Residency',    'icon' => 'fa-file-alt',        'color' => 'teal'],
        'certofindigency' => ['label' => 'Cert. of Indigency',    'icon' => 'fa-file-alt',        'color' => 'teal'],
        'clearance'       => ['label' => 'Brgy. Clearance',       'icon' => 'fa-file',            'color' => 'gold'],
        'bspermit'        => ['label' => 'Business Permit',       'icon' => 'fa-file-contract',   'color' => 'gold'],
        'blotter'         => ['label' => 'Peace & Order Report',  'icon' => 'fa-user-shield',     'color' => 'red'],
        'youth'           => ['label' => 'Youth Profile',         'icon' => 'fa-users',           'color' => 'teal'],
        'brgyid'          => ['label' => 'Barangay ID',           'icon' => 'fa-id-card',         'color' => 'blue'],
        'staff'           => ['label' => 'Staff',                 'icon' => 'fa-user-tie',        'color' => 'blue'],
        'message'         => ['label' => 'Resident Message',      'icon' => 'fa-comments',         'color' => 'blue'],
    ];

    // ── Handle permanent delete (single) ────────────────────
    if (isset($_POST['permanent_delete']) && !empty($_POST['id_archive'])) {
        $id = (int)$_POST['id_archive'];
        $stmt = $conn->prepare("DELETE FROM tbl_archive WHERE id_archive = ?");
        $stmt->execute([$id]);
        echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'delete',msg:'Record permanently deleted.'})); window.location.href='admn_archive.php';</script>";
        exit;
    }

    // ── Handle bulk permanent delete ─────────────────────────
    if (isset($_POST['bulk_permanent_delete']) && !empty($_POST['selected_archives'])) {
        $ids = array_filter($_POST['selected_archives'], 'is_numeric');
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $stmt = $conn->prepare("DELETE FROM tbl_archive WHERE id_archive IN ($placeholders)");
            $stmt->execute(array_values($ids));
            $count = $stmt->rowCount();
            $msg = "$count record(s) permanently deleted.";
            echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'delete',msg:" . json_encode($msg) . "})); window.location.href='admn_archive.php';</script>";
            exit;
        }
    }

    // ── Helper: restore one archive row by its data ─────────
    function restore_single_row($conn, $arc) {
        $data = json_decode($arc['record_data'], true);
        $type = $arc['record_type'];
        switch ($type) {

            case 'staff':
                $s = $conn->prepare("
                    INSERT INTO tbl_user
                        (id_user, login_identity, email, phone_number, password,
                         lname, fname, mi, age, sex, address, contact,
                         position, role, addedby, photo)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                        login_identity = VALUES(login_identity),
                        email          = VALUES(email),
                        phone_number   = VALUES(phone_number),
                        password       = VALUES(password),
                        lname          = VALUES(lname),
                        fname          = VALUES(fname),
                        mi             = VALUES(mi),
                        age            = VALUES(age),
                        sex            = VALUES(sex),
                        address        = VALUES(address),
                        contact        = VALUES(contact),
                        position       = VALUES(position),
                        role           = VALUES(role),
                        addedby        = VALUES(addedby),
                        photo          = VALUES(photo)
                ");
                $s->execute([
                    $data['id_user']        ?? null,
                    $data['login_identity'] ?? '',
                    $data['email']          ?? '',
                    $data['phone_number']   ?? '',
                    $data['password']       ?? '',
                    $data['lname']          ?? '',
                    $data['fname']          ?? '',
                    $data['mi']             ?? '',
                    $data['age']            ?? 0,
                    $data['sex']            ?? '',
                    $data['address']        ?? '',
                    $data['contact']        ?? '',
                    $data['position']       ?? '',
                    $data['role']           ?? '',
                    $data['addedby']        ?? '',
                    $data['photo']          ?? '',
                ]);
                return true;

            case 'resident':
                $s = $conn->prepare("
                    INSERT INTO tbl_resident
                        (id_resident, email, phone_number, password,
                         lname, fname, mi, age, sex, status,
                         houseno, street, brgy, municipal, address, contact,
                         bdate, bplace, nationality, voter, family_role, role,
                         is_verified, verified_at, verified_by, addedby)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)
                    ON DUPLICATE KEY UPDATE
                        email        = VALUES(email),
                        phone_number = VALUES(phone_number),
                        password     = VALUES(password),
                        lname        = VALUES(lname),
                        fname        = VALUES(fname),
                        mi           = VALUES(mi),
                        age          = VALUES(age),
                        sex          = VALUES(sex),
                        status       = VALUES(status),
                        houseno      = VALUES(houseno),
                        street       = VALUES(street),
                        brgy         = VALUES(brgy),
                        municipal    = VALUES(municipal),
                        address      = VALUES(address),
                        contact      = VALUES(contact),
                        bdate        = VALUES(bdate),
                        bplace       = VALUES(bplace),
                        nationality  = VALUES(nationality),
                        voter        = VALUES(voter),
                        family_role  = VALUES(family_role),
                        role         = VALUES(role),
                        is_verified  = VALUES(is_verified),
                        verified_at  = VALUES(verified_at),
                        verified_by  = VALUES(verified_by),
                        addedby      = VALUES(addedby)
                ");
                $s->execute([
                    $data['id_resident']    ?? null,
                    $data['email']          ?? null,
                    $data['phone_number']   ?? null,
                    $data['password']       ?? '',
                    $data['lname']          ?? '',
                    $data['fname']          ?? '',
                    $data['mi']             ?? '',
                    $data['age']            ?? 0,
                    $data['sex']            ?? '',
                    $data['status']         ?? '',
                    $data['houseno']        ?? null,
                    $data['street']         ?? null,
                    $data['brgy']           ?? null,
                    $data['municipal']      ?? null,
                    $data['address']        ?? null,
                    $data['contact']        ?? null,
                    $data['bdate']          ?? null,
                    $data['bplace']         ?? '',
                    $data['nationality']    ?? '',
                    $data['voter']          ?? '',
                    $data['family_role']    ?? '',
                    $data['role']           ?? 'resident',
                    $data['is_verified']    ?? 0,
                    $data['verified_at']    ?? null,
                    $data['verified_by']    ?? null,
                    $data['addedby']        ?? '',
                ]);
                return true;

            case 'certofres':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_rescert
                    (id_rescert,id_resident,lname,fname,mi,age,nationality,houseno,street,brgy,municipal,date,purpose)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_rescert'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['age'],$data['nationality'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['date'],$data['purpose']]);
                return true;

            case 'certofindigency':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_indigency
                    (id_indigency,id_resident,lname,fname,mi,nationality,houseno,street,brgy,municipal,purpose,date)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_indigency'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['nationality'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['purpose'],$data['date']]);
                return true;

            case 'clearance':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_clearance
                    (id_clearance,id_resident,lname,fname,mi,purpose,houseno,street,brgy,municipal,status,age)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_clearance'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['purpose'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['status'],$data['age']]);
                return true;

            case 'bspermit':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_bspermit
                    (id_bspermit,id_resident,lname,fname,mi,bsname,houseno,street,brgy,municipal,bsindustry,aoe)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_bspermit'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['bsname'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['bsindustry'],$data['aoe']]);
                return true;

            case 'blotter':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_blotter
                    (id_blotter,id_resident,lname,fname,mi,houseno,street,brgy,municipal,contact,narrative)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_blotter'],$data['id_resident'],$data['lname'],$data['fname'],$data['mi'],$data['houseno'],$data['street'],$data['brgy'],$data['municipal'],$data['contact'],$data['narrative']]);
                return true;

            case 'youth':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_youth
                    (id_youth,lname,fname,mi,age,sex,civil_status,contact_number,email_address,educ_attain,emp_status,skill_name)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([$data['id_youth'],$data['lname'],$data['fname'],$data['mi'],$data['age'],$data['sex'],$data['civil_status'],$data['contact_number'],$data['email_address'],$data['educ_attain'],$data['emp_status'],$data['skill_name']]);
                return true;

            case 'brgyid':
                $s = $conn->prepare("INSERT IGNORE INTO tbl_brgyid
                    (id_brgyid,id_resident,lname,fname,mi,houseno,street,brgy,municipal,bplace,bdate,contact,relation,inc_lname,inc_fname,inc_contact)
                    VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");
                $s->execute([
                    $data['id_brgyid']    ?? null, $data['id_resident']  ?? null,
                    $data['lname']        ?? '',   $data['fname']         ?? '',
                    $data['mi']           ?? '',   $data['houseno']       ?? '',
                    $data['street']       ?? '',   $data['brgy']          ?? '',
                    $data['municipal']    ?? '',   $data['bplace']        ?? '',
                    $data['bdate']        ?? '',   $data['contact']       ?? '',
                    $data['relation']     ?? '',   $data['inc_lname']     ?? '',
                    $data['inc_fname']    ?? '',   $data['inc_contact']   ?? '',
                ]);
                return true;

            case 'message':
                $ok = true;
                if (!empty($data['admin_messages'])) {
                    $s1 = $conn->prepare("INSERT IGNORE INTO admin_messages
                        (id_admin_msg, id_resident, message_text, date_sent, status, reply_text, reply_date, replied_by)
                        VALUES (?,?,?,?,?,?,?,?)");
                    foreach ($data['admin_messages'] as $m) {
                        $s1->execute([
                            $m['id_admin_msg']  ?? null,
                            $m['id_resident']   ?? null,
                            $m['message_text']  ?? '',
                            $m['date_sent']     ?? null,
                            $m['status']        ?? 'unread',
                            $m['reply_text']    ?? null,
                            $m['reply_date']    ?? null,
                            $m['replied_by']    ?? null,
                        ]);
                    }
                }
                if (!empty($data['resident_messages'])) {
                    $s2 = $conn->prepare("INSERT IGNORE INTO resident_messages
                        (id_message, id_resident, message_text, date_sent)
                        VALUES (?,?,?,?)");
                    foreach ($data['resident_messages'] as $m) {
                        $s2->execute([
                            $m['id_message']   ?? null,
                            $m['id_resident']  ?? null,
                            $m['message_text'] ?? '',
                            $m['date_sent']    ?? null,
                        ]);
                    }
                }
                return $ok;
        }
        return false;
    }

    // ── Handle bulk restore ──────────────────────────────────
    if (isset($_POST['bulk_restore']) && !empty($_POST['selected_archives'])) {
        $ids = array_filter($_POST['selected_archives'], 'is_numeric');
        if (!empty($ids)) {
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $rows_stmt = $conn->prepare("SELECT * FROM tbl_archive WHERE id_archive IN ($placeholders)");
            $rows_stmt->execute(array_values($ids));
            $rows = $rows_stmt->fetchAll(PDO::FETCH_ASSOC);

            $success = 0;
            $failed  = 0;
            $restored_ids = [];

            foreach ($rows as $arc) {
                try {
                    if (restore_single_row($conn, $arc)) {
                        $restored_ids[] = $arc['id_archive'];
                        $success++;
                    }
                } catch (Exception $e) {
                    $failed++;
                }
            }

            if (!empty($restored_ids)) {
                $del_placeholders = implode(',', array_fill(0, count($restored_ids), '?'));
                $conn->prepare("DELETE FROM tbl_archive WHERE id_archive IN ($del_placeholders)")
                     ->execute($restored_ids);
            }

            $msg = "$success record(s) restored successfully.";
            if ($failed > 0) $msg .= " $failed failed.";
            echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'restore',msg:" . json_encode($msg) . "})); window.location.href='admn_archive.php';</script>";
            exit;
        }
    }

    // ── Handle restore (re-insert) ───────────────────────────
    if (isset($_POST['restore_record']) && !empty($_POST['id_archive'])) {
        $id  = (int)$_POST['id_archive'];
        $row = $conn->prepare("SELECT * FROM tbl_archive WHERE id_archive = ?");
        $row->execute([$id]);
        $arc = $row->fetch(PDO::FETCH_ASSOC);

        $restored = false;
        if ($arc) {
            try {
                $restored = restore_single_row($conn, $arc);

                if ($restored) {
                    $del = $conn->prepare("DELETE FROM tbl_archive WHERE id_archive = ?");
                    $del->execute([$id]);
                    echo "<script>sessionStorage.setItem('archiveToast', JSON.stringify({type:'restore',msg:'Record restored successfully!'})); window.location.href='admn_archive.php';</script>";
                    exit;
                }
            } catch (Exception $e) {
                $msg = addslashes($e->getMessage());
                echo "<script>alert('SQL Error: $msg');</script>";
            }
        }
    }

    // ── Filter parameters ────────────────────────────────────
    $filter_type    = isset($_GET['type'])    ? $_GET['type']    : '';
    $filter_keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
    $filter_status  = isset($_GET['status'])  ? $_GET['status']  : 'all';

    // ── Build query ──────────────────────────────────────────
    $where  = [];
    $params = [];

    if ($filter_type !== '') {
        $where[]  = "record_type = ?";
        $params[] = $filter_type;
    }
    if ($filter_keyword !== '') {
        $where[]  = "(full_name LIKE ? OR summary LIKE ?)";
        $params[] = "%$filter_keyword%";
        $params[] = "%$filter_keyword%";
    }
    $sql = "SELECT * FROM tbl_archive";
    if ($where) $sql .= " WHERE " . implode(" AND ", $where);
    $sql .= " ORDER BY deleted_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ── Counts per type ──────────────────────────────────────
    $counts_stmt = $conn->query("SELECT record_type, COUNT(*) as cnt FROM tbl_archive GROUP BY record_type");
    $counts = [];
    foreach ($counts_stmt->fetchAll(PDO::FETCH_ASSOC) as $c) {
        $counts[$c['record_type']] = $c['cnt'];
    }
    $total_archived = array_sum($counts);
?>

<?php 
        $this->view('admin/archive.php');

    }

    public function activityLogs()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
// ⚠️ ABSOLUTELY NOTHING before this line – no spaces, no BOM
session_start();

$userdetails = $bmis->get_userdata();

// ── FLEXIBLE ADMIN CHECK ─────────────────────────────────────────────
$admin_roles = ['admin', 'Administrator', 'Admin', 'ADMIN', 'administrator'];
$user_role   = $userdetails['role'] ?? '';
$is_admin    = in_array($user_role, $admin_roles);

if (!$is_admin) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Unauthorized.']);
        exit;
    }
    http_response_code(403);
    require('403.php');
    exit;
}

// ── CSRF TOKEN ────────────────────────────────────────────────────────
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

// ── POST HANDLER ─────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $body = json_decode(file_get_contents('php://input'), true);

    // CSRF check (shared by all POST actions)
    if (!isset($body['csrf_token']) || $body['csrf_token'] !== $_SESSION['csrf_token']) {
        echo json_encode(['success' => false, 'message' => 'CSRF token mismatch.']);
        exit;
    }

    $action = $body['action'] ?? '';

    // ── BULK DELETE (by checkbox IDs) ─────────────────────────────────
    if ($action === 'bulk_delete') {
        $ids_raw = $body['ids'] ?? [];
        if (!is_array($ids_raw)) {
            echo json_encode(['success' => false, 'message' => 'Invalid IDs format.']);
            exit;
        }
        $ids = array_filter(array_map('intval', $ids_raw), fn($id) => $id > 0);
        if (empty($ids)) {
            echo json_encode(['success' => false, 'message' => 'No valid IDs provided.']);
            exit;
        }
        try {
            $conn = $bmis->openConn();
            $placeholders = implode(',', array_fill(0, count($ids), '?'));

            // ── Protect certificate/document issuance records ──────────────
            // GENERATE_DOCUMENT rows feed the "Certificates Issued" dashboard
            // chart. They are never deletable from this page, even if the
            // admin selects them — the chart depends on them permanently.
            $protectStmt = $conn->prepare("SELECT id_log FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action = 'GENERATE_DOCUMENT'");
            $protectStmt->execute(array_values($ids));
            $protectedIds = array_map('intval', $protectStmt->fetchAll(PDO::FETCH_COLUMN));

            $stmt = $conn->prepare("DELETE FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action != 'GENERATE_DOCUMENT'");
            $stmt->execute(array_values($ids));
            $deleted = $stmt->rowCount();
            $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log");
            $countStmt->execute();
            $newTotal = (int)$countStmt->fetchColumn();
            echo json_encode([
                'success'       => true,
                'deleted'       => $deleted,
                'protected'     => count($protectedIds),
                'protected_ids' => $protectedIds,
                'new_total'     => $newTotal
            ]);
        } catch (Exception $e) {
            error_log('Bulk delete error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    // ── RANGE COUNT (preview how many will be deleted) ─────────────────
    if ($action === 'range_count') {
        $range = $body['range'] ?? '';
        $cutoff = getRangeCutoff($range);
        if (!$cutoff) {
            echo json_encode(['success' => false, 'message' => 'Invalid range.']);
            exit;
        }
        try {
            $conn  = $bmis->openConn();
            $stmt  = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE created_at < ? AND action != 'GENERATE_DOCUMENT'");
            $stmt->execute([$cutoff]);
            $count = (int)$stmt->fetchColumn();

            // How many protected certificate-issuance rows fall in this range too
            // (shown to the admin so the preview count isn't a mystery, but they
            // are never included in $count / the eventual delete).
            $protectStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE created_at < ? AND action = 'GENERATE_DOCUMENT'");
            $protectStmt->execute([$cutoff]);
            $protectedCount = (int)$protectStmt->fetchColumn();

            echo json_encode(['success' => true, 'count' => $count, 'protected' => $protectedCount, 'cutoff' => $cutoff]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    // ── RANGE DELETE ───────────────────────────────────────────────────
    if ($action === 'delete_by_range') {
        $range = $body['range'] ?? '';
        $cutoff = getRangeCutoff($range);
        if (!$cutoff) {
            echo json_encode(['success' => false, 'message' => 'Invalid range.']);
            exit;
        }
        try {
            $conn  = $bmis->openConn();

            $protectStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log WHERE created_at < ? AND action = 'GENERATE_DOCUMENT'");
            $protectStmt->execute([$cutoff]);
            $protectedCount = (int)$protectStmt->fetchColumn();

            // Certificate/document issuance records are never purged, regardless
            // of age — they're the permanent source data for the dashboard chart.
            $stmt  = $conn->prepare("DELETE FROM tbl_activity_log WHERE created_at < ? AND action != 'GENERATE_DOCUMENT'");
            $stmt->execute([$cutoff]);
            $deleted = $stmt->rowCount();
            $countStmt = $conn->prepare("SELECT COUNT(*) FROM tbl_activity_log");
            $countStmt->execute();
            $newTotal = (int)$countStmt->fetchColumn();
            echo json_encode([
                'success'   => true,
                'deleted'   => $deleted,
                'protected' => $protectedCount,
                'new_total' => $newTotal
            ]);
        } catch (Exception $e) {
            error_log('Range delete error: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
        exit;
    }

    echo json_encode(['success' => false, 'message' => 'Unknown action.']);
    exit;
}

// ── HELPER: compute cutoff date from range key ────────────────────────
function getRangeCutoff(string $range): string|false {
    $now = new DateTime();
    return match($range) {
        'week'    => (clone $now)->modify('-1 week')->format('Y-m-d H:i:s'),
        'month'   => (clone $now)->modify('-1 month')->format('Y-m-d H:i:s'),
        '3months' => (clone $now)->modify('-3 months')->format('Y-m-d H:i:s'),
        '6months' => (clone $now)->modify('-6 months')->format('Y-m-d H:i:s'),
        'year'    => (clone $now)->modify('-1 year')->format('Y-m-d H:i:s'),
        default   => false,
    };
}

// ── GET: pagination & filters ─────────────────────────────────────────
$page     = max(1, (int)($_GET['page'] ?? 1));
$per_page = 20;
$filters  = [
    'search'    => trim($_GET['search']    ?? ''),
    'module'    => trim($_GET['module']    ?? ''),
    'date_from' => trim($_GET['date_from'] ?? ''),
    'date_to'   => trim($_GET['date_to']   ?? ''),
];

$result      = $bmis->get_activity_logs($filters, $page, $per_page);
$logs        = $result['rows'];
$total       = $result['total'];
$total_pages = max(1, (int)ceil($total / $per_page));
        $this->view('admin/activityLogs.php');

    }

    public function activityLogsDelete()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * admn_activity_logs_delete.php
 * Accepts a JSON POST: { "ids": [1, 2, 3, …] }
 * Returns:            { "success": true }  or  { "success": false, "message": "…" }
 */

header('Content-Type: application/json');

// Must be POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

// Decode body
$body = json_decode(file_get_contents('php://input'), true);
$ids  = $body['ids'] ?? [];

// Validate
if (empty($ids) || !is_array($ids)) {
    echo json_encode(['success' => false, 'message' => 'No IDs provided.']);
    exit;
}

// Sanitise – keep only positive integers
$ids = array_filter(array_map('intval', $ids), fn($id) => $id > 0);

if (empty($ids)) {
    echo json_encode(['success' => false, 'message' => 'Invalid IDs.']);
    exit;
}

// ---- Run the delete via your existing class ----
// Option A – if your class exposes a method:
//   $result = $bmis->delete_activity_logs($ids);

// Option B – raw PDO query (replace $bmis->pdo with your actual connection property):
try {
    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    // ── Protect certificate/document issuance records ──────────────────
    // GENERATE_DOCUMENT rows feed the "Certificates Issued" dashboard
    // chart and must never be removable through this endpoint either.
    $protectStmt = $conn->prepare("SELECT id_log FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action = 'GENERATE_DOCUMENT'");
    $protectStmt->execute(array_values($ids));
    $protectedIds = array_map('intval', $protectStmt->fetchAll(PDO::FETCH_COLUMN));

    $stmt = $conn->prepare("DELETE FROM tbl_activity_log WHERE id_log IN ($placeholders) AND action != 'GENERATE_DOCUMENT'");
    $stmt->execute(array_values($ids));

    echo json_encode([
        'success'       => true,
        'deleted'       => $stmt->rowCount(),
        'protected'     => count($protectedIds),
        'protected_ids' => $protectedIds,
    ]);
} catch (Exception $e) {
    // Log internally; never expose raw DB errors to the client
    error_log('Bulk delete error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Database error occurred.']);
}

    }

    public function loginHistory()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
    

    // ==========================================
    // BACKEND: Handle Bulk Deletion Action
    // ==========================================
    $delete_message = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bulk_delete'])) {
        $ids_to_delete = isset($_POST['selected_ids']) ? array_map('intval', $_POST['selected_ids']) : [];
        
        if (!empty($ids_to_delete)) {
            if (method_exists($bmis, 'delete_login_history')) {
                $success = $bmis->delete_login_history($ids_to_delete);
            } else {
                $success = false; 
            }

            if ($success) {
                $delete_message = '<div class="alert alert-success" style="background:#e3f3e9; color:#1e6e3e; padding:12px 20px; border-radius:8px; margin-bottom:20px; font-size:14px; font-weight:500;"><i class="fas fa-check-circle mr-2"></i> Successfully deleted ' . count($ids_to_delete) . ' record(s).</div>';
            }
        }
    }

    // ==========================================
    // DATA FETCHING & FILTERS
    // ==========================================
    $page     = max(1, (int)($_GET['page']     ?? 1));
    $per_page = 20;
    $filters  = [
        'search'    => trim($_GET['search']    ?? ''),
        'event'     => trim($_GET['event']     ?? ''),
        'date_from' => trim($_GET['date_from'] ?? ''),
        'date_to'   => trim($_GET['date_to']   ?? ''),
    ];

    // Re-run fetching after a successful post statement to update the table immediately
    $result      = $bmis->get_login_history($filters, $page, $per_page);
    $logs        = $result['rows'];
    $total       = $result['total'];
    $total_pages = max(1, (int)ceil($total / $per_page));

    
        $this->view('admin/loginHistory.php');

    }

    public function passwordResetRequests()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;
/**
 * admn_password_reset_requests.php
 * Admin page: view, approve, or reject resident password reset requests.
 */
      error_reporting(E_ALL ^ E_WARNING);
   ini_set('display_errors',1);
bmis_session_start();
$userdetails = bmis_require_admin();
date_default_timezone_set('Asia/Manila');

$error   = '';
$success = '';

// ── Approve: generate temp password, update resident, mark resolved ──────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_reset'])) {
    bmis_verify_csrf();
    $req_id      = (int) $_POST['req_id'];
    $id_resident = (int) $_POST['id_resident'];

    // Generate readable temp password: e.g. BrgyReset@4821
    $temp = 'BrgyReset@' . rand(1000, 9999);
    $hash = password_hash($temp, PASSWORD_DEFAULT);

    $conn->prepare("UPDATE tbl_resident SET password = ?, must_change_password = 1 WHERE id_resident = ?")
         ->execute([$hash, $id_resident]);

    $conn->prepare(
        "UPDATE tbl_password_reset_requests
         SET status='approved', temp_password=?, resolved_at=NOW(), resolved_by=?
         WHERE id=?"
    )->execute([$temp, $userdetails['fname'].' '.$userdetails['lname'], $req_id]);

    $success = "Approved. Temporary password set to: <strong>" . htmlspecialchars($temp) . "</strong> — give this to the resident in person.";
}

// ── Reject ───────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reject_reset'])) {
    bmis_verify_csrf();
    $req_id = (int) $_POST['req_id'];
    $conn->prepare(
        "UPDATE tbl_password_reset_requests SET status='rejected', resolved_at=NOW(), resolved_by=? WHERE id=?"
    )->execute([$userdetails['fname'].' '.$userdetails['lname'], $req_id]);
    $success = 'Request rejected.';
}

// ── Delete ───────────────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reset'])) {
    bmis_verify_csrf();
    $req_id = (int) $_POST['req_id'];
    $conn->prepare("DELETE FROM tbl_password_reset_requests WHERE id = ? AND status != 'pending'")
         ->execute([$req_id]);
    $success = 'Request deleted.';
}

// ── Fetch all requests ───────────────────────────────────────────────────────
$requests = $conn->query(
    "SELECT r.*, res.email, res.phone_number
     FROM tbl_password_reset_requests r
     JOIN tbl_resident res ON res.id_resident = r.id_resident
     ORDER BY FIELD(r.status,'pending','approved','rejected'), r.requested_at DESC"
)->fetchAll(PDO::FETCH_ASSOC);

$pendingCount = count(array_filter($requests, fn($r) => $r['status'] === 'pending'));
        $this->view('admin/passwordResetRequests.php');

    }

    public function announcementCrud()
    {
        $conn = $this->conn;
        $bmis = $this->bmis;
        $residentbmis = $this->residentbmis;
        $staffbmis = $this->staffbmis;
        $userdetails = $this->userdetails;

error_reporting(E_ALL ^ E_WARNING);
ini_set('display_errors', 1);
$userdetails = $bmis->get_userdata();
   $current_admin_id = $userdetails['id_resident'];

   if(isset($_POST['create_announce'])) {
       $bmis->create_announcement();
       header("Location: admn_announcement_crud.php?toast=created");
       exit();
   }

   if(isset($_POST['delete_announcement'])) {
       $bmis->admin_delete_announcement();
       header("Location: admn_announcement_crud.php?toast=deleted");
       exit();
   }

   $view = $bmis->view_announcement(); 
   $announcementcount = $bmis->count_announcement();

   $dt = new DateTime("now", new DateTimeZone('Asia/Manila'));
   $cdate = $dt->format('Y/m/d');   

   /**
    * Escapes announcement text for safe HTML output, then turns any
    * http://, https://, or www. links inside it into clickable <a> tags
    * that open in a new tab.
    */
   function bmis_linkify_announcement($text) {
       $escaped = htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');

       return preg_replace_callback(
           '/((https?:\/\/|www\.)[^\s<]+)/i',
           function ($m) {
               $url = $m[0];

               // Don't swallow trailing punctuation into the link (e.g. "visit site.com.")
               $trailing = '';
               while ($url !== '' && strpos('.,!?;:)"\'', substr($url, -1)) !== false) {
                   $trailing = substr($url, -1) . $trailing;
                   $url = substr($url, 0, -1);
               }

               $href = (stripos($url, 'http') === 0) ? $url : 'https://' . $url;

               return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" '
                    . 'onclick="event.stopPropagation();" '
                    . 'style="color:#1a4480; font-weight:600; text-decoration:underline; word-break:break-all;">'
                    . $url . '</a>' . $trailing;
           },
           $escaped
       );
   }
?>

<?php 
        $this->view('admin/announcementCrud.php');

    }

}
