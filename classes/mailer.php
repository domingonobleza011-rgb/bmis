<?php
/**
 * Gmail SMTP mail helper.
 *
 * Uses PHPMailer's plain source files (no composer/vendor — InfinityFree
 * has had upload/inode issues with a full vendor/ tree in this project
 * before) from /libs/PHPMailer. Requires config.php to define
 * GMAIL_SMTP_USER, GMAIL_SMTP_PASS (a Gmail "App Password", not the login
 * password), and GMAIL_FROM_NAME.
 *
 * Every call is wrapped so a failed/unconfigured send never breaks the
 * page it's called from (registration approval, certificate creation,
 * etc.) — it only logs the error and returns false.
 */

require_once __DIR__ . '/../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/../config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

/**
 * Send an HTML email through Gmail SMTP.
 *
 * @param string $to        Recipient email address.
 * @param string $subject   Email subject.
 * @param string $bodyHtml  HTML body (already built by the caller).
 * @param string $toName    Optional recipient display name.
 * @return bool             True on success, false on failure (see error_log).
 */
function send_gmail_mail($to, $subject, $bodyHtml, $toName = '') {
    if (empty($to) || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
        return false; // no/invalid email on file — nothing to send
    }
    if (!defined('GMAIL_SMTP_USER') || !defined('GMAIL_SMTP_PASS')
        || GMAIL_SMTP_USER === '' || strpos(GMAIL_SMTP_PASS, 'REPLACE_WITH') === 0) {
        error_log('send_gmail_mail: GMAIL_SMTP_USER/GMAIL_SMTP_PASS not configured in config.php — skipping send.');
        file_put_contents(__DIR__ . '/../mail_debug.log', date('Y-m-d H:i:s') . " send_gmail_mail SKIPPED - GMAIL_SMTP_USER/PASS not configured\n", FILE_APPEND);
        return false;
    }

    $mail = new PHPMailer(true);
    try {
       $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = GMAIL_SMTP_USER;
        $mail->Password   = GMAIL_SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        // InfinityFree free tier commonly blocks outbound SMTP/cURL to
        // external hosts — if sends silently fail there, this is why.
        $mail->SMTPOptions = [
            'ssl' => ['verify_peer' => true, 'verify_peer_name' => true, 'allow_self_signed' => false],
        ];

        $mail->setFrom(GMAIL_SMTP_USER, defined('GMAIL_FROM_NAME') ? GMAIL_FROM_NAME : 'Barangay BMIS');
        $mail->addAddress($to, $toName);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $bodyHtml;
        $mail->AltBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $bodyHtml)));

        $mail->send();
        return true;
    } catch (PHPMailerException $e) {
        error_log('send_gmail_mail failed to ' . $to . ': ' . $mail->ErrorInfo);
        file_put_contents(__DIR__ . '/../mail_debug.log', date('Y-m-d H:i:s') . " send_gmail_mail FAILED to $to | " . $mail->ErrorInfo . "\n", FILE_APPEND);
        return false;
    }
}

/**
 * Looks up the resident's email by id and, if present, sends a
 * "document ready" notice. Safe no-op if the resident has no email on
 * file or SMTP isn't configured. Call this right after INSERTing a new
 * certificate/document/permit request.
 *
 * @param PDO    $connection
 * @param mixed  $id_resident
 * @param string $fname
 * @param string $lname
 * @param string $doc_label  Human-readable document name, e.g. "Certificate of Indigency"
 * @param string $purpose    Optional purpose text to include in the email
 */
function bmis_notify_document_ready($connection, $id_resident, $fname, $lname, $doc_label, $purpose = '') {
    $__log = __DIR__ . '/../mail_debug.log';
    file_put_contents($__log, date('Y-m-d H:i:s') . " bmis_notify_document_ready called | id_resident=" . var_export($id_resident, true) . " doc=$doc_label\n", FILE_APPEND);

    $stmt = $connection->prepare("SELECT email FROM tbl_resident WHERE id_resident = ?");
    $stmt->execute([$id_resident]);
    $email = $stmt->fetchColumn();

    file_put_contents($__log, date('Y-m-d H:i:s') . " lookup result | email=" . var_export($email, true) . "\n", FILE_APPEND);

    if (empty($email)) {
        file_put_contents($__log, date('Y-m-d H:i:s') . " SKIPPED - no email on file for id_resident=$id_resident\n\n", FILE_APPEND);
        return; // registered with phone number, or resident not found
    }
    $applicant_name = trim($fname . ' ' . $lname);
    $purpose_line = $purpose !== '' ? " (Purpose: {$purpose})" : '';
    $body = bmis_mail_template(
        'Document Ready',
        "<p>Hi {$applicant_name},</p>
         <p>Your requested <strong>{$doc_label}</strong>{$purpose_line} is now ready. Please visit the Barangay Hall to claim it.</p>",
        'success'
    );
    $result = send_gmail_mail($email, "Your {$doc_label} is ready", $body, $applicant_name);
    file_put_contents($__log, date('Y-m-d H:i:s') . " send_gmail_mail returned=" . var_export($result, true) . "\n\n", FILE_APPEND);
}

