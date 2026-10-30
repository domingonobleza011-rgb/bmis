<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta name="description" content="Barangay San Pedro eServices — certificates and permits, announcements, complaints, budget transparency, youth programs and resident records in one portal.">
  <meta name="theme-color" content="#ffffff">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="default">
  <meta name="apple-mobile-web-app-title" content="Brgy San Pedro">
  <link rel="apple-touch-icon" href="/icons/pwa/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
  <link rel="manifest" href="/manifest.json">
  <title>Barangay Management Information System</title>

  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <style>
    /* ============================================================
       Barangay San Pedro — landing page
       Mobile-first. White theme, matches the login page.
       ============================================================ */
    :root {
      --bg: #ffffff;
      --bg-soft: #f0f2f5;
      --surface: #ffffff;
      --ink: #1c1e21;
      --muted: #65676b;
      --line: #e4e6ea;
      --brand: #1b74e4;
      --brand-deep: #0a5ecf;
      --brand-soft: #e7f3ff;
      --ok: #1e9e5a;
      --ok-soft: #e6f6ed;
      --warn: #d97706;
      --warn-soft: #fffbeb;
      --bad: #ef4444;
      --bad-soft: #fef2f2;
      --shadow-sm: 0 1px 3px rgba(0, 0, 0, .06);
      --shadow-md: 0 8px 24px rgba(0, 0, 0, .08);
      --shadow-lg: 0 16px 48px rgba(0, 0, 0, .14);
      --radius: 16px;
      --nav-h: 62px;
    }

    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; scroll-padding-top: calc(var(--nav-h) + 12px); -webkit-text-size-adjust: 100%; }
    body {
      margin: 0;
      background: var(--bg);
      color: var(--ink);
      font-family: 'Segoe UI', system-ui, -apple-system, Roboto, sans-serif;
      line-height: 1.6;
      -webkit-font-smoothing: antialiased;
      overflow-x: hidden;
    }
    img { max-width: 100%; display: block; }
    a { color: inherit; text-decoration: none; }
    ul { list-style: none; margin: 0; padding: 0; }
    h1, h2, h3, h4, p { margin: 0; }
    :focus-visible { outline: 3px solid var(--brand); outline-offset: 3px; border-radius: 8px; }

    .container { width: min(1160px, 100% - 2rem); margin-inline: auto; }
    .skip-link {
      position: absolute; left: 1rem; top: -60px; z-index: 2000;
      background: var(--brand); color: #fff; padding: .6rem 1rem; border-radius: 10px; font-weight: 700;
    }
    .skip-link:focus { top: .75rem; }

    /* ---------- buttons ---------- */
    .btn {
      display: inline-flex; align-items: center; justify-content: center; gap: .5rem;
      padding: .8rem 1.5rem; border-radius: 12px; border: 1.5px solid transparent;
      font: inherit; font-weight: 700; font-size: .95rem; line-height: 1.1; cursor: pointer;
      transition: transform .18s ease, box-shadow .18s ease, background .18s ease, color .18s ease, border-color .18s ease;
      white-space: nowrap;
    }
    .btn-primary { background: var(--brand); color: #fff; }
    .btn-primary:hover { background: var(--brand-deep); transform: translateY(-2px); box-shadow: 0 4px 14px rgba(27, 116, 228, .3); }
    .btn-outline { background: transparent; color: var(--ink); border-color: var(--line); }
    .btn-outline:hover { border-color: var(--brand); color: var(--brand); transform: translateY(-2px); }
    .btn-ghost { background: transparent; color: var(--ink); padding-inline: 1rem; }
    .btn-ghost:hover { background: var(--brand-soft); color: var(--brand); }
    .btn-light { background: #fff; color: var(--brand-deep); box-shadow: 0 6px 18px rgba(0, 0, 0, .14); }
    .btn-light:hover { transform: translateY(-2px); }
    .btn-line-light { background: transparent; color: #fff; border-color: rgba(255, 255, 255, .5); }
    .btn-line-light:hover { background: rgba(255, 255, 255, .12); border-color: #fff; transform: translateY(-2px); }
    .btn-lg { padding: 1rem 1.8rem; font-size: 1rem; }

    /* ---------- navbar ---------- */
    .nav {
      position: fixed; inset: 0 0 auto 0; z-index: 1000; height: var(--nav-h);
      display: flex; align-items: center;
      background: #ffffff; border-bottom: 1px solid var(--line); box-shadow: 0 1px 3px rgba(0, 0, 0, .04);
    }
    .nav.scrolled { box-shadow: 0 4px 16px rgba(0, 0, 0, .06); }
    .nav .container { display: flex; align-items: center; justify-content: space-between; gap: 1rem; }
    .brand { display: inline-flex; align-items: center; gap: .65rem; font-weight: 800; font-size: 1.15rem; letter-spacing: -.01em; }
    .brand img { width: 40px; height: 40px; border-radius: 50%; background: #fff; object-fit: cover; box-shadow: 0 0 0 1px var(--line); }
    .brand b { color: var(--brand); }
    .nav-links { display: none; align-items: center; gap: .25rem; }
    .nav-links a { padding: .5rem .95rem; border-radius: 999px; font-weight: 600; font-size: .92rem; color: var(--muted); transition: background .18s, color .18s; }
    .nav-links a:hover { background: var(--brand-soft); color: var(--brand); }
    .nav-actions { display: none; align-items: center; gap: .5rem; }
    .menu-btn {
      width: 44px; height: 44px; display: inline-grid; place-items: center; border-radius: 12px;
      border: 1px solid var(--line); background: var(--surface); color: var(--ink); font-size: 1.4rem; cursor: pointer;
    }
    .mobile-menu {
      position: fixed; inset: var(--nav-h) 0 auto 0; z-index: 999;
      background: var(--bg); border-bottom: 1px solid var(--line); box-shadow: var(--shadow-md);
      padding: 1rem 1rem 1.4rem; display: grid; gap: .25rem;
      transform: translateY(-14px); opacity: 0; visibility: hidden;
      transition: transform .22s ease, opacity .22s ease, visibility .22s;
    }
    .mobile-menu.open { transform: none; opacity: 1; visibility: visible; }
    .mobile-menu a.link { padding: .85rem 1rem; border-radius: 12px; font-weight: 600; color: var(--ink); }
    .mobile-menu a.link:hover { background: var(--brand-soft); color: var(--brand); }
    .mobile-menu .row { display: grid; grid-template-columns: 1fr 1fr; gap: .6rem; margin-top: .6rem; }
    @media (min-width: 992px) {
      .nav-links, .nav-actions { display: flex; }
      .menu-btn, .mobile-menu { display: none; }
    }
    /* six nav links: tighten them on narrow desktops so nothing wraps */
    @media (min-width: 992px) and (max-width: 1199px) {
      .nav-links a { padding: .5rem .55rem; font-size: .86rem; }
    }


    /* ---------- officials carousel ---------- */
    .officials { padding: calc(var(--nav-h) + 1.5rem) 0 0; background: linear-gradient(180deg, var(--brand-soft), #fff); }
    .officials-head { text-align: center; margin-bottom: 1.2rem; }
    .officials-head h2 { font-size: clamp(1.4rem, 4vw, 2rem); font-weight: 800; letter-spacing: -.02em; margin-top: .6rem; }
    .officials-head p { color: var(--muted); font-size: .95rem; }
    .oc { position: relative; max-width: 980px; margin-inline: auto; }
    .oc-viewport { overflow: hidden; border-radius: var(--radius); }
    .oc-track { display: flex; align-items: stretch; transition: transform .5s ease; will-change: transform; }
    .oc-slide { flex: 0 0 100%; padding: .4rem; display: flex; }
    /* Fixed-size card: every card is the same height no matter how long the name is */
    .oc-card {
      width: 100%; height: 100%; min-height: 330px;
      display: flex; flex-direction: column; align-items: center; text-align: center; padding: 1.8rem 1rem 1.6rem;
      background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); box-shadow: var(--shadow-md);
    }
    .oc-photo { flex: 0 0 auto; width: 150px; height: 150px; border-radius: 50%; object-fit: cover; background: var(--bg-soft); box-shadow: 0 0 0 4px #fff, 0 0 0 7px var(--brand); }
    .oc-initials { display: grid; place-items: center; font-size: 2.6rem; font-weight: 800; color: var(--brand); }
    .oc-name {
      margin-top: 1rem; font-size: 1.15rem; font-weight: 800; line-height: 1.3;
      min-height: 2.6em; display: flex; align-items: center; justify-content: center; /* always reserves 2 lines */
      overflow-wrap: anywhere;
    }
    .oc-pos { margin-top: auto; display: inline-block; padding: .25rem .8rem; border-radius: 999px; background: var(--brand-soft); color: var(--brand-deep); font-weight: 700; font-size: .82rem; }
    .oc-btn {
      position: absolute; top: 50%; transform: translateY(-50%); z-index: 2; width: 42px; height: 42px; border-radius: 50%;
      border: 1px solid var(--line); background: #fff; color: var(--ink); font-size: 1.2rem; cursor: pointer; box-shadow: var(--shadow-sm);
      display: grid; place-items: center; transition: background .18s, color .18s;
    }
    .oc-btn:hover { background: var(--brand); color: #fff; }
    .oc-prev { left: -8px; } .oc-next { right: -8px; }
    .oc-dots { display: flex; justify-content: center; flex-wrap: wrap; gap: .4rem; margin-top: .9rem; }
    .oc-dot { width: 9px; height: 9px; padding: 0; border-radius: 50%; border: 0; background: var(--line); cursor: pointer; transition: background .2s, width .2s; }
    .oc-dot.active { background: var(--brand); width: 24px; border-radius: 6px; }
    @media (min-width: 640px) { .oc-slide { flex-basis: 50%; } }
    @media (min-width: 992px) { .oc-slide { flex-basis: 33.3333%; } .oc-prev { left: -20px; } .oc-next { right: -20px; } }
    @media (prefers-reduced-motion: reduce) { .oc-track { transition: none; } }
    .officials + .hero { padding-top: 2rem; }

    /* officials: roles hint + modal */
    .oc-card { cursor: pointer; min-height: 365px; transition: box-shadow .18s, transform .18s; }
    .oc-card:hover { box-shadow: var(--shadow-lg); transform: translateY(-2px); }
    .oc-hint { margin-top: .6rem; font-size: .78rem; font-weight: 700; color: var(--brand); display: inline-flex; align-items: center; gap: .3rem; }
    .rm-backdrop {
      position: fixed; inset: 0; z-index: 3000; background: rgba(15, 23, 42, .55); display: grid; place-items: center; padding: 1rem;
      opacity: 0; visibility: hidden; transition: opacity .2s, visibility .2s;
    }
    .rm-backdrop.open { opacity: 1; visibility: visible; }
    .rm {
      width: min(560px, 100%); max-height: min(86vh, 720px); overflow: auto; background: #fff; border-radius: 20px; box-shadow: var(--shadow-lg);
      transform: translateY(14px) scale(.98); transition: transform .2s;
    }
    .rm-backdrop.open .rm { transform: none; }
    .rm-head { position: relative; display: flex; align-items: center; gap: 1rem; padding: 1.3rem 3.2rem 1.1rem 1.3rem; background: var(--brand-soft); border-bottom: 1px solid var(--line); }
    .rm-head .oc-photo { width: 72px; height: 72px; box-shadow: 0 0 0 2px #fff, 0 0 0 4px var(--brand); font-size: 1.5rem; }
    .rm-name { font-size: 1.15rem; font-weight: 800; line-height: 1.25; overflow-wrap: anywhere; }
    .rm-close {
      position: absolute; top: .7rem; right: .7rem; width: 36px; height: 36px; border-radius: 50%; border: 0; background: #fff; color: var(--ink);
      font-size: 1.1rem; cursor: pointer; display: grid; place-items: center; box-shadow: var(--shadow-sm);
    }
    .rm-close:hover { background: var(--brand); color: #fff; }
    .rm-body { padding: 1.2rem 1.3rem 1.5rem; }
    .rm-body h4 { font-size: .95rem; font-weight: 800; display: flex; align-items: center; gap: .5rem; margin-bottom: .8rem; }
    .rm-body h4 i { color: var(--brand); }
    .rm-list { display: grid; gap: .6rem; }
    .rm-list li { display: flex; gap: .6rem; align-items: flex-start; font-size: .93rem; color: var(--ink); }
    .rm-list li i { color: var(--ok); margin-top: .15rem; flex: 0 0 auto; }
    .rm-empty { color: var(--muted); font-size: .93rem; }

    /* ---------- hero ---------- */
    .hero { position: relative; padding: calc(var(--nav-h) + 2.5rem) 0 3.5rem; overflow: hidden; isolation: isolate; }
    .hero-grid { display: grid; gap: 3rem; align-items: center; }
    .eyebrow {
      display: inline-flex; align-items: center; gap: .5rem; padding: .4rem .9rem; border-radius: 999px;
      background: var(--brand-soft); color: var(--brand); font-weight: 700; font-size: .8rem; letter-spacing: .02em;
    }
    .eyebrow .dot { width: 8px; height: 8px; border-radius: 50%; background: var(--ok); box-shadow: 0 0 0 4px rgba(15, 157, 107, .18); }
    .hero h1 {
      margin-top: 1.1rem; font-size: clamp(2.3rem, 7vw, 4rem); line-height: 1.05; font-weight: 800; letter-spacing: -.03em; text-wrap: balance;
    }
    .hero h1 .grad { color: var(--brand); }
    .hero .lead { margin-top: 1.2rem; font-size: clamp(1.02rem, 2.4vw, 1.2rem); color: var(--muted); max-width: 52ch; }
    .cta-row { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: .75rem; }
    .trust { margin-top: 2rem; display: flex; flex-wrap: wrap; gap: .6rem 1.4rem; color: var(--muted); font-size: .88rem; font-weight: 600; }
    .trust span { display: inline-flex; align-items: center; gap: .45rem; }
    .trust i { color: var(--ok); font-size: 1.05rem; }

    /* phone mockup */
    .device-wrap { position: relative; display: grid; place-items: center; padding: 1rem 0 .5rem; width: 100%; max-width: 470px; margin-inline: auto; }
    .device {
      position: relative; width: min(320px, 100%); border-radius: 40px; padding: 12px;
      background: linear-gradient(160deg, #1b2440, #0c1226); box-shadow: var(--shadow-lg);
    }
    .screen { background: var(--surface); border-radius: 30px; padding: 1.1rem 1rem 1.2rem; min-height: 470px; overflow: hidden; }
    .notch { width: 96px; height: 22px; border-radius: 0 0 16px 16px; background: #0c1226; margin: -12px auto .8rem; }
    .screen .top { display: flex; align-items: center; justify-content: space-between; margin-bottom: .9rem; }
    .screen .top h4 { font-size: 1.05rem; font-weight: 800; }
    .screen .top .avatar { width: 32px; height: 32px; border-radius: 50%; background: var(--brand-soft); color: var(--brand); display: grid; place-items: center; font-size: 1rem; }
    .req { display: flex; gap: .7rem; align-items: center; padding: .75rem; border: 1px solid var(--line); border-radius: 16px; margin-bottom: .65rem; background: var(--bg); }
    .req .ic { width: 40px; height: 40px; border-radius: 12px; display: grid; place-items: center; font-size: 1.15rem; flex: none; }
    .req .txt { min-width: 0; flex: 1; }
    .req .txt b { display: block; font-size: .82rem; line-height: 1.25; }
    .req .txt small { color: var(--muted); font-size: .72rem; }
    .pill { font-size: .66rem; font-weight: 800; padding: .25rem .55rem; border-radius: 999px; white-space: nowrap; }
    .s-ok { background: var(--ok-soft); color: var(--ok); }
    .s-warn { background: var(--warn-soft); color: var(--warn); }
    .s-info { background: var(--brand-soft); color: var(--brand); }
    .ic.i-ok { background: var(--ok-soft); color: var(--ok); }
    .ic.i-warn { background: var(--warn-soft); color: var(--warn); }
    .ic.i-info { background: var(--brand-soft); color: var(--brand); }
    .bar { height: 6px; border-radius: 99px; background: var(--line); margin-top: .35rem; overflow: hidden; }
    .bar i { display: block; height: 100%; border-radius: 99px; background: var(--brand); }
    .new-btn { margin-top: .9rem; width: 100%; padding: .75rem; border-radius: 14px; background: var(--brand); color: #fff; font-weight: 700; font-size: .85rem; text-align: center; }
    .chip {
      position: absolute; display: none; align-items: center; gap: .55rem; padding: .6rem .9rem; border-radius: 16px;
      background: var(--surface); border: 1px solid var(--line); box-shadow: var(--shadow-md); font-size: .8rem; font-weight: 700;
      animation: floaty 6s ease-in-out infinite;
    }
    .chip i { font-size: 1.15rem; }
    .chip { z-index: 3; }
    .chip.c1 { top: 9%; right: 0; color: var(--ok); }
    .chip.c2 { bottom: 9%; left: 0; color: var(--brand); animation-delay: -3s; }
    .chip span { color: var(--ink); }
    @keyframes floaty { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    @media (min-width: 560px) { .chip { display: inline-flex; } }
    @media (min-width: 992px) {
      .hero { padding: calc(var(--nav-h) + 4.5rem) 0 5rem; }
      .hero-grid { grid-template-columns: 1.1fr .9fr; gap: 4rem; }
    }

    /* ---------- stat strip ---------- */
    .stats { margin-top: -1.2rem; position: relative; z-index: 2; }
    .stats-card {
      display: grid; grid-template-columns: repeat(2, 1fr); gap: 1px; background: var(--line);
      border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; box-shadow: var(--shadow-md);
    }
    .stat { background: var(--surface); padding: 1.3rem 1rem; text-align: center; }
    .stat b { display: block; font-size: clamp(1.5rem, 4vw, 2.1rem); font-weight: 800; letter-spacing: -.02em; color: var(--brand); line-height: 1.1; }
    .stat span { color: var(--muted); font-size: .85rem; font-weight: 600; }
    @media (min-width: 768px) { .stats-card { grid-template-columns: repeat(4, 1fr); } }

    /* ---------- sections ---------- */
    section { padding: clamp(3.5rem, 8vw, 6rem) 0; }
    .sec-head { text-align: center; max-width: 660px; margin: 0 auto clamp(2rem, 5vw, 3.2rem); }
    .sec-head .kicker { color: var(--brand); font-weight: 800; font-size: .8rem; letter-spacing: .12em; text-transform: uppercase; }
    .sec-head h2 { margin-top: .6rem; font-size: clamp(1.8rem, 5vw, 2.7rem); line-height: 1.12; font-weight: 800; letter-spacing: -.025em; }
    .sec-head p { margin-top: .9rem; color: var(--muted); font-size: 1.05rem; }
    .soft { background: var(--bg-soft); }

    /* ---------- before / after ---------- */
    .compare { display: grid; gap: 1.2rem; }
    .cmp {
      border-radius: var(--radius); padding: 1.8rem; border: 1px solid var(--line); background: var(--surface); box-shadow: var(--shadow-sm);
    }
    .cmp h3 { display: flex; align-items: center; gap: .6rem; font-size: 1.15rem; font-weight: 800; margin-bottom: 1.1rem; }
    .cmp h3 .tag { font-size: .7rem; padding: .25rem .6rem; border-radius: 999px; font-weight: 800; }
    .cmp li { display: flex; gap: .75rem; align-items: flex-start; padding: .55rem 0; color: var(--muted); font-weight: 500; }
    .cmp li i { flex: none; margin-top: .15rem; font-size: 1.1rem; }
    .cmp.old h3 .tag { background: var(--bad-soft); color: var(--bad); }
    .cmp.old li i { color: var(--bad); }
    .cmp.new { border-color: color-mix(in srgb, var(--brand) 40%, var(--line)); background: linear-gradient(180deg, var(--brand-soft), var(--surface) 70%); }
    .cmp.new h3 .tag { background: var(--ok-soft); color: var(--ok); }
    .cmp.new li { color: var(--ink); }
    .cmp.new li i { color: var(--ok); }
    @media (min-width: 820px) { .compare { grid-template-columns: 1fr 1fr; gap: 1.6rem; } }

    /* ---------- services ---------- */
    .svc-tools { display: flex; justify-content: center; margin-bottom: 2rem; }
    .search {
      position: relative; width: min(520px, 100%);
    }
    .search i { position: absolute; left: 1.1rem; top: 50%; transform: translateY(-50%); color: var(--muted); }
    .search input {
      width: 100%; padding: .95rem 1.2rem .95rem 2.9rem; border-radius: 999px; border: 1.5px solid var(--line);
      background: var(--surface); color: var(--ink); font: inherit; font-size: 1rem; box-shadow: var(--shadow-sm);
    }
    .search input:focus { outline: none; border-color: var(--brand); box-shadow: 0 0 0 4px color-mix(in srgb, var(--brand) 18%, transparent); }
    .svc-grid { display: grid; gap: 1rem; grid-template-columns: 1fr; }
    @media (min-width: 560px) { .svc-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 992px) { .svc-grid { grid-template-columns: repeat(3, 1fr); gap: 1.25rem; } }
    .svc {
      display: flex; flex-direction: column; gap: .7rem; height: 100%;
      background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 1.5rem;
      transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .svc:hover { transform: translateY(-5px); box-shadow: var(--shadow-md); border-color: color-mix(in srgb, var(--brand) 45%, var(--line)); }
    .svc .ico {
      width: 52px; height: 52px; border-radius: 16px; display: grid; place-items: center; font-size: 1.5rem;
      background: var(--brand-soft); color: var(--brand); transition: background .2s, color .2s;
    }
    .svc:hover .ico { background: var(--brand); color: #fff; }
    .svc h3 { font-size: 1.05rem; font-weight: 800; line-height: 1.25; }
    .svc p { color: var(--muted); font-size: .92rem; flex: 1; }
    .svc .go { display: inline-flex; align-items: center; gap: .35rem; font-weight: 700; font-size: .88rem; color: var(--brand); }
    .svc .go i { transition: transform .2s; }
    .svc:hover .go i { transform: translateX(4px); }
    .svc-empty { display: none; text-align: center; color: var(--muted); padding: 2rem 0 0; font-weight: 600; }

    /* ---------- features / portals ---------- */
    .feat-grid { display: grid; gap: 1rem; grid-template-columns: 1fr; }
    @media (min-width: 600px) { .feat-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (min-width: 992px) { .feat-grid { grid-template-columns: repeat(3, 1fr); gap: 1.25rem; } }
    .feat {
      display: flex; flex-direction: column; gap: .6rem; height: 100%;
      background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 1.5rem;
      box-shadow: var(--shadow-sm); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease;
    }
    .feat:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: color-mix(in srgb, var(--brand) 45%, var(--line)); }
    .feat .ico { width: 52px; height: 52px; border-radius: 16px; display: grid; place-items: center; font-size: 1.5rem; background: var(--brand-soft); color: var(--brand); }
    .feat h3 { font-size: 1.05rem; font-weight: 800; line-height: 1.25; margin: .2rem 0 0; }
    .feat p { color: var(--muted); font-size: .92rem; margin: 0; }
    .feat ul { margin-top: .2rem; display: grid; gap: .35rem; }
    .feat li { display: flex; gap: .5rem; align-items: flex-start; color: var(--muted); font-size: .88rem; }
    .feat li i { color: var(--ok); margin-top: .22rem; flex: none; }
    .portal-grid { display: grid; gap: 1.2rem; grid-template-columns: 1fr; }
    @media (min-width: 900px) { .portal-grid { grid-template-columns: repeat(3, 1fr); gap: 1.5rem; } }
    .portal { background: var(--surface); border: 1px solid var(--line); border-radius: 20px; padding: 1.8rem 1.5rem; box-shadow: var(--shadow-sm); display: flex; flex-direction: column; gap: .7rem; }
    .portal .ph { display: flex; align-items: center; gap: .8rem; }
    .portal .ph .ico { width: 48px; height: 48px; border-radius: 14px; display: grid; place-items: center; font-size: 1.4rem; background: var(--brand); color: #fff; flex: none; }
    .portal h3 { font-size: 1.15rem; font-weight: 800; margin: 0; line-height: 1.2; }
    .portal .who { color: var(--brand-deep); font-size: .8rem; font-weight: 700; letter-spacing: .02em; }
    .portal ul { display: grid; gap: .5rem; margin-top: .3rem; }
    .portal li { display: flex; gap: .55rem; align-items: flex-start; color: var(--muted); font-size: .92rem; }
    .portal li i { color: var(--brand); margin-top: .2rem; flex: none; }

    /* ---------- steps ---------- */
    .steps { display: grid; gap: 1.2rem; }
    .step { position: relative; background: var(--surface); border: 1px solid var(--line); border-radius: var(--radius); padding: 1.8rem 1.5rem; box-shadow: var(--shadow-sm); }
    .step .num {
      width: 46px; height: 46px; border-radius: 14px; display: grid; place-items: center;
      background: var(--brand); color: #fff; font-weight: 800; font-size: 1.15rem; margin-bottom: 1rem;
      box-shadow: 0 4px 12px rgba(27, 116, 228, .3);
    }
    .step h3 { font-size: 1.1rem; font-weight: 800; margin-bottom: .4rem; }
    .step p { color: var(--muted); font-size: .95rem; }
    @media (min-width: 820px) {
      .steps { grid-template-columns: repeat(3, 1fr); gap: 1.6rem; }
      .step:not(:last-child)::after {
        content: ""; position: absolute; top: 47px; right: -1.6rem; width: 1.6rem; border-top: 2px dashed color-mix(in srgb, var(--brand) 45%, var(--line));
      }
    }

    /* ---------- CTA band ---------- */
    .cta-band {
      position: relative; overflow: hidden; border-radius: 24px; padding: clamp(2.2rem, 6vw, 4rem) clamp(1.4rem, 5vw, 3.5rem);
      background: linear-gradient(135deg, var(--brand), var(--brand-deep)); color: #fff; text-align: center; box-shadow: var(--shadow-lg);
      isolation: isolate;
    }
    .cta-band::before, .cta-band::after { content: ""; position: absolute; border-radius: 50%; background: rgba(255, 255, 255, .07); z-index: -1; }
    .cta-band::before { width: 340px; height: 340px; top: -150px; right: -90px; }
    .cta-band::after { width: 240px; height: 240px; bottom: -120px; left: -60px; }
    .cta-band h2 { font-size: clamp(1.7rem, 5vw, 2.6rem); font-weight: 800; letter-spacing: -.02em; line-height: 1.15; max-width: 22ch; margin: 0 auto; }
    .cta-band p { margin: 1rem auto 0; max-width: 52ch; color: rgba(255, 255, 255, .85); font-size: 1.05rem; }
    .cta-band .cta-row { justify-content: center; }

    /* ---------- FAQ ---------- */
    .faq { max-width: 780px; margin: 0 auto; display: grid; gap: .85rem; }
    .qa { border: 1px solid var(--line); border-radius: 18px; background: var(--surface); overflow: hidden; transition: border-color .2s, box-shadow .2s; }
    .qa.open { border-color: color-mix(in srgb, var(--brand) 45%, var(--line)); box-shadow: var(--shadow-sm); }
    .qa button {
      width: 100%; display: flex; align-items: center; justify-content: space-between; gap: 1rem; text-align: left;
      padding: 1.15rem 1.3rem; background: none; border: 0; color: var(--ink); font: inherit; font-weight: 700; font-size: 1rem; cursor: pointer;
    }
    .qa button i { color: var(--brand); transition: transform .25s ease; flex: none; }
    .qa.open button i { transform: rotate(180deg); }
    .qa .a { display: grid; grid-template-rows: 0fr; transition: grid-template-rows .28s ease; }
    .qa.open .a { grid-template-rows: 1fr; }
    .qa .a > div { overflow: hidden; }
    .qa .a p { padding: 0 1.3rem 1.2rem; color: var(--muted); font-size: .95rem; }

    /* ---------- footer ---------- */
    footer { background: var(--bg-soft); border-top: 1px solid var(--line); padding: 3.5rem 0 calc(1.6rem + env(safe-area-inset-bottom)); }
    .foot-grid { display: grid; gap: 2rem; grid-template-columns: 1fr; }
    @media (min-width: 560px) { .foot-grid { grid-template-columns: 1fr 1fr; } }
    @media (min-width: 992px) { .foot-grid { grid-template-columns: 1.3fr .8fr 1.3fr 1.2fr; gap: 2.5rem; } }
    footer h5 { font-size: .78rem; letter-spacing: .1em; text-transform: uppercase; font-weight: 800; margin: 0 0 1rem; }
    footer p.about { color: var(--muted); font-size: .9rem; margin-top: .9rem; max-width: 30ch; }
    footer li { display: flex; gap: .6rem; align-items: flex-start; margin-bottom: .65rem; color: var(--muted); font-size: .9rem; }
    footer li i { color: var(--brand); margin-top: .2rem; }
    footer li i.red { color: var(--bad); }
    footer a:hover { color: var(--brand); }
    .foot-base {
      margin-top: 2.5rem; padding-top: 1.3rem; border-top: 1px solid var(--line);
      display: flex; flex-direction: column; gap: .6rem; color: var(--muted); font-size: .8rem;
    }
    .foot-base i { color: var(--brand); }
    @media (min-width: 820px) { .foot-base { flex-direction: row; justify-content: space-between; align-items: center; } }

    /* ---------- reveal on scroll ---------- */
    .reveal { opacity: 0; transform: translateY(22px); transition: opacity .6s ease, transform .6s ease; }
    .reveal.in { opacity: 1; transform: none; }
    @media (prefers-reduced-motion: reduce) {
      html { scroll-behavior: auto; }
      .reveal { opacity: 1; transform: none; transition: none; }
      .chip { animation: none; }
      * { transition-duration: .01ms !important; }
    }

    /* mobile: center the card icons (with their title and text) */
    @media (max-width: 599px) {
      .svc, .feat { align-items: center; text-align: center; }
      .svc .ico, .feat .ico { margin-inline: auto; }
      .feat ul { align-self: stretch; text-align: left; }
      .portal .ph { flex-direction: column; text-align: center; }
      .portal ul { text-align: left; }
    }

    /* small phones */
    @media (max-width: 400px) {
      .cta-row .btn { flex: 1 1 100%; }
    }
  </style>
<link rel="stylesheet" href="css/skeleton-public.css?v=20261026c">
</head>
<body>
  <a class="skip-link" href="#main">Skip to content</a>

  <!-- ===== Navbar ===== -->
  <nav class="nav" id="navbar" aria-label="Main">
    <div class="container">
      <a href="#top" class="brand" aria-label="Barangay San Pedro home">
        <img src="icons/logo.png" alt="" width="40" height="40">
        <span><b>Brgy</b> San Pedro</span>
      </a>

      <div class="nav-links">
        <a href="#features">Features</a>
        <a href="#portals">Portals</a>
        <a href="#services">Services</a>
        <a href="#how-it-works">How it works</a>
        <a href="#faq">FAQ</a>
        <a href="#contact">Contact</a>
      </div>

      <div class="nav-actions">
        <a href="resident_registration.php" class="btn btn-ghost">Register</a>
        <a href="login.php" class="btn btn-primary">Log In</a>
      </div>

      <button class="menu-btn" id="menuBtn" type="button" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenu">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
      </button>
    </div>
  </nav>

  <div class="mobile-menu" id="mobileMenu">
    <a class="link" href="#features">Features</a>
    <a class="link" href="#portals">Portals</a>
    <a class="link" href="#services">Services</a>
    <a class="link" href="#how-it-works">How it works</a>
    <a class="link" href="#faq">FAQ</a>
    <a class="link" href="#contact">Contact</a>
    <div class="row">
      <a href="resident_registration.php" class="btn btn-outline">Register</a>
      <a href="login.php" class="btn btn-primary">Log In</a>
    </div>
  </div>

  <main id="main">
    <?php if (!empty($officials)): ?>
    <!-- ===== Barangay Officials Carousel ===== -->
    <section class="officials" id="officials" aria-label="Barangay officials">
      <div class="container">
        <div class="officials-head">
          <span class="eyebrow">Barangay San Pedro · Iriga City</span>
          <h2>Meet Your Barangay Officials</h2>
          <p>The people serving our community.</p>
        </div>
        <div class="oc" id="oc" aria-roledescription="carousel">
          <button class="oc-btn oc-prev" type="button" aria-label="Previous officials"><i class="bi bi-chevron-left"></i></button>
          <div class="oc-viewport">
            <div class="oc-track">
              <?php foreach ($officials as $o):
                $full = trim(($o['fname'] ?? '') . ' ' . (!empty($o['mi']) ? rtrim($o['mi'], '.') . '. ' : '') . ($o['lname'] ?? ''));
                $initials = strtoupper(mb_substr($o['fname'] ?? '', 0, 1) . mb_substr($o['lname'] ?? '', 0, 1));
                $photo = !empty($o['photo']) ? ltrim($o['photo'], '/') : '';
              ?>
              <div class="oc-slide">
                <article class="oc-card" role="button" tabindex="0"
                         data-name="<?= htmlspecialchars($full) ?>"
                         data-position="<?= htmlspecialchars($o['position']) ?>"
                         data-photo="<?= htmlspecialchars($photo) ?>"
                         data-initials="<?= htmlspecialchars($initials) ?>"
                         aria-label="View roles and responsibilities of <?= htmlspecialchars($full) ?>">
                  <?php if ($photo): ?>
                    <img class="oc-photo" src="<?= htmlspecialchars($photo) ?>" alt="<?= htmlspecialchars($full) ?>" loading="lazy">
                  <?php else: ?>
                    <div class="oc-photo oc-initials" aria-hidden="true"><?= htmlspecialchars($initials) ?></div>
                  <?php endif; ?>
                  <h3 class="oc-name"><?= htmlspecialchars($full) ?></h3>
                  <span class="oc-pos"><?= htmlspecialchars($o['position']) ?></span>
                </article>
              </div>
              <?php endforeach; ?>
            </div>
          </div>
          <button class="oc-btn oc-next" type="button" aria-label="Next officials"><i class="bi bi-chevron-right"></i></button>
          <div class="oc-dots" role="tablist"></div>
        </div>
      </div>
    </section>

    <!-- Roles & responsibilities modal -->
    <div class="rm-backdrop" id="rmBackdrop" aria-hidden="true">
      <div class="rm" role="dialog" aria-modal="true" aria-labelledby="rmName">
        <div class="rm-head">
          <div id="rmPhoto"></div>
          <div>
            <div class="rm-name" id="rmName"></div>
            <span class="oc-pos" id="rmPos" style="margin-top:.35rem"></span>
          </div>
          <button type="button" class="rm-close" id="rmClose" aria-label="Close"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="rm-body">
          <h4><i class="bi bi-clipboard2-check-fill"></i> Assigned Roles &amp; Responsibilities</h4>
          <ul class="rm-list" id="rmList"></ul>
        </div>
      </div>
    </div>
    <script>
      window.OFFICIAL_ROLES = <?= json_encode(array_map(function ($r) { return $r['roles']; }, $roleDescriptions ?? []), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE) ?>;
    </script>
    <?php endif; ?>
    <!-- ===== Hero ===== -->
    <header class="hero" id="top">
      <div class="container hero-grid">
        <div>
          <span class="eyebrow"><span class="dot"></span> Barangay San Pedro · Iriga City</span>
          <h1>Your barangay, <span class="grad">in your pocket.</span></h1>
          <p class="lead">One portal for everything the barangay does: request certificates and permits, file complaints, read announcements and the barangay budget, and join youth programs. No long queues, no lost forms.</p>

          <div class="cta-row">
            <a href="login.php" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
            <a href="resident_registration.php" class="btn btn-outline btn-lg"><i class="bi bi-person-plus"></i> Create account</a>
          </div>

          <div class="trust">
            <span><i class="bi bi-check-circle-fill"></i> Free to file online</span>
            <span><i class="bi bi-clock-history"></i> Track status anytime</span>
            <span><i class="bi bi-shield-lock-fill"></i> Data Privacy Act protected</span>
          </div>
        </div>

        <!-- phone mockup -->
        <div class="device-wrap" aria-hidden="true">
          <div class="chip c1"><i class="bi bi-patch-check-fill"></i><span>Request approved</span></div>
          <div class="chip c2"><i class="bi bi-bell-fill"></i><span>Ready for pick-up</span></div>
          <div class="device">
            <div class="screen">
              <div class="notch"></div>
              <div class="top"><h4>My requests</h4><div class="avatar"><i class="bi bi-person-fill"></i></div></div>

              <div class="req">
                <div class="ic i-ok"><i class="bi bi-house-check-fill"></i></div>
                <div class="txt"><b>Certificate of Residency</b><small>Ready for pick-up</small></div>
                <span class="pill s-ok">Done</span>
              </div>
              <div class="req">
                <div class="ic i-info"><i class="bi bi-person-vcard-fill"></i></div>
                <div class="txt"><b>Barangay ID</b><small>Being processed</small><div class="bar"><i style="width:65%"></i></div></div>
                <span class="pill s-info">65%</span>
              </div>
              <div class="req">
                <div class="ic i-warn"><i class="bi bi-file-earmark-check-fill"></i></div>
                <div class="txt"><b>Barangay Clearance</b><small>Waiting for review</small></div>
                <span class="pill s-warn">Pending</span>
              </div>
              <div class="req">
                <div class="ic i-ok"><i class="bi bi-patch-check-fill"></i></div>
                <div class="txt"><b>Good Moral Character</b><small>Approved</small></div>
                <span class="pill s-ok">Done</span>
              </div>
              <div class="new-btn"><i class="bi bi-plus-lg"></i> New request</div>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- ===== Stats ===== -->
    <div class="stats">
      <div class="container">
        <div class="stats-card reveal">
          <div class="stat"><b>9</b><span>Online services</span></div>
          <div class="stat"><b>24/7</b><span>Request anytime</span></div>
          <div class="stat"><b>3</b><span>Portals: Resident, Staff, SK</span></div>
          <div class="stat"><b>0</b><span>Queues to line up in</span></div>
        </div>
      </div>
    </div>

    <!-- ===== Features ===== -->
    <section id="features">
      <div class="container">
        <div class="sec-head reveal">
          <div class="kicker">What the system does</div>
          <h2>System Features</h2>
          <p>The Barangay San Pedro is a full Barangay Management Information System, built to run barangay services, records and communication in one place.</p>
        </div>

        <div class="feat-grid">
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-file-earmark-check-fill"></i></div>
            <h3>Certificates, IDs &amp; permits</h3>
            <p>Request and track barangay documents online, from filing to release.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>9 services including Barangay Clearance, Barangay ID and Business Permit</span></li>
              <li><i class="bi bi-check-lg"></i><span>Staff review, approve and generate the printable document</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-people-fill"></i></div>
            <h3>Resident records</h3>
            <p>A digital database of the barangay's residents and households.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Online registration with verification by barangay staff</span></li>
              <li><i class="bi bi-check-lg"></i><span>Household, voter, senior citizen, PWD and solo parent records</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-megaphone-fill"></i></div>
            <h3>Announcements</h3>
            <p>Official barangay and SK announcements, posted once and seen by every resident.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Pinned posts for important notices</span></li>
              <li><i class="bi bi-check-lg"></i><span>Tappable links to forms and outside pages</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-chat-left-text-fill"></i></div>
            <h3>Complaints &amp; messages</h3>
            <p>Report concerns and talk to the barangay without going to the hall.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>File a complaint and follow its status</span></li>
              <li><i class="bi bi-check-lg"></i><span>Direct messaging between residents and barangay staff</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-cash-coin"></i></div>
            <h3>Budget transparency</h3>
            <p>See how barangay funds are planned and used, open to every resident.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Budget information published by the barangay</span></li>
              <li><i class="bi bi-check-lg"></i><span>Viewable from the resident dashboard</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-lightbulb-fill"></i></div>
            <h3>Youth profiling &amp; SK programs</h3>
            <p>A youth portal for the Sangguniang Kabataan and young residents.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Youth member profiles and records</span></li>
              <li><i class="bi bi-check-lg"></i><span>Browse and join SK programs, with participation tracking</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-bell-fill"></i></div>
            <h3>Notifications &amp; mobile app</h3>
            <p>Stay updated on your requests and barangay news.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Push notifications when something changes</span></li>
              <li><i class="bi bi-check-lg"></i><span>Install it on your phone like an app</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-shield-lock-fill"></i></div>
            <h3>Security &amp; accountability</h3>
            <p>Role-based access keeps each person to the tools they need.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Password recovery and security questions</span></li>
              <li><i class="bi bi-check-lg"></i><span>Activity logs and login history for staff actions</span></li>
            </ul>
          </div>
          <div class="feat reveal">
            <div class="ico"><i class="bi bi-moon-stars-fill"></i></div>
            <h3>Made for comfort</h3>
            <p>Use it the way that suits you, on any device.</p>
            <ul>
              <li><i class="bi bi-check-lg"></i><span>Light and dark themes</span></li>
              <li><i class="bi bi-check-lg"></i><span>Adjustable text size and font</span></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Portals ===== -->
    <section id="portals" class="soft">
      <div class="container">
        <div class="sec-head reveal">
          <div class="kicker">Portals</div>
          <h2>One system, three portals</h2>
          <p>Everyone signs in at the same place and lands in the portal that fits their role.</p>
        </div>

        <div class="portal-grid">
          <div class="portal reveal">
            <div class="ph"><div class="ico"><i class="bi bi-person-fill"></i></div><div><h3>Resident Portal</h3><div class="who">For residents of San Pedro</div></div></div>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i><span>Request certificates, IDs and permits, and track each one</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>File complaints and message the barangay</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Read announcements and the barangay budget</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Youth residents: register a youth profile and join SK programs</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Manage your profile and password</span></li>
            </ul>
          </div>
          <div class="portal reveal">
            <div class="ph"><div class="ico"><i class="bi bi-building-fill-gear"></i></div><div><h3>Barangay Staff Portal</h3><div class="who">For officials and staff</div></div></div>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i><span>Approve resident registrations and manage resident records</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Process and generate certificates, IDs and permits</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Handle complaints and reply to resident messages</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Post announcements and publish the budget</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Dashboard statistics, archive, activity logs and login history</span></li>
            </ul>
          </div>
          <div class="portal reveal">
            <div class="ph"><div class="ico"><i class="bi bi-stars"></i></div><div><h3>SK Portal</h3><div class="who">For the SK Chairperson</div></div></div>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i><span>Keep the youth member records, including youth without an account</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Create and manage programs and activities</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Track enrollment and participation</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Post SK announcements for the youth sector</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Overview dashboard of youth and program numbers</span></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Before / After ===== -->
    <section id="why">
      <div class="container">
        <div class="sec-head reveal">
          <div class="kicker">Why go online</div>
          <h2>Barangay services, simplified.</h2>
          <p>Residents of Barangay San Pedro deserve better access to barangay services and information. Here is what changes.</p>
        </div>

        <div class="compare">
          <div class="cmp old reveal">
            <h3>The old way <span class="tag">Before</span></h3>
            <ul>
              <li><i class="bi bi-x-circle-fill"></i><span>Hours spent lining up at the barangay hall for simple requests.</span></li>
              <li><i class="bi bi-x-circle-fill"></i><span>Paper forms, repeated details and slow, in-person approvals.</span></li>
              <li><i class="bi bi-x-circle-fill"></i><span>Walk-in only, with no way to check a request, a complaint or the latest announcement without going back.</span></li>
            </ul>
          </div>
          <div class="cmp new reveal">
            <h3>With the portal <span class="tag">Now</span></h3>
            <ul>
              <li><i class="bi bi-check-circle-fill"></i><span>File your request from your phone or computer, wherever you are.</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Fill out one online form and get it reviewed by barangay staff.</span></li>
              <li><i class="bi bi-check-circle-fill"></i><span>Follow every request and complaint from your dashboard, and get announcements right on your phone.</span></li>
            </ul>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== Services ===== -->
    <section id="services" class="soft">
      <div class="container">
        <div class="sec-head reveal">
          <div class="kicker">Certificates &amp; permits</div>
          <h2>Barangay documents you can request online</h2>
          <p>Log in to file any of these requests online.</p>
        </div>

        <div class="svc-tools reveal">
          <label class="search">
            <i class="bi bi-search"></i>
            <input type="search" id="svcSearch" placeholder="Search a service, e.g. clearance" autocomplete="off" aria-label="Search services">
          </label>
        </div>

        <div class="svc-grid" id="serviceGrid">
          <a class="svc reveal" href="login.php" data-label="barangay clearance">
            <div class="ico"><i class="bi bi-file-earmark-check-fill"></i></div>
            <h3>Barangay Clearance</h3>
            <p>General clearance for permits and transactions.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="barangay id">
            <div class="ico"><i class="bi bi-person-vcard-fill"></i></div>
            <h3>Barangay ID</h3>
            <p>Get your official Barangay ID quickly.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="certificate of residency">
            <div class="ico"><i class="bi bi-house-check-fill"></i></div>
            <h3>Certificate of Residency</h3>
            <p>Proof of residence for various transactions.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="certificate of indigency">
            <div class="ico"><i class="bi bi-briefcase-fill"></i></div>
            <h3>Certificate of Indigency</h3>
            <p>For financial assistance and social services.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="good moral character">
            <div class="ico"><i class="bi bi-patch-check-fill"></i></div>
            <h3>Good Moral Character</h3>
            <p>For employment, school or visa applications.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="business permit">
            <div class="ico"><i class="bi bi-file-earmark-medical-fill"></i></div>
            <h3>Business Permit</h3>
            <p>Apply for or renew your business permit online.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="certificate of livestock">
            <div class="ico"><i class="bi bi-clipboard2-pulse-fill"></i></div>
            <h3>Certificate of Livestock</h3>
            <p>For livestock owners and agricultural purposes.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="guardianship">
            <div class="ico"><i class="bi bi-people-fill"></i></div>
            <h3>Guardianship</h3>
            <p>Legal guardianship certification.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
          <a class="svc reveal" href="login.php" data-label="solo parent">
            <div class="ico"><i class="bi bi-person-heart"></i></div>
            <h3>Solo Parent</h3>
            <p>For solo parents seeking benefits and support.</p>
            <span class="go">Request <i class="bi bi-arrow-right"></i></span>
          </a>
        </div>
        <div class="svc-empty" id="svcEmpty">No services match your search.</div>
      </div>
    </section>

    <!-- ===== How it works ===== -->
    <section id="how-it-works">
      <div class="container">
        <div class="sec-head reveal">
          <div class="kicker">How it works</div>
          <h2>Three steps, no walk-in required</h2>
        </div>
        <div class="steps">
          <div class="step reveal">
            <div class="num">1</div>
            <h3>Sign in or register</h3>
            <p>Use your resident account to file requests. New here? Registration takes only a few minutes.</p>
          </div>
          <div class="step reveal">
            <div class="num">2</div>
            <h3>Pick what you need</h3>
            <p>Request a certificate or permit, file a complaint, message the barangay, or browse announcements and youth programs.</p>
          </div>
          <div class="step reveal">
            <div class="num">3</div>
            <h3>Track and stay updated</h3>
            <p>Follow your request status from your dashboard, get notified of updates, and claim documents at the barangay hall.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== CTA ===== -->
    <section style="padding-top:0">
      <div class="container">
        <div class="cta-band reveal">
          <h2>Ready to skip the line?</h2>
          <p>Barangay San Pedro brings services, records, announcements and youth programs into one secure, easy-to-use portal.</p>
          <div class="cta-row">
            <a href="login.php" class="btn btn-light btn-lg"><i class="bi bi-box-arrow-in-right"></i> Log In</a>
            <a href="resident_registration.php" class="btn btn-line-light btn-lg"><i class="bi bi-person-plus"></i> Register now</a>
          </div>
        </div>
      </div>
    </section>

    <!-- ===== FAQ ===== -->
    <section id="faq" class="soft">
      <div class="container">
        <div class="sec-head reveal">
          <div class="kicker">FAQ</div>
          <h2>Frequently asked questions</h2>
          <p>Everything you need to know about the Barangay San Pedro eServices portal.</p>
        </div>

        <div class="faq reveal">
          <div class="qa">
            <button type="button" aria-expanded="false"><span>Is this portal free to use?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>Yes. Filing a request through this portal is free. You only pay the standard fee for the certificate or permit itself, the same as requesting it in person.</p></div></div>
          </div>
          <div class="qa">
            <button type="button" aria-expanded="false"><span>Is this only for certificate requests?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>No. Besides certificates, IDs and permits, the portal also handles resident registration and records, complaints, messaging with barangay staff, announcements, budget transparency, and youth profiling with SK programs.</p></div></div>
          </div>
          <div class="qa">
            <button type="button" aria-expanded="false"><span>Who can use the system?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>Residents use the Resident Portal, barangay officials and staff use the Staff Portal, and the SK Chairperson uses the SK Portal. You sign in on the same page and are taken to the portal for your role.</p></div></div>
          </div>
          <div class="qa">
            <button type="button" aria-expanded="false"><span>What is the Youth Portal?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>Young residents register a youth profile, browse programs and activities from the Sangguniang Kabataan, and join the ones they like. The SK Chairperson keeps the youth records and tracks participation.</p></div></div>
          </div>
          <div class="qa">
            <button type="button" aria-expanded="false"><span>Is my data secure?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>Your information is only accessible to barangay staff who process your request, and every account is protected by a password and login verification.</p></div></div>
          </div>
          <div class="qa">
            <button type="button" aria-expanded="false"><span>What certificates and permits can I request online?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>Barangay Clearance, Barangay ID, Certificate of Residency, Indigency, Good Moral Character, Guardianship, Solo Parent Certification, Certificate of Livestock, and Business Permit.</p></div></div>
          </div>
          <div class="qa">
            <button type="button" aria-expanded="false"><span>How do I verify my identity?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>When you register, you will provide your resident details for the barangay to verify against its records. Once verified, you can file requests under your account.</p></div></div>
          </div>
          <div class="qa open">
            <button type="button" aria-expanded="true"><span>Can I use my account on multiple devices?</span><i class="bi bi-chevron-down"></i></button>
            <div class="a"><div><p>Yes. You can log in with your credentials from any phone, tablet or computer, and your requests will stay in sync across all of them.</p></div></div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- ===== Footer ===== -->
  <footer id="contact">
    <div class="container">
      <div class="foot-grid">
        <div>
          <a href="#top" class="brand"><img src="icons/logo.png" alt="" width="40" height="40"><span><b>Brgy</b> San Pedro</span></a>
          <p class="about">Bringing barangay services, records and communication online for the residents of San Pedro, Iriga City.</p>
        </div>

        <div>
          <h5>Quick links</h5>
          <ul>
            <li><a href="#features">Features</a></li>
            <li><a href="#portals">Portals</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#how-it-works">How it works</a></li>
            <li><a href="#faq">FAQ</a></li>
            <li><a href="resident_registration.php">Register</a></li>
            <li><a href="login.php">Log In</a></li>
          </ul>
        </div>

        <div>
          <h5>Contact</h5>
          <ul>
            <li><i class="bi bi-geo-alt-fill"></i><span>Barangay Hall, San Pedro, Iriga City, Camarines Sur</span></li>
            <li><i class="bi bi-telephone-fill"></i><span>(054) 000-0000</span></li>
            <li><i class="bi bi-envelope-fill"></i><a href="mailto:brgysanpedro.iriga@gmail.com">brgysanpedro.iriga@gmail.com</a></li>
            <li><i class="bi bi-clock-fill"></i><span>Mon–Fri, 8:00 AM–5:00 PM</span></li>
          </ul>
        </div>

        <div>
          <h5>Emergency hotlines</h5>
          <ul>
            <li><i class="bi bi-shield-fill-exclamation red"></i><span>Barangay Tanod: 0900-000-0000</span></li>
            <li><i class="bi bi-hospital-fill red"></i><span>Health Center: 0900-000-0000</span></li>
            <li><i class="bi bi-cone-striped red"></i><span>Police Sub-Station: 0900-000-0000</span></li>
          </ul>
        </div>
      </div>

      <div class="foot-base">
        <span>© 2026 Barangay San Pedro Management Information System. All rights reserved. · <a href="/privacy" style="color:inherit;">Privacy Notice</a> · <a href="/terms" style="color:inherit;">Terms</a> · <a href="/cookie-policy" style="color:inherit;">Cookie Policy</a></span>
        <span><i class="bi bi-shield-lock-fill"></i> Your data is protected under RA 10173 – Data Privacy Act of 2012.</span>
      </div>
    </div>
  </footer>

  <script>
    (function () {
      var nav = document.getElementById('navbar');
      var menuBtn = document.getElementById('menuBtn');
      var menu = document.getElementById('mobileMenu');

      /* nav shadow on scroll */
      function onScroll() { nav.classList.toggle('scrolled', window.scrollY > 8); }
      onScroll();
      window.addEventListener('scroll', onScroll, { passive: true });

      /* mobile menu */
      function setMenu(open) {
        menu.classList.toggle('open', open);
        menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
        menuBtn.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
        menuBtn.innerHTML = open
          ? '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18"/></svg>'
          : '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true"><path d="M4 7h16M4 12h16M4 17h16"/></svg>';
      }
      menuBtn.addEventListener('click', function () { setMenu(!menu.classList.contains('open')); });
      menu.addEventListener('click', function (e) { if (e.target.closest('a')) setMenu(false); });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setMenu(false); });
      window.addEventListener('resize', function () { if (window.innerWidth >= 992) setMenu(false); });

      /* service search */
      var search = document.getElementById('svcSearch');
      var items = document.querySelectorAll('#serviceGrid .svc');
      var empty = document.getElementById('svcEmpty');
      if (search) {
        search.addEventListener('input', function () {
          var q = this.value.trim().toLowerCase(), shown = 0;
          items.forEach(function (el) {
            var hit = (el.getAttribute('data-label') || '').indexOf(q) > -1;
            el.style.display = hit ? '' : 'none';
            if (hit) shown++;
          });
          empty.style.display = shown ? 'none' : 'block';
        });
      }

      /* FAQ accordion (one open at a time) */
      var qas = document.querySelectorAll('.qa');
      qas.forEach(function (qa) {
        qa.querySelector('button').addEventListener('click', function () {
          var willOpen = !qa.classList.contains('open');
          qas.forEach(function (o) { o.classList.remove('open'); o.querySelector('button').setAttribute('aria-expanded', 'false'); });
          if (willOpen) { qa.classList.add('open'); this.setAttribute('aria-expanded', 'true'); }
        });
      });

      /* reveal on scroll */
      var els = document.querySelectorAll('.reveal');
      if ('IntersectionObserver' in window) {
        var io = new IntersectionObserver(function (entries) {
          entries.forEach(function (en) { if (en.isIntersecting) { en.target.classList.add('in'); io.unobserve(en.target); } });
        }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
        els.forEach(function (el) { io.observe(el); });
      } else {
        els.forEach(function (el) { el.classList.add('in'); });
      }
    })();
  </script>
  <script>
    (function () {
      var oc = document.getElementById('oc'); if (!oc) return;
      var track = oc.querySelector('.oc-track'), slides = oc.querySelectorAll('.oc-slide'),
          dotsEl = oc.querySelector('.oc-dots'), idx = 0, timer = null, startX = null, paused = false;
      function perView() { return window.innerWidth >= 992 ? 3 : window.innerWidth >= 640 ? 2 : 1; }
      function maxIdx() { return Math.max(0, slides.length - perView()); }
      function buildDots() {
        dotsEl.innerHTML = '';
        for (var i = 0; i <= maxIdx(); i++) {
          (function (n) {
            var b = document.createElement('button'); b.type = 'button'; b.className = 'oc-dot';
            b.setAttribute('aria-label', 'Go to slide ' + (n + 1));
            b.onclick = function () { go(n); restart(); }; dotsEl.appendChild(b);
          })(i);
        }
      }
      function go(n) {
        var m = maxIdx(); idx = n > m ? 0 : n < 0 ? m : n;
        track.style.transform = 'translateX(' + (-idx * (100 / perView())) + '%)';
        var d = dotsEl.children; for (var i = 0; i < d.length; i++) d[i].classList.toggle('active', i === idx);
      }
      function restart() {
        clearInterval(timer);
        if (paused) return;
        if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches && slides.length > perView())
          timer = setInterval(function () { go(idx + 1); }, 4000);
      }
      oc.addEventListener('mouseenter', function () { clearInterval(timer); });
      oc.addEventListener('mouseleave', restart);
      oc.querySelector('.oc-prev').onclick = function () { go(idx - 1); restart(); };
      oc.querySelector('.oc-next').onclick = function () { go(idx + 1); restart(); };
      oc.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; clearInterval(timer); }, { passive: true });
      oc.addEventListener('touchend', function (e) {
        if (startX === null) return; var dx = e.changedTouches[0].clientX - startX;
        if (Math.abs(dx) > 40) go(idx + (dx < 0 ? 1 : -1)); startX = null; restart();
      });
      window.addEventListener('resize', function () { buildDots(); go(Math.min(idx, maxIdx())); restart(); });

      // ----- roles & responsibilities modal -----
      var bd = document.getElementById('rmBackdrop'), lastFocus = null;
      function esc(t) { var d = document.createElement('div'); d.textContent = t; return d.innerHTML; }
      function openRoles(card) {
        var name = card.dataset.name, pos = card.dataset.position, photo = card.dataset.photo;
        document.getElementById('rmName').textContent = name;
        document.getElementById('rmPos').textContent = pos;
        document.getElementById('rmPhoto').innerHTML = photo
          ? '<img class="oc-photo" src="' + esc(photo) + '" alt="' + esc(name) + '">'
          : '<div class="oc-photo oc-initials">' + esc(card.dataset.initials) + '</div>';
        var roles = (window.OFFICIAL_ROLES || {})[pos] || [];
        document.getElementById('rmList').innerHTML = roles.length
          ? roles.map(function (r) { return '<li><i class="bi bi-check-circle-fill"></i><span>' + esc(r) + '</span></li>'; }).join('')
          : '<li class="rm-empty">No roles listed for this position yet.</li>';
        lastFocus = document.activeElement; paused = true; clearInterval(timer);
        bd.classList.add('open'); bd.setAttribute('aria-hidden', 'false'); document.body.style.overflow = 'hidden';
        document.getElementById('rmClose').focus();
      }
      function closeRoles() {
        bd.classList.remove('open'); bd.setAttribute('aria-hidden', 'true'); document.body.style.overflow = '';
        paused = false; restart(); if (lastFocus) lastFocus.focus();
      }
      oc.addEventListener('click', function (e) { var c = e.target.closest('.oc-card'); if (c) openRoles(c); });
      oc.addEventListener('keydown', function (e) {
        var c = e.target.closest && e.target.closest('.oc-card');
        if (c && (e.key === 'Enter' || e.key === ' ')) { e.preventDefault(); openRoles(c); }
      });
      document.getElementById('rmClose').addEventListener('click', closeRoles);
      bd.addEventListener('click', function (e) { if (e.target === bd) closeRoles(); });
      document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && bd.classList.contains('open')) closeRoles(); });
      buildDots(); go(0); restart();
    })();
  </script>
  <script src="/js/pwa.js"></script>
<script src="js/skeleton-pages.js?v=20261026c"></script>
<script src="js/skeleton-public.js?v=20261026c"></script>
<?php include __DIR__ . '/partials/legal_widgets.php'; ?>
</body>
</html>