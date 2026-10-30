<?php
/**
 * Shared widgets, included once just before </body> on public + resident pages:
 *   1. Cookie / local-storage notice banner
 *   2. Consent enforcement for forms that contain a [data-bmis-consent] checkbox
 *   3. Accessibility helpers: label<->field linking, autocomplete hints,
 *      keyboard access for click-only elements, "skip to content" link
 * Safe to include more than once (it only outputs the first time).
 */
if (defined('BMIS_LEGAL_WIDGETS_LOADED')) { return; }
define('BMIS_LEGAL_WIDGETS_LOADED', true);
?>
<style>
/* ===== Cookie notice ===== */
#bmisCookieNotice {
    position: fixed; left: 12px; right: 12px;
    bottom: calc(var(--rnav-h, 0px) + 12px);
    z-index: 2200; max-width: 720px; margin: 0 auto;
    background: #ffffff; color: #1c1e21;
    border: 1px solid #dce0e4; border-radius: 14px;
    box-shadow: 0 8px 28px rgba(0,0,0,.22);
    padding: 14px 16px; font: 14px/1.45 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
}
#bmisCookieNotice[hidden] { display: none; }
#bmisCookieNotice h2 { font-size: 15px; font-weight: 700; margin: 0 0 4px; color: inherit; }
#bmisCookieNotice p { margin: 0 0 10px; color: inherit; }
#bmisCookieNotice a { color: #1b74e4; font-weight: 600; }
#bmisCookieNotice .bmis-cn-actions { display: flex; gap: 8px; flex-wrap: wrap; align-items: center; }
#bmisCookieNotice button {
    border: 0; border-radius: 999px; padding: 8px 18px; font-weight: 700; font-size: 14px; cursor: pointer;
    background: #1b74e4; color: #fff;
}
#bmisCookieNotice button:focus-visible, #bmisCookieNotice a:focus-visible { outline: 3px solid #f5a623; outline-offset: 2px; }
html[data-theme="dark"] #bmisCookieNotice { background: #1a1a1a; color: #f1f1f1; border-color: #333; }
html[data-theme="dark"] #bmisCookieNotice a { color: #7db7ff; }

