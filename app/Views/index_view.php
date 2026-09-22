<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="theme-color" content="#ffffff">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Brgy San Pedro">
    <link rel="apple-touch-icon" href="/icons/pwa/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/icons/pwa/favicon-32x32.png">
    <link rel="manifest" href="/manifest.json">
    <title>Barangay San Pedro - Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <style>
    :root {
      --blue: #1447e6;
      --blue-deep: #0b2a86;
      --red: #0a18e2;
      --pink-bg: #fdecee;
      --ink: #1c2430;
      --ink-soft: #6b7280;
      --line: #eceef2;
      --shadow-sm: 0 8px 20px rgba(16,24,40,.06);
      --radius-lg: 24px;
    }
    * { box-sizing: border-box; }
    body {
      margin: 0;
      background: #fff;
      color: var(--ink);
      font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
      -webkit-font-smoothing: antialiased;
      padding-top: 76px; /* navbar fixed offset */
    }
    a { text-decoration: none; }

    /* ---------- navbar fixed ---------- */
    .nav-portal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      z-index: 1050;
      padding: 0.8rem 0;
      border-bottom: 1px solid var(--line);
      background: rgba(255,255,255,0.82);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      box-shadow: 0 2px 12px rgba(0,0,0,0.04);
      transition: background 0.2s, box-shadow 0.2s;
    }
    .nav-portal.scrolled {
      background: rgba(255,255,255,0.96);
      box-shadow: 0 6px 24px rgba(0,0,0,0.06);
    }
    .wordmark {
      font-size: 1.4rem;
      font-weight: 800;
      letter-spacing: -0.01em;
      transition: all 0.2s;
    }
    .wordmark .b { color: var(--blue); }
    .wordmark .sp { color: var(--blue); }
    .nav-portal .nav-link {
      color: #374151;
      font-weight: 600;
      font-size: .92rem;
      padding: .35rem .9rem !important;
      border-radius: 40px;
      transition: all 0.2s;
    }
    .nav-portal .nav-link:hover {
      background: #f0f4ff;
      color: var(--blue);
    }
    .btn-blue {
      background: var(--blue);
      border-color: var(--blue);
      color: #fff;
      font-weight: 700;
      border-radius: 40px;
      padding: 0.4rem 1.2rem;
      transition: all 0.2s;
      box-shadow: 0 4px 10px rgba(20,71,230,0.15);
      font-size: 0.9rem;
    }
    .btn-blue:hover {
      background: var(--blue-deep);
      border-color: var(--blue-deep);
      color: #fff;
      transform: translateY(-2px);
      box-shadow: 0 8px 18px rgba(20,71,230,0.25);
    }
    .btn-ghost {
      color: var(--ink);
      font-weight: 700;
      border-radius: 40px;
      padding: 0.4rem 1.2rem;
      transition: all 0.2s;
      font-size: 0.9rem;
    }
    .btn-ghost:hover {
      background: #f0f4ff;
      color: var(--blue);
    }

    /* ---------- hero ---------- */
    .hero {
      padding: 4rem 0 3rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    .hero::before {
      content: "";
      position: absolute;
      width: 500px;
      height: 500px;
      background: radial-gradient(circle, rgba(20,71,230,0.03) 0%, transparent 70%);
      top: -200px;
      right: -100px;
      border-radius: 50%;
      pointer-events: none;
    }
    .hero h1 {
      font-size: clamp(2.2rem, 4.8vw, 3.4rem);
      font-weight: 800;
      letter-spacing: -0.02em;
      max-width: 16ch;
      margin: 0 auto 1.1rem;
      line-height: 1.1;
    }
    .hero p {
      color: var(--ink-soft);
      font-size: 1.15rem;
      max-width: 52ch;
      margin: 0 auto;
    }
    .hero .cta-row {
      margin-top: 2.2rem;
      display: flex;
      gap: 0.8rem;
      justify-content: center;
      flex-wrap: wrap;
    }
    .hero .btn-outline-secondary {
      border-radius: 40px;
      font-weight: 700;
      border-color: #d1d5db;
      color: var(--ink);
      transition: all 0.2s;
    }
    .hero .btn-outline-secondary:hover {
      background: var(--ink);
      color: #fff;
      border-color: var(--ink);
    }

    /* ---------- problem cards ---------- */
    .problems {
      padding: 2.5rem 0 3rem;
    }
    .problem-card {
      background: var(--pink-bg);
      border-radius: var(--radius-lg);
      padding: 2rem 1.8rem;
      height: 100%;
      transition: all 0.2s ease;
      border: 1px solid transparent;
    }
    .problem-card:hover {
      transform: translateY(-6px);
      border-color: #fbd9dd;
      box-shadow: var(--shadow-sm);
    }
    .problem-card .icon-box {
      width: 52px;
      height: 52px;
      border-radius: 16px;
      background: #fbd9dd;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--red);
      font-size: 1.4rem;
      margin-bottom: 1.4rem;
    }
    .problem-card h3 {
      font-size: 1.2rem;
      font-weight: 700;
      margin-bottom: .5rem;
    }
    .problem-card p {
      color: var(--ink-soft);
      font-size: .92rem;
      margin: 0;
    }

    /* ---------- solution band ---------- */
    .solution-band {
      background:var(--blue-deep);
      border-radius: 32px;
      padding: 3.8rem 2.5rem;
      text-align: center;
      color: #fff;
      position: relative;
      overflow: hidden;
      box-shadow: 0 16px 40px rgba(20,71,230,0.2);
    }
    .solution-band::before {
      content: "";
      position: absolute;
      width: 400px;
      height: 400px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
      top: -160px;
      right: -120px;
    }
    .solution-band::after {
      content: "";
      position: absolute;
      width: 260px;
      height: 260px;
      border-radius: 50%;
      background: rgba(255,255,255,.04);
      bottom: -140px;
      left: -80px;
    }
    .solution-pill {
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      background: rgba(255,255,255,.12);
      color: #dbe4ff;
      font-size: .8rem;
      font-weight: 700;
      padding: .4rem 1.2rem;
      border-radius: 60px;
      margin-bottom: 1.6rem;
      position: relative;
      z-index: 1;
      backdrop-filter: blur(2px);
    }
    .solution-band h2 {
      font-size: clamp(1.8rem, 3.8vw, 2.6rem);
      font-weight: 800;
      max-width: 24ch;
      margin: 0 auto 1.2rem;
      line-height: 1.2;
      position: relative;
      z-index: 1;
    }
    .solution-band .quote {
      color: #c6d3fb;
      font-style: italic;
      font-size: 1.05rem;
      position: relative;
      z-index: 1;
      opacity: 0.9;
    }

    /* ---------- steps ---------- */
    .steps-section {
      padding: 4.5rem 0 2rem;
    }
    .section-eyebrow {
      text-align: center;
      color: var(--blue);
      font-weight: 700;
      font-size: .8rem;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: .6rem;
    }
    .section-title {
      text-align: center;
      font-size: 2.2rem;
      font-weight: 800;
      letter-spacing: -0.01em;
      margin-bottom: 2.6rem;
    }
    .section-title .accent { color: var(--blue); }
    .step-card {
      text-align: center;
      padding: 0 1rem;
    }
    .step-card .num {
      width: 48px;
      height: 48px;
      border-radius: 50%;
      background: var(--pink-bg);
      color: var(--red);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 1.2rem;
      margin: 0 auto 1.2rem;
      transition: all 0.2s;
    }
    .step-card:hover .num {
      background: var(--red);
      color: #fff;
      transform: scale(1.05);
    }
    .step-card h3 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: .4rem;
    }
    .step-card p {
      color: var(--ink-soft);
      font-size: .9rem;
      margin: 0;
    }

    /* ---------- services ---------- */
    .services-section {
      padding: 4.5rem 0 4rem;
      background: #f8f9fc;
    }
    .search-field {
      margin: 0 auto 2.8rem;
      max-width: 520px;
      border: 1px solid var(--line);
      border-radius: 60px;
      background: #fff;
      display: flex;
      align-items: center;
      padding: .15rem .15rem .15rem 1.6rem;
      box-shadow: 0 2px 8px rgba(16,24,40,.04);
      transition: all 0.2s;
    }
    .search-field:focus-within {
      border-color: var(--blue);
      box-shadow: 0 4px 16px rgba(20,71,230,0.08);
    }
    .search-field input {
      border: 0;
      outline: 0;
      flex: 1;
      padding: .7rem .5rem;
      font-size: .95rem;
      background: transparent;
    }
    .search-field button {
      border-radius: 60px;
      padding: .6rem 1.6rem;
      background: var(--blue);
      border: 0;
      color: #fff;
      font-weight: 600;
      transition: all 0.2s;
    }
    .search-field button:hover {
      background: var(--blue-deep);
    }

    .svc-card {
      background: #fff;
      border: 1px solid var(--line);
      border-radius: 20px;
      padding: 1.6rem 1.6rem 1.4rem;
      height: 100%;
      display: flex;
      flex-direction: column;
      transition: all 0.2s ease;
      box-shadow: 0 2px 6px rgba(0,0,0,0.01);
      cursor: default;
    }
    .svc-card:hover {
      transform: translateY(-6px);
      box-shadow: 0 16px 32px rgba(16,24,40,.08);
      border-color: #c9d6fb;
    }
    .svc-icon {
      width: 48px;
      height: 48px;
      border-radius: 16px;
      background: #e8edfd;
      color: var(--blue);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.3rem;
      margin-bottom: 1.2rem;
      transition: all 0.2s;
    }
    .svc-card:hover .svc-icon {
      background: var(--blue);
      color: #fff;
    }
    .svc-card h3 {
      font-size: 1.1rem;
      font-weight: 700;
      margin-bottom: .4rem;
    }
    .svc-card p {
      font-size: .86rem;
      color: var(--ink-soft);
      flex: 1;
      margin-bottom: 1.2rem;
    }
    .svc-card .svc-go {
      font-size: .86rem;
      font-weight: 700;
      color: var(--blue);
      display: inline-flex;
      align-items: center;
      gap: .4rem;
      pointer-events: none;
    }
    .svc-card .svc-go i {
      transition: transform 0.2s ease;
    }
    .svc-card:hover .svc-go i {
      transform: translateX(6px);
    }
    .svc-search-empty {
      display: none;
      text-align: center;
      padding: 2.5rem 0;
      color: var(--ink-soft);
    }

    /* ---------- concern band ---------- */
    .concern-band {
      background: #fff;
      padding: 3.5rem 0;
    }
    .concern-inner {
      background: #f8f9fc;
      border: 1px solid var(--line);
      border-radius: 28px;
      padding: 2.8rem 2.8rem;
      display: flex;
      flex-wrap: wrap;
      gap: 1.2rem;
      align-items: center;
      justify-content: space-between;
    }
    .concern-inner h3 {
      font-weight: 800;
      font-size: 1.4rem;
      margin-bottom: .3rem;
    }
    .concern-inner p {
      color: var(--ink-soft);
      margin: 0;
      max-width: 46ch;
    }
    .concern-inner .btn-blue {
      padding: 0.6rem 2rem;
    }

    /* ---------- FAQ ---------- */
    .faq-section {
      padding: 5rem 0 4.5rem;
      background: #fff;
    }
    .faq-list {
      max-width: 760px;
      margin: 0 auto;
    }
    .faq-item {
      border: 1px solid var(--line);
      border-radius: 18px;
      margin-bottom: 1rem;
      overflow: hidden;
      transition: all 0.2s ease;
      background: #fff;
    }
    .faq-item.open {
      border-color: #c9d6fb;
      background: #f5f7ff;
      box-shadow: 0 4px 12px rgba(20,71,230,0.04);
    }
    .faq-q {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 1rem;
      padding: 1.2rem 1.6rem;
      cursor: pointer;
      user-select: none;
      font-weight: 700;
      font-size: 1rem;
      color: var(--ink);
    }
    .faq-q i {
      color: var(--blue);
      flex-shrink: 0;
      transition: transform 0.25s ease;
      font-size: 1.1rem;
    }
    .faq-item.open .faq-q i {
      transform: rotate(180deg);
    }
    .faq-a {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.25s ease, padding 0.25s ease;
      padding: 0 1.6rem;
    }
    .faq-item.open .faq-a {
      max-height: 220px;
      padding: 0 1.6rem 1.2rem;
    }
    .faq-a p {
      color: var(--ink-soft);
      font-size: .9rem;
      margin: 0;
      line-height: 1.6;
    }

    /* ---------- footer ---------- */
    footer.site-footer {
      border-top: 1px solid var(--line);
      padding: 3rem 0 1.6rem;
      background: #fafbfc;
    }
    footer.site-footer h6 {
      font-size: .78rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: var(--ink);
      font-weight: 800;
      margin-bottom: 1rem;
    }
    footer.site-footer li {
      margin-bottom: .5rem;
      font-size: .88rem;
    }
    footer.site-footer a {
      color: var(--ink-soft);
      transition: color 0.15s;
    }
    footer.site-footer a:hover {
      color: var(--blue);
    }
    .footer-base {
      border-top: 1px solid var(--line);
      margin-top: 2.5rem;
      padding-top: 1.2rem;
      font-size: .78rem;
      color: var(--ink-soft);
    }

    /* responsive tweaks */
    @media (max-width: 768px) {
      body { padding-top: 66px; }
      .hero { padding: 3rem 0 2rem; }
      .solution-band { padding: 2.8rem 1.5rem; }
      .concern-inner { padding: 1.8rem; }
      .nav-portal .container { flex-wrap: wrap; gap: 0.4rem; }
      .section-title { font-size: 1.8rem; }
      .nav-portal .nav-link { font-size: .85rem; padding: .3rem .7rem !important; }
      .btn-blue, .btn-ghost { font-size: .8rem; padding: 0.3rem 1rem; }
    }
  </style>
