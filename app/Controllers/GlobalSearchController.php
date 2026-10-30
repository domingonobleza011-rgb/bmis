<?php
/**
 * Global (centralized) search for the admin portal topbar.
 *
 * GET global_search.php?q=juan+cruz
 *
 * - Discovers every table in the current database through INFORMATION_SCHEMA,
 *   so new tables/columns are searched automatically (no schema list to maintain).
 * - Searches only text-like columns, and never touches credential / token /
 *   image-path columns (see $BLOCKED_COLUMN_RE) or internal tables
 *   (see $SKIP_TABLES).
 * - Multi-word queries: every word must match somewhere in the row (AND),
 *   each word may match any searchable column (OR).
 * - Read-only, prepared statements only, returns JSON.
 */
if (!defined('ROOT_PATH')) { define('ROOT_PATH', dirname(__DIR__, 2)); }

define('BMIS_ROLE_REQUIRED', 'staff');
require ROOT_PATH . '/secure_header.php';          // session + auth ($userdetails)
require ROOT_PATH . '/classes/conn.php';           // $conn (PDO)

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

// ── Config ──────────────────────────────────────────────────────────────────
const GS_MIN_LEN        = 2;    // minimum characters in the query
const GS_MAX_TERMS      = 4;    // max words considered
const GS_PER_TABLE      = 5;    // rows returned per table
const GS_MAX_TABLES     = 30;   // safety cap on tables scanned
const GS_SNIPPET_LEN    = 90;

// Tables that are never searched (secrets, tokens, internal bookkeeping).
$SKIP_TABLES = [
    'tbl_fcm_tokens', 'tbl_login_attempts', 'tbl_id_uploads',
    'tbl_hidden_announcements', 'tbl_announcement_reactions',
];
// Tables only full administrators may search (staff accounts may not).
$ADMIN_ONLY_TABLES = [
    'tbl_admin', 'tbl_user', 'tbl_activity_log',
    'tbl_password_reset_requests', 'tbl_pw_requests',
];
// Columns that are never searched or shown.
$BLOCKED_COLUMN_RE = '/pass|pwd|token|secret|hash|otp|salt|security|answer|signature|photo|image|img|picture|avatar|qr|file|path|ip_addr|session|fcm|key/i';

// table => [label, page, icon]
$TABLE_MAP = [
    'tbl_resident'          => ['Residents',            'admn_resident_crud.php',            'fa-users'],
    'tbl_residents'         => ['Residents',            'admn_resident_crud.php',            'fa-users'],
    'tbl_resident_pending'  => ['Pending Residents',    'admn_resident_pending.php',         'fa-user-clock'],
    'tbl_family_members'    => ['Family Members',       'admn_resident_crud.php',            'fa-people-roof'],
    'tbl_user'              => ['Staff Accounts',       'admn_staff_crud.php',               'fa-user-tie'],
    'tbl_staff'             => ['Staff',                'admn_staff_crud.php',               'fa-user-tie'],
    'tbl_admin'             => ['Administrators',       'admn_staff_crud.php',               'fa-user-shield'],
    'tbl_youth'             => ['Youth Profiles',       'admn_youth_profile.php',            'fa-child'],
    'tbl_youth_programs'    => ['Youth Programs',       'admn_youth_profile.php',            'fa-calendar-check'],
    'tbl_youth_enrollment'  => ['Youth Enrollment',     'admn_youth_profile.php',            'fa-clipboard-list'],
    'tbl_youth_bulletin'    => ['Youth Bulletin',       'admn_youth_profile.php',            'fa-bullhorn'],
    'tbl_rescert'           => ['Certificate of Residency',   'admn_certofres.php',          'fa-file-alt'],
    'tbl_goodmoral'         => ['Certificate of Good Moral',  'admn_certofgoodmoral.php',    'fa-file-alt'],
    'tbl_livestock'         => ['Livestock Certificate',      'admn_certoflivestock.php',    'fa-file-alt'],
    'tbl_guardianship'      => ['Guardianship Certificate',   'admn_certofguardianship.php', 'fa-file-alt'],
    'tbl_soloparent'        => ['Solo Parent Certificate',    'admn_soloparent.php',         'fa-file-alt'],
    'tbl_indigency'         => ['Certificate of Indigency',   'admn_certofindigency.php',    'fa-file-alt'],
    'tbl_brgyid'            => ['Barangay ID',          'admn_brgyid.php',                   'fa-id-card'],
    'tbl_bspermit'          => ['Business Permit',      'admn_bspermit.php',                 'fa-store'],
    'tbl_clearance'         => ['Barangay Clearance',   'admn_brgyclearance.php',            'fa-file-signature'],
    'tbl_blotter'           => ['Blotter',              'admn_complaints.php',               'fa-gavel'],
    'tbl_complaints'        => ['Complaints',           'admn_complaints.php',               'fa-comment-dots'],
    'tbl_announcement'      => ['Announcements',        'admn_announcement_crud.php',        'fa-bullhorn'],
    'tbl_announcement_comments' => ['Announcement Comments', 'admn_announcement_crud.php',   'fa-comments'],
    'tbl_budget'            => ['Budget',               'admn_budget.php',                   'fa-coins'],
    'tbl_archive'           => ['Archive',              'admn_archive.php',                  'fa-box-archive'],
    'tbl_activity_log'      => ['Activity Logs',        'admn_activity_logs.php',            'fa-clock-rotate-left'],
    'tbl_password_reset_requests' => ['Password Reset Requests', 'admn_password_reset_requests.php', 'fa-key'],
    'tbl_pw_requests'       => ['Password Reset Requests', 'admn_password_reset_requests.php', 'fa-key'],
];

