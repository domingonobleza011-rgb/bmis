
    <?php
ob_start();
if (empty($all_messages)):
?>
    <div class="chat-empty">
        <i class="bi bi-chat-dots" style="font-size:2.5rem; margin-bottom:10px;"></i>
        <div class="fw-semibold">No messages yet</div>
        <small>Send a message to the Barangay Administration below.</small>
    </div>
<?php
else:
    $prev_date = '';
    foreach ($all_messages as $msg):
        $this_date = date('F j, Y', strtotime($msg['date_sent']));
        if ($this_date !== $prev_date):
            $prev_date = $this_date;
?>
    <div class="chat-day-label">— <?= $this_date ?> —</div>
<?php
        endif;
        $side     = $msg['side'];
        $is_me    = ($side === 'me');
        $time_str = date('h:i A', strtotime($msg['date_sent']));
?>
    <div class="bubble-row <?= $is_me ? 'me' : 'admin' ?>">
        <div class="bubble-avatar <?= $is_me ? 'me-av' : 'admin-av' ?>">
            <?php if ($is_me): ?>
            <i class="bi bi-person-fill"></i>
            <?php else: ?>
            <img src="icons/logo.png" alt="Barangay San Pedro">
            <?php endif; ?>
        </div>
        <div class="bubble <?= $is_me ? 'me' : 'admin' ?>">
            <?= nl2br(htmlspecialchars($msg['message_text'])) ?>
            <span class="bubble-time"><?= $time_str ?></span>
        </div>
        <?php if (!$is_me && !empty($msg['id_msg'])): ?>
        <form action="" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">
            <input type="hidden" name="id_msg" value="<?= $msg['id_msg'] ?>">
            <button type="submit" name="delete_msg" class="bubble-delete" title="Delete">
                <i class="bi bi-trash3-fill"></i>
            </button>
        </form>
        <?php endif; ?>
    </div>
<?php
    endforeach;
endif;
$chat_html = ob_get_clean();

echo json_encode([
    'chat_html' => $chat_html,
    'count'     => count($all_messages),
]);
