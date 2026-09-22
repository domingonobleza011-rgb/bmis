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
                    <a href="logout.php" class="btn btn-danger">Yes, log out</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <script>
        function showLogoutConfirm(e) {
            if (e) e.preventDefault();
            $('#logoutConfirmModal').modal('show');
            return false;
        }
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