/**
 * Emails every administrator account on file (tbl_admin, role of
 * "admin"/"administrator", case-insensitive) with the same message.
 * Used to alert admins the moment a resident submits something that
 * needs their attention (a certificate/document request, a new
 * registration, a password reset request, or a message).
 *
 * Safe no-op if no admin has an email on file or SMTP isn't configured
 * — mirrors send_gmail_mail's "never break the calling page" behavior.
 *
 * @param PDO    $connection
 * @param string $subject   Email subject line.
 * @param string $heading   Short badge/heading for the mail template, e.g. "New Request"
 * @param string $bodyHtml  HTML message body (already built by the caller).
 * @param string $type      'info' | 'success' | 'error' — passed through to bmis_mail_template
 */
function bmis_notify_admins($connection, $subject, $heading, $bodyHtml, $type = 'info') {
    $__log = __DIR__ . '/../mail_debug.log';
    file_put_contents($__log, date('Y-m-d H:i:s') . " bmis_notify_admins called | subject=$subject\n", FILE_APPEND);

    try {
        $stmt = $connection->query(
            "SELECT email, fname, lname FROM tbl_admin
             WHERE LOWER(role) IN ('admin', 'administrator')
               AND email IS NOT NULL AND email <> ''"
        );
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $e) {
        file_put_contents($__log, date('Y-m-d H:i:s') . " bmis_notify_admins SKIPPED - could not read tbl_admin: " . $e->getMessage() . "\n\n", FILE_APPEND);
        return;
    }

    if (empty($admins)) {
        file_put_contents($__log, date('Y-m-d H:i:s') . " bmis_notify_admins SKIPPED - no admin accounts with an email on file\n\n", FILE_APPEND);
        return;
    }

    $body = bmis_mail_template($heading, $bodyHtml, $type);

    foreach ($admins as $admin) {
        $admin_name = trim(($admin['fname'] ?? '') . ' ' . ($admin['lname'] ?? ''));
        $result = send_gmail_mail($admin['email'], $subject, $body, $admin_name);
        file_put_contents($__log, date('Y-m-d H:i:s') . " bmis_notify_admins -> {$admin['email']} returned=" . var_export($result, true) . "\n", FILE_APPEND);
    }
    file_put_contents($__log, date('Y-m-d H:i:s') . " bmis_notify_admins done\n\n", FILE_APPEND);
}

/**
 * Alerts every admin that a resident just submitted a certificate /
 * document / permit request. Call right after the row is INSERTed,
 * alongside bmis_notify_document_ready.
 */
function bmis_notify_admins_new_request($connection, $doc_label, $applicant_name, $purpose = '') {
    $purpose_line = $purpose !== '' ? " (Purpose: {$purpose})" : '';
    bmis_notify_admins(
        $connection,
        "New Request: {$doc_label}",
        'New Request',
        "<p><strong>{$applicant_name}</strong> just submitted a request for <strong>{$doc_label}</strong>{$purpose_line}.</p>
         <p>Please log in to the BMIS admin dashboard to review it.</p>",
        'info'
    );
}

/**
 * Alerts every admin that a new resident registration is awaiting
 * approval. Call right after the row is INSERTed into
 * tbl_resident_pending.
 */
function bmis_notify_admins_new_registration($connection, $applicant_name) {
    bmis_notify_admins(
        $connection,
        'New Resident Registration Pending',
        'New Registration',
        "<p><strong>{$applicant_name}</strong> just submitted a resident registration.</p>
         <p>Please log in to the BMIS admin dashboard to review their valid ID and approve or reject the application.</p>",
        'info'
    );
}

/**
 * Alerts every admin that a resident has requested a password reset
 * that needs manual approval. Call right after the row is INSERTed
 * into tbl_password_reset_requests.
 */
