

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
    include(ROOT_PATH . '/dashboard_sidebar_start.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

   
    <div class="row mt-3">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Residents</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $kpi_residents ?></div>
                            <br>
                            <a href="admn_table_totalres.php">View All</a>
                        </div>
                        <div class="col-auto"><i class="bi bi-people-fill" style="font-size:2rem;"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Certificate Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $kpi_certs ?></div>
                        </div>
                        <div class="col-auto"><i class="bi bi-file-earmark-text-fill" style="font-size:2rem;"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Total Complaints</div>
                            <div class="h5 mb-0 font-weight-bold text-dark" data-live="cmp_total"><?= $complaint_total ?></div>
                            <br>
                            <a href="admn_complaints.php">View All</a>
                        </div>
                        <div class="col-auto"><i class="bi bi-megaphone-fill" style="font-size:2rem;"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Announcements</div>
                            <div class="h5 mb-0 font-weight-bold text-dark"><?= $kpi_announcements ?></div>
                            <br>
                            <a href="admn_announcement_crud.php">View All</a>
                        </div>
                        <div class="col-auto"><i class="bi bi-bell-fill" style="font-size:2rem;"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Heading -->
    <div class="row mt-3 equal-height-cards">

        <!-- Pie Chart: Gender -->
        <div class="col-md-4 mb-3">
            <div class="card shadow" style="border-top: 3px solid var(--navy-light) !important;">
                <div class="card-body card-body-flex">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-3">
                        <i class="fas fa-venus-mars me-1"></i> Gender Distribution
                    </div>
                    <div class="chart-section">
                        <div class="chart-wrapper">
                            <canvas id="genderPieChart"></canvas>
                        </div>
                        <div class="chart-legend-grid">
                            <span class="chart-legend-item">
                                <span class="chart-legend-color" style="background:#5996FF;"></span>
                                Male <span class="chart-legend-value" data-live="res_male"><?= $rescountm ?></span>
                            </span>
                            <span class="chart-legend-item">
                                <span class="chart-legend-color" style="background:#FFADEE;"></span>
                                Female <span class="chart-legend-value" data-live="res_female"><?= $rescountf ?></span>
                            </span>
                        </div>
                        <div class="click-hint"><i class="bi bi-hand-index-thumb me-1"></i> Click slice to view records</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Horizontal Bar Chart: Resident Demographics Overview -->
        <div class="col-md-8 mb-3">
            <div class="card shadow" style="border-top: 3px solid var(--navy-light) !important;">
                <div class="card-body card-body-flex">
                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-3">
                        <i class="fas fa-chart-bar me-1"></i> Resident Demographics Overview
                    </div>
                    <div class="chart-section">
                        <div class="bar-chart-container">
                            <canvas id="residentBarChart"></canvas>
                        </div>
                        <div class="bar-legend-grid">
                            <span class="bar-legend-item">
                                <span class="bar-legend-color" style="background:#0d9488;"></span>
                                Households
                            </span>
                            <span class="bar-legend-item">
                                <span class="bar-legend-color" style="background:#c9943a;"></span>
                                Voters
                            </span>
                            <span class="bar-legend-item">
                                <span class="bar-legend-color" style="background:#dc2626;"></span>
                                Seniors
                            </span>
                            <span class="bar-legend-item">
                                <span class="bar-legend-color" style="background:#6f42c1;"></span>
                                PWD
                            </span>
                        </div>
                        <div class="click-hint"><i class="bi bi-hand-index-thumb me-1"></i> Click any bar to view records</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h4>Certificates Issued</h4>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow cert-chart-card" style="border-top: 3px solid var(--gold) !important;">
                <div class="card-body">

                    <div class="cert-chart-header">
                        <div class="cert-chart-title">
                            <i class="bi bi-file-earmark-text-fill"></i>
                            <div class="cert-chart-title-text">
                                <div class="label">Documents Issued Over Time</div>
                                <div class="sub">Barangay clearances, certificates, permits &amp; IDs generated</div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center" style="gap:1.25rem;">
                            <div class="cert-chart-total">
                                <div class="num" id="certChartTotal">0</div>
                                <div class="lbl">Total Issued</div>
                            </div>
                            <div class="period-toggle" role="group" aria-label="Chart time range">
                                <button type="button" class="active" data-range="week">Week</button>
                                <button type="button" data-range="month">Month</button>
                                <button type="button" data-range="year">Year</button>
                            </div>
                        </div>
                    </div>

                    <div class="cert-chart-container" id="certChartContainer">
                        <canvas id="certificatesChart"></canvas>
                        <div class="cert-chart-spinner"></div>
                        <div class="cert-chart-empty" id="certChartEmpty">
                            <i class="bi bi-inbox"></i>
                            <span class="msg">No documents issued in this period yet.</span>
                        </div>
                    </div>

                    <div class="cert-legend-grid" id="certChartLegend"></div>

                </div>
            </div>
        </div>
    </div>
  

</div>
<!-- End of Main Content -->

<br><br>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>

<script>
// ─── GENDER PIE CHART ──────────────────────────────────────────
(function() {
    var ctx = document.getElementById('genderPieChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Male', 'Female'],
            datasets: [{
                data: [<?= (int)$rescountm ?>, <?= (int)$rescountf ?>],
                backgroundColor: ['#5996FF', '#FFADEE'],
                borderColor: ['#fff','#fff'],
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            var total = ctx.dataset.data.reduce(function(a,b){return a+b;},0);
                            var pct = total > 0 ? Math.round(ctx.parsed / total * 100) : 0;
                            return ' ' + ctx.label + ': ' + ctx.parsed + ' (' + pct + '%)';
                        }
                    }
                }
            }
        }
    });

    document.getElementById('genderPieChart').addEventListener('click', function(e) {
        var points = chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
        if (points.length === 0) return;
        var links = [
            'admn_table_maleres.php',
            'admn_table_femaleres.php'
        ];
        if (links[points[0].index]) {
            window.location.href = links[points[0].index];
        }
    });

    document.getElementById('genderPieChart').style.cursor = 'pointer';
    window.genderChart = chart;
})();

