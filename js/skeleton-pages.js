/* Per-page skeleton screens.
   SkeletonPages.kindFor(href, scope)  -> skeleton kind for the page being opened
   SkeletonPages.render(el, href, scope) -> fills the loader element with that skeleton
   scope: 'public' | 'admin' | 'resident'  (decides the fallback for unknown pages) */
(function () {
    var S = function (c) { return '<span class="sk ' + (c || '') + '"></span>'; };
    var rep = function (n, f) { var o = ''; for (var i = 0; i < n; i++) o += f(i); return o; };
    var field = function () { return '<div class="skp-field">' + S('skp-lb') + S('skp-in') + '</div>'; };
    var head = function () { return S('skp-h') + S('skp-s'); };

    var KINDS = {
        // ---- public pages ----
        login: function () {
            return '<div class="skp-auth">' + S('skp-logo') + S('skp-h') + S('skp-s') +
                field() + field() + S('skp-btn') + S('skp-link') + S('skp-link') + '</div>';
        },
        register: function () {
            return '<div class="skp-auth wide">' + S('skp-logo') + S('skp-h') + S('skp-s') +
                '<div class="skp-grid g2">' + rep(10, field) + '</div>' + S('skp-btn') + S('skp-link') + '</div>';
        },
        forgot: function () {
            return '<div class="skp-auth">' + S('skp-logo') + S('skp-h') + S('skp-s') +
                field() + S('skp-btn') + S('skp-link') + '</div>';
        },
        landing: function () {
            return '<div class="skp-land">' + S('skp-nav') +
                '<div class="skp-hero"><div>' + S('big') + S('big w80') + S('skp-t w60') + S('skp-t w80') +
                '<div class="skp-row" style="margin-top:18px">' + S('skp-btn') + S('skp-btn') + '</div></div>' +
                S('skp-hero-img') + '</div>' +
                '<div class="skp-grid g3">' + rep(3, function () {
                    return '<div class="skp-card">' + S('skp-media') + S('skp-t w60') + S('skp-t') + S('skp-t w80') + '</div>';
                }) + '</div></div>';
        },
        // ---- app pages (admin + resident) ----
        dashboard: function () {
            return head() +
                '<div class="skp-grid g4" style="margin-bottom:16px">' + rep(4, function () {
                    return '<div class="skp-card skp-stat" style="margin:0">' + S() + S() + S() + '</div>';
                }) + '</div>' +
                '<div class="skp-dash-split"><div class="skp-card">' + S('skp-t w30') + S('skp-chart') + '</div>' +
                '<div class="skp-card">' + S('skp-t w40') + S('skp-donut') + '</div></div>' +
                '<div class="skp-card">' + S('skp-t w30') + rep(3, function () { return S('skp-t'); }) + '</div>';
        },
        table: function () {
            var cols = rep(6, function () { return S(); });
            return head() +
                '<div class="skp-toolbar">' + S('skp-in') + S('skp-btn') + '</div>' +
                '<div class="skp-card"><div class="skp-thead">' + cols + '</div>' +
                rep(8, function () { return '<div class="skp-tr">' + S('dot') + rep(5, function () { return S(); }) + '</div>'; }) + '</div>';
        },
        form: function () {
            return head() + '<div class="skp-card">' + S('skp-t w30') +
                '<div class="skp-grid g2" style="margin-top:14px">' + rep(6, field) + '</div>' +
                '<div class="skp-field">' + S('skp-lb') + S('skp-ta') + '</div>' +
                '<div style="margin-top:16px">' + S('skp-btn') + '</div></div>';
        },
        cards: function () {
            return head() + '<div class="skp-grid g3">' + rep(6, function () {
                return '<div class="skp-card" style="margin:0">' + S('skp-media') + S('skp-t w60') + S('skp-t') + S('skp-t w80') + '</div>';
            }) + '</div>';
        },
        chat: function () {
            var contact = function () {
                return '<div class="skp-row skp-contact">' + S('skp-av') + '<div class="grow">' + S('skp-t w60') + S('skp-t w40') + '</div></div>';
            };
            return head() + '<div class="skp-chat"><div class="skp-card">' + rep(6, contact) + '</div>' +
                '<div class="skp-card">' + S('skp-bubble') + S('skp-bubble me') + S('skp-bubble') + S('skp-bubble me') +
                S('skp-bubble') + '<div style="position:relative;margin-top:24px">' + S('skp-in') + '</div></div></div>';
        },
        feed: function () {
            var card = '<div class="skp-card">' + S() + S() + S() + '</div>';
            var post = '<div class="skp-card"><div class="skp-row" style="margin-bottom:14px">' + S('skp-av') +
                '<div class="grow">' + S('skp-t w40') + S('skp-t w30') + '</div></div>' +
                S('skp-t') + S('skp-t w80') + S('skp-t w60') + '</div>';
            return head() + '<div class="skp-feed-cards">' + card + card + card + '</div>' + post + post + post;
        },
        profile: function () {
            return '<div class="skp-card" style="padding-top:0;overflow:hidden">' + S('skp-cover') + S('skp-avatar-lg') +
                S('skp-h') + S('skp-s') + '</div>' +
                '<div class="skp-card">' + S('skp-t w30') + '<div class="skp-grid g2" style="margin-top:14px">' +
                rep(6, field) + '</div></div>';
        }
    };
    var PUBLIC = { login: 1, register: 1, forgot: 1, landing: 1 };

    // exact page -> kind
    var MAP = {
        'login.php': 'login', 'logout.php': 'login',
        'resident_registration.php': 'register',
        'forgot_password.php': 'forgot', 'setup_security_question.php': 'forgot',
        'index.php': 'landing', '': 'landing',
        'admn_dashboard.php': 'dashboard', 'sk_dashboard.php': 'dashboard',
        'admn_messages.php': 'chat', 'resident_messages.php': 'chat',
        'admn_settings.php': 'form', 'admin_changepass.php': 'form', 'add_admin.php': 'form',
        'admn_staff_crud.php': 'table',
        'admn_budget.php': 'cards', 'sk_programs.php': 'cards', 'sk_announcements.php': 'cards',
        'admn_announcement_crud.php': 'cards',
        'resident_homepage.php': 'feed', 'resident_announcement.php': 'feed',
        'resident_profile.php': 'profile',
        'resident_budget.php': 'cards', 'resident_programs.php': 'cards',
        'resident_changepass.php': 'form', 'resident_complaint.php': 'form',
        'resident_youth_profile.php': 'form', 'sk_enrollment.php': 'form'
    };

    function kindFor(href, scope) {
        var path = '';
        try { path = new URL(href || location.href, location.href).pathname; } catch (e) { path = location.pathname; }
        var file = path.split('/').pop().toLowerCase();
        if (MAP.hasOwnProperty(file)) return MAP[file];
        if (/^services_|_form\.php$/.test(file)) return 'form';          // service request forms
        if (/^admn_|^sk_/.test(file)) return 'table';                     // every other admin list page
        return scope === 'resident' ? 'feed' : (scope === 'public' ? 'login' : 'table');
    }

    function render(el, href, scope) {
        if (!el) return;
        var kind = kindFor(href, scope);
        if (el.__skKind === kind && el.querySelector('.skp')) return;
        el.__skKind = kind;
        var old = el.querySelector('.skp');
        if (old) old.remove();
        var w = document.createElement('div');
        // sk-wrap (app layouts) / sk-auth (public) keep the existing "only show the skeleton" CSS rules working
        w.className = 'skp skp-' + kind + (scope === 'public' ? ' sk-auth' : ' sk-wrap');
        if (PUBLIC[kind]) w.className = 'skp skp-' + kind + (scope === 'public' ? ' sk-auth' : ' sk-wrap skp-bare');
        w.innerHTML = KINDS[kind]();
        el.appendChild(w);
        // public skeletons that appear inside admin/resident (e.g. logout -> login) cover the whole screen
        el.classList.toggle('skp-full', !!PUBLIC[kind] && scope !== 'public');
    }

    window.SkeletonPages = { kindFor: kindFor, render: render };
})();
