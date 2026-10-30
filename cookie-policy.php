<?php
require __DIR__ . '/app/Views/partials/legal_layout.php';
bmis_legal_header('Cookie Policy', 'cookies');
?>
<h1>Cookie Policy</h1>
<p class="meta">Effective <?= bl('effective') ?> &middot; Last updated <?= bl('last_updated') ?></p>

<p>This page explains the cookies and similar browser storage used by the <?= bl('system_name') ?>. <strong>We do not use advertising, analytics or tracking cookies.</strong>
Everything below is needed to run the site or to remember your own settings.</p>

<h2>1. What we store on your device</h2>
<div class="tw"><table>
<thead><tr><th>Name</th><th>Type</th><th>Purpose</th><th>How long</th></tr></thead>
<tbody>
<tr><td>Session cookie (usually <code>PHPSESSID</code>)</td><td>Cookie &ndash; essential</td><td>Keeps you signed in and protects forms and requests from forgery.</td><td>Until you sign out or close the browser</td></tr>
<tr><td><code>bmis_theme</code>, <code>bmis_font</code>, <code>bmis_fontsize</code></td><td>Local storage &ndash; preference</td><td>Remember your light/dark theme, font and text size.</td><td>Until you clear your browser data</td></tr>
<tr><td><code>bmis_cookie_notice</code></td><td>Local storage &ndash; preference</td><td>Remembers that you saw the cookie notice.</td><td>Until you clear your browser data</td></tr>
<tr><td><code>pwa-install-dismissed</code></td><td>Local storage &ndash; preference</td><td>Remembers that you dismissed the “install the app” prompt.</td><td>Until you clear your browser data</td></tr>
<tr><td><code>notif-banner-dismissed</code>, <code>bmisActiveChat</code>, <code>archiveToast</code></td><td>Session storage &ndash; functional</td><td>Remember which banner or chat is open during your visit.</td><td>Until you close the tab</td></tr>
<tr><td><code>bmis_resident_registration_draft</code></td><td>Session storage &ndash; functional</td><td>Keeps what you typed in the registration form if the page reloads. It is deleted when you submit or close the tab.</td><td>Until you close the tab</td></tr>
<tr><td>Offline cache (service worker)</td><td>Browser cache</td><td>Lets the installed app open faster and show basic pages without a connection.</td><td>Until updated or cleared</td></tr>
</tbody></table></div>

<h2>2. Third-party services</h2>
<p>When you open the site, your browser may contact the services below to load code, icons or fonts, or to protect the site. These providers can
see your IP address and browser details and may set their own cookies, under their own privacy policies.</p>
<ul>
  <li><strong>Cloudflare Turnstile</strong> &ndash; bot-check on sign-in, registration and password reset.</li>
  <li><strong>Google Fonts</strong> and <strong>Firebase Cloud Messaging</strong> &ndash; fonts, and push notifications if you allow them.</li>
  <li><strong>Font Awesome</strong>, <strong>jsDelivr</strong>, <strong>cdnjs</strong> and <strong>jQuery CDN</strong> &ndash; icons, styles and scripts.</li>
</ul>

<h2>3. Managing cookies and storage</h2>
<p>You can delete or block cookies and site data in your browser settings. If you block the essential session cookie you will not be able to sign in.
Clearing site data will also reset your saved preferences. Notifications can be turned off in your browser or phone settings at any time.</p>

<h2>4. Changes</h2>
<p>If we ever add analytics or other non-essential cookies, we will update this page and ask for your choice first.</p>
<p>Questions? See the contact details in our <a href="/privacy">Privacy Notice</a>.</p>
<?php bmis_legal_footer();