</head>
<body>

<!-- navbar fixed -->
<nav class="nav-portal" id="navbar">
  <div class="container d-flex align-items-center justify-content-between flex-wrap">
    <a href="#" class="wordmark"><span class="b">Brgy</span><span class="sp">SanPedro</span></a>
    <div class="d-none d-lg-flex align-items-center gap-1">
      <a href="#problems" class="nav-link">Problems</a>
      <a href="#solution" class="nav-link">Solution</a>
      <a href="#how-it-works" class="nav-link">How It Works</a>
      <a href="#services" class="nav-link">Services</a>
      <a href="#faq" class="nav-link">FAQ</a>
    </div>
    <div class="d-flex align-items-center gap-2">
      <a href="resident_registration.php" class="btn btn-ghost px-3">Register</a>
      <a href="login.php" class="btn btn-blue px-4">Log In</a>
    </div>
  </div>
</nav>

<!-- hero -->
<header class="hero">
  <div class="container">
    <h1>Barangay services shouldn't mean waiting in line.</h1>
    <p>Residents of Barangay San Pedro deserve better access to barangay services. No more long queues, lost forms, or confusing walk-ins.</p>
    <div class="cta-row">
      <a href="index.php" class="btn btn-blue px-4 py-2">Log In</a>
      <a href="#services" class="btn btn-outline-secondary px-4 py-2">Browse Services</a>
    </div>
  </div>
