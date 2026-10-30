<?php
/* ============================================================
   RESIDENT THEME + TEXT SETTINGS
   Included by resident_navbar.php, so every resident page gets it.

   - Theme:  Default (original blue design) / Light / Dark
   - Text size: Small / Medium / Large
   - Font: Default + 11 choices
   Same behaviour (and same localStorage keys) as the admin
   dashboard, so a person's choice follows them everywhere:
     bmis_theme, bmis_fontsize, bmis_font
   ============================================================ */

$rtm_fonts = [
    'default'      => ['Default', null],
    'nunito'       => ['Nunito', "'Nunito', sans-serif"],
    'inter'        => ['Inter', "'Inter', sans-serif"],
    'poppins'      => ['Poppins', "'Poppins', sans-serif"],
    'roboto'       => ['Roboto', "'Roboto', sans-serif"],
    'opensans'     => ['Open Sans', "'Open Sans', sans-serif"],
    'lato'         => ['Lato', "'Lato', sans-serif"],
    'montserrat'   => ['Montserrat', "'Montserrat', sans-serif"],
    'merriweather' => ['Merriweather', "'Merriweather', serif"],
    'serif'        => ['Serif (Georgia)', "Georgia, 'Times New Roman', serif"],
    'verdana'      => ['Verdana', "Verdana, Geneva, sans-serif"],
    'mono'         => ['Monospace', "ui-monospace, 'Cascadia Mono', Consolas, 'Courier New', monospace"],
];

/* Renders the two buttons + dropdown menus (used for desktop and mobile bars). */
function rtm_render_controls(array $fonts)
{ ?>
    <div class="rtm-controls">
        <div class="rtm-wrap">
            <button type="button" class="rtm-btn" data-rtm-toggle aria-haspopup="true" aria-expanded="false" title="Theme" aria-label="Theme">
                <i class="bi bi-circle-half" data-rtm-theme-icon></i>
            </button>
            <div class="rtm-menu" role="menu">
                <div class="rtm-head">Theme</div>
                <a href="#" class="rtm-item" data-theme-choice="light"><i class="bi bi-sun-fill"></i> Light <i class="bi bi-check-lg rtm-check"></i></a>
                <a href="#" class="rtm-item" data-theme-choice="dark"><i class="bi bi-moon-stars-fill"></i> Dark <i class="bi bi-check-lg rtm-check"></i></a>
                <a href="#" class="rtm-item" data-theme-choice="default"><i class="bi bi-display"></i> Default <i class="bi bi-check-lg rtm-check"></i></a>
            </div>
        </div>
        <div class="rtm-wrap">
            <button type="button" class="rtm-btn" data-rtm-toggle aria-haspopup="true" aria-expanded="false" title="Text size &amp; font" aria-label="Text size and font">
                <i class="bi bi-fonts"></i>
            </button>
            <div class="rtm-menu rtm-menu-tall" role="menu">
                <div class="rtm-head">Text size</div>
                <a href="#" class="rtm-item" data-size-choice="small"><span class="rtm-a" style="font-size:.8em">A</span> Small <i class="bi bi-check-lg rtm-check"></i></a>
                <a href="#" class="rtm-item" data-size-choice="medium"><span class="rtm-a" style="font-size:1em">A</span> Medium <i class="bi bi-check-lg rtm-check"></i></a>
                <a href="#" class="rtm-item" data-size-choice="large"><span class="rtm-a" style="font-size:1.25em">A</span> Large <i class="bi bi-check-lg rtm-check"></i></a>
                <div class="rtm-sep"></div>
                <div class="rtm-head">Font</div>
                <?php foreach ($fonts as $key => $f): ?>
                    <a href="#" class="rtm-item" data-font-choice="<?= htmlspecialchars($key) ?>">
                        <span<?= $f[1] ? ' style="font-family:' . htmlspecialchars($f[1], ENT_QUOTES) . '"' : '' ?>><?= htmlspecialchars($f[0]) ?></span>
                        <i class="bi bi-check-lg rtm-check"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php }
?>
<!-- Font choices offered in the Text & Font menu -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Nunito:wght@400;500;600;700;800&family=Poppins:wght@400;500;600;700&family=Roboto:wght@400;500;700&family=Open+Sans:wght@400;600;700&family=Lato:wght@400;700&family=Montserrat:wght@400;500;600;700&family=Merriweather:wght@400;700&display=swap" rel="stylesheet">

<!-- Apply saved theme / text size / font before the page paints (no flash) -->
<script>
try {
    var __r = document.documentElement;
    var __t = localStorage.getItem('bmis_theme');
    if (__t === 'light' || __t === 'dark') __r.setAttribute('data-theme', __t);
    var __fs = localStorage.getItem('bmis_fontsize');
    if (__fs === 'small' || __fs === 'large') __r.setAttribute('data-fontsize', __fs);
    var __ff = localStorage.getItem('bmis_font');
    if (<?= json_encode(array_values(array_diff(array_keys($rtm_fonts), ['default']))) ?>.indexOf(__ff) > -1) __r.setAttribute('data-font', __ff);
} catch (e) {}
</script>

<link rel="stylesheet" href="resident_theme.css?v=<?= @filemtime(__DIR__ . '/resident_theme.css') ?: 1 ?>">
<script src="resident_theme.js?v=<?= @filemtime(__DIR__ . '/resident_theme.js') ?: 1 ?>" defer></script>
