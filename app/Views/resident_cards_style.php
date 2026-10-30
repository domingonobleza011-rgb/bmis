<?php
if (!function_exists('format_resident_address')) {
    function format_resident_address(array $row): string {
        $parts = array_filter(array_map('trim', [$row['houseno'] ?? '', $row['street'] ?? '', $row['brgy'] ?? '', $row['municipal'] ?? '', $row['province'] ?? '']), fn($p) => $p !== '');
        return !empty($parts) ? implode(', ', $parts) : trim($row['address'] ?? '');
    }
}
?>
<?php
if (!function_exists('res_val')) {
    function res_val($v): string {
        $v = trim((string)$v);
        return $v === '' ? '—' : htmlspecialchars($v);
    }
    /** Age in whole years computed from the birth date (null if missing/invalid/future). */
    function res_age($bdate): ?int {
        $bdate = trim((string)$bdate);
        if ($bdate === '' || $bdate === '0000-00-00') return null;
        try {
            $tz   = new DateTimeZone('Asia/Manila');
            $born = new DateTime($bdate, $tz);
            $now  = new DateTime('now', $tz);
            if ($born > $now) return null;
            return (int)$born->diff($now)->y;
        } catch (Throwable $e) {
            return null;
        }
    }
    function res_full_name(array $r): string {
        $mi = trim((string)($r['mi'] ?? ''));
        return trim($r['lname'] . ', ' . $r['fname'] . ($mi !== '' ? ' ' . $mi : ''));
    }
}
?>
<style>
.res-grid { display: grid; gap: 16px; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); }
.res-card {
    position: relative; background: #fff; border: 1px solid #e8ecf0; border-radius: 14px;
    box-shadow: 0 2px 8px rgba(15,45,90,.07); padding: 16px; cursor: pointer;
    transition: box-shadow .2s ease, border-color .2s ease;
}
.res-card:hover { box-shadow: 0 6px 24px rgba(15,45,90,.12); border-color: #c9d6ea; }
.res-card-top { display: flex; align-items: center; gap: 12px; }
.res-avatar {
    width: 48px; height: 48px; border-radius: 50%; flex-shrink: 0; color: #fff; font-weight: 700; font-size: 1.1rem;
    background: linear-gradient(135deg, #0f2d5a, #2b5ea7); display: flex; align-items: center; justify-content: center;
}
.res-avatar { overflow: hidden; }
.res-avatar.lg { width: 56px; height: 56px; font-size: 1.3rem; }
.res-id { min-width: 0; flex: 1; }
.res-name { font-weight: 700; color: #1a1a2e; font-size: .95rem; line-height: 1.25; word-break: break-word; }
.res-sub  { font-size: .75rem; color: #8894a8; margin-top: 2px; }
.res-actions { flex-shrink: 0; align-self: flex-start; }
.res-actions form { margin: 0 !important; }
.res-actions .dropdown > .btn {
    width: 32px !important; height: 32px !important; padding: 0 !important; border-radius: 50% !important;
    display: inline-flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #b8c4d6 !important;
}
.res-actions .dropdown > .btn i { font-size: 1rem; color: #25365a; }
.res-meta { margin-top: 12px; padding-top: 10px; border-top: 1px solid #e8ecf0; display: grid; gap: 6px; font-size: .8rem; color: #4a5568; }
.res-meta div { display: flex; gap: 8px; align-items: flex-start; }
.res-meta i { width: 14px; text-align: center; color: #8894a8; margin-top: 3px; flex-shrink: 0; }
.res-meta .res-text { min-width: 0; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
.res-chip { display: inline-block; font-size: .68rem; font-weight: 700; padding: 2px 9px; border-radius: 999px; background: #e8eef7; color: #0f2d5a; margin-right: 4px; }
.res-chip.head { background: #fdf3e3; color: #b9832c; }
.res-empty { text-align: center; color: #718096; padding: 40px 0; font-style: italic; }

/* info modal */
.res-info-head { background: linear-gradient(135deg, #0f2d5a, #1a4480); color: #fff; padding: 18px 20px; display: flex; align-items: center; gap: 14px; }
.res-info-head .res-avatar { background: rgba(255,255,255,.18); }
.res-info-head h5 { margin: 0; color: #fff !important; font-weight: 700; }
.res-info-head small { opacity: .8; }
.res-info-section { font-size: .72rem; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; color: #8894a8; margin: 4px 0 10px; }
.res-info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 18px; margin-bottom: 18px; }
.res-info-grid .full { grid-column: 1 / -1; }
.res-info-grid .lbl { font-size: .72rem; color: #8894a8; margin-bottom: 1px; }
.res-info-grid .val { font-size: .9rem; color: #1a1a2e; font-weight: 600; word-break: break-word; }
@media (max-width: 480px) { .res-info-grid { grid-template-columns: 1fr; } }

/* Light / Dark theme (the admin theme blanks backgrounds & colours) */
html[data-theme] .res-card { background: var(--tm-surface) !important; border: 1px solid var(--tm-border) !important; box-shadow: var(--tm-shadow) !important; }
html[data-theme] .res-card:hover { background: var(--tm-hover) !important; }
html[data-theme] .res-name { color: var(--tm-head) !important; }
html[data-theme] .res-sub, html[data-theme] .res-meta i, html[data-theme] .res-info-grid .lbl, html[data-theme] .res-info-section { color: var(--tm-muted) !important; }
html[data-theme] .res-meta { border-color: var(--tm-border) !important; }
html[data-theme] .res-avatar { background: var(--tm-icon-active) !important; color: #fff !important; }
html[data-theme="dark"] .res-avatar { background: #4f46e5 !important; }
html[data-theme] .res-info-head { background: var(--tm-icon-active) !important; }
html[data-theme="dark"] .res-info-head { background: #312e81 !important; }
html[data-theme] .res-info-head h5, html[data-theme] .res-info-head small { color: #fff !important; }
html[data-theme] .res-info-head .res-avatar { background: rgba(255,255,255,.2) !important; }
html[data-theme] .res-info-grid .val { color: var(--tm-head) !important; }
html[data-theme] .res-chip { background: var(--tm-surface2) !important; color: var(--tm-head) !important; }
html[data-theme] .res-actions .dropdown > .btn { background: var(--tm-surface2) !important; border-color: var(--tm-border) !important; }
html[data-theme] .res-actions .dropdown > .btn i { color: var(--tm-head) !important; }
</style>