// ── Input ───────────────────────────────────────────────────────────────────
$q = trim((string)($_GET['q'] ?? ''));
$q = preg_replace('/\s+/u', ' ', $q);
if (function_exists('mb_substr')) { $q = mb_substr($q, 0, 100); } else { $q = substr($q, 0, 100); }

$len = function_exists('mb_strlen') ? mb_strlen($q) : strlen($q);
if (!isset($_GET['view']) && $len < GS_MIN_LEN) {
    echo json_encode(['q' => $q, 'groups' => [], 'total' => 0, 'hint' => 'Type at least ' . GS_MIN_LEN . ' characters']);
    exit;
}

$terms = array_slice(array_values(array_filter(explode(' ', $q), 'strlen')), 0, GS_MAX_TERMS);
$likeTerms = array_map(function ($t) {
    return '%' . addcslashes($t, '\\%_') . '%';
}, $terms);

$isFullAdmin = in_array($userdetails['role'] ?? '', ['administrator', 'Admin'], true);

// ── Discover searchable tables / columns ────────────────────────────────────
$cols = $conn->query(
    "SELECT TABLE_NAME, COLUMN_NAME, DATA_TYPE, COLUMN_KEY
       FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME IN (SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
                            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_TYPE = 'BASE TABLE')
      ORDER BY TABLE_NAME, ORDINAL_POSITION"
)->fetchAll(PDO::FETCH_ASSOC);

$schema = [];   // table => ['search' => [cols], 'pk' => col|null]
foreach ($cols as $c) {
    $t = $c['TABLE_NAME'];
    if (!isset($schema[$t])) { $schema[$t] = ['search' => [], 'all' => [], 'pk' => null]; }
    if ($c['COLUMN_KEY'] === 'PRI' && $schema[$t]['pk'] === null) { $schema[$t]['pk'] = $c['COLUMN_NAME']; }
    if (!preg_match($BLOCKED_COLUMN_RE, $c['COLUMN_NAME'])) { $schema[$t]['all'][] = $c['COLUMN_NAME']; }
    if (!in_array(strtolower($c['DATA_TYPE']), ['char', 'varchar', 'tinytext', 'text', 'mediumtext', 'longtext', 'enum'], true)) { continue; }
    if (preg_match($BLOCKED_COLUMN_RE, $c['COLUMN_NAME'])) { continue; }
    $schema[$t]['search'][] = $c['COLUMN_NAME'];
}

// Known tables first (in $TABLE_MAP order), then any other table in the DB.
$ordered = [];
foreach (array_keys($TABLE_MAP) as $t) { if (isset($schema[$t])) { $ordered[] = $t; } }
foreach (array_keys($schema) as $t)    { if (!in_array($t, $ordered, true)) { $ordered[] = $t; } }

function gs_title(array $row, array $searchCols): string {
    $has = fn($k) => isset($row[$k]) && trim((string)$row[$k]) !== '';
    if ($has('fname') || $has('lname')) {
        $mi = $has('mi') ? ' ' . trim($row['mi']) : '';
        return trim(($row['fname'] ?? '') . $mi . ' ' . ($row['lname'] ?? ''));
    }
    foreach (['name', 'title', 'subject', 'program_name', 'business_name', 'description', 'email', 'action'] as $k) {
        if ($has($k)) { return (string)$row[$k]; }
    }
    foreach ($searchCols as $c) { if ($has($c)) { return (string)$row[$c]; } }
    return '(no title)';
}

function gs_clip(string $s): string {
    $s = trim(preg_replace('/\s+/u', ' ', strip_tags($s)));
    if (function_exists('mb_strlen') && mb_strlen($s) > GS_SNIPPET_LEN) { return mb_substr($s, 0, GS_SNIPPET_LEN) . '…'; }
    return $s;
}

