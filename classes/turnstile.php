<?php
/**
 * Cloudflare Turnstile server-side check (same site key / secret as login).
 * Usage:  $cap = bmis_verify_turnstile();  if (!$cap['ok']) { ...show $cap['error']... }
 */
if (!defined('BMIS_TURNSTILE_SITEKEY')) define('BMIS_TURNSTILE_SITEKEY', '0x4AAAAAAEHzfWAU9-zKulev');
if (!defined('BMIS_TURNSTILE_SECRET'))  define('BMIS_TURNSTILE_SECRET',  '0x4AAAAAAEHzfRghdiBNzcb0lvRy5zy2Zok');

if (!function_exists('bmis_verify_turnstile')) {
    function bmis_verify_turnstile(): array {
        $token = $_POST['cf-turnstile-response'] ?? '';
        if ($token === '') {
            return ['ok' => false, 'error' => 'Please complete the security check (CAPTCHA) and try again.'];
        }
        $ch = curl_init('https://challenges.cloudflare.com/turnstile/v0/siteverify');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'secret'   => BMIS_TURNSTILE_SECRET,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR'] ?? '',
        ]));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $verify  = curl_exec($ch);
        $curlErr = curl_error($ch);
        curl_close($ch);

        $result = json_decode((string)$verify, true);
        if (!$result || empty($result['success'])) {
            return ['ok' => false, 'error' => 'CAPTCHA failed: ' . ($curlErr ?: implode(', ', $result['error-codes'] ?? ['no response']))];
        }
        return ['ok' => true, 'error' => ''];
    }
}