// ─── HORIZONTAL BAR CHART ──────────────────────────────────────
(function() {
    var ctx = document.getElementById('residentBarChart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Households', 'Voters', 'Seniors', 'PWD'],
            datasets: [{
                label: 'Resident Count',
                data: [
                    <?= (int)$rescountfh ?>,
                    <?= (int)$rescountvoter ?>,
                    <?= (int)$rescountsenior ?>,
                    <?= (int)$rescountpwd ?>
                ],
                backgroundColor: [
                    'rgba(13, 148, 136, 0.85)',
                    'rgba(201, 148, 58, 0.85)',
                    'rgba(220, 38, 38, 0.85)',
                    'rgba(111, 66, 193, 0.85)'
                ],
                borderColor: [
                    '#0d9488', '#c9943a', '#dc2626', '#6f42c1'
                ],
                borderWidth: 2,
                borderRadius: 6,
                barThickness: 35
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(ctx) {
                            return ' ' + ctx.parsed.x.toLocaleString() + ' residents';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { 
                        font: { size: 11 },
                        stepSize: 1
                    },
                    beginAtZero: true
                },
                y: {
                    grid: { display: false },
                    ticks: { 
                        font: { size: 12, weight: '600' }
                    }
                }
            },
            animation: {
                duration: 800,
                easing: 'easeInOutQuart'
            }
        }
    });

    // ── Click handler for bar chart ──
    document.getElementById('residentBarChart').addEventListener('click', function(e) {
        var points = chart.getElementsAtEventForMode(e, 'nearest', { intersect: true }, true);
        if (points.length === 0) return;
        
        var links = [
            'admn_table_totalhouse.php',   // Households
            'admn_table_voters.php',        // Voters
            'admn_table_senior.php',        // Seniors
            'admn_table_pwd.php'           // PWD
        ];
        
        var index = points[0].index;
        if (links[index]) {
            window.location.href = links[index];
        }
    });

    document.getElementById('residentBarChart').style.cursor = 'pointer';
    window.residentBarChart = chart;
})();