function bmis_notify_admins_new_password_reset($connection, $applicant_name) {
    bmis_notify_admins(
        $connection,
        'New Password Reset Request',
        'Password Reset',
        "<p><strong>{$applicant_name}</strong> just requested a password reset.</p>
         <p>Please log in to the BMIS admin dashboard to review and process the request.</p>",
        'info'
    );
}

/**
 * Alerts every admin that a resident sent a new message. Call right
 * after the row is INSERTed into admin_messages.
 */
function bmis_notify_admins_new_message($connection, $sender_name, $message_text) {
    $safe_message = nl2br(htmlspecialchars($message_text));
    bmis_notify_admins(
        $connection,
        'New Message from ' . $sender_name,
        'New Message',
        "<p><strong>{$sender_name}</strong> sent a new message:</p>
         <p style=\"background:#f7f8fc; border-left:3px solid #c9943a; padding:10px 14px; border-radius:6px;\">{$safe_message}</p>
         <p>Please log in to the BMIS admin dashboard to reply.</p>",
        'info'
    );
}

/**
 * Shared HTML wrapper so every automated email looks consistent
 * (navy/gold civic theme, matches the rest of the BMIS UI).
 *
 * @param string $heading   Short title, e.g. "Document Ready"
 * @param string $bodyHtml  Message HTML (paragraphs, etc.)
 * @param string $type      'info' (navy, default) | 'success' (green) | 'error' (red) — colors the accent bar and badge
 */
function bmis_mail_template($heading, $bodyHtml, $type = 'info') {
    $palette = [
        'info'    => ['accent' => '#c9943a', 'badge_bg' => '#fdf3e2', 'badge_tx' => '#8a6416'],
        'success' => ['accent' => '#1d9e75', 'badge_bg' => '#e1f5ee', 'badge_tx' => '#0f6e56'],
        'error'   => ['accent' => '#e24b4a', 'badge_bg' => '#fcebeb', 'badge_tx' => '#a32d2d'],
    ];
    $c = $palette[$type] ?? $palette['info'];
    $logo_url = defined('BMIS_SITE_URL') ? rtrim(BMIS_SITE_URL, '/') . '/icons/logo.png' : 'https://barangaysanpedro.gt.tc/icons/logo.png';

    return <<<HTML
    <div style="background:#eef1f6; padding:32px 16px; font-family: 'DM Sans', Arial, sans-serif;">
    <div style="max-width: 480px; margin: 0 auto; background:#ffffff; border-radius: 14px; overflow: hidden; box-shadow: 0 4px 18px rgba(15,45,90,0.12);">

        <div style="background: linear-gradient(135deg, #0f2d5a 0%, #163f7a 100%); padding: 28px 24px 22px; text-align:center;">
            <img src="{$logo_url}" width="64" height="64" alt="Barangay San Pedro Seal"
                 style="width:64px; height:64px; border-radius:50%; border:3px solid #c9943a; background:#fff; display:inline-block; margin-bottom:10px;">
            <div style="color:#ffffff; font-weight:700; font-size:19px; letter-spacing:.2px;">Barangay San Pedro</div>
            <div style="color:#c9943a; font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:1px; margin-top:2px;">Barangay Management Information System</div>
        </div>

        <div style="height:4px; background: linear-gradient(90deg, {$c['accent']}, #0f2d5a);"></div>

        <div style="padding: 28px 26px 8px;">
            <span style="display:inline-block; background:{$c['badge_bg']}; color:{$c['badge_tx']}; font-size:12px; font-weight:700; padding:5px 12px; border-radius:20px; margin-bottom:14px;">
                {$heading}
            </span>
            <div style="color: #1f2937; font-size: 14.5px; line-height: 1.7;">
                {$bodyHtml}
            </div>
        </div>

        <div style="padding: 4px 26px 26px;">
            <a href="https://barangaysanpedro.gt.tc" style="display:inline-block; background:#0f2d5a; color:#ffffff; text-decoration:none; font-size:13px; font-weight:600; padding:10px 20px; border-radius:8px;">
                Open BMIS Portal
            </a>
        </div>

        <div style="background: #f7f8fc; padding: 16px 26px; color: #6b7280; font-size: 11.5px; line-height:1.6; border-top:1px solid #e5e7eb; text-align:center;">
            This is an automated message from the Barangay San Pedro BMIS.<br>
            Please do not reply to this email &nbsp;•&nbsp; Iriga City, Camarines Sur
        </div>
    </div>
    </div>
    HTML;
}
