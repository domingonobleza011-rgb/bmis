<?php
/** Shared layout for the public legal pages (privacy / terms / cookie policy). No login needed. */
$GLOBALS['BMIS_LEGAL'] = require __DIR__ . '/../../Config/legal.php';

/** Print a config value; unfinished placeholders (starting with "[") are highlighted. */
function bl(string $key): string {
    $v = (string)($GLOBALS['BMIS_LEGAL'][$key] ?? '');
    $e = htmlspecialchars($v, ENT_QUOTES, 'UTF-8');
    return (isset($v[0]) && $v[0] === '[') ? '<mark class="ph" title="To be completed by the barangay">' . $e . '</mark>' : $e;
}

function bmis_legal_header(string $title, string $current): void {
    // Modal mode: /privacy?modal=1 returns only the document body (no page chrome) for the pop-up viewer.
    if (!empty($_GET['modal'])) {
        header('X-Robots-Tag: noindex');
        echo '<div class="bmis-legal-doc">';
        return;
    }
    $org = htmlspecialchars($GLOBALS['BMIS_LEGAL']['org_name'], ENT_QUOTES, 'UTF-8');
    $t = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    ?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<title><?= $t ?> – <?= $org ?></title>
<meta name="robots" content="index,follow">
<link rel="icon" href="/icons/pwa/icon-192x192.png">
<style>
:root{--bg:#f0f2f5;--card:#fff;--text:#1c1e21;--muted:#5b6470;--line:#dce0e4;--brand:#1b74e4;--mark:#fff3bf;}
@media (prefers-color-scheme: dark){:root:not([data-theme="light"]){--bg:#0d0d0d;--card:#1a1a1a;--text:#f1f1f1;--muted:#a9b0ba;--line:#333;--brand:#7db7ff;--mark:#5c4a00;}}
html[data-theme="dark"]{--bg:#0d0d0d;--card:#1a1a1a;--text:#f1f1f1;--muted:#a9b0ba;--line:#333;--brand:#7db7ff;--mark:#5c4a00;}
*{box-sizing:border-box}
body{margin:0;background:var(--bg);color:var(--text);font:16px/1.65 system-ui,-apple-system,"Segoe UI",Roboto,sans-serif;}
.bar{background:var(--card);border-bottom:1px solid var(--line);padding:10px 16px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;position:sticky;top:0;z-index:5}
.bar img{width:40px;height:40px;border-radius:50%}
.bar .name{font-weight:800}
.bar nav{margin-left:auto;display:flex;gap:6px;flex-wrap:wrap}
.bar nav a{padding:7px 12px;border-radius:999px;text-decoration:none;color:var(--text);font-weight:600;font-size:14px}
.bar nav a[aria-current="page"]{background:var(--brand);color:#fff}
.bar nav a:hover{background:rgba(27,116,228,.12)}
a{color:var(--brand)} a:focus-visible,button:focus-visible{outline:3px solid #f5a623;outline-offset:2px}
main{max-width:860px;margin:22px auto;padding:0 14px 60px}
article{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:26px 24px}
h1{margin:.1em 0 .2em;font-size:1.8rem} h2{margin:1.6em 0 .4em;font-size:1.2rem;border-bottom:1px solid var(--line);padding-bottom:.25em}
.meta{color:var(--muted);font-size:.9rem;margin:0 0 1em}
table{width:100%;border-collapse:collapse;margin:.6em 0 1em;font-size:.93rem}
th,td{border:1px solid var(--line);padding:8px 10px;text-align:left;vertical-align:top} th{background:rgba(127,127,127,.12)}
.tw{overflow-x:auto}
mark.ph{background:var(--mark);color:inherit;padding:0 4px;border-radius:4px}
.note{background:rgba(27,116,228,.08);border-left:4px solid var(--brand);padding:10px 14px;border-radius:6px;margin:1em 0}
footer{max-width:860px;margin:0 auto;padding:0 14px 40px;color:var(--muted);font-size:.85rem;text-align:center}
footer a{color:var(--muted)}
@media(max-width:600px){article{padding:20px 16px}.bar nav{margin-left:0;width:100%}}
</style>
<script>try{var t=localStorage.getItem('bmis_theme');if(t==='dark'||t==='light')document.documentElement.setAttribute('data-theme',t);}catch(e){}</script>
</head>
<body>
<header class="bar">
  <img src="/icons/pwa/icon-192x192.png" alt="Seal of <?= $org ?>">
  <span class="name"><?= $org ?></span>
  <nav aria-label="Legal pages">
    <a href="/privacy"<?= $current==='privacy'?' aria-current="page"':'' ?>>Privacy Notice</a>
    <a href="/terms"<?= $current==='terms'?' aria-current="page"':'' ?>>Terms</a>
    <a href="/cookie-policy"<?= $current==='cookies'?' aria-current="page"':'' ?>>Cookie Policy</a>
    <a href="/">Home</a>
  </nav>
</header>
<main id="main-content">
<article>
<?php
}

function bmis_legal_footer(): void {
    if (!empty($_GET['modal'])) { echo '</div>'; return; }
    $org = htmlspecialchars($GLOBALS['BMIS_LEGAL']['org_name'], ENT_QUOTES, 'UTF-8');
    ?>
</article>
</main>
<footer>
  <p>&copy; <?= date('Y') ?> <?= $org ?>. All rights reserved.
  &middot; <a href="/privacy">Privacy Notice</a> &middot; <a href="/terms">Terms</a> &middot; <a href="/cookie-policy">Cookie Policy</a></p>
</footer>
</body>
</html>
<?php
}