function gs_nice(string $col): string {
    return ucwords(str_replace('_', ' ', preg_replace('/^(tbl_|id_)/', '', $col)));
}

// ── Single-record view: global_search.php?view=tbl_resident&id=12 ───────────
if (isset($_GET['view'])) {
    $t  = (string)$_GET['view'];
    $id = (string)($_GET['id'] ?? '');
    if (!isset($schema[$t]) || in_array($t, $SKIP_TABLES, true)
        || (in_array($t, $ADMIN_ONLY_TABLES, true) && !$isFullAdmin)
        || !$schema[$t]['pk'] || !$schema[$t]['all'] || $id === '') {
        http_response_code(404);
        echo json_encode(['error' => 'Record not available']);
        exit;
    }
    $pk = $schema[$t]['pk'];
    $st = $conn->prepare('SELECT `' . implode('`,`', $schema[$t]['all']) . "` FROM `$t` WHERE `$pk` = ? LIMIT 1");
    $st->execute([$id]);
    $row = $st->fetch(PDO::FETCH_ASSOC);
    if (!$row) { http_response_code(404); echo json_encode(['error' => 'Record not found']); exit; }
    $map = $TABLE_MAP[$t] ?? [gs_nice($t), null, 'fa-database'];
    $fields = [];
    foreach ($row as $k => $v) {
        if ($v === null || trim((string)$v) === '') { continue; }
        $fields[] = ['label' => gs_nice($k), 'value' => (string)$v];
    }
    echo json_encode([
        'label' => $map[0], 'url' => $map[1], 'icon' => $map[2],
        'title' => gs_title($row, $schema[$t]['search']),
        'fields' => $fields,
    ], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
    exit;
}

// ── Search ──────────────────────────────────────────────────────────────────
$groups = [];
$total  = 0;
$scanned = 0;

foreach ($ordered as $table) {
    if ($scanned >= GS_MAX_TABLES) { break; }
    if (in_array($table, $SKIP_TABLES, true)) { continue; }
    if (in_array($table, $ADMIN_ONLY_TABLES, true) && !$isFullAdmin) { continue; }
    $sc = $schema[$table]['search'];
    if (!$sc) { continue; }
    if (!preg_match('/^[A-Za-z0-9_]+$/', $table)) { continue; }
    $scanned++;

    // Build: (c1 LIKE ? OR c2 LIKE ? ...) AND (...) for each term
    $where = [];
    $params = [];
    foreach ($likeTerms as $lt) {
        $or = [];
        foreach ($sc as $c) { $or[] = "`$c` LIKE ?"; $params[] = $lt; }
        $where[] = '(' . implode(' OR ', $or) . ')';
    }
    $pk = $schema[$table]['pk'];
    $select = array_unique(array_merge($pk ? [$pk] : [], $sc));
    $sql = 'SELECT `' . implode('`,`', $select) . '` FROM `' . $table . '` WHERE ' . implode(' AND ', $where)
         . ($pk ? " ORDER BY `$pk` DESC" : '')
         . ' LIMIT ' . (GS_PER_TABLE + 1);

    try {
        $st = $conn->prepare($sql);
        $st->execute($params);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        continue; // never let one odd table break the whole search
    }
    if (!$rows) { continue; }

    $more  = count($rows) > GS_PER_TABLE;
    $rows  = array_slice($rows, 0, GS_PER_TABLE);
    $map   = $TABLE_MAP[$table] ?? [gs_nice($table), null, 'fa-database'];
    $items = [];

    foreach ($rows as $row) {
        // which columns matched? (show up to 2 as the snippet)
        $hits = [];
        foreach ($sc as $c) {
            $v = (string)($row[$c] ?? '');
            if ($v === '') { continue; }
            foreach ($terms as $t) {
                if (stripos($v, $t) !== false) { $hits[$c] = $v; break; }
            }
            if (count($hits) >= 2) { break; }
        }
        $snippet = [];
        foreach ($hits as $c => $v) { $snippet[] = gs_nice($c) . ': ' . gs_clip($v); }
        $items[] = [
            'id'      => $pk ? ($row[$pk] ?? null) : null,
            'title'   => gs_clip(gs_title($row, $sc)),
            'snippet' => implode('  ·  ', $snippet),
        ];
    }

    $total += count($items);
    $groups[] = [
        'table' => $table,
        'label' => $map[0],
        'url'   => $map[1],
        'icon'  => $map[2],
        'more'  => $more,
        'items' => $items,
    ];
}

echo json_encode(['q' => $q, 'groups' => $groups, 'total' => $total], JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