</header>

<!-- problem cards -->
<section class="problems" id="problems">
  <div class="container">
    <div class="section-eyebrow">The Problem</div>
    <h2 class="section-title">Barangay Services, <span class="accent">Simplified.</span></h2>
    <p class="text-center text-secondary mb-5" style="max-width:50ch;margin-left:auto;margin-right:auto;">Residents of Barangay San Pedro deserve better access to barangay services. No more unnecessary trips, lost forms, or confusing processes.</p>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="problem-card">
          <div class="icon-box"><i class="bi bi-hourglass-split"></i></div>
          <h3>Long Queues</h3>
          <p>Hours spent lining up at the barangay hall for simple requests.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="problem-card">
          <div class="icon-box"><i class="bi bi-file-earmark-text"></i></div>
          <h3>Paper-based Forms</h3>
          <p>Manual forms, redundant details, and slow, in-person approvals.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="problem-card">
          <div class="icon-box"><i class="bi bi-signpost-split"></i></div>
          <h3>Walk-in Only</h3>
          <p>No way to check request status without going back to the hall.</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- solution band -->
<div class="container" id="solution">
  <div class="solution-band">
    <span class="solution-pill"><i class="bi bi-arrow-right"></i> The Solution</span>
    <h2>Barangay San Pedro brings every barangay service into one secure, easy-to-use portal.</h2>
    <p class="quote">"No more waiting in line. Your barangay, in your pocket."</p>
  </div>
