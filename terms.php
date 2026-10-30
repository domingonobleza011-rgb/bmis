<?php
require __DIR__ . '/app/Views/partials/legal_layout.php';
bmis_legal_header('Terms and Conditions', 'terms');
?>
<h1>Terms and Conditions</h1>
<p class="meta">Effective <?= bl('effective') ?> &middot; Last updated <?= bl('last_updated') ?></p>

<p>These terms govern your use of the <?= bl('system_name') ?> operated by <?= bl('org_name') ?>. By creating an account or using the site you agree to them.
Please also read our <a href="/privacy">Privacy Notice</a> and <a href="/cookie-policy">Cookie Policy</a>.</p>

<h2>1. Who may use the site</h2>
<p>The resident portal is for residents of <?= bl('org_name') ?> and for authorized Barangay officials and staff. If you are under 18, use it with the help of a parent or guardian.</p>

<h2>2. Your account</h2>
<ul>
  <li>Provide information that is true, complete and current, and keep it up to date.</li>
  <li>The account is for your exclusive use. Keep your password and security answers secret and do not let others sign in as you.</li>
  <li>Tell the Barangay right away if you think your account has been used without your permission.</li>
</ul>

<h2>3. Requests and services</h2>
<p>Submitting a request (certificate, clearance, ID, permit, complaint) does not guarantee approval. Requests are verified by Barangay staff,
may need supporting documents, and may be subject to the fees and rules set by the Barangay. Giving false information or false documents may be
punished under Philippine law, and the Barangay may refuse or cancel the request.</p>

<h2>4. Acceptable use</h2>
<p>You agree not to: use another person’s identity or information; upload unlawful, offensive or malicious content or files; try to access accounts or data that
are not yours; interfere with or attempt to break the security of the site; use automated tools to scrape or overload it; or use the site to harass others.
The Barangay may suspend or close accounts that break these terms.</p>

<h2>5. Your information</h2>
<p>How we collect and use your personal information is explained in the <a href="/privacy">Privacy Notice</a>. Where a form asks for your consent, you can give it
by ticking the consent box; you may withdraw consent later as described in the Privacy Notice.</p>

<h2>6. Intellectual property and image credits</h2>
<ul>
  <li>The Barangay seal, logo, text, announcements and other content on this site belong to <?= bl('org_name') ?> and are protected by copyright and other laws.
      You may not copy or reuse them for any other purpose without written permission, except to the extent allowed by law.</li>
  <li>Photos of officials, programs and events are used with the permission of the people shown or the photographers. If you believe a photo or other content on this
      site infringes your rights or shows you without permission, contact our Compliance Officer for Privacy (see the Privacy Notice) and we will review it promptly.</li>
  <li>This site uses open-source software under their own licenses, including Bootstrap and Bootstrap Icons (MIT), jQuery (MIT), DataTables (MIT),
      Font Awesome Free (icons: CC BY 4.0, fonts: SIL OFL 1.1, code: MIT) and Firebase (Apache 2.0). All trademarks belong to their owners.</li>
  <li>If you upload a photo or document, you confirm you have the right to share it with the Barangay for the stated purpose.</li>
</ul>

<h2>7. Availability and changes</h2>
<p>We work to keep the site available, but it may be unavailable for maintenance or reasons beyond our control. We may change, suspend or stop any feature.
The Barangay is not responsible for losses caused by interruptions, or by incorrect information that you provide, to the extent allowed by law.</p>

<h2>8. Changes to these terms</h2>
<p>We may update these terms. The “Last updated” date will change, and continued use of the site after an update means you accept it.</p>

<h2>9. Governing law and contact</h2>
<p>These terms are governed by the laws of the Republic of the Philippines. Questions can be sent to: <?= bl('dpo_name') ?>, <?= bl('dpo_email') ?>, <?= bl('office_address') ?>.</p>
<?php bmis_legal_footer();
