<?php
/**
 * Shared markup for the admin Messages page (used by admn_messages_view.php
 * and ajax_messages.php so the 60s poll never drifts from the first render).
 *
 * Expects: $part ('item' | 'pane'), $conv, $thread, $lastMsg
 */
$rid    = (int)$conv['id_resident'];
$mfname = htmlspecialchars($conv['fname']);
$mfull  = htmlspecialchars(trim($conv['fname'] . ' ' . $conv['lname']));
$init   = strtoupper(mb_substr($conv['fname'], 0, 1));
$ts     = strtotime($conv['last_date']);
$when   = date('Y-m-d', $ts) === date('Y-m-d') ? date('g:i A', $ts) : date('M j', $ts);
$prev   = trim((string)($conv['last_message'] ?? ''));
if (mb_strlen($prev) > 60) $prev = mb_substr($prev, 0, 60) . '…';
$unread = (int)($conv['unread_count'] ?? 0);

if ($part === 'item'): ?>
<div class="chat-item" id="chatItem<?= $rid ?>" data-rid="<?= $rid ?>"
     data-search="<?= strtolower($mfull . ' ' . htmlspecialchars($prev)) ?>"
     onclick="selectConversation(<?= $rid ?>)">
    <input type="checkbox" class="msg-checkbox" value="<?= $rid ?>"
           onclick="event.stopPropagation()" onchange="updateBulkToolbar()">
    <div class="chat-avatar-lg"><?= bmis_avatar_inner($conv['profile_photo'] ?? null, $init) ?></div>
    <div class="chat-item-body">
        <div class="chat-item-top">
            <span class="chat-item-name"><?= $mfull ?></span>
            <span class="chat-item-time"><?= $when ?></span>
        </div>
        <div class="chat-item-bottom">
            <span class="chat-item-preview"><?= htmlspecialchars($prev) ?></span>
            <?php if ($unread > 0): ?><span class="chat-unread"><?= $unread ?></span><?php endif; ?>
        </div>
    </div>
</div>
<?php else: ?>
<div class="thread-pane d-none" id="threadPane<?= $rid ?>" data-rid="<?= $rid ?>">
    <div class="thread-header">
        <button type="button" class="thread-back" onclick="closeConversation()" title="Back">
            <i class="bi bi-arrow-left"></i>
        </button>
        <div class="chat-avatar-lg sm"><?= bmis_avatar_inner($conv['profile_photo'] ?? null, $init) ?></div>
        <div class="thread-title">
            <div class="thread-name"><?= $mfull ?></div>
            <div class="thread-sub">Resident</div>
        </div>
        <button type="button" class="thread-delete" title="Move to Archive"
                onclick="openDeleteMsgModal(<?= $rid ?>, '<?= $mfname ?>')">
            <i class="bi bi-trash-fill"></i>
        </button>
    </div>

    <div class="thread-body" id="threadBody<?= $rid ?>">
        <?php foreach ($thread as $bubble): ?>
            <?php if ($bubble['side'] === 'resident'): ?>
                <div class="chat-row mt-3">
                    <div class="chat-avatar chat-avatar-sm"><?= bmis_avatar_inner($conv['profile_photo'] ?? null, $init) ?></div>
                    <div class="chat-bubble chat-bubble-incoming"><?= nl2br(htmlspecialchars($bubble['message_text'])) ?></div>
                </div>
                <div class="chat-timestamp"><?= date('F j, Y, g:i a', strtotime($bubble['date_sent'])) ?></div>
            <?php else: ?>
                <div class="chat-row chat-row-outgoing mt-3">
                    <div class="chat-bubble chat-bubble-outgoing"><?= nl2br(htmlspecialchars($bubble['message_text'])) ?></div>
                </div>
                <div class="chat-timestamp chat-timestamp-outgoing"><?= date('F j, Y, g:i a', strtotime($bubble['date_sent'])) ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div class="thread-composer">
        <textarea class="form-control reply-textarea" id="replyText<?= $rid ?>" rows="1"
                  placeholder="Reply to <?= $mfname ?>…"
                  oninput="autoGrow(this)"
                  onkeydown="if(event.key==='Enter' && !event.shiftKey){event.preventDefault();sendReply(<?= $rid ?>);}"></textarea>
        <button type="button" class="composer-send" onclick="sendReply(<?= $rid ?>)" title="Send">
            <i class="bi bi-send-fill"></i>
        </button>
    </div>
</div>
<?php endif; ?>
