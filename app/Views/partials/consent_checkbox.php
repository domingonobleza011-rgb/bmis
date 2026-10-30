<?php
/**
 * Reusable privacy-consent checkbox.
 * Set before including:
 *   $bmis_consent_id      unique id for this checkbox on the page (required)
 *   $bmis_consent_purpose short phrase, e.g. "process this request" (optional)
 *   $bmis_consent_third   true when the form also contains other people's data (optional)
 */
$__cid = isset($bmis_consent_id) ? preg_replace('/[^A-Za-z0-9_-]/', '', $bmis_consent_id) : 'privacyConsent';
$__purpose = isset($bmis_consent_purpose) ? $bmis_consent_purpose : 'process this request';
$__third = !empty($bmis_consent_third);
?>
<div class="bmis-consent" style="margin:14px 0 6px;">
    <div class="form-check" style="display:flex;gap:.55rem;align-items:flex-start;padding-left:0;">
        <input class="form-check-input" style="margin:.2rem 0 0;flex:0 0 auto;float:none;width:1.15rem;height:1.15rem;" type="checkbox"
               id="<?= $__cid ?>" name="privacy_consent" value="1" data-bmis-consent required
               aria-describedby="<?= $__cid ?>_err">
        <label class="form-check-label" for="<?= $__cid ?>" style="font-size:.85rem;line-height:1.45;">
            I have read the <a href="/privacy" target="_blank" rel="noopener">Privacy Notice</a> and the
            <a href="/terms" target="_blank" rel="noopener">Terms and Conditions</a>, and I consent to Barangay San Pedro
            collecting and processing the personal information on this form to <?= htmlspecialchars($__purpose) ?>.
            <?php if ($__third): ?>I confirm that I have the permission of the person(s) whose details I am entering.<?php endif; ?>
        </label>
    </div>
    <div class="bmis-consent-err" id="<?= $__cid ?>_err" role="alert" hidden
         style="color:#b3261e;font-size:.8rem;margin-top:4px;">Please tick the box to give your consent before submitting.</div>
</div>
