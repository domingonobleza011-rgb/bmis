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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">Are you sure you want to log out?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
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
        #page-nav-loader {
            position: fixed;
            inset: 0;
            background: rgba(15, 45, 90, 0.72);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 99999;
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 1.25rem;
        }
        #page-nav-loader.show { display: flex; }
        #page-nav-loader .spinner {
            width: 56px;
            height: 56px;
            border: 4px solid rgba(255,255,255,0.25);
            border-top-color: #c9943a;
            border-radius: 50%;
            animation: page-nav-spin 0.85s linear infinite;
        }
        #page-nav-loader .loader-text {
            color: #fff;
            font-weight: 600;
            font-size: 1.05rem;
            letter-spacing: 0.3px;
        }
        @keyframes page-nav-spin { to { transform: rotate(360deg); } }
    </style>
    <div id="page-nav-loader">
        <div class="spinner"></div>
        <div class="loader-text" id="page-nav-loader-text">Loading...</div>
    </div>

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
            $('#logoutConfirmModal').modal('show');
            return false;
        }

        function showPageNavLoader(msg) {
            var loader = document.getElementById('page-nav-loader');
            var text = document.getElementById('page-nav-loader-text');
            if (text) text.textContent = msg || 'Loading...';
            if (loader) loader.classList.add('show');
        }

        // Show the loading overlay the moment logout is confirmed, then let
        // the link's normal navigation to logout.php proceed underneath it.
        document.getElementById('confirmLogoutBtn').addEventListener('click', function () {
            showPageNavLoader('Logging you out...');
        });

        // Show the same loading screen whenever a sidebar link (top-level
        // nav-links or collapse/dropdown sub-items) is clicked and about to
        // navigate to another admin page — e.g. Dashboard -> Budget
        // Transparency. Skip anything that doesn't actually leave the page
        // (collapse toggles, "#" placeholders) and modified/middle clicks
        // that open a new tab, since those shouldn't block the current one.
        document.querySelectorAll('.sidebar .nav-link, .sidebar .collapse-item').forEach(function (link) {
            link.addEventListener('click', function (e) {
                if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;

                var href = link.getAttribute('href');
                if (!href || href === '#' || href.indexOf('javascript:') === 0) return;
                if (link.hasAttribute('data-bs-toggle') || link.hasAttribute('data-toggle')) return;
                if (link.target && link.target !== '' && link.target !== '_self') return;

                showPageNavLoader('Loading...');
            });
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
                var loader = document.getElementById('page-nav-loader');
                if (loader) loader.classList.remove('show');
                location.reload();
            }
        });
    </script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
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

</body>

</html>