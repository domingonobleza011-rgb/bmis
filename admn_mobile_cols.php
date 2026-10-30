<?php
/**
 * Mobile-only column trimming for admin tables.
 * Usage (before including):  $mobile_keep_cols = ['actions', 'resident id', 'full name'];
 * Header text is matched case-insensitively. Desktop (>= 768px) is untouched.
 */
$mobile_keep_cols = $mobile_keep_cols ?? ['actions'];
?>
<style>
@media (max-width: 767.98px) {
    table.mcols-table { min-width: 0 !important; width: 100% !important; }
    table.mcols-table .mcols-hide { display: none !important; }
    table.mcols-table th,
    table.mcols-table td { padding: 8px 6px !important; font-size: 0.85rem; vertical-align: middle; }
}
</style>
<script>
(function () {
    var KEEP = <?= json_encode(array_values($mobile_keep_cols)) ?>;

    function setup(table) {
        var headRow = table.querySelector('thead tr');
        if (!headRow) return;
        var heads = Array.prototype.slice.call(headRow.children).map(function (th) {
            return th.textContent.replace(/\s+/g, ' ').trim().toLowerCase();
        });
        if (heads.indexOf('actions') < 0) return;
        if (!heads.some(function (h) { return KEEP.indexOf(h) > -1 && h !== 'actions'; })) return;

        table.classList.add('mcols-table');

        // modals nested in table cells would be hidden with their column -> move to <body>
        Array.prototype.slice.call(table.querySelectorAll('.modal')).forEach(function (m) {
            document.body.appendChild(m);
        });

        Array.prototype.slice.call(table.rows).forEach(function (row) {
            if (row.cells.length !== heads.length) return;   // e.g. "No records" colspan row
            Array.prototype.slice.call(row.cells).forEach(function (cell, i) {
                if (KEEP.indexOf(heads[i]) < 0) cell.classList.add('mcols-hide');
            });
        });
    }

    function init() {
        Array.prototype.slice.call(document.querySelectorAll('table')).forEach(setup);
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
</script>
