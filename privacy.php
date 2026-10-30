<?php
require __DIR__ . '/app/Views/partials/legal_layout.php';
bmis_legal_header('Privacy Notice', 'privacy');
?>
<h1>Privacy Notice</h1>
<p class="meta">Effective <?= bl('effective') ?> &middot; Last updated <?= bl('last_updated') ?></p>

<p><?= bl('org_name') ?> (“the Barangay”, “we”) operates the <?= bl('system_name') ?>. We respect your right to privacy and process
your personal information in accordance with the <strong>Data Privacy Act of 2012 (Republic Act No. 10173)</strong>, its Implementing Rules
and Regulations, and the issuances of the National Privacy Commission (NPC).</p>

<h2>1. Who is responsible for your data</h2>
<p>The Barangay is the <strong>personal information controller</strong>. Questions, requests and complaints about your data may be sent to our
Compliance Officer for Privacy (COP):</p>
<p>
  <strong>Compliance Officer for Privacy:</strong> <?= bl('dpo_name') ?><br>
  <strong>Email:</strong> <?= bl('dpo_email') ?><br>
  <strong>Phone:</strong> <?= bl('dpo_phone') ?><br>
  <strong>Office:</strong> <?= bl('office_address') ?>
</p>
<?php if (!empty($GLOBALS['BMIS_LEGAL']['city_dpo_line'])): ?><p><?= bl('city_dpo_line') ?></p><?php endif; ?>

<h2>2. What we collect</h2>
<div class="tw"><table>
<thead><tr><th>When you…</th><th>We may collect</th></tr></thead>
<tbody>
<tr><td>Register as a resident</td><td>Name, sex, civil status, birthdate and birthplace, nationality, home address, mobile number and/or email, a username and password, and a photo and/or ID image you provide or capture.</td></tr>
<tr><td>Update your profile or add family members</td><td>The profile details above, a profile photo, and household/family member information (name, relationship, birthdate and similar details).</td></tr>
<tr><td>Complete the Youth Profile</td><td>Age, sex, civil status, educational attainment, employment status, special skills and interests.</td></tr>
<tr><td>Request a certificate, clearance, ID or permit</td><td>The personal details needed for that document, the purpose of the request, and any supporting images or documents you upload.</td></tr>
<tr><td>File a complaint or send a message</td><td>Your message or complaint, the people and events involved, and any photos you attach.</td></tr>
<tr><td>Sign in and use the site</td><td>Login records (such as date/time, IP address and device/browser information), security-question answers, and activity logs kept for security and accountability.</td></tr>
<tr><td>Turn on notifications</td><td>A device notification token so we can send you push notifications.</td></tr>
</tbody></table></div>
<p>Some of this information, such as age, marital status and education, is <strong>sensitive personal information</strong> under the law and is
given extra care.</p>

<h2>3. Why we use it</h2>
<p>We use your information only to: verify that you are a resident of the Barangay; create and secure your account; process and release the
documents and services you request; handle complaints and messages; run Barangay programs, including youth (SK) programs; send you
announcements and notifications; keep records required of the Barangay; prevent fraud and misuse; and comply with law. We carry out these
activities as part of the Barangay’s mandated functions and, where applicable, <strong>with your consent</strong>, which we ask for on the forms that collect your data.
We do not sell your personal information and we do not use it for advertising.</p>

<h2>4. Who we share it with</h2>
<ul>
  <li><strong>Authorized Barangay officials and staff</strong>, only as needed to do their work.</li>
  <li><strong>Other government offices, courts or law enforcement</strong>, when required by law or a lawful order.</li>
  <li><strong>Service providers that help us run the site</strong>, under appropriate safeguards. These include our web hosting provider; Cloudflare (bot-check on sign-in and registration);
      Google (push notifications and web fonts); and content-delivery networks that serve the site’s code, icons and fonts. When your browser loads
      these, the provider can see your IP address and basic browser details. See our <a href="/cookie-policy">Cookie Policy</a>.</li>
</ul>

<h2>5. How long we keep it</h2>
<div class="tw"><table>
<thead><tr><th>Record</th><th>Kept for</th></tr></thead>
<tbody>
<tr><td>Resident and household records</td><td><?= bl('retention_residents') ?></td></tr>
<tr><td>Document and service requests</td><td><?= bl('retention_requests') ?></td></tr>
<tr><td>Complaints and messages</td><td><?= bl('retention_complaints') ?></td></tr>
<tr><td>Login and activity logs</td><td><?= bl('retention_logs') ?></td></tr>
</tbody></table></div>
<p>After that period, records are securely deleted or anonymized unless the law requires us to keep them longer.</p>

<h2>6. How we protect it</h2>
<p>We use measures that include secure (HTTPS) connections, password-protected accounts, limits on repeated failed sign-in attempts,
role-based access for staff, checks on uploaded files, and activity logs. No system is completely secure. If a personal data breach
that requires notification occurs, we will notify the NPC and the affected persons within the period required by law.</p>

<h2>7. Your rights</h2>
<p>Under the Data Privacy Act you have the right to: be informed; access your personal data; object to its processing; correct inaccurate data;
ask for erasure or blocking in the cases allowed by law; data portability; be indemnified for damages caused by inaccurate, unlawfully obtained or
unauthorized use of your data; and withdraw consent where consent is the basis for processing. To use any of these rights, contact our Compliance Officer for Privacy above.
If you are not satisfied with our response, you may complain to the <a href="https://privacy.gov.ph" target="_blank" rel="noopener">National Privacy Commission</a>.</p>
<p class="note">Withdrawing consent or asking for deletion may mean we can no longer provide certain services, and we may keep records that the law requires us to keep.</p>

<h2>8. Minors</h2>
<p>If you are under 18, please ask a parent or guardian to help you with registration and to read this notice and the Terms with you.
Adults who enter information about household members must have those persons’ permission.</p>

<h2>9. Changes to this notice</h2>
<p>We may update this notice. The “Last updated” date above will change, and we will show a notice on the site for significant changes.</p>
<?php bmis_legal_footer();
