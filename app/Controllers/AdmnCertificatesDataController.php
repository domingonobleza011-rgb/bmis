<?php
/**
 * admn_certificates_data.php
 * ─────────────────────────────────────────────────────────────
 * Lightweight JSON endpoint that powers the "Certificates Issued"
 * grouped bar chart on admn_dashboard.php.
 *
 * Source of truth: tbl_activity_log, action = 'GENERATE_DOCUMENT'
 * (already written on every certificate/permit/ID generation by
 *  rescert_form.php, indigency_form.php, brgyclearance_form.php,
 *  businesspermit_form.php and barangayid_form.php via log_activity()).
 *
 * GET params:
 *   range = week | month | year   (default: week)
 *
 * Response shape (Chart.js friendly):
 * {
 *   "range": "week",
 *   "labels": ["Jul 7–13", "Jul 14–20", ...],
 *   "datasets": [
 *       { "label": "Certificate of Residency", "data": [3,5,2,...] },
 *       { "label": "Certificate of Indigency",  "data": [1,0,4,...] },
 *       ...
 *   ],
 *   "total": 42
 * }
 */

error_reporting(0);
define('BMIS_ROLE_REQUIRED', 'admin_dashboard');
require(ROOT_PATH . '/secure_header.php');
require_once ROOT_PATH . '/classes/conn.php'; // provides $conn (PDO)

header('Content-Type: application/json');
header('Cache-Control: no-store');

date_default_timezone_set('Asia/Manila');

// ── Validate requested range ─────────────────────────────────
$range = $_GET['range'] ?? 'week';
if (!in_array($range, ['week', 'month', 'year'], true)) {
    $range = 'week';
}

// How many buckets to show per range
$bucketCounts = ['week' => 8, 'month' => 6, 'year' => 5];
$bucketCount  = $bucketCounts[$range];

$now = new DateTime('now');

// ── Build the ordered list of empty buckets + figure out the earliest datetime to query ──
$buckets    = []; // key => label
$rangeStart = null;

if ($range === 'week') {
    $mondayThisWeek = clone $now;
    $mondayThisWeek->modify('monday this week');

    for ($i = $bucketCount - 1; $i >= 0; $i--) {
        $ws = clone $mondayThisWeek;
        $ws->modify("-{$i} week");
        $we = clone $ws;
        $we->modify('+6 day');

        $key   = $ws->format('Y-m-d');
        $label = $ws->format('M j') . '–' . $we->format('j');
        $buckets[$key] = $label;

        if ($rangeStart === null) $rangeStart = clone $ws;
    }
} elseif ($range === 'month') {
    for ($i = $bucketCount - 1; $i >= 0; $i--) {
        $d = clone $now;
        $d->modify('first day of this month');
        $d->modify("-{$i} month");

        $key = $d->format('Y-m');
        $buckets[$key] = $d->format('M Y');

        if ($rangeStart === null) $rangeStart = clone $d;
    }
} else { // year
    for ($i = $bucketCount - 1; $i >= 0; $i--) {
        $y = (int)$now->format('Y') - $i;
        $buckets[(string)$y] = (string)$y;

        if ($rangeStart === null) $rangeStart = new DateTime("$y-01-01");
    }
}

$rangeStart->setTime(0, 0, 0);

// ── Helper: compute which bucket key a given DateTime falls into ──
function bucket_key_for(DateTime $dt, string $range): string {
    if ($range === 'week') {
        $d = clone $dt;
        $day = (int)$d->format('N'); // 1 (Mon) .. 7 (Sun)
        if ($day > 1) $d->modify('-' . ($day - 1) . ' day');
        $d->setTime(0, 0, 0);
        return $d->format('Y-m-d');
    } elseif ($range === 'month') {
        return $dt->format('Y-m');
    }
    return $dt->format('Y');
}

// ── Friendly display names / colors for known document types ──
// (falls back gracefully to whatever string is actually stored in `module`)
$knownColors = [
    'Certificate of Residency'  => '#1a4480', // navy-light
    'Certificate of Indigency'  => '#c9943a', // gold
    'Barangay Clearance'        => '#0d9488', // teal
    'Business Permit'           => '#dc2626', // danger
    'Barangay ID'               => '#6f42c1', // purple
];
$fallbackPalette = ['#2b5ea7', '#e8b86d', '#059669', '#d97706', '#0891b2', '#be185d'];

try {
    $stmt = $conn->prepare("
        SELECT module, created_at
        FROM tbl_activity_log
        WHERE action = 'GENERATE_DOCUMENT'
          AND created_at >= :start
        ORDER BY created_at ASC
    ");
    $stmt->execute([':start' => $rangeStart->format('Y-m-d H:i:s')]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $rows = [];
}

// ── Tally counts: [module][bucketKey] = count ──
$tally   = [];
$modules = []; // preserve first-seen order, known types first

foreach (array_keys($knownColors) as $km) {
    $modules[$km] = true;
}

foreach ($rows as $row) {
    $mod = trim($row['module']) !== '' ? trim($row['module']) : 'Other Document';
    $modules[$mod] = true;

    try {
        $dt = new DateTime($row['created_at']);
    } catch (Exception $e) {
        continue;
    }
    $bkey = bucket_key_for($dt, $range);

    if (!isset($tally[$mod])) $tally[$mod] = [];
    if (!isset($tally[$mod][$bkey])) $tally[$mod][$bkey] = 0;
    $tally[$mod][$bkey]++;
}

// ── Only keep modules that actually have at least one record in range ──
$activeModules = [];
foreach (array_keys($modules) as $mod) {
    $hasData = isset($tally[$mod]) && array_sum($tally[$mod]) > 0;
    if ($hasData) $activeModules[] = $mod;
}

// If nothing at all was logged yet, still show the 5 known categories at zero
// so the chart renders a friendly empty state instead of looking broken.
if (empty($activeModules)) {
    $activeModules = array_keys($knownColors);
}

// ── Build Chart.js dataset payload ──
$labels     = array_values($buckets);
$bucketKeys = array_keys($buckets);
$datasets   = [];
$colorIdx   = 0;
$total      = 0;

foreach ($activeModules as $mod) {
    $data = [];
    foreach ($bucketKeys as $bkey) {
        $c = $tally[$mod][$bkey] ?? 0;
        $data[] = (int)$c;
        $total += (int)$c;
    }
    $color = $knownColors[$mod] ?? $fallbackPalette[$colorIdx++ % count($fallbackPalette)];
    $datasets[] = [
        'label' => $mod,
        'data'  => $data,
        'color' => $color,
    ];
}

echo json_encode([
    'range'    => $range,
    'labels'   => $labels,
    'datasets' => $datasets,
    'total'    => $total,
]);