// ─── CERTIFICATES ISSUED — GROUPED BAR CHART ────────────────────
(function() {
    var ctx          = document.getElementById('certificatesChart').getContext('2d');
    var container     = document.getElementById('certChartContainer');
    var emptyState    = document.getElementById('certChartEmpty');
    var legendEl       = document.getElementById('certChartLegend');
    var totalEl        = document.getElementById('certChartTotal');
    var toggleButtons  = document.querySelectorAll('.period-toggle button');
    var currentRange   = 'week';
    var certChart       = null;

    function buildLegend(datasets) {
        legendEl.innerHTML = '';
        datasets.forEach(function(ds, i) {
            var item = document.createElement('span');
            item.className = 'cert-legend-item';
            item.innerHTML =
                '<span class="cert-legend-color" style="background:' + ds.backgroundColor + ';"></span>' +
                ds.label;
            item.style.cursor = 'pointer';
            item.addEventListener('click', function() {
                var meta = certChart.getDatasetMeta(i);
                meta.hidden = meta.hidden === null ? !certChart.data.datasets[i].hidden : !meta.hidden;
                item.style.opacity = meta.hidden ? '0.4' : '1';
                certChart.update();
            });
            legendEl.appendChild(item);
        });
    }

    function renderChart(payload) {
        var labels   = payload.labels || [];
        var datasets = (payload.datasets || []).map(function(ds) {
            return {
                label: ds.label,
                data: ds.data,
                backgroundColor: ds.color,
                borderRadius: 4,
                borderSkipped: false,
                maxBarThickness: 34
            };
        });

        totalEl.textContent = (payload.total || 0).toLocaleString();

        var hasData = datasets.some(function(ds) {
            return ds.data.some(function(v) { return v > 0; });
        });
        emptyState.classList.toggle('show', !hasData);

        if (certChart) {
            certChart.data.labels = labels;
            certChart.data.datasets = datasets;
            certChart.update();
        } else {
            certChart = new Chart(ctx, {
                type: 'bar',
                data: { labels: labels, datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(ctxTip) {
                                    return ' ' + ctxTip.dataset.label + ': ' + ctxTip.parsed.y.toLocaleString();
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { size: 11 }, color: '#4a5568' }
                        },
                        y: {
                            beginAtZero: true,
                            grid: { color: 'rgba(0,0,0,0.05)' },
                            ticks: { precision: 0, font: { size: 11 }, color: '#4a5568' }
                        }
                    },
                    animation: { duration: 700, easing: 'easeInOutQuart' }
                }
            });
        }

        buildLegend(datasets);
    }

    function loadRange(range) {
        currentRange = range;
        container.classList.add('is-loading');

        fetch('admn_certificates_data.php?range=' + encodeURIComponent(range), { cache: 'no-store' })
            .then(function(res) { return res.json(); })
            .then(function(payload) {
                renderChart(payload);
            })
            .catch(function(err) {
                console.error('Failed to load certificates chart data:', err);
            })
            .finally(function() {
                container.classList.remove('is-loading');
            });
    }

    toggleButtons.forEach(function(btn) {
        btn.addEventListener('click', function() {
            if (btn.classList.contains('active')) return;
            toggleButtons.forEach(function(b) { b.classList.remove('active'); });
            btn.classList.add('active');
            loadRange(btn.getAttribute('data-range'));
        });
    });

    // Initial load
    loadRange(currentRange);

    // Refresh alongside the live-stats poller (every 60s) without hammering the endpoint
    setInterval(function() { loadRange(currentRange); }, 60000);

    window.certificatesChart = certChart;
})();
</script>
<script src="js/live-stats.js"></script>
<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>