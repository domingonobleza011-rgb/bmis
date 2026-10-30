<?php
/**
 * Mobile-only layout for the admin "Barangay Services" tables.
 *
 * On screens <= 767.98px:
 *   - only Actions (collapsed into a 3-dots menu), Status and Full Name are shown
 *   - every other column is hidden
 * On larger screens NOTHING changes (all rules live inside the media query;
 * the 3-dots button is display:none by default).
 *
 * Included once at the bottom of each admn_*_view.php service page.
 */
?>
<style>
/* 3-dots toggle: never visible on desktop */
.svcm-toggle { display: none; }

@media (max-width: 767.98px) {

    /* let the table fit the screen instead of forcing 1000px */
    table.svcm-table { min-width: 0 !important; width: 100% !important; }
    table.svcm-table th,
    table.svcm-table td { padding: 8px 6px !important; vertical-align: middle; font-size: 0.85rem; }

    /* hidden columns (everything except Actions / Status / Full Name) */
    table.svcm-table .svcm-hide { display: none !important; }

    /* Actions column */
    table.svcm-table .svcm-actcell { width: 46px; white-space: nowrap; text-align: center; }

    .svcm-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        padding: 0;
        border: 1px solid #dde3ee;
        border-radius: 50%;
        background: #fff;
        color: #4a5568;
        cursor: pointer;
    }
    .svcm-toggle:active,
    .svcm-toggle[aria-expanded="true"] { background: #e8eef7; color: #0f2d5a; }
    .svcm-toggle svg { width: 18px; height: 18px; fill: currentColor; pointer-events: none; }

    /* the original action buttons become a popup menu */
    table.svcm-table form.svcm-menu { display: none; margin: 0; }
    form.svcm-menu.svcm-open {
        display: block !important;
        position: fixed;
        z-index: 100000;
        min-width: 170px;
        max-width: calc(100vw - 16px);
        padding: 10px;
        background: #fff;
        border: 1px solid #e8ecf0;
        border-radius: 12px;
        box-shadow: 0 8px 28px rgba(15, 45, 90, 0.22);
    }
    form.svcm-menu.svcm-open .btn,
    form.svcm-menu.svcm-open a.btn {
        display: block;
        width: 100% !important;
        margin: 0 0 6px 0 !important;
        font-size: 15px !important;
        text-align: center;
    }
    form.svcm-menu.svcm-open .btn:last-child { margin-bottom: 0 !important; }
}
</style>

<script>
(function () {
    var mq = window.matchMedia('(max-width: 767.98px)');
    var openMenu = null;
    var DOTS = '<svg viewBox="0 0 16 16" aria-hidden="true"><circle cx="8" cy="2.5" r="1.6"/><circle cx="8" cy="8" r="1.6"/><circle cx="8" cy="13.5" r="1.6"/></svg>';

    function closeMenu() {
        if (!openMenu) return;
        openMenu.menu.classList.remove('svcm-open');
        openMenu.menu.style.top = openMenu.menu.style.left = '';
        openMenu.btn.setAttribute('aria-expanded', 'false');
        openMenu = null;
    }

    function positionMenu(btn, menu) {
        var r = btn.getBoundingClientRect();
        var w = menu.offsetWidth, h = menu.offsetHeight, pad = 8;
        var left = Math.min(Math.max(pad, r.left), window.innerWidth - w - pad);
        var top = r.bottom + 4;
        if (top + h > window.innerHeight - pad) top = Math.max(pad, r.top - h - 4);
        menu.style.left = left + 'px';
        menu.style.top = top + 'px';
    }

    function setup(table) {
        var headRow = table.querySelector('thead tr');
        if (!headRow) return;
        var ths = Array.prototype.slice.call(headRow.children);
        var actIdx = -1, statusIdx = -1, nameIdx = -1;
        ths.forEach(function (th, i) {
            var t = th.textContent.replace(/\s+/g, ' ').trim().toLowerCase();
            if (t === 'actions') actIdx = i;
            else if (t === 'status') statusIdx = i;
            else if (/^full ?name$/.test(t)) nameIdx = i;
        });
        if (actIdx < 0) return;                 // not a service table
        table.classList.add('svcm-table');

        // Message modals are sometimes nested inside table cells that get hidden
        // on mobile; move them to <body> so they still open.
        Array.prototype.slice.call(table.querySelectorAll('.modal')).forEach(function (m) {
            document.body.appendChild(m);
        });

        Array.prototype.slice.call(table.rows).forEach(function (row) {
            Array.prototype.slice.call(row.cells).forEach(function (cell, i) {
                if (i !== actIdx && i !== statusIdx && i !== nameIdx) {
                    cell.classList.add('svcm-hide');
                }
            });

            var actCell = row.cells[actIdx];
            if (!actCell) return;
            actCell.classList.add('svcm-actcell');
            var form = actCell.querySelector('form');
            if (!form || form.classList.contains('svcm-menu')) return;

            form.classList.add('svcm-menu');
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'svcm-toggle';
            btn.setAttribute('aria-label', 'Actions');
            btn.setAttribute('aria-haspopup', 'true');
            btn.setAttribute('aria-expanded', 'false');
            btn.innerHTML = DOTS;
            actCell.insertBefore(btn, form);

            btn.addEventListener('click', function (e) {
                e.stopPropagation();
                if (openMenu && openMenu.menu === form) { closeMenu(); return; }
                closeMenu();
                form.classList.add('svcm-open');
                btn.setAttribute('aria-expanded', 'true');
                openMenu = { btn: btn, menu: form };
                positionMenu(btn, form);
            });

            // close after choosing an action (deferred so the click/submit still fires)
            form.addEventListener('click', function (e) {
                if (e.target.closest('a, button')) setTimeout(closeMenu, 0);
            });
        });
    }

    function init() {
        Array.prototype.slice.call(document.querySelectorAll('table')).forEach(setup);
    }

    document.addEventListener('click', function (e) {
        if (openMenu && !openMenu.menu.contains(e.target)) closeMenu();
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeMenu(); });
    window.addEventListener('scroll', closeMenu, true);
    window.addEventListener('resize', closeMenu);
    (mq.addEventListener ? mq.addEventListener.bind(mq, 'change') : mq.addListener.bind(mq))(closeMenu);

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
</script>
