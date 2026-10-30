/* Skeleton loading screen for the public pages (login, register, forgot password).
   Shows the skeleton of the page being opened (needs js/skeleton-pages.js loaded first):
   tapping "Register here" shows the register skeleton, "Back to Login" the login skeleton, etc.
   Page-owned showLoader() calls (form submits) show the skeleton of the current page. */
(function () {
    function init() {
        var el = document.getElementById('page-loader');
        if (!el) {
            el = document.createElement('div');
            el.id = 'page-loader';
            document.body.appendChild(el);
        }
        function draw(href) { if (window.SkeletonPages) SkeletonPages.render(el, href || location.href, 'public'); }
        draw(location.href);
        // page-owned loader (form submit) adds .show itself -> make sure the current page's skeleton is in place
        new MutationObserver(function () {
            if (el.classList.contains('show') && !el.__skNav) draw(location.href);
        }).observe(el, { attributes: true, attributeFilter: ['class'] });

        var timer = null;
        function show(href) {
            el.__skNav = true;
            draw(href);
            el.classList.add('show');
            clearTimeout(timer);
            timer = setTimeout(function () { el.classList.remove('show'); el.__skNav = false; }, 12000); // failsafe
        }
        // Any internal link that leaves this page ("Register here", "Back to Login", ...)
        document.addEventListener('click', function (e) {
            var a = e.target.closest && e.target.closest('a[href]');
            if (!a || e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button > 0) return;
            var href = a.getAttribute('href');
            if (!href || href.charAt(0) === '#' || /^(javascript:|mailto:|tel:)/i.test(href)) return;
            if (a.hasAttribute('data-bs-toggle') || a.hasAttribute('data-no-loader') || a.hasAttribute('download')) return;
            if (a.target && a.target !== '_self') return;
            if (a.origin && a.origin !== location.origin) return;
            if (a.pathname === location.pathname && a.search === location.search) return;
            show(a.href);
        });
        window.addEventListener('pageshow', function () { el.classList.remove('show'); el.__skNav = false; draw(location.href); });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
