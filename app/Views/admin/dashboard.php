include('dashboard_sidebar_start.php');
?>

<!-- Begin Page Content -->
<div class="container-fluid">

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
    <!-- ══ END CERTIFICATES ISSUED ══ -->
    <hr>
    <br>

    
    <!-- ══════════════════════════════════════ -->
    <!--  RESIDENT COMPLAINTS SECTION          -->
    <!-- ══════════════════════════════════════ -->
    <br>
    <hr>
    <br>

    <div class="row">
        <div class="col-12">
            <h4>Resident Complaints</h4>
        </div>
    </div>

    <div class="row mt-3">

        <!-- Total Complaints -->
        <div class="col-md-4 mb-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Total Complaints
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark" data-live="cmp_total"><?= $complaint_total ?></div>
                            <br>
                            <a href="admn_complaints.php">View All</a>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-megaphone-fill" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Complaints -->
        <div class="col-md-4 mb-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pending Complaints
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark" data-live="cmp_pending"><?= $complaint_pending ?></div>
                            <br>
                            <a href="admn_complaints.php?status=pending" class="text-warning fw-semibold">Review Now</a>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clock-history" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resolved Complaints -->
        <div class="col-md-4 mb-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Resolved Complaints
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-dark" data-live="cmp_resolved"><?= $complaint_resolved ?></div>
                            <br>
                            <a href="admn_complaints.php?status=resolved" class="text-success">View Resolved</a>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-check-circle-fill" style="font-size:2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- ══ END COMPLAINTS ══ -->

    <!-- ══════════════════════════════════════ -->
    <!--  CERTIFICATES ISSUED SECTION          -->
    <!-- ══════════════════════════════════════ -->
    <br>
    <hr>
    <br>

    

<!-- /.container-fluid -->
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
<?php include('dashboard_sidebar_end.php'); ?>