/* ===== Consent checkbox error state ===== */
.bmis-consent input.is-invalid { outline: 2px solid #b3261e; outline-offset: 1px; }
html[data-theme="dark"] .bmis-consent-err { color: #ff8a80 !important; }

/* ===== Keyboard focus visibility (only for keyboard users) ===== */
a:focus-visible, button:focus-visible, [role="button"]:focus-visible, summary:focus-visible,
select:focus-visible, input[type="checkbox"]:focus-visible, input[type="radio"]:focus-visible,
input[type="file"]:focus-visible, [data-bmis-kb]:focus-visible {
    outline: 3px solid #f5a623; outline-offset: 2px;
}

/* ===== Skip link ===== */
.bmis-skip {
    position: fixed; left: 8px; top: -60px; z-index: 3000; background: #1b74e4; color: #fff !important;
    padding: 10px 16px; border-radius: 8px; font-weight: 700; text-decoration: none; transition: top .15s;
}
.bmis-skip:focus { top: 8px; }

/* ===== Small legal links strip (public pages only) ===== */
.bmis-legal-strip {
    position: fixed; left: 50%; transform: translateX(-50%); bottom: 6px; z-index: 900;
    background: rgba(255,255,255,.88); color: #444; border-radius: 999px; padding: 3px 14px;
    font: 12px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; white-space: nowrap;
    box-shadow: 0 1px 6px rgba(0,0,0,.15);
}
.bmis-legal-strip a { color: inherit; text-decoration: underline; margin: 0 4px; }
html[data-theme="dark"] .bmis-legal-strip { background: rgba(30,30,30,.9); color: #ddd; }

/* ===== Legal documents modal ===== */
.bmis-lm[hidden] { display: none; }
.bmis-lm { position: fixed; inset: 0; z-index: 2400; display: flex; align-items: center; justify-content: center; padding: 14px;
    --lm-bg:#fff; --lm-text:#1c1e21; --lm-muted:#5b6470; --lm-line:#dce0e4; --lm-brand:#1b74e4; --lm-mark:#fff3bf; --lm-chip:#eef1f5; }
html[data-theme="dark"] .bmis-lm { --lm-bg:#1a1a1a; --lm-text:#f1f1f1; --lm-muted:#a9b0ba; --lm-line:#333; --lm-brand:#7db7ff; --lm-mark:#5c4a00; --lm-chip:#262626; }
.bmis-lm-backdrop { position: absolute; inset: 0; background: rgba(0,0,0,.55); }
.bmis-lm-dialog { position: relative; width: 100%; max-width: 820px; max-height: 88vh; max-height: 88dvh; display: flex; flex-direction: column;
    background: var(--lm-bg); color: var(--lm-text); border: 1px solid var(--lm-line); border-radius: 16px; box-shadow: 0 16px 48px rgba(0,0,0,.4);
    font: 15px/1.6 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; outline: none; }
.bmis-lm-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 14px 10px 18px; border-bottom: 1px solid var(--lm-line); }
.bmis-lm-head strong { font-size: 1.05rem; }
.bmis-lm-x { flex: 0 0 auto; width: 38px; height: 38px; border: 0; border-radius: 50%; cursor: pointer; background: var(--lm-chip); color: var(--lm-text);
    display: inline-flex; align-items: center; justify-content: center; padding: 0; }
.bmis-lm-x svg { width: 18px; height: 18px; display: block; }
.bmis-lm-x:hover { background: var(--lm-line); }
.bmis-lm-tabs { display: flex; gap: 6px; flex-wrap: wrap; padding: 10px 14px 0 18px; }
.bmis-lm-tabs button { border: 0; border-radius: 999px; padding: 6px 14px; cursor: pointer; font-weight: 700; font-size: 13.5px; background: var(--lm-chip); color: var(--lm-text); }
.bmis-lm-tabs button[aria-pressed="true"] { background: var(--lm-brand); color: #fff; }
html[data-theme="dark"] .bmis-lm-tabs button[aria-pressed="true"] { color: #0d0d0d; }
.bmis-lm-body { flex: 1 1 auto; overflow-y: auto; -webkit-overflow-scrolling: touch; padding: 6px 20px 18px; overscroll-behavior: contain; }
.bmis-lm-foot { padding: 10px 14px; border-top: 1px solid var(--lm-line); display: flex; justify-content: flex-end; }
.bmis-lm-foot button { border: 0; border-radius: 999px; padding: 8px 22px; font-weight: 700; cursor: pointer; background: var(--lm-brand); color: #fff; }
html[data-theme="dark"] .bmis-lm-foot button { color: #0d0d0d; }
.bmis-lm button:focus-visible, .bmis-lm a:focus-visible, .bmis-lm-body:focus-visible { outline: 3px solid #f5a623; outline-offset: 2px; }
.bmis-lm-body a { color: var(--lm-brand); }
.bmis-lm-doc-loading, .bmis-lm-doc-error { padding: 24px 4px; color: var(--lm-muted); }
/* document typography inside the modal */
.bmis-legal-doc > h1 { display: none; }
.bmis-legal-doc h2 { margin: 1.4em 0 .4em; font-size: 1.08rem; border-bottom: 1px solid var(--lm-line); padding-bottom: .25em; }
.bmis-legal-doc .meta { color: var(--lm-muted); font-size: .88rem; margin: .6em 0 1em; }
.bmis-legal-doc table { width: 100%; border-collapse: collapse; margin: .6em 0 1em; font-size: .9rem; }
.bmis-legal-doc th, .bmis-legal-doc td { border: 1px solid var(--lm-line); padding: 7px 9px; text-align: left; vertical-align: top; }
.bmis-legal-doc th { background: rgba(127,127,127,.12); }
.bmis-legal-doc .tw { overflow-x: auto; }
.bmis-legal-doc mark.ph { background: var(--lm-mark); color: inherit; padding: 0 4px; border-radius: 4px; }
.bmis-legal-doc .note { background: rgba(27,116,228,.08); border-left: 4px solid var(--lm-brand); padding: 9px 13px; border-radius: 6px; margin: 1em 0; }
.bmis-legal-doc code { font-size: .85em; }
@media (max-width: 600px) { .bmis-lm { padding: 0; align-items: flex-end; } .bmis-lm-dialog { max-height: 92vh; max-height: 92dvh; border-radius: 16px 16px 0 0; } .bmis-lm-body { padding: 4px 14px 14px; } }
</style>

<div id="bmisCookieNotice" role="region" aria-label="Cookie notice" hidden>
    <h2>We use cookies and similar storage</h2>
    <p>
        This site uses a session cookie so you can stay signed in, and stores your display preferences
        (such as theme and text size) on your device. We do <strong>not</strong> use advertising or tracking cookies.
        <a href="/cookie-policy">Cookie Policy</a> &middot; <a href="/privacy">Privacy Notice</a>
    </p>
    <div class="bmis-cn-actions">
        <button type="button" id="bmisCookieOk">I understand</button>
    </div>
</div>

<div id="bmisLegalModal" class="bmis-lm" hidden>
    <div class="bmis-lm-backdrop" data-lm-close></div>
    <div class="bmis-lm-dialog" role="dialog" aria-modal="true" aria-labelledby="bmisLmTitle" tabindex="-1">
        <div class="bmis-lm-head">
            <strong id="bmisLmTitle">Privacy Notice</strong>
            <button type="button" class="bmis-lm-x" data-lm-close aria-label="Close">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true"><path d="M3 3l10 10M13 3L3 13"/></svg>
            </button>
        </div>
        <div class="bmis-lm-tabs" role="group" aria-label="Legal documents">
            <button type="button" data-lm-doc="/privacy">Privacy Notice</button>
            <button type="button" data-lm-doc="/terms">Terms</button>
            <button type="button" data-lm-doc="/cookie-policy">Cookie Policy</button>
        </div>
        <div class="bmis-lm-body" id="bmisLmBody" tabindex="0" aria-live="polite"></div>
        <div class="bmis-lm-foot"><button type="button" data-lm-close>Close</button></div>
    </div>
</div>

<?php if (!empty($bmis_legal_links)): ?>
<nav class="bmis-legal-strip" aria-label="Legal">
    <a href="/privacy">Privacy Notice</a>&middot;<a href="/terms">Terms</a>&middot;<a href="/cookie-policy">Cookie Policy</a>
</nav>
<?php endif; ?>

<script>
(function () {
    'use strict';

    /* ---------- 1. Cookie notice ---------- */
    var KEY = 'bmis_cookie_notice';
    var box = document.getElementById('bmisCookieNotice');
    var seen = false;
    try { seen = localStorage.getItem(KEY) === '1'; } catch (e) {}
    if (box && !seen) { box.hidden = false; }
    var ok = document.getElementById('bmisCookieOk');
    if (ok) ok.addEventListener('click', function () {
        try { localStorage.setItem(KEY, '1'); } catch (e) {}
        box.hidden = true;
    });

    /* ---------- 1b. Legal documents modal ---------- */
    var LM = document.getElementById('bmisLegalModal');
    var DOCS = { '/privacy': 'Privacy Notice', '/terms': 'Terms and Conditions', '/cookie-policy': 'Cookie Policy' };
    var docCache = {}, lmOpener = null, lmPrevOverflow = '', lmCurrent = '';

    function docPath(a) {
        try {
            var u = new URL(a.getAttribute('href'), window.location.href);
            if (u.origin !== window.location.origin) return '';
            var p = u.pathname.replace(/\.php$/, '').replace(/\/+$/, '');
            return DOCS[p] ? p : '';
        } catch (e) { return ''; }
    }
    function lmShow(path) {
        lmCurrent = path;
        var body = document.getElementById('bmisLmBody');
        document.getElementById('bmisLmTitle').textContent = DOCS[path];
        LM.querySelectorAll('[data-lm-doc]').forEach(function (b) {
            b.setAttribute('aria-pressed', b.getAttribute('data-lm-doc') === path ? 'true' : 'false');
        });
        body.scrollTop = 0;
        if (docCache[path]) { body.innerHTML = docCache[path]; return; }
        body.innerHTML = '<div class="bmis-lm-doc-loading">Loading&hellip;</div>';
        fetch(path + '?modal=1', { credentials: 'same-origin' })
            .then(function (r) { if (!r.ok) throw new Error(r.status); return r.text(); })
            .then(function (t) {
                docCache[path] = t;
                if (lmCurrent === path) body.innerHTML = t;
            })
            .catch(function () {
                if (lmCurrent === path) body.innerHTML = '<div class="bmis-lm-doc-error">Sorry, this document could not be loaded. ' +
                    '<a href="' + path + '" target="_blank" rel="noopener">Open it as a page</a>.</div>';
            });
    }
    function lmOpen(path, opener) {
        if (!LM) return false;
        if (LM.hidden) {
            lmOpener = opener || document.activeElement;
            lmPrevOverflow = document.documentElement.style.overflow;
            document.documentElement.style.overflow = 'hidden';
            LM.hidden = false;
        }
        lmShow(path);
        var dlg = LM.querySelector('.bmis-lm-dialog');
        var x = LM.querySelector('.bmis-lm-x');
        try { (x || dlg).focus(); } catch (e) {}
        return true;
    }
    function lmClose() {
        if (!LM || LM.hidden) return;
        LM.hidden = true;
        document.documentElement.style.overflow = lmPrevOverflow;
        if (lmOpener && lmOpener.focus) { try { lmOpener.focus(); } catch (e) {} }
        lmOpener = null;
    }
    if (LM) {
        document.addEventListener('click', function (e) {
            if (e.defaultPrevented || e.button !== 0 || e.ctrlKey || e.metaKey || e.shiftKey || e.altKey) return;
            var a = e.target && e.target.closest ? e.target.closest('a[href]') : null;
            if (!a) return;
            var path = docPath(a);
            if (!path) return;
            e.preventDefault();
            lmOpen(path, a);
        }, true);
        LM.addEventListener('click', function (e) {
            var t = e.target;
            if (!t || !t.closest) return;
            if (t.closest('[data-lm-close]')) { lmClose(); return; }
            var tab = t.closest('[data-lm-doc]');
            if (tab) lmShow(tab.getAttribute('data-lm-doc'));
        });
        // Bootstrap modals pull focus back into themselves; let focus move freely inside the legal viewer
        window.addEventListener('focusin', function (e) {
            if (!LM.hidden && LM.contains(e.target)) e.stopPropagation();
        }, true);
        document.addEventListener('keydown', function (e) {
            if (LM.hidden) return;
            if (e.key === 'Escape') { e.preventDefault(); e.stopPropagation(); lmClose(); return; }
            if (e.key === 'Tab') {            // keep keyboard focus inside the dialog
                var f = LM.querySelectorAll('button, a[href], [tabindex="0"]');
                if (!f.length) return;
                var first = f[0], last = f[f.length - 1];
                if (!LM.contains(document.activeElement)) { e.preventDefault(); first.focus(); }
                else if (e.shiftKey && document.activeElement === first) { e.preventDefault(); last.focus(); }
                else if (!e.shiftKey && document.activeElement === last) { e.preventDefault(); first.focus(); }
            }
        }, true);
    }

    /* ---------- 2. Consent enforcement ---------- */
    window.addEventListener('submit', function (e) {
        var f = e.target;
        if (!f || !f.querySelector) return;
        var c = f.querySelector('input[data-bmis-consent]');
        if (!c || c.checked) return;
        e.preventDefault();
        e.stopPropagation();           // also stops the "Processing..." loader from starting
        c.classList.add('is-invalid');
        var err = document.getElementById(c.id + '_err');
        if (err) err.hidden = false;
        try { c.focus(); } catch (x) {}
    }, true);
    document.addEventListener('change', function (e) {
        var c = e.target;
        if (c && c.matches && c.matches('input[data-bmis-consent]') && c.checked) {
            c.classList.remove('is-invalid');
            var err = document.getElementById(c.id + '_err');
            if (err) err.hidden = true;
        }
    });

    /* ---------- 3. Accessibility helpers ---------- */
    var uid = 0;
    var CONTROL = 'input:not([type=hidden]):not([type=checkbox]):not([type=radio]):not([type=submit]):not([type=button]),select,textarea';

    function acFor(el) {
        var n = ((el.getAttribute('name') || '') + ' ' + (el.id || '')).toLowerCase();
        var t = (el.getAttribute('type') || 'text').toLowerCase();
        if (t === 'password') return /new|confirm|retype|repeat/.test(n) ? 'new-password' : 'current-password';
        if (t === 'email') return 'email';
        if (t === 'tel' || /phone|contact|mobile|cp_?no/.test(n)) return 'tel';
        if (/^(lname|surname|lastname|last_name)/.test(n)) return 'family-name';
        if (/^(fname|firstname|first_name)/.test(n)) return 'given-name';
        if (/^(mi|mname|middlename|middle_name)/.test(n)) return 'additional-name';
        if (/bdate|birthdate|birth_date|dob/.test(n)) return 'bday';
        if (/login_identity|username/.test(n)) return 'username';
        if (/street/.test(n)) return 'address-line1';
        if (/zip|postal/.test(n)) return 'postal-code';
        return '';
    }

    function accessibleName(el) {
        if (el.getAttribute('aria-label') || el.getAttribute('aria-labelledby') || el.getAttribute('title')) return true;
        if (el.closest('label')) return true;
        if (el.id) {
            var l = document.querySelector('label[for="' + (window.CSS && CSS.escape ? CSS.escape(el.id) : el.id) + '"]');
            if (l) return true;
        }
        return false;
    }

    function linkLabels(root) {
        root = root || document;
        var labels = root.querySelectorAll('label:not([for])');
        labels.forEach(function (label) {
            if (label.querySelector('input,select,textarea')) return;      // already wraps its field
            var target = null, el = label.nextElementSibling, hops = 0;
            while (el && hops < 3 && !target) {
                if (el.tagName === 'LABEL') break;
                target = el.matches(CONTROL) ? el : el.querySelector(CONTROL);
                el = el.nextElementSibling; hops++;
            }
            if (!target && label.parentElement) {
                var inParent = label.parentElement.querySelectorAll(CONTROL);
                if (inParent.length === 1) target = inParent[0];
            }
            if (!target || accessibleName(target)) return;
            if (!target.id) target.id = 'bmis-f-' + (++uid);
            label.setAttribute('for', target.id);
        });

        // Fields still without any accessible name: fall back to the placeholder text
        root.querySelectorAll(CONTROL).forEach(function (el) {
            if (!accessibleName(el)) {
                var ph = el.getAttribute('placeholder');
                if (ph) el.setAttribute('aria-label', ph);
            }
            if (!el.hasAttribute('autocomplete') && el.tagName === 'INPUT') {
                var ac = acFor(el);
                if (ac) el.setAttribute('autocomplete', ac);
            }
        });
    }

    function makeKeyboardOperable(root) {
        (root || document).querySelectorAll('div[onclick],span[onclick],li[onclick],i[onclick],td[onclick]').forEach(function (el) {
            if (el.hasAttribute('data-bmis-kb') || el.closest('a,button,label')) return;
            el.setAttribute('data-bmis-kb', '1');
            if (!el.hasAttribute('role')) el.setAttribute('role', 'button');
            if (!el.hasAttribute('tabindex')) el.setAttribute('tabindex', '0');
        });
    }
    document.addEventListener('keydown', function (e) {
        var el = e.target;
        if (!el || !el.hasAttribute || !el.hasAttribute('data-bmis-kb')) return;
        if (e.key === 'Enter' || e.key === ' ' || e.key === 'Spacebar') {
            e.preventDefault();
            el.click();
        }
    });

    function addSkipLink() {
        if (document.querySelector('.bmis-skip')) return;
        var main = document.querySelector('main, [role="main"], #content, #content-wrapper, .main-content');
        if (!main) return;
        if (!main.id) main.id = 'bmis-main';
        if (!main.hasAttribute('tabindex')) main.setAttribute('tabindex', '-1');
        var a = document.createElement('a');
        a.className = 'bmis-skip'; a.href = '#' + main.id; a.textContent = 'Skip to main content';
        document.body.insertBefore(a, document.body.firstChild);
    }

    function run(root) { linkLabels(root); makeKeyboardOperable(root); }
    function init() {
        run(document); addSkipLink();
        // Forms/modals that are added to the page later (chat, AJAX-loaded content)
        var pending = false;
        new MutationObserver(function () {
            if (pending) return; pending = true;
            (window.requestAnimationFrame || setTimeout)(function () { pending = false; run(document); });
        }).observe(document.body, { childList: true, subtree: true });
    }
    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init); else init();
})();
</script>