</div>

<!-- steps -->
<section class="steps-section" id="how-it-works">
  <div class="container">
    <div class="section-eyebrow">How It Works</div>
    <h2 class="section-title">Three steps, no walk-in required</h2>
    <div class="row g-5">
      <div class="col-md-4 step-card">
        <div class="num">1</div>
        <h3>Sign in or register</h3>
        <p>Use your resident account to file requests. New here? Registration takes a few minutes.</p>
      </div>
      <div class="col-md-4 step-card">
        <div class="num">2</div>
        <h3>Pick a service and fill it out</h3>
        <p>Choose the certificate or permit you need and complete the form online.</p>
      </div>
      <div class="col-md-4 step-card">
        <div class="num">3</div>
        <h3>Track and claim</h3>
        <p>Follow your request status from your dashboard, then claim it at the barangay hall.</p>
      </div>
    </div>
  </div>
</section>

<!-- services grid -->
<section class="services-section" id="services">
  <div class="container">
    <div class="section-eyebrow">Services</div>
    <h2 class="section-title">Available <span class="accent">Services</span></h2>



    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4 service-grid" id="serviceGrid">
      <!-- each card — all links removed -->
      <div class="col svc-item" data-label="business permit">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-file-earmark-medical-fill"></i></div>
          <h3>Business Permit</h3>
          <p>Apply for or renew your business permit online.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="barangay id">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-person-vcard-fill"></i></div>
          <h3>Barangay ID</h3>
          <p>Get your official Barangay ID quickly.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="indigency">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-briefcase-fill"></i></div>
          <h3>Certificate of Indigency</h3>
          <p>For financial assistance and social services.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="residency">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-house-check-fill"></i></div>
          <h3>Certificate of Residency</h3>
          <p>Proof of residence for various transactions.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="good moral">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-patch-check-fill"></i></div>
          <h3>Good Moral Character</h3>
          <p>For employment, school, or visa applications.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="livestock">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-clipboard2-pulse-fill"></i></div>
          <h3>Certificate of Livestock</h3>
          <p>For livestock owners and agricultural purposes.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="guardianship">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-people-fill"></i></div>
          <h3>Guardianship</h3>
          <p>Legal guardianship certification.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="solo parent">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-person-heart"></i></div>
          <h3>Solo Parent</h3>
          <p>For solo parents seeking benefits and support.</p>
        </div>
      </div>
      <div class="col svc-item" data-label="clearance">
        <div class="svc-card">
          <div class="svc-icon"><i class="bi bi-file-earmark-check-fill"></i></div>
          <h3>Barangay Clearance</h3>
          <p>General clearance for permits and transactions.</p>
        </div>
      </div>
    </div>
    <div id="svcEmpty" class="svc-search-empty">No services match your search.</div>
  </div>
