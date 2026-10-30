(function () {
    var THEME_KEY = 'bmis_theme', SIZE_KEY = 'bmis_fontsize', FONT_KEY = 'bmis_font';
    var FONTS = ["default", "nunito", "inter", "poppins", "roboto", "opensans", "lato", "montserrat", "merriweather", "serif", "verdana", "mono"];
    var ICONS = { 'default': 'bi-circle-half', light: 'bi-sun-fill', dark: 'bi-moon-stars-fill' };
    var root = document.documentElement;

    function get(key, allowed, dflt) {
        try { var v = localStorage.getItem(key); return allowed.indexOf(v) > -1 ? v : dflt; } catch (e) { return dflt; }
    }
    function set(key, val, dflt) {
        try { if (val === dflt) localStorage.removeItem(key); else localStorage.setItem(key, val); } catch (e) {}
    }
    function mark(attr, value) {
        document.querySelectorAll('[' + attr + ']').forEach(function (a) {
            a.classList.toggle('active-theme', a.getAttribute(attr) === value);
        });
    }

    function paint() {
        var theme = get(THEME_KEY, ['light', 'dark'], 'default');
        var size  = get(SIZE_KEY, ['small', 'medium', 'large'], 'medium');
        var font  = get(FONT_KEY, FONTS, 'default');

        if (theme === 'default') root.removeAttribute('data-theme'); else root.setAttribute('data-theme', theme);
        if (size === 'medium') root.removeAttribute('data-fontsize'); else root.setAttribute('data-fontsize', size);
        if (font === 'default') root.removeAttribute('data-font'); else root.setAttribute('data-font', font);

        document.querySelectorAll('[data-rtm-theme-icon]').forEach(function (i) {
            i.className = 'bi ' + ICONS[theme];
        });
        mark('data-theme-choice', theme);
        mark('data-size-choice', size);
        mark('data-font-choice', font);
        window.dispatchEvent(new Event('resize'));
    }

    function closeAll(except) {
        document.querySelectorAll('.rtm-wrap.open').forEach(function (w) {
            if (w !== except) { w.classList.remove('open'); var b = w.querySelector('[data-rtm-toggle]'); if (b) b.setAttribute('aria-expanded', 'false'); }
        });
    }

    document.addEventListener('click', function (e) {
        var toggle = e.target.closest('[data-rtm-toggle]');
        if (toggle) {
            e.preventDefault();
            var wrap = toggle.closest('.rtm-wrap');
            var open = !wrap.classList.contains('open');
            closeAll(wrap);
            wrap.classList.toggle('open', open);
            toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            return;
        }
        var t = e.target.closest('[data-theme-choice]');
        if (t) { e.preventDefault(); set(THEME_KEY, t.getAttribute('data-theme-choice'), 'default'); paint(); return; }
        var s = e.target.closest('[data-size-choice]');
        if (s) { e.preventDefault(); set(SIZE_KEY, s.getAttribute('data-size-choice'), 'medium'); paint(); return; }
        var f = e.target.closest('[data-font-choice]');
        if (f) { e.preventDefault(); set(FONT_KEY, f.getAttribute('data-font-choice'), 'default'); paint(); return; }
        if (!e.target.closest('.rtm-menu')) closeAll(null);
    });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeAll(null); });

    // Keep other open tabs in sync
    window.addEventListener('storage', function (e) {
        if (e.key === THEME_KEY || e.key === SIZE_KEY || e.key === FONT_KEY) paint();
    });

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', paint); else paint();
})();
