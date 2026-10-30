/* SK Portal — mobile helpers
   1) Tag data tables so CSS can render them as stacked cards on phones.
   2) Drawer niceties: scroll lock, Esc to close, close when a link is tapped. */
(function () {
    'use strict';

    function tagTables() {
        document.querySelectorAll('#content table').forEach(function (t) {
            if (t.classList.contains('sk-cards') || t.closest('.modal, .swal2-container')) return;
            var heads = t.querySelectorAll('thead th');
            if (heads.length < 3) return;                 // tiny 1-2 column tables: leave alone
            var labels = Array.prototype.map.call(heads, function (th) {
                return (th.textContent || '').replace(/\s+/g, ' ').trim();
            });
            t.querySelectorAll('tbody tr').forEach(function (tr) {
                var i = 0;
                Array.prototype.forEach.call(tr.children, function (td) {
                    if (td.tagName !== 'TD' && td.tagName !== 'TH') return;
                    var span = parseInt(td.getAttribute('colspan') || '1', 10) || 1;
                    if (span === 1) {
                        var label = labels[i] || '';
                        if (!label && td.querySelector('input[type=checkbox]')) label = 'Select';
                        td.setAttribute('data-label', label === '#' ? 'No.' : label);
                        if (!td.querySelector(':scope > .sk-val')) {
                            var box = document.createElement('div');
                            box.className = 'sk-val';
                            while (td.firstChild) box.appendChild(td.firstChild);   // moves nodes: listeners survive
                            td.appendChild(box);
                        }
                        if (/^(program|description|remarks)$/i.test(label)) td.classList.add('sk-wide');
                    }
                    i += span;
                });
            });
            t.classList.add('sk-cards');
        });
    }

    /* Three-dots menu + "View" details modal for tables marked .sk-rowmenu (phones only).
       The original buttons stay in the DOM and are triggered from the menu. */
    function ensureViewModal() {
        var m = document.getElementById('skRowViewModal');
        if (m) return m;
        m = document.createElement('div');
        m.className = 'modal fade'; m.id = 'skRowViewModal'; m.tabIndex = -1;
        m.innerHTML = '<div class="modal-dialog modal-dialog-centered"><div class="modal-content">' +
            '<div class="modal-header"><h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Details</h5>' +
            '<button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>' +
            '<div class="modal-body"><dl class="sk-detail-list mb-0"></dl></div>' +
            '<div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>' +
            '</div></div>';
        document.body.appendChild(m);
        return m;
    }
    function showRowDetails(tr) {
        var m = ensureViewModal(), dl = m.querySelector('dl');
        dl.innerHTML = '';
        Array.prototype.forEach.call(tr.children, function (td) {
            var label = td.getAttribute('data-label');
            if (!label || label === 'Actions' || label === 'Select' || td.classList.contains('sk-menu-cell')) return;
            var row = document.createElement('div'), dt = document.createElement('dt'), dd = document.createElement('dd');
            dt.textContent = label;
            dd.innerHTML = (td.querySelector('.sk-val') || td).innerHTML;
            dd.querySelectorAll('button, .dropdown').forEach(function (n) { n.remove(); });
            row.appendChild(dt); row.appendChild(dd); dl.appendChild(row);
        });
        bootstrap.Modal.getOrCreateInstance(m).show();
    }
    function menuLabel(el) {
        if (el.classList.contains('edit-btn')) return ['Edit', 'fa-edit', ''];
        if (el.classList.contains('del-btn')) return ['Delete', 'fa-trash', 'text-danger'];
        var h = el.getAttribute('href') || '';
        if (/enrollment/.test(h)) return ['Participants', 'fa-users', ''];
        return [(el.getAttribute('title') || 'Open'), 'fa-arrow-right', ''];
    }
    function buildRowMenus() {
        document.querySelectorAll('table.sk-rowmenu tbody tr').forEach(function (tr) {
            var td = tr.lastElementChild;
            if (!td || td.hasAttribute('colspan') || td.querySelector('.sk-dots')) return;
            var btns = td.querySelectorAll('.action-btn');
            if (!btns.length) return;
            td.classList.add('sk-menu-cell');
            var wrap = document.createElement('div');
            wrap.className = 'dropdown d-md-none sk-dots';
            var html = '<button type="button" class="dots-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions"><i class="fas fa-ellipsis-v"></i></button>' +
                '<ul class="dropdown-menu dropdown-menu-end shadow"><li><button type="button" class="dropdown-item" data-act="view"><i class="fas fa-eye fa-fw me-2"></i>View</button></li>';
            Array.prototype.forEach.call(btns, function (b, i) {
                var l = menuLabel(b);
                html += '<li><button type="button" class="dropdown-item ' + l[2] + '" data-act="' + i + '"><i class="fas ' + l[1] + ' fa-fw me-2"></i>' + l[0] + '</button></li>';
            });
            wrap.innerHTML = html + '</ul>';
            wrap.addEventListener('click', function (e) {
                var it = e.target.closest('[data-act]');
                if (!it) return;
                if (it.getAttribute('data-act') === 'view') showRowDetails(tr);
                else btns[parseInt(it.getAttribute('data-act'), 10)].click();
            });
            var box = td.querySelector('.sk-val') || td;
            box.appendChild(wrap);
        });
    }

    function drawer() {
        var sb = document.querySelector('.sidebar');
        if (!sb) return;
        function sync() {
            var open = sb.classList.contains('toggled') && window.innerWidth <= 991.98;
            document.body.classList.toggle('sk-drawer-open', open);
        }
        if (window.MutationObserver) new MutationObserver(sync).observe(sb, { attributes: true, attributeFilter: ['class'] });
        window.addEventListener('resize', sync);
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && sb.classList.contains('toggled')) sb.classList.remove('toggled');
        });
        // swipe left on the open drawer to close it
        var x0 = null;
        sb.addEventListener('touchstart', function (e) { x0 = e.touches[0].clientX; }, { passive: true });
        sb.addEventListener('touchend', function (e) {
            if (x0 === null) return;
            if (x0 - e.changedTouches[0].clientX > 60) sb.classList.remove('toggled');
            x0 = null;
        }, { passive: true });
        sync();
    }

    function init() { tagTables(); buildRowMenus(); drawer(); }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
    else init();
})();