</section>

<!-- FAQ -->
<section class="faq-section" id="faq">
  <div class="container">
    <div class="section-eyebrow">FAQ</div>
    <h2 class="section-title">Frequently Asked <span class="accent">Questions</span></h2>
    <p class="text-center text-secondary mb-5" style="max-width:46ch;margin-left:auto;margin-right:auto;">Everything you need to know about the Barangay San Pedro eServices portal.</p>

    <div class="faq-list">
      <div class="faq-item">
<div class="faq-q">
<span>Is this portal free to use?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-a"><p>Yes. Filing a request through this portal is free. You only pay the standard fee for the certificate or permit itself, the same as requesting it in person.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">
          <span>Is my data secure?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-a"><p>Your information is only accessible to barangay staff who process your request, and every account is protected by a password and login verification.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">
          <span>What barangay services are available online?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-a"><p>Barangay Clearance, Barangay ID, Certificate of Residency, Indigency, Good Moral Character, Guardianship, Solo Parent Certification, Certificate of Livestock, and Business Permit.</p></div>
      </div>
      <div class="faq-item">
        <div class="faq-q">
          <span>How do I verify my identity?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-a"><p>When you register, you will provide your resident details for the barangay to verify against its records. Once verified, you can file requests under your account.</p></div>
      </div>
      <div class="faq-item open">
       <div class="faq-q">
          <span>Can I use my account on multiple devices?</span>
          <i class="bi bi-chevron-down"></i>
        </div>
        <div class="faq-a"><p>Yes. You can log in with your credentials from any phone, tablet, or computer, and your requests will stay in sync across all of them.</p></div>
      </div>
    </div>
  </div>
