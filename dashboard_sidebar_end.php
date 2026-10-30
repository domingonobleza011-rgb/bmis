<!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout confirmation modal — same modal, same wording as the student
         portal's confirm, but shown via jQuery's Bootstrap 4 modal() call
         (this template is SB Admin 2 / Bootstrap 4 + jQuery, not Bootstrap 5,
         so data-bs-toggle doesn't apply here — jQuery is what's actually
         driving the sidebar/dropdowns already, so it's the reliable option). -->
    <div class="modal fade" id="logoutConfirmModal" tabindex="-1" role="dialog" aria-labelledby="logoutConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutConfirmModalLabel">Log out?</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to log out?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="logout.php" id="confirmLogoutBtn" class="btn btn-danger">Yes, log out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Page Navigation Loading Overlay — same look as the sign-in loader on
         login.php, reused here for logout AND for every sidebar link click,
         so switching between admin pages (e.g. Dashboard -> Budget
         Transparency) shows the same loading screen as signing in. -->
    <style>
        /* Default + Light: dimmed navy backdrop, white card, navy/gold spinner */
        #page-nav-loader {
            --pl-overlay: rgba(11, 30, 60, 0.62);
            --pl-card-bg: #ffffff;
            --pl-card-border: rgba(11, 43, 92, 0.12);
            --pl-card-shadow: 0 20px 60px rgba(0, 0, 0, 0.35);
            --pl-track: #dbe3f0;
            --pl-arc: #0b2b5c;
            --pl-arc2: #c9943a;
            --pl-glow: none;
            --pl-text: #0b2b5c;
            --pl-sub: #64748b;
            --pl-bar-bg: #e5eaf3;
            --pl-bar: linear-gradient(90deg, #0b2b5c, #c9943a);
            position: fixed;
            inset: 0;
            background: var(--pl-overlay);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
        }
        /* Dark: near-black backdrop, dark card, bright glowing spinner */
        html[data-theme="dark"] #page-nav-loader {
            --pl-overlay: rgba(0, 0, 0, 0.80);
            --pl-card-bg: #141414;
            --pl-card-border: rgba(255, 255, 255, 0.22);
            --pl-card-shadow: 0 0 0 1px rgba(255, 255, 255, 0.06), 0 20px 60px rgba(0, 0, 0, 0.85);
            --pl-track: rgba(255, 255, 255, 0.16);
            --pl-arc: #ffffff;
            --pl-arc2: #818cf8;
            --pl-glow: drop-shadow(0 0 8px rgba(165, 180, 252, 0.75));
            --pl-text: #ffffff;
            --pl-sub: #aab2c5;
            --pl-bar-bg: rgba(255, 255, 255, 0.14);
            --pl-bar: linear-gradient(90deg, #ffffff, #818cf8);
        }
        #page-nav-loader.show { display: flex; }
        #page-nav-loader .loader-card {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.1rem;
            min-width: 240px;
            padding: 2rem 2.5rem 1.75rem;
            background: var(--pl-card-bg);
            border: 1px solid var(--pl-card-border);
            border-radius: 18px;
            box-shadow: var(--pl-card-shadow);
        }
        #page-nav-loader .spinner {
            width: 68px;
            height: 68px;
            border: 6px solid var(--pl-track);
            border-top-color: var(--pl-arc);
            border-right-color: var(--pl-arc2);
            border-radius: 50%;
            filter: var(--pl-glow);
            animation: page-nav-spin 0.8s linear infinite;
        }
        #page-nav-loader .loader-text {
            color: var(--pl-text);
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.3px;
            text-align: center;
        }
        #page-nav-loader .loader-sub {
            color: var(--pl-sub);
            font-size: 0.8rem;
            margin-top: -0.6rem;
        }
        #page-nav-loader .loader-bar {
            width: 100%;
            height: 5px;
            border-radius: 99px;
            background: var(--pl-bar-bg);
            overflow: hidden;
        }
        #page-nav-loader .loader-bar::after {
            content: "";
            display: block;
            width: 40%;
            height: 100%;
            border-radius: 99px;
            background: var(--pl-bar);
            animation: page-nav-bar 1.1s ease-in-out infinite;
        }
        @keyframes page-nav-spin { to { transform: rotate(360deg); } }
        @keyframes page-nav-bar {
            0%   { transform: translateX(-110%); }
            100% { transform: translateX(260%); }
        }
    </style>
    <!-- Skeleton-screen loader: replaces the spinner overlay. It covers only the
         content area (sidebar + topbar stay visible) with shimmering placeholders. -->
    <style>
        #page-nav-loader, #page-loader {
            --sk-bg: #f3f6fb; --sk-card: #ffffff; --sk-line: #e5eaf3;
            --sk-base: #e4e9f2; --sk-shine: #f4f7fc;
            position: fixed !important; inset: auto !important;
            top: var(--sk-top, 64px) !important; left: var(--sk-left, 0px) !important;
            right: 0 !important; bottom: 0 !important;
            display: none; align-items: flex-start !important; justify-content: flex-start !important;
            background: var(--sk-bg) !important;
            backdrop-filter: none !important; -webkit-backdrop-filter: none !important;
            z-index: 1040 !important; overflow: hidden; padding: 0 !important; gap: 0 !important;
        }
        html[data-theme="dark"] #page-nav-loader, html[data-theme="dark"] #page-loader {
            --sk-bg: #0b0b0b; --sk-card: #000000; --sk-line: rgba(255,255,255,.12);
            --sk-base: #1c1c1c; --sk-shine: #2b2b2b;
        }
        html[data-theme="light"] #page-nav-loader, html[data-theme="light"] #page-loader {
            --sk-bg: #f8f9fc; --sk-line: #d9dfe9;
        }
        #page-nav-loader.show, #page-loader.show { display: block !important; }
        /* hide the old spinner / text, keep only the skeleton */
        #page-nav-loader > :not(.sk-wrap), #page-loader > :not(.sk-wrap) { display: none !important; }

        .sk-wrap { padding: 1.5rem 2rem; width: 100%; height: 100%; overflow: hidden; }
        /* !important: the admin theme blanks every background inside the page */
        .sk { display: block; border-radius: 8px; background-color: var(--sk-base) !important;
              background-image: linear-gradient(90deg, transparent 0, var(--sk-shine) 50%, transparent 100%) !important;
              background-size: 200px 100%; background-repeat: no-repeat; background-position: -200px 0;
              animation: sk-shimmer 1.3s ease-in-out infinite; }
        @keyframes sk-shimmer { to { background-position: calc(100% + 200px) 0; } }
        .sk-title { height: 30px; width: 260px; max-width: 70%; margin: 0 auto 10px; }
        .sk-sub   { height: 12px; width: 180px; max-width: 50%; margin: 0 auto 28px; }
        .sk-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 22px; }
        .sk-card  { background: var(--sk-card) !important; border: 1px solid var(--sk-line) !important; border-radius: 14px; padding: 18px; }
        .sk-stat .sk:nth-child(1) { height: 10px; width: 55%; margin-bottom: 14px; }
        .sk-stat .sk:nth-child(2) { height: 26px; width: 38%; margin-bottom: 14px; }
        .sk-stat .sk:nth-child(3) { height: 10px; width: 30%; }
        .sk-table-head { display: grid; grid-template-columns: 90px 2fr 1fr 1fr 2fr 1.5fr; gap: 16px; padding: 4px 0 14px; border-bottom: 1px solid var(--sk-line); margin-bottom: 6px; }
        .sk-row { display: grid; grid-template-columns: 90px 2fr 1fr 1fr 2fr 1.5fr; gap: 16px; padding: 13px 0; border-bottom: 1px solid var(--sk-line); align-items: center; }
        .sk-row:last-child { border-bottom: 0; }
        .sk-row .sk, .sk-table-head .sk { height: 14px; }
        .sk-table-head .sk { height: 11px; opacity: .8; }
        .sk-row .sk.sk-btn { height: 30px; width: 30px; border-radius: 50%; }
        @media (max-width: 991.98px) { .sk-stats { grid-template-columns: repeat(2, 1fr); } .sk-wrap { padding: 1rem; } }
        @media (max-width: 575.98px) {
            .sk-table-head, .sk-row { grid-template-columns: 60px 1fr 1fr; }
            .sk-table-head .sk:nth-child(n+4), .sk-row .sk:nth-child(n+4) { display: none; }
        }

        /* thin top bar for quick background (AJAX) requests — no content swap */
        #page-async-bar { position: fixed; top: 0; left: 0; right: 0; height: 3px; z-index: 100000; display: none; overflow: hidden; background: transparent; }
        #page-async-bar.show { display: block; }
        #page-async-bar::after { content: ""; display: block; width: 35%; height: 100%; background: linear-gradient(90deg, #0b2b5c, #c9943a); animation: page-nav-bar 1s ease-in-out infinite; }
        html[data-theme="dark"] #page-async-bar::after { background: linear-gradient(90deg, #ffffff, #818cf8); }
    </style>
    <div id="page-async-bar"></div>
    <div id="page-nav-loader">
        <div class="loader-card">
            <div class="spinner"></div>
            <div class="loader-text" id="page-nav-loader-text">Loading...</div>
            <div class="loader-sub">Please wait</div>
            <div class="loader-bar"></div>
        </div>
    </div>

    <link rel="stylesheet" href="css/skeleton-pages.css?v=20261026c">
    <script src="js/skeleton-pages.js?v=20261026c"></script>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Re-assert Bootstrap 5 as window.bootstrap: the vendor bundle above is
         Bootstrap 4.3.1 and overwrites the v5 build loaded in <head>, which
         breaks any page using the modern `new bootstrap.Modal(...)` API
         (its modal never gets a backdrop). jQuery's .modal()/.dropdown()
         plugins from the vendor bundle are unaffected by this and keep
         working for legacy data-toggle/data-target pages. -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showLogoutConfirm(e) {
            if (e) e.preventDefault();
            bootstrap.Modal.getOrCreateInstance(document.getElementById('logoutConfirmModal')).show();
            return false;
        }

        /* ------------------------------------------------------------
           Global loader — one overlay for EVERY feature:
             • page navigation (sidebar + any in-page link)
             • form submits (add / edit / delete / search / filters)
             • fetch() and jQuery AJAX calls (shown only if a request
               takes longer than ASYNC_DELAY, so quick calls don't flash)
           Background polling (messages, stats, notifications) is
           excluded so it never pops the loader up on its own.
           Opt out per element with data-no-loader.
           ------------------------------------------------------------ */
        var ASYNC_DELAY = 350;      // ms before an AJAX call shows the loader
        var NAV_FAILSAFE = 12000;   // ms — hides a nav loader that never unloaded (e.g. file download)
        var BACKGROUND_URL = /(poll_stats|sse_stats|ajax_messages|ajax_complaints|ajax_resident_chat|admn_certificates_data|mark_notification_read|save_token|save_fcm_token|heartbeat|notif)/i;
        var FILE_LINK = /\.(pdf|docx?|xlsx?|csv|zip|rar|png|jpe?g|gif|webp|svg|txt)(\?|#|$)/i;

        var loaderEl = document.getElementById('page-nav-loader');
        var loaderTextEl = document.getElementById('page-nav-loader-text');
        var navTimer = null, asyncTimer = null, asyncPending = 0;

        function showPageNavLoader(msg, href) {
            if (loaderTextEl) loaderTextEl.textContent = msg || 'Loading...';
            if (loaderEl) {
                // skeleton of the page being opened (falls back to the current page)
                loaderEl.__skNav = true;
                SkeletonPages.render(loaderEl, href || location.href, 'admin');
                loaderEl.classList.add('show');
            }
        }
        function hidePageNavLoader() {
            if (loaderEl) { loaderEl.classList.remove('show'); loaderEl.__skNav = false; }
        }
        function showNavLoader(msg, href) {
            showPageNavLoader(msg, href);
            clearTimeout(navTimer);
            navTimer = setTimeout(hidePageNavLoader, NAV_FAILSAFE);
        }
        function asyncBegin(url) {
            if (url && BACKGROUND_URL.test(String(url))) return false;
            asyncPending++;
            if (asyncPending === 1) {
                asyncTimer = setTimeout(function () {
                    if (asyncPending > 0 && asyncBarEl) asyncBarEl.classList.add('show');
                }, ASYNC_DELAY);
            }
            return true;
        }
        function asyncEnd() {
            asyncPending = Math.max(0, asyncPending - 1);
            if (asyncPending === 0) {
                clearTimeout(asyncTimer);
                if (asyncBarEl) asyncBarEl.classList.remove('show');
            }
        }

        /* ---- Skeleton screen ---------------------------------------------- */
        function placeSkeleton() {
            // cover only the content area: right of the sidebar, below the topbar
            var cw = document.getElementById('content-wrapper');
            var tb = document.querySelector('.topbar');
            var left = 0, top = 0;
            if (cw) { var r = cw.getBoundingClientRect(); left = Math.max(0, r.left); }
            if (tb) { top = Math.max(0, tb.getBoundingClientRect().bottom); }
            document.documentElement.style.setProperty('--sk-left', left + 'px');
            document.documentElement.style.setProperty('--sk-top', top + 'px');
        }
        ['page-nav-loader', 'page-loader'].forEach(function (id) {
            var el = document.getElementById(id);
            if (!el) return;
            SkeletonPages.render(el, location.href, 'admin');
            new MutationObserver(function () {
                if (!el.classList.contains('show')) return;
                // page-owned #page-loader calls (no target link): show the current page's skeleton
                if (!el.__skNav) SkeletonPages.render(el, location.href, 'admin');
                placeSkeleton();
            }).observe(el, { attributes: true, attributeFilter: ['class'] });
        });
        var asyncBarEl = document.getElementById('page-async-bar');
        window.PageLoader = { show: showPageNavLoader, hide: hidePageNavLoader };

        // fetch()
        if (window.fetch) {
            var _fetch = window.fetch;
            window.fetch = function (input, init) {
                var url = (typeof input === 'string') ? input : (input && input.url);
                var tracked = asyncBegin(url);
                var p = _fetch.apply(this, arguments);
                if (tracked) p.then(asyncEnd, asyncEnd);
                return p;
            };
        }
        // jQuery AJAX
        if (window.jQuery) {
            jQuery(document).ajaxSend(function (ev, xhr, opts) {
                xhr.__pl = asyncBegin(opts && opts.url);
            }).ajaxComplete(function (ev, xhr) {
                if (xhr.__pl) asyncEnd();
            });
        }

        // Show the loading overlay the moment logout is confirmed, then let
        // the link's normal navigation to logout.php proceed underneath it.
        document.getElementById('confirmLogoutBtn').addEventListener('click', function () {
            showPageNavLoader('Logging you out...', 'logout.php');
        });

        // Any link that leaves the page (sidebar, tables, pagination, buttons
        // styled as links...). Skips toggles, "#" anchors, new tabs, downloads.
        document.addEventListener('click', function (e) {
            var link = e.target.closest && e.target.closest('a[href]');
            if (!link || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey || e.button > 0) return;
            setTimeout(function () {
                if (e.defaultPrevented || (loaderEl && loaderEl.classList.contains('show'))) return;
                var href = link.getAttribute('href');
                if (!href || href.charAt(0) === '#' || /^(javascript:|mailto:|tel:|blob:|data:)/i.test(href)) return;
                if (link.hasAttribute('data-toggle') || link.hasAttribute('data-bs-toggle') ||
                    link.hasAttribute('data-dismiss') || link.hasAttribute('data-bs-dismiss') ||
                    link.hasAttribute('download') || link.hasAttribute('data-no-loader')) return;
                if (link.target && link.target !== '' && link.target !== '_self') return;
                if (FILE_LINK.test(href)) return;
                if (link.origin && link.origin !== location.origin) return;
                if (link.pathname === location.pathname && link.search === location.search && link.hash) return;
                showNavLoader('Loading...', link.href);
            }, 0);
        });

        // Any form that actually submits (validation passed, not cancelled by
        // a page script that handles it via fetch/AJAX instead).
        document.addEventListener('submit', function (e) {
            var form = e.target;
            setTimeout(function () {
                if (e.defaultPrevented || !form || form.hasAttribute('data-no-loader')) return;
                var sub = e.submitter;
                var target = (sub && sub.getAttribute('formtarget')) || form.target;
                if (target && target !== '_self') return;
                var isPost = ((sub && sub.getAttribute('formmethod')) || form.method || 'get').toLowerCase() === 'post';
                var act = (sub && sub.getAttribute('formaction')) || form.getAttribute('action') || location.href;
                showNavLoader(isPost ? 'Processing...' : 'Loading...', act);
            }, 0);
        });

        // If the page is restored from back/forward cache (e.g. the user
        // hits the browser Back button, or taps a "Back to Admin" link that
        // the browser serves from its in-memory bfcache snapshot instead of
        // re-requesting from the server), two things can go stale:
        //  1) a stuck loader spinner from the previous click, and
        //  2) the page's actual data (e.g. a certificate's Status badge
        //     still showing "Not Generated" right after it was generated,
        //     because the snapshot was taken before that update happened).
        // Force a real reload in that case so the server-rendered data
        // (and headers/session state) are always fresh.
        window.addEventListener('pageshow', function (e) {
            if (e.persisted) {
                hidePageNavLoader();
                location.reload();
            }
        });
    </script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js?v=2"></script>
    <!-- PWA -->
    <script src="js/pwa.js"></script>

    <!--
        Fix: Bootstrap dropdown menus (e.g. the "Actions" menu in student tables)
        were getting clipped/cut off by the scrollable .table-responsive wrapper,
        and Bootstrap's own Popper positioning kept fighting our fix. Each toggle
        now has data-display="static" so Popper never touches these menus — we
        fully control their position ourselves, switching them to `position: fixed`
        computed from the toggle button's on-screen coordinates so they float on
        top of the table (like a native <select>) instead of getting clipped.

        Scoped to `.actions-dropdown-menu` only (:has(...) below) — the topbar's
        notification bell and user-info dropdowns also carry the generic
        `.dropdown` class, and pinning them to raw viewport coordinates the same
        way broke them (they'd render off-screen since the `position: fixed`
        CSS only targets `.actions-dropdown-menu`). Those two use Bootstrap's
        normal positioning untouched.
    -->
    <script>
    $(document).on('shown.bs.dropdown', '.dropdown:has(.actions-dropdown-menu)', function () {
        var $menu   = $(this).find('.actions-dropdown-menu').first();
        var $toggle = $(this).find('[data-toggle="dropdown"]').first();
        if (!$menu.length || !$toggle.length) return;

        $menu.addClass('dropdown-menu-floating');

        var rect = $toggle[0].getBoundingClientRect();
        var menuWidth = $menu.outerWidth();

        var left = rect.right - menuWidth;
        if (left < 4) left = 4; // don't run off the left edge of the screen

        var top = rect.bottom + 2;
        // If there isn't enough room below, open the menu upward instead
        var menuHeight = $menu.outerHeight();
        if (top + menuHeight > window.innerHeight && rect.top - menuHeight > 0) {
            top = rect.top - menuHeight - 2;
        }

        $menu.css({ top: top + 'px', left: left + 'px', right: 'auto' });
    });

    $(document).on('hidden.bs.dropdown', '.dropdown:has(.actions-dropdown-menu)', function () {
        $(this).find('.actions-dropdown-menu').removeClass('dropdown-menu-floating').css({ top: '', left: '', right: '' });
    });

    // Reposition while the dropdown is open if the page is scrolled/resized
    $(window).on('scroll resize', function () {
        $('.dropdown:has(.actions-dropdown-menu)').has('.dropdown-menu.show').trigger('shown.bs.dropdown');
    });
    </script>

<?php include __DIR__ . '/app/Views/partials/legal_widgets.php'; ?>
</body>

</html>