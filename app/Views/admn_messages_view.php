<?php include(ROOT_PATH . '/dashboard_sidebar_start.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <title>Messages - Barangay Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
    <style>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');

        /* ===== Page heading ===== */
        .msgs-title {
            display: flex; align-items: center; gap: 10px;
            font-size: 1.75rem; font-weight: 700; color: #0f2d5a; margin: 0 0 18px;
        }
        .msgs-title i { font-size: 1.6rem; }

        /* ===== Two-pane shell ===== */
        .msgs-shell {
            display: flex; background: #fff; border: 1px solid #e5eaf3;
            border-radius: 16px; box-shadow: 0 1px 3px rgba(15,45,90,.06), 0 8px 24px rgba(15,45,90,.05);
            height: calc(100vh - 190px); min-height: 520px; overflow: hidden;
        }

        /* ===== Left: chats ===== */
        .msgs-side {
            width: 340px; flex-shrink: 0; display: flex; flex-direction: column;
            border-right: 1px solid #e5eaf3; min-height: 0;
        }
        .msgs-side-head { padding: 16px 16px 12px; }
        .msgs-side-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px; }
        .msgs-side-title { font-size: 1.15rem; font-weight: 700; color: #0f2d5a; margin: 0; }
        .msgs-select-btn {
            background: none; border: none; color: #6b7a90; font-size: 13px; font-weight: 600;
            padding: 4px 8px; border-radius: 8px; cursor: pointer;
        }
        .msgs-select-btn:hover { background: #eff6ff; color: #0d6efd; }
        .msgs-search {
            width: 100%; border: 1px solid #cfd6e2; border-radius: 999px;
            padding: 9px 16px; font-size: 13.5px; outline: none; background: #fff; color: #16213f;
        }
        .msgs-search:focus { border-color: #0d6efd; box-shadow: 0 0 0 3px rgba(13,110,253,.12); }

        #bulkToolbar {
            background: #eff6ff; border-top: 1px solid #bfdbfe; border-bottom: 1px solid #bfdbfe;
            padding: 8px 16px; align-items: center; gap: 8px; font-size: 13px;
        }
        #bulkToolbar .count { font-weight: 700; color: #0d6efd; flex: 1; }

        #chat-list { flex: 1; overflow-y: auto; min-height: 0; padding: 4px 8px 12px; }
        #chat-list::-webkit-scrollbar, .thread-body::-webkit-scrollbar { width: 6px; }
        #chat-list::-webkit-scrollbar-thumb, .thread-body::-webkit-scrollbar-thumb { background: rgba(15,45,90,.18); border-radius: 3px; }
        .chat-empty-list { text-align: center; color: #6b7a90; font-size: 13.5px; padding: 28px 12px; }

        .chat-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 10px;
            border-radius: 12px; cursor: pointer; transition: background .15s ease;
        }
        .chat-item:hover { background: #f3f6fb; }
        .chat-item.active { background: #e9f0fb; }
        .chat-item.row-selected { background: #eff6ff; }
        .chat-avatar-lg {
            width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
            background: linear-gradient(135deg, #0f2d5a, #2b5ea7); color: #fff;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px;
        }
        .chat-avatar-lg, .chat-avatar { overflow: hidden; }
        .chat-avatar-lg.sm { width: 40px; height: 40px; font-size: 15px; }
        .chat-item-body { flex: 1; min-width: 0; }
        .chat-item-top, .chat-item-bottom { display: flex; align-items: center; justify-content: space-between; gap: 8px; }
        .chat-item-name { font-weight: 600; font-size: 14px; color: #16213f; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .chat-item-time { font-size: 11.5px; color: #8894a8; flex-shrink: 0; }
        .chat-item-preview { font-size: 12.5px; color: #6b7a90; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .chat-unread {
            background: #d94b41; color: #fff; font-size: 11px; font-weight: 700;
            min-width: 20px; height: 20px; padding: 0 6px; border-radius: 999px;
            display: inline-flex; align-items: center; justify-content: center; flex-shrink: 0;
        }

        /* checkboxes only appear in select mode */
        .msg-checkbox {
            display: none; appearance: none; -webkit-appearance: none; flex-shrink: 0;
            width: 18px; height: 18px; border: 2px solid #6c757d; border-radius: 5px;
            background: #fff; cursor: pointer; position: relative;
        }
        .select-mode .msg-checkbox { display: inline-block; }
        .msg-checkbox:checked { background: #0d6efd; border-color: #0d6efd; }
        .msg-checkbox:checked::after {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round' d='M3 8l3.5 3.5L13 4.5'/%3E%3C/svg%3E") center / 12px no-repeat;
        }

        /* ===== Right: conversation ===== */
        .msgs-main { flex: 1; min-width: 0; display: flex; flex-direction: column; position: relative; }
        .msgs-placeholder {
            flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-align: center; color: #6b7a90; padding: 24px;
        }
        .msgs-placeholder i { font-size: 3rem; color: #a9b4c6; margin-bottom: 10px; }
        .msgs-placeholder .ph-title { font-weight: 700; font-size: 1.05rem; color: #4c5872; }
        .msgs-placeholder .ph-sub { font-size: 13px; margin-top: 2px; }

        #threads { flex: 1; display: flex; flex-direction: column; min-height: 0; }
        .thread-pane { flex: 1; display: flex; flex-direction: column; min-height: 0; }
        .thread-pane.d-none { display: none !important; }
        .thread-header {
            display: flex; align-items: center; gap: 12px; padding: 12px 18px;
            border-bottom: 1px solid #e5eaf3; background: #fff;
        }
        .thread-title { flex: 1; min-width: 0; }
        .thread-name { font-weight: 700; color: #0f2d5a; font-size: 15px; line-height: 1.2; }
        .thread-sub { font-size: 12px; color: #8894a8; }
        .thread-back, .thread-delete {
            background: none; border: none; width: 36px; height: 36px; border-radius: 10px;
            color: #6b7a90; cursor: pointer; font-size: 16px;
        }
        .thread-back { display: none; }
        .thread-back:hover { background: #f3f6fb; color: #0f2d5a; }
        .thread-delete:hover { background: #fdeceb; color: #d94b41; }

        .thread-body {
            flex: 1; overflow-y: auto; min-height: 0; padding: 8px 22px 18px;
            background: #f4f7fc; scroll-behavior: smooth;
        }
        .thread-composer {
            display: flex; align-items: flex-end; gap: 10px; padding: 12px 16px;
            border-top: 1px solid #e5eaf3; background: #fff;
        }
        .reply-textarea {
            resize: none; border-radius: 20px; font-size: 14px; padding: 9px 16px;
            max-height: 120px; line-height: 1.4;
        }
        .reply-textarea:focus { box-shadow: 0 0 0 3px rgba(13,110,253,.15); border-color: #0d6efd; }
        .composer-send {
            width: 40px; height: 40px; border-radius: 50%; border: none; flex-shrink: 0;
            background: #0d6efd; color: #fff; cursor: pointer; transition: background .15s;
        }
        .composer-send:hover { background: #0b5ed7; }

        .chat-row { display: flex; align-items: flex-end; gap: 10px; }
        .chat-avatar {
            width: 32px; height: 32px; border-radius: 50%; background: #0d6efd; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .chat-bubble { max-width: 70%; padding: 11px 15px; font-size: 14px; line-height: 1.5; word-wrap: break-word; }
        .chat-bubble-incoming {
            background: #fff; color: #212529; border-radius: 4px 16px 16px 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,.08);
        }
        .chat-row-outgoing { justify-content: flex-end; }
        .chat-bubble-outgoing {
            background: #0d6efd; color: #fff; border-radius: 16px 4px 16px 16px;
            box-shadow: 0 1px 2px rgba(0,0,0,.12);
        }
        .chat-timestamp { margin: 6px 0 0 42px; font-size: 11px; color: #8a94a6; }
        .chat-timestamp-outgoing { margin: 6px 0 0; text-align: right; }

        /* ===== Mobile: one pane at a time ===== */
        @media (max-width: 767.98px) {
            .msgs-shell { height: calc(100vh - 150px); }
            .msgs-side { width: 100%; border-right: none; }
            .msgs-main { display: none; }
            .msgs-shell.chat-open .msgs-side { display: none; }
            .msgs-shell.chat-open .msgs-main { display: flex; }
            .thread-back { display: inline-block; }
            .chat-bubble { max-width: 85%; }
        }

        /* ===== Confirmation modals ===== */
        .bmis-modal-backdrop {
            display: none; position: fixed; inset: 0; z-index: 9999;
            background: rgba(0,0,0,0.45); align-items: center; justify-content: center;
        }
        .bmis-modal-backdrop.open { display: flex; }
        .bmis-modal-card {
            background: #fff; border-radius: 14px; padding: 28px 32px;
            width: 100%; max-width: 430px; margin: 0 16px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        }
        .bmis-modal-icon {
            width: 40px; height: 40px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .bmis-modal-title  { font-size: 15px; font-weight: 600; margin: 0; color: #0f2d5a; }
        .bmis-modal-sub    { font-size: 13px; color: #6b7280; margin: 0; }
        .bmis-modal-info   { border-radius: 8px; padding: 12px 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; }
        .bmis-modal-info p { margin: 0; }
        .bmis-btn-cancel {
            padding: 8px 18px; font-size: 13px; border-radius: 8px; cursor: pointer;
            border: 1px solid #d1d5db; background: #fff; color: #6b7280;
        }
        .bmis-btn-confirm {
            padding: 8px 20px; font-size: 13px; font-weight: 600; border-radius: 8px;
            cursor: pointer; border: none; color: #fff;
        }

        /* ===== Toast ===== */
        #bmisToast { display: none; position: fixed; bottom: 28px; right: 28px; z-index: 10000; min-width: 300px; max-width: 400px; }
        #bmisToastInner {
            border-radius: 14px; padding: 16px 20px; box-shadow: 0 8px 32px rgba(0,0,0,0.18);
            display: flex; align-items: flex-start; gap: 14px; animation: toastIn 0.3s ease;
        }
        #bmisToastIcon { width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px; }
        @keyframes toastIn { from { opacity: 0; transform: translateY(14px); } to { opacity: 1; transform: translateY(0); } }

        /* ===== Loading overlay ===== */
        #page-loader {
            position: fixed; inset: 0; background: rgba(15, 45, 90, 0.72);
            backdrop-filter: blur(6px); -webkit-backdrop-filter: blur(6px);
            z-index: 99999; display: none; align-items: center; justify-content: center;
            flex-direction: column; gap: 1.25rem;
        }
        #page-loader.show { display: flex; }
        #page-loader .spinner {
            width: 56px; height: 56px; border: 4px solid rgba(255,255,255,0.25);
            border-top-color: #c9943a; border-radius: 50%; animation: spin 0.85s linear infinite;
        }
        #page-loader .loader-text { color: #fff; font-weight: 600; font-size: 1.05rem; }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* ===== Light / Dark theme support =====
           The admin theme (dashboard_sidebar_start.php) resets every element to a
           transparent background + its own text colour, so the chat UI has to
           re-declare its colours from the --tm-* tokens. */
        html[data-theme] .msgs-shell { background: var(--tm-surface) !important; border: 1px solid var(--tm-border) !important; box-shadow: var(--tm-shadow) !important; }
        html[data-theme] .msgs-side,
        html[data-theme] .thread-header,
        html[data-theme] .thread-composer { border-color: var(--tm-border) !important; }
        html[data-theme] .msgs-side-title,
        html[data-theme] .thread-name,
        html[data-theme] .chat-item-name { color: var(--tm-head) !important; }
        html[data-theme] .chat-item-preview,
        html[data-theme] .chat-item-time,
        html[data-theme] .thread-sub,
        html[data-theme] .chat-timestamp,
        html[data-theme] .chat-empty-list,
        html[data-theme] .msgs-select-btn,
        html[data-theme] .thread-back,
        html[data-theme] .thread-delete { color: var(--tm-muted) !important; }
        html[data-theme] .msgs-placeholder { color: var(--tm-muted) !important; }
        html[data-theme] .msgs-placeholder .ph-title { color: var(--tm-mid) !important; }
        html[data-theme] .msgs-placeholder i { color: var(--tm-line) !important; }

        html[data-theme] .msgs-search,
        html[data-theme] .reply-textarea {
            background: var(--tm-input) !important; color: var(--tm-head) !important;
            border: 1px solid var(--tm-input-border) !important;
        }
        html[data-theme] .msgs-search:focus,
        html[data-theme] .reply-textarea:focus { border-color: var(--tm-icon-active) !important; box-shadow: 0 0 0 3px rgba(var(--tm-focus-rgb), .3) !important; }

        html[data-theme] .chat-item:hover,
        html[data-theme] .msgs-select-btn:hover,
        html[data-theme] .thread-back:hover { background: var(--tm-hover) !important; }
        html[data-theme] .chat-item.active { background: var(--tm-active-bg) !important; }
        html[data-theme] .chat-item.row-selected { background: var(--tm-active-bg) !important; }
        html[data-theme] .thread-delete:hover { background: rgba(220,38,38,.15) !important; color: #ef4444 !important; }

        html[data-theme] #bulkToolbar { background: var(--tm-surface2) !important; border-color: var(--tm-border) !important; }
        html[data-theme] #bulkToolbar .count { color: var(--tm-icon-active) !important; }

        html[data-theme] .chat-avatar-lg,
        html[data-theme] .chat-avatar { background: var(--tm-icon-active) !important; color: #fff !important; }
        html[data-theme="dark"] .chat-avatar-lg,
        html[data-theme="dark"] .chat-avatar { background: #4f46e5 !important; }
        html[data-theme] .chat-unread { background: #dc2626 !important; color: #fff !important; }

        html[data-theme] .thread-body { background: var(--tm-page) !important; }
        html[data-theme] .chat-bubble-incoming {
            background: var(--tm-surface2) !important; color: var(--tm-text) !important;
            border: 1px solid var(--tm-border) !important;
        }
        html[data-theme] .chat-bubble-outgoing { background: var(--tm-icon-active) !important; color: #fff !important; }
        html[data-theme] .composer-send { background: var(--tm-icon-active) !important; color: #fff !important; }
        html[data-theme="dark"] .chat-bubble-outgoing,
        html[data-theme="dark"] .composer-send { background: #4f46e5 !important; }

        html[data-theme] .msg-checkbox { background: var(--tm-input) !important; border-color: var(--tm-input-border) !important; }
        html[data-theme] .msg-checkbox:checked { background: var(--tm-icon-active) !important; border-color: var(--tm-icon-active) !important; }
        html[data-theme] .msg-checkbox:checked::after {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-width='2.5' stroke-linecap='round' stroke-linejoin='round' d='M3 8l3.5 3.5L13 4.5'/%3E%3C/svg%3E") !important;
            background-repeat: no-repeat !important; background-position: center !important; background-size: 12px !important;
        }

        /* confirm dialogs, toast, loader (theme blanks their inline backgrounds) */
        html[data-theme] .bmis-modal-backdrop { background: rgba(0,0,0,.55) !important; }
        html[data-theme] .bmis-modal-card { background: var(--tm-surface) !important; border: 1px solid var(--tm-border) !important; box-shadow: var(--tm-pop-shadow) !important; }
        html[data-theme] .bmis-modal-title { color: var(--tm-head) !important; }
        html[data-theme] .bmis-modal-sub { color: var(--tm-muted) !important; }
        html[data-theme] .bmis-modal-info { background: rgba(220,38,38,.10) !important; border: 1.5px solid rgba(220,38,38,.35) !important; }
        html[data-theme] .bmis-modal-info p { color: var(--tm-text) !important; }
        html[data-theme] .bmis-modal-icon { background: rgba(220,38,38,.15) !important; }
        html[data-theme] .bmis-modal-icon i { color: #ef4444 !important; }
        html[data-theme] .bmis-btn-cancel { background: transparent !important; color: var(--tm-text) !important; border: 1px solid var(--tm-input-border) !important; }
        html[data-theme] .bmis-btn-confirm { background: #dc2626 !important; color: #fff !important; }
        html[data-theme] #bmisToastInner { background: var(--tm-surface2) !important; border: 1.5px solid var(--tm-border) !important; box-shadow: var(--tm-pop-shadow) !important; }
        html[data-theme] #bmisToastMsg { color: var(--tm-muted) !important; }
        html[data-theme] #page-loader { background: rgba(0,0,0,.65) !important; }
        html[data-theme] #page-loader .spinner { border-color: rgba(255,255,255,.25) !important; border-top-color: #c9943a !important; }
        html[data-theme] #page-loader .loader-text { color: #fff !important; }
    </style>
</head>
<body>
<div class="container-fluid my-4 px-3 px-md-4">
    <h2 class="msgs-title">
        <i class="bi bi-chat-left-dots-fill"></i> Messages
    </h2>

    <div class="msgs-shell" id="msgsShell">
        <!-- Chats list -->
        <aside class="msgs-side">
            <div class="msgs-side-head">
                <div class="msgs-side-top">
                    <h5 class="msgs-side-title">Chats</h5>
                    <button type="button" class="msgs-select-btn" id="selectModeBtn" onclick="toggleSelectMode()">Select</button>
                </div>
                <input type="text" class="msgs-search" id="chatSearch" placeholder="Search name or message"
                       oninput="filterChats()" autocomplete="off">
            </div>

            <div id="bulkToolbar" class="d-none">
                <span class="count" id="selectedCount"></span>
                <button type="button" class="btn btn-danger btn-sm rounded-pill px-3 fw-bold" onclick="openBulkDeleteModal()">
                    <i class="bi bi-trash-fill me-1"></i> Delete
                </button>
                <button type="button" class="btn btn-outline-secondary btn-sm rounded-pill px-3" onclick="clearSelection()">Cancel</button>
            </div>

            <div id="chat-list">
                <?php if (!empty($conversations)): ?>
                    <?php foreach ($conversations as $conv):
                        $thread  = $systemObject->getConversationThread($conv['id_resident']);
                        $lastMsg = end($thread);
                        $part = 'item';
                        include ROOT_PATH . '/app/Views/admn_messages_parts.php';
                    endforeach; ?>
                <?php else: ?>
                    <div class="chat-empty-list">No messages yet.</div>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Conversation -->
        <section class="msgs-main">
            <div class="msgs-placeholder" id="msgsPlaceholder">
                <i class="bi bi-chat-dots"></i>
                <div class="ph-title">Select a conversation</div>
                <div class="ph-sub">Messages sent by residents appear here.</div>
            </div>
            <div id="threads">
                <?php foreach ($conversations as $conv):
                    $thread  = $systemObject->getConversationThread($conv['id_resident']);
                    $lastMsg = end($thread);
                    $part = 'pane';
                    include ROOT_PATH . '/app/Views/admn_messages_parts.php';
                endforeach; ?>
            </div>
        </section>
    </div>
</div>

<!-- Hidden forms -->
<form id="deleteMsgForm" method="POST" action="admn_messages.php" style="display:none;">
    <input type="hidden" name="id_resident" id="deleteMsgId">
    <input type="hidden" name="delete_msg" value="1">
</form>

<form id="bulkDeleteForm" method="POST" action="admn_messages.php" style="display:none;">
    <input type="hidden" name="bulk_delete_msg" value="1">
    <div id="bulkDeleteIds"></div>
</form>

<form id="replyForm" method="POST" action="admn_messages.php" style="display:none;">
    <input type="hidden" name="id_resident" id="replyMsgId">
    <input type="hidden" name="reply_text" id="replyMsgText">
    <input type="hidden" name="reply_msg" value="1">
</form>

<!-- DELETE MESSAGE MODAL -->
<div id="deleteMsgModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
      <div class="bmis-modal-icon" style="background:#fee2e2;">
        <i class="bi bi-trash-fill" style="color:#dc2626; font-size:18px;"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Move to Archive</p>
        <p class="bmis-modal-sub">You can restore it later from Archive.</p>
      </div>
    </div>
    <hr style="margin:16px 0; border-color:#e5e7eb;">
    <div class="bmis-modal-info" style="background:#fef2f2; border:1.5px solid #fecaca;">
      <i class="bi bi-chat-left-dots-fill" style="color:#dc2626; font-size:20px; flex-shrink:0;"></i>
      <div>
        <p id="deleteMsgModalName" style="font-size:14px; font-weight:700; color:#991b1b;"></p>
        <p style="font-size:12px; color:#b91c1c;">This conversation will be moved to Archive. You can restore or permanently delete it from there.</p>
      </div>
    </div>
    <div style="display:flex; gap:8px; justify-content:flex-end;">
      <button class="bmis-btn-cancel" onclick="closeAllModals()">Cancel</button>
      <button class="bmis-btn-confirm" style="background:linear-gradient(135deg,#dc2626,#ef4444);"
              onclick="showLoader('Archiving conversation...'); document.getElementById('deleteMsgForm').submit();">
        <i class="bi bi-trash-fill me-1"></i> Yes, delete
      </button>
    </div>
  </div>
</div>

<!-- BULK DELETE MODAL -->
<div id="bulkDeleteModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
      <div class="bmis-modal-icon" style="background:#fee2e2;">
        <i class="bi bi-trash-fill" style="color:#dc2626; font-size:18px;"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Move to Archive</p>
        <p class="bmis-modal-sub">You can restore them later from Archive.</p>
      </div>
    </div>
    <hr style="margin:16px 0; border-color:#e5e7eb;">
    <div class="bmis-modal-info" style="background:#fef2f2; border:1.5px solid #fecaca;">
      <i class="bi bi-exclamation-triangle-fill" style="color:#dc2626; font-size:20px; flex-shrink:0;"></i>
      <div>
        <p id="bulkDeleteCount" style="font-size:14px; font-weight:700; color:#991b1b;"></p>
        <p style="font-size:12px; color:#b91c1c;">All selected conversations will be moved to Archive. You can restore or permanently delete them from there.</p>
      </div>
    </div>
    <div style="display:flex; gap:8px; justify-content:flex-end;">
      <button class="bmis-btn-cancel" onclick="closeAllModals()">Cancel</button>
      <button class="bmis-btn-confirm" style="background:linear-gradient(135deg,#dc2626,#ef4444);"
              onclick="submitBulkDelete()">
        <i class="bi bi-trash-fill me-1"></i> Yes, delete all
      </button>
    </div>
  </div>
</div>

<!-- SUCCESS TOAST -->
<div id="bmisToast">
  <div id="bmisToastInner">
    <div id="bmisToastIcon"><i id="bmisToastIconI"></i></div>
    <div style="flex:1; min-width:0;">
      <p id="bmisToastTitle" style="font-size:13px; font-weight:700; margin:0 0 2px;"></p>
      <p id="bmisToastMsg" style="font-size:12px; margin:0; color:#6b7280; line-height:1.4;"></p>
    </div>
    <button onclick="closeToast()" style="background:none; border:none; cursor:pointer; color:#9ca3af; font-size:16px; padding:0; flex-shrink:0; line-height:1;">
      <i class="bi bi-x-lg"></i>
    </button>
  </div>
</div>

<!-- Loading Overlay -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script>
// ── Loader ───────────────────────────────────────────────────
function showLoader(msg) {
    var loader = document.getElementById('page-loader');
    var text   = document.getElementById('loader-msg');
    if (text) text.textContent = msg || 'Please wait...';
    if (loader) loader.classList.add('show');
}
function hideLoader() {
    var loader = document.getElementById('page-loader');
    if (loader) loader.classList.remove('show');
}

// ── Conversation selection ───────────────────────────────────
var activeRid = null;
try { activeRid = sessionStorage.getItem('bmisActiveChat'); } catch (e) {}

function scrollThreadToBottom(rid) {
    var body = document.getElementById('threadBody' + rid);
    if (body) requestAnimationFrame(function () { body.scrollTop = body.scrollHeight; });
}
function applyActive(scroll) {
    var shell = document.getElementById('msgsShell');
    var found = false;
    document.querySelectorAll('.thread-pane').forEach(function (p) {
        var on = activeRid !== null && p.dataset.rid === String(activeRid);
        p.classList.toggle('d-none', !on);
        if (on) found = true;
    });
    document.querySelectorAll('.chat-item').forEach(function (i) {
        i.classList.toggle('active', activeRid !== null && i.dataset.rid === String(activeRid));
    });
    document.getElementById('msgsPlaceholder').style.display = found ? 'none' : 'flex';
    document.getElementById('threads').style.display = found ? 'flex' : 'none';
    shell.classList.toggle('chat-open', found);
    if (found && scroll) scrollThreadToBottom(activeRid);
}
function selectConversation(rid) {
    if (document.getElementById('msgsShell').classList.contains('select-mode')) {
        var cb = document.querySelector('#chatItem' + rid + ' .msg-checkbox');
        if (cb) { cb.checked = !cb.checked; updateBulkToolbar(); }
        return;
    }
    activeRid = String(rid);
    try { sessionStorage.setItem('bmisActiveChat', activeRid); } catch (e) {}
    applyActive(true);
    var ta = document.getElementById('replyText' + rid);
    if (ta && window.innerWidth >= 768) ta.focus();
}
function closeConversation() {
    activeRid = null;
    try { sessionStorage.removeItem('bmisActiveChat'); } catch (e) {}
    applyActive(false);
}

// ── Search ───────────────────────────────────────────────────
function filterChats() {
    var q = document.getElementById('chatSearch').value.trim().toLowerCase();
    document.querySelectorAll('.chat-item').forEach(function (i) {
        i.style.display = (!q || (i.dataset.search || '').indexOf(q) !== -1) ? '' : 'none';
    });
}

// ── Select mode / bulk delete ────────────────────────────────
function toggleSelectMode() {
    var shell = document.getElementById('msgsShell');
    var on = shell.classList.toggle('select-mode');
    document.getElementById('selectModeBtn').textContent = on ? 'Done' : 'Select';
    if (!on) clearSelection();
}
function updateBulkToolbar() {
    var checked = document.querySelectorAll('.msg-checkbox:checked');
    var toolbar = document.getElementById('bulkToolbar');
    document.querySelectorAll('.chat-item').forEach(function (i) {
        var c = i.querySelector('.msg-checkbox');
        i.classList.toggle('row-selected', !!(c && c.checked));
    });
    toolbar.classList.toggle('d-none', checked.length === 0);
    toolbar.classList.toggle('d-flex', checked.length > 0);
    document.getElementById('selectedCount').textContent =
        checked.length + ' selected';
}
function clearSelection() {
    document.querySelectorAll('.msg-checkbox').forEach(function (c) { c.checked = false; });
    updateBulkToolbar();
}
function openBulkDeleteModal() {
    var count = document.querySelectorAll('.msg-checkbox:checked').length;
    document.getElementById('bulkDeleteCount').textContent =
        count + ' conversation' + (count !== 1 ? 's' : '') + ' will be moved to Archive.';
    document.getElementById('bulkDeleteModal').classList.add('open');
}
function submitBulkDelete() {
    var ids = Array.from(document.querySelectorAll('.msg-checkbox:checked')).map(function (c) { return c.value; });
    document.getElementById('bulkDeleteIds').innerHTML = ids.map(function (id) {
        return '<input type="hidden" name="msg_ids[]" value="' + id + '">';
    }).join('');
    showLoader('Archiving selected conversations...');
    document.getElementById('bulkDeleteForm').submit();
}

// ── Single delete modal ──────────────────────────────────────
function openDeleteMsgModal(msgId, name) {
    document.getElementById('deleteMsgId').value = msgId;
    document.getElementById('deleteMsgModalName').textContent = 'Conversation with ' + name;
    document.getElementById('deleteMsgModal').classList.add('open');
}
function closeAllModals() {
    document.querySelectorAll('.bmis-modal-backdrop').forEach(function (m) { m.classList.remove('open'); });
}
document.querySelectorAll('.bmis-modal-backdrop').forEach(function (m) {
    m.addEventListener('click', function (e) { if (e.target === m) closeAllModals(); });
});

// ── Reply ────────────────────────────────────────────────────
function autoGrow(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
}
function sendReply(rid) {
    var textarea = document.getElementById('replyText' + rid);
    var text = textarea.value.trim();
    if (text === '') { textarea.focus(); return; }
    textarea.value = '';
    autoGrow(textarea);
    var body = new URLSearchParams();
    body.set('reply_msg', '1');
    body.set('id_resident', rid);
    body.set('reply_text', text);
    // Send in the background (no page reload / loader), then refresh the chat.
    fetch('admn_messages.php', { method: 'POST', body: body, credentials: 'same-origin' })
        .then(function () { return window.refreshChats(true); })
        .then(function () {
            var ta = document.getElementById('replyText' + rid);
            if (ta) ta.focus();
            scrollThreadToBottom(rid);
        })
        .catch(function () { showToast(toastConfigs.error); });
}

// ── Toast ────────────────────────────────────────────────────
var toastConfigs = {
    approved:       { type: 'success', title: 'Approved',   msg: 'Resident account has been verified successfully.' },
    rejected:       { type: 'warning', title: 'Rejected',   msg: 'ID submission rejected. Resident has been notified.' },
    upload_deleted: { type: 'delete',  title: 'Deleted',    msg: 'ID submission record permanently deleted.' },
    msg_deleted:    { type: 'delete',  title: 'Archived',   msg: 'Conversation(s) moved to Archive.' },
    reply_sent:     { type: 'success', title: 'Reply Sent', msg: 'Your reply has been sent to the resident.' },
    error:          { type: 'error',   title: 'Error',      msg: 'Something went wrong. Please try again.' }
};
function showToast(cfg) {
    var isSuccess = cfg.type === 'success';
    var isWarning = cfg.type === 'warning';
    var bg        = isSuccess ? '#f0fdf4' : (isWarning ? '#fffbeb' : '#fef2f2');
    var border    = isSuccess ? '#bbf7d0' : (isWarning ? '#fde68a' : '#fecaca');
    var iconBg    = isSuccess ? '#d1fae5' : (isWarning ? '#fef3c7' : '#fee2e2');
    var iconColor = isSuccess ? '#059669' : (isWarning ? '#d97706' : '#dc2626');
    var iconCls   = isSuccess ? 'bi-check-circle-fill' : (isWarning ? 'bi-exclamation-circle-fill' : 'bi-trash-fill');

    var inner = document.getElementById('bmisToastInner');
    inner.style.background = bg;
    inner.style.border     = '1.5px solid ' + border;
    document.getElementById('bmisToastIcon').style.background = iconBg;

    var iconI = document.getElementById('bmisToastIconI');
    iconI.className      = 'bi ' + iconCls;
    iconI.style.color    = iconColor;
    iconI.style.fontSize = '16px';

    var titleEl = document.getElementById('bmisToastTitle');
    titleEl.textContent = cfg.title;
    titleEl.style.color = iconColor;

    document.getElementById('bmisToastMsg').textContent = cfg.msg;
    document.getElementById('bmisToast').style.display  = 'block';
    setTimeout(closeToast, 4500);
}
function closeToast() {
    var t = document.getElementById('bmisToast');
    t.style.opacity = '0';
    t.style.transition = 'opacity 0.3s';
    setTimeout(function () { t.style.display = 'none'; t.style.opacity = ''; t.style.transition = ''; }, 300);
}

(function () {
    var params = new URLSearchParams(window.location.search);
    var key    = params.get('toast');
    if (key && toastConfigs[key]) {
        showToast(toastConfigs[key]);
        history.replaceState(null, '', window.location.pathname);
    }
    // After archiving a chat, don't try to reopen it
    if (key === 'msg_deleted') { activeRid = null; try { sessionStorage.removeItem('bmisActiveChat'); } catch (e) {} }
})();

// ── Auto-poll every 60s ──────────────────────────────────────
(function () {
    function composerBusy() {
        var el = document.activeElement;
        if (el && el.classList && el.classList.contains('reply-textarea')) return true;
        return Array.prototype.some.call(document.querySelectorAll('.reply-textarea'), function (t) { return t.value.trim() !== ''; });
    }
    function poll(force) {
        if (!force && (composerBusy() || document.querySelector('.bmis-modal-backdrop.open'))) return Promise.resolve();
        return fetch('ajax_messages.php')
            .then(function (r) { return r.json(); })
            .then(function (data) {
                var checked = Array.from(document.querySelectorAll('.msg-checkbox:checked')).map(function (c) { return c.value; });
                var list = document.getElementById('chat-list');
                list.innerHTML = data.messages_html || '<div class="chat-empty-list">No messages yet.</div>';
                document.getElementById('threads').innerHTML = data.threads_html || '';
                checked.forEach(function (v) {
                    var cb = document.querySelector('.msg-checkbox[value="' + v + '"]');
                    if (cb) cb.checked = true;
                });
                updateBulkToolbar();
                filterChats();
                applyActive(false);
            })
            .catch(function () {});
    }
    window.refreshChats = poll;
    setInterval(function () { poll(false); }, 60000);
})();

window.addEventListener('pageshow', hideLoader);
applyActive(true);
</script>

</body>
<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>
</html>