</section>

<!-- footer -->
<footer class="site-footer">
  <div class="container">
    <div class="row g-4">
      <!-- Column 1: Brand -->
      <div class="col-6 col-md-3">
        <div class="wordmark mb-2" style="font-size:1.15rem;">
          <span class="b">Brgy</span><span class="sp">SanPedro</span>
        </div>
        <p class="text-secondary" style="font-size:.85rem;max-width:22ch;">
          Bringing barangay services online for the residents of San Pedro, Iriga City.
        </p>
      </div>

      <!-- Column 2: Quick Links -->
      <div class="col-6 col-md-3">
        <h6>Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="#services">Services</a></li>
          <li><a href="#faq">FAQ</a></li>
          <li><a href="resident_registration.php">Register</a></li>
          <li><a href="login.php">Log In</a></li>
        </ul>
      </div>

      <!-- Column 3: Contact -->
      <div class="col-6 col-md-3">
        <h6>Contact</h6>
        <ul class="list-unstyled">
          <li class="d-flex gap-2">
            <i class="bi bi-geo-alt-fill" style="color:var(--blue);"></i>
            <span class="text-secondary">Barangay Hall, San Pedro, Iriga City, Camarines Sur</span>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-telephone-fill" style="color:var(--blue);"></i>
            <span class="text-secondary">(054) 000-0000</span>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-envelope-fill" style="color:var(--blue);"></i>
            <span class="text-secondary">brgysanpedro.iriga@gmail.com</span>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-clock-fill" style="color:var(--blue);"></i>
            <span class="text-secondary">Mon–Fri, 8:00 AM–5:00 PM</span>
          </li>
        </ul>
      </div>

      <!-- Column 4: Emergency Hotlines -->
      <div class="col-6 col-md-3">
        <h6>Emergency Hotlines</h6>
        <ul class="list-unstyled">
          <li class="d-flex gap-2">
            <i class="bi bi-shield-fill-exclamation" style="color:var(--red);"></i>
            <span class="text-secondary">Barangay Tanod: 0900-000-0000</span>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-hospital-fill" style="color:var(--red);"></i>
            <span class="text-secondary">Health Center: 0900-000-0000</span>
          </li>
          <li class="d-flex gap-2">
            <i class="bi bi-cone-striped" style="color:var(--red);"></i>
            <span class="text-secondary">Police Sub-Station: 0900-000-0000</span>
          </li>
        </ul>
      </div>
    </div>

    <div class="footer-base d-flex flex-column flex-md-row justify-content-between gap-2">
      <span>© 2026 Barangay San Pedro Management Information System. All rights reserved.</span>
<span><i class="bi bi-shield-lock-fill" style="color:var(--blue);"></i> Your data is protected under RA 10173 – Data Privacy Act of 2012.</span>    </div>

  </div>
</footer>

<!-- search & scroll script -->
<script>
  // Search
  const search = document.getElementById('svcSearch');
  const items = document.querySelectorAll('.svc-item');
  const empty = document.getElementById('svcEmpty');

  if (search) {
    search.addEventListener('input', function () {
      const q = this.value.trim().toLowerCase();
      let visible = 0;
      items.forEach(el => {
        const label = el.getAttribute('data-label') || '';
        const match = label.includes(q);
        el.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      if (empty) empty.style.display = visible === 0 ? 'block' : 'none';
    });
  }

  // Add shadow on scroll
  const navbar = document.getElementById('navbar');
  window.addEventListener('scroll', () => {
    if (window.scrollY > 10) {
      navbar.classList.add('scrolled');
    } else {
      navbar.classList.remove('scrolled');
    }
  });
  
  // FAQ accordion - only one open at a time
document.querySelectorAll('.faq-q').forEach(q => {
  q.addEventListener('click', () => {
    const item = q.parentElement;
    const isOpen = item.classList.contains('open');

    document.querySelectorAll('.faq-item.open').forEach(el => {
      el.classList.remove('open');
    });

    if (!isOpen) {
      item.classList.add('open');
    }
  });
});
</script>
<script src="/js/pwa.js"></script>
</body>
</html>