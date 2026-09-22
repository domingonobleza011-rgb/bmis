
<?php include(ROOT_PATH . '/dashboard_sidebar_start.php'); ?>
<style>
    .main-container { padding: 30px; background-color: #f8f9fa; min-height: 100vh; }
    .card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); transition: transform 0.3s; }
    .card-header { border-top-left-radius: 15px !important; border-top-right-radius: 15px !important; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; }
    .btn-primary { background: linear-gradient(45deg, #007bff, #0056b3); border: none; box-shadow: 0 4px 10px rgba(0,123,255,0.3); }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(0,123,255,0.4); }
    .table thead { background-color: #f1f3f5; }
    .clickable-row { cursor: pointer; transition: background 0.2s; }

    .bmis-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(0,0,0,0.45);
        align-items: center;
        justify-content: center;
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
    .bmis-modal-title { font-size: 15px; font-weight: 600; margin: 0; color: #0f2d5a; }
    .bmis-modal-sub   { font-size: 13px; color: #6b7280; margin: 0; }
    .bmis-modal-info  { border-radius: 8px; padding: 12px 16px; margin-bottom: 18px; display: flex; align-items: center; gap: 12px; }
    .bmis-modal-info p { margin: 0; }
    .bmis-btn-cancel {
        padding: 8px 18px; font-size: 13px; border-radius: 8px; cursor: pointer;
        border: 1px solid #d1d5db; background: #fff; color: #6b7280;
    }
    .bmis-btn-confirm {
        padding: 8px 20px; font-size: 13px; font-weight: 600; border-radius: 8px;
        cursor: pointer; border: none; color: #fff;
    }

    #bmisToast {
        display: none; position: fixed; bottom: 28px; right: 28px;
        z-index: 10000; min-width: 300px; max-width: 400px;
    }
    #bmisToastInner {
        border-radius: 14px; padding: 16px 20px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
        display: flex; align-items: flex-start; gap: 14px;
        animation: toastIn 0.3s ease;
    }
    #bmisToastIcon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; margin-top: 1px;
    }
    @keyframes toastIn {
        from { opacity: 0; transform: translateY(14px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .post-gallery { position: relative; }
    .post-gallery-more {
        position: absolute; inset: 0; display: flex; align-items: center; justify-content: center;
        background: rgba(15,45,90,0.55); color: #fff; font-size: 1.6rem; font-weight: 700;
        pointer-events: none;
    }
    #imageViewerModal {
        display: none; position: fixed; inset: 0; z-index: 10500;
        background: rgba(10,15,26,0.92); align-items: center; justify-content: center;
    }
    #imageViewerModal.open { display: flex; }
    .iv-stage {
        position: relative; width: 100%; height: 100%;
        display: flex; align-items: center; justify-content: center; padding: 60px 90px;
        box-sizing: border-box;
    }
    .iv-stage img {
        max-width: 100%; max-height: 100%; object-fit: contain;
        border-radius: 10px; box-shadow: 0 12px 48px rgba(0,0,0,0.5);
        animation: ivFadeIn 0.18s ease;
    }
    @keyframes ivFadeIn { from { opacity: 0; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }
    .iv-close {
        position: absolute; top: 18px; right: 22px; width: 42px; height: 42px;
        border-radius: 50%; border: none; background: rgba(255,255,255,0.1); color: #fff;
        font-size: 18px; display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background var(--transition); z-index: 2;
    }
    .iv-close:hover { background: rgba(255,255,255,0.22); }
    .iv-nav {
        position: absolute; top: 50%; transform: translateY(-50%);
        width: 48px; height: 48px; border-radius: 50%; border: none;
        background: rgba(255,255,255,0.1); color: #fff; font-size: 18px;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer; transition: background var(--transition), transform var(--transition); z-index: 2;
    }
    .iv-nav:hover { background: rgba(255,255,255,0.22); transform: translateY(-50%) scale(1.06); }
    .iv-prev { left: 18px; }
    .iv-next { right: 18px; }
    .iv-counter {
        position: absolute; bottom: 22px; left: 50%; transform: translateX(-50%);
        background: rgba(255,255,255,0.12); color: #fff; font-size: 0.8rem; font-weight: 600;
        padding: 6px 16px; border-radius: 999px; letter-spacing: 0.3px;
    }
    @media (max-width: 640px) {
        .iv-stage { padding: 70px 16px; }
        .iv-nav { width: 40px; height: 40px; font-size: 15px; }
        .iv-prev { left: 6px; }
        .iv-next { right: 6px; }
    }

@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600;700&display=swap');
:root {
    --navy:          #0f2d5a;
    --navy-mid:      #1a4480;
    --navy-light:    #2b5ea7;
    --navy-pale:     #e8eef7;
    --gold:          #c9943a;
    --gold-light:    #e8b86d;
    --gold-pale:     #fdf3e3;
    --teal:          #0d9488;
    --teal-pale:     #e0f2f0;
    --danger:        #dc2626;
    --danger-pale:   #fef2f2;
    --warning:       #d97706;
    --warning-pale:  #fffbeb;
    --success:       #059669;
    --success-pale:  #ecfdf5;
    --cream:         #f7f8fc;
    --white:         #ffffff;
    --text-dark:     #1a1a2e;
    --text-mid:      #4a5568;
    --text-light:    #718096;
    --border:        #e8ecf0;
    --shadow-sm:     0 2px 8px rgba(15,45,90,0.07);
    --shadow-md:     0 6px 24px rgba(15,45,90,0.11);
    --radius:        14px;
    --radius-sm:     10px;
    --transition:    0.22s cubic-bezier(0.4,0,0.2,1);
}
body { font-family: 'DM Sans', -apple-system, sans-serif !important; background: var(--cream) !important; color: var(--text-dark) !important; }
h1, h2, h3, h4, h5, h6 { font-family: 'DM Sans', sans-serif !important; }
h4 { font-weight: 700 !important; font-size: 1.05rem !important; color: var(--navy) !important; letter-spacing: 0.2px; display: flex; align-items: center; gap: 10px; }
h4::before { content: ''; display: inline-block; width: 4px; height: 20px; background: linear-gradient(to bottom, var(--gold), var(--gold-light)); border-radius: 4px; flex-shrink: 0; }
hr { border-color: var(--border) !important; opacity: 1 !important; margin: 0.5rem 0 !important; }
.sidebar { background: linear-gradient(180deg, var(--navy) 0%, var(--navy-mid) 60%, #153560 100%) !important; border-right: none !important; box-shadow: 4px 0 24px rgba(15,45,90,0.18); }
.sidebar-brand { padding: 1.6rem 1rem 1.4rem !important; background: rgba(0,0,0,0.12) !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important; gap: 10px; }
.sidebar-brand-text { font-family: 'DM Sans', sans-serif !important; font-size: 0.82rem !important; font-weight: 600 !important; letter-spacing: 0.3px !important; color: rgba(255,255,255,0.95) !important; text-transform: none !important; line-height: 1.3; }
.sidebar-divider { border-top-color: rgba(255,255,255,0.08) !important; margin: 0.6rem 1rem !important; }
.sidebar-heading { font-size: 0.65rem !important; font-weight: 700 !important; letter-spacing: 1.8px !important; text-transform: uppercase !important; color: rgba(255,255,255,0.35) !important; padding: 0.8rem 1.2rem 0.4rem !important; }
.sidebar .nav-item .nav-link { color: rgba(255,255,255,0.72) !important; font-size: 0.875rem !important; font-weight: 400 !important; padding: 10px 20px !important; border-radius: 0 !important; transition: all var(--transition) !important; display: flex; align-items: center; gap: 10px; border-left: 3px solid transparent; }
.sidebar .nav-item .nav-link i, .sidebar .nav-item .nav-link .bi { font-size: 0.95rem; width: 18px; text-align: center; flex-shrink: 0; color: rgba(255,255,255,0.5); transition: color var(--transition); }
.sidebar .nav-item .nav-link:hover { color: var(--white) !important; background: rgba(255,255,255,0.07) !important; border-left-color: rgba(201,148,58,0.5) !important; }
.sidebar .nav-item .nav-link:hover i, .sidebar .nav-item .nav-link:hover .bi { color: var(--gold-light); }
.sidebar .nav-item.active .nav-link, .sidebar .nav-item .nav-link.active { color: var(--white) !important; background: rgba(201,148,58,0.15) !important; border-left-color: var(--gold) !important; font-weight: 500 !important; }
.topbar { background: var(--white) !important; box-shadow: 0 2px 16px rgba(15,45,90,0.08) !important; border-bottom: 1px solid var(--border) !important; padding: 0 20px !important; height: 60px; align-items: center; }
.topbar .nav-item .nav-link { color: var(--text-mid) !important; font-size: 0.875rem; font-weight: 500; padding: 8px 14px !important; border-radius: 8px; transition: all var(--transition); display: flex; align-items: center; gap: 8px; }
.topbar .nav-item .nav-link:hover { background: var(--cream); color: var(--navy) !important; }
.topbar .text-gray-800 { color: var(--text-dark) !important; font-weight: 500; }
#content-wrapper { background: var(--cream) !important; }
#content { padding-bottom: 2rem; }
.container-fluid { padding: 1.5rem 2rem !important; }
.card { border: none !important; border-radius: var(--radius) !important; box-shadow: var(--shadow-sm) !important; transition: all var(--transition) !important; overflow: hidden; background: var(--white) !important; }
.card:hover { box-shadow: var(--shadow-md) !important; transform: translateY(-3px); }
.card-body { padding: 1.4rem 1.6rem !important; }
.card.border-left-primary { border-top: 3px solid var(--navy-light) !important; border-left: none !important; }
.card.border-left-info    { border-top: 3px solid var(--teal)       !important; border-left: none !important; }
.card.border-left-danger  { border-top: 3px solid var(--danger)     !important; border-left: none !important; }
.card.border-left-warning { border-top: 3px solid var(--warning)    !important; border-left: none !important; }
.card.border-left-success { border-top: 3px solid var(--success)    !important; border-left: none !important; }
.table { font-size: 0.875rem; }
.table thead th { background: var(--navy); color: var(--white); font-weight: 600; letter-spacing: 0.5px; font-size: 0.78rem; text-transform: uppercase; border: none; padding: 12px 16px; }
.table tbody tr:hover { background: var(--navy-pale); }
.table td, .table th { border-color: var(--border); vertical-align: middle; padding: 10px 16px; }
.btn-primary { background: linear-gradient(135deg, var(--navy), var(--navy-light)) !important; border: none !important; border-radius: 8px !important; font-weight: 600 !important; font-size: 0.875rem !important; letter-spacing: 0.3px; box-shadow: 0 3px 10px rgba(15,45,90,0.25) !important; transition: all var(--transition) !important; }
.btn-primary:hover { transform: translateY(-1px) !important; box-shadow: 0 6px 18px rgba(15,45,90,0.3) !important; }
.sticky-footer { background: var(--white) !important; border-top: 1px solid var(--border) !important; font-size: 0.8rem !important; color: var(--text-light) !important; padding: 16px 24px !important; }
::-webkit-scrollbar { width: 6px; }
::-webkit-scrollbar-track { background: transparent; }
::-webkit-scrollbar-thumb { background: rgba(15,45,90,0.15); border-radius: 3px; }
::-webkit-scrollbar-thumb:hover { background: rgba(15,45,90,0.28); }
@media (max-width: 768px) { .container-fluid { padding: 1rem 1.2rem !important; } .card:hover { transform: none; } }

.feed-wrap { max-width: 640px; margin: 0 auto; }
.composer-card { border-radius: var(--radius) !important; box-shadow: var(--shadow-sm) !important; background: var(--white); margin-bottom: 22px; overflow: visible; }
.composer-card:hover { transform: none !important; box-shadow: var(--shadow-sm) !important; }
.composer-header { display: flex; gap: 14px; padding: 20px 22px; align-items: flex-start; }
.composer-avatar, .post-avatar {
    display: flex; align-items: center; justify-content: center;
    border-radius: 50%; flex-shrink: 0; overflow: hidden;
    background: var(--white); border: 2px solid var(--navy-pale);
}
.composer-avatar { width: 44px; height: 44px; box-shadow: 0 3px 8px rgba(15,45,90,0.25); }
.post-avatar { width: 42px; height: 42px; box-shadow: 0 3px 8px rgba(15,45,90,0.16); }
.composer-avatar img, .post-avatar img { width: 100%; height: 100%; object-fit: cover; }
.composer-form { flex: 1; min-width: 0; }
.composer-textarea {
    width: 100%; border: 1px solid var(--border); border-radius: 14px;
    padding: 12px 16px; font-size: 0.95rem; resize: vertical; min-height: 64px;
    background: var(--cream); transition: all var(--transition);
}
.composer-textarea:focus { outline: none; border-color: var(--navy-light); background: var(--white); box-shadow: 0 0 0 3px rgba(43,94,167,0.12); }
.composer-image-preview { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.composer-image-preview img { width: 78px; height: 78px; object-fit: cover; border-radius: 10px; border: 1px solid var(--border); }
.composer-toolbar { display: flex; align-items: center; justify-content: space-between; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); }
.composer-attach-btn {
    display: inline-flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600;
    color: var(--teal); background: var(--teal-pale); padding: 8px 14px; border-radius: 999px;
    cursor: pointer; transition: all var(--transition); border: 1px solid transparent;
}
.composer-attach-btn:hover { background: #d4efec; }
.composer-post-btn {
    border: none; border-radius: 999px; padding: 9px 26px; font-weight: 700; font-size: 0.875rem;
    color: #fff; background: linear-gradient(135deg, var(--navy), var(--navy-light));
    box-shadow: 0 3px 10px rgba(15,45,90,0.25); transition: all var(--transition); cursor: pointer;
}
.composer-post-btn:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(15,45,90,0.3); }
.feed-heading { display: flex; align-items: center; justify-content: space-between; margin: 0 0 14px; }
.feed-heading h5 { margin: 0; font-weight: 700; color: var(--navy); font-size: 0.95rem; letter-spacing: 0.3px; display: flex; align-items: center; gap: 8px; }
.feed-heading h5 i { color: var(--gold); }
.feed-count-badge { background: var(--navy-pale); color: var(--navy); font-size: 0.72rem; font-weight: 700; padding: 4px 11px; border-radius: 999px; }
.post-card { border-radius: var(--radius) !important; box-shadow: var(--shadow-sm) !important; background: var(--white); margin-bottom: 18px; overflow: hidden; }
.post-card:hover { transform: none !important; box-shadow: var(--shadow-md) !important; }
.post-card-header { display: flex; align-items: center; gap: 12px; padding: 16px 20px 4px; }
.post-meta { flex: 1; min-width: 0; }
.post-name { font-weight: 700; font-size: 0.9rem; color: var(--text-dark); display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.badge-official { background: var(--teal-pale); color: var(--teal); font-size: 0.62rem; font-weight: 700; letter-spacing: 0.4px; text-transform: uppercase; padding: 2px 8px; border-radius: 999px; }
.post-sub { font-size: 0.76rem; color: var(--text-light); margin-top: 1px; }
.post-delete-btn {
    border: none; background: var(--cream); color: var(--text-light); width: 34px; height: 34px;
    border-radius: 10px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; transition: all var(--transition); flex-shrink: 0;
}
.post-delete-btn:hover { background: var(--danger-pale); color: var(--danger); }
.post-body { padding: 10px 20px 4px; font-size: 0.92rem; line-height: 1.55; color: var(--text-dark); white-space: pre-wrap; word-break: break-word; }
.post-gallery { display: grid; gap: 3px; padding: 12px 0 0; }
.post-gallery > div { position: relative; overflow: hidden; }
.post-gallery img { display: block; width: 100%; height: 100%; object-fit: cover; cursor: pointer; transition: filter var(--transition); }
.post-gallery img:hover { filter: brightness(0.94); }
.post-gallery-1 { grid-template-columns: 1fr; }
.post-gallery-1 > div { max-height: 420px; }
.post-gallery-2 { grid-template-columns: 1fr 1fr; }
.post-gallery-2 > div { height: 240px; }
.post-gallery-3 { grid-template-columns: 1fr 1fr; grid-auto-rows: 160px; }
.post-gallery-3 > div:first-child { grid-row: span 2; height: 100%; }
.post-stats { display: flex; align-items: center; justify-content: space-between; padding: 12px 20px 2px; font-size: 0.78rem; color: var(--text-light); }
.post-stats i { color: var(--danger); margin-right: 4px; }
.post-comment-toggle { cursor: pointer; font-weight: 600; transition: color var(--transition); }
.post-comment-toggle:hover { color: var(--navy-light); text-decoration: underline; }
.post-comments { display: none; padding: 10px 20px 18px; border-top: 1px solid var(--border); margin-top: 10px; max-height: 320px; overflow-y: auto; }
.post-comments.open { display: block; }
.comment-item { background: var(--cream); border-radius: 12px; padding: 9px 13px; margin-bottom: 8px; }
.comment-author { font-weight: 700; font-size: 0.8rem; color: var(--navy); }
.comment-text { font-size: 0.85rem; color: var(--text-dark); margin-top: 1px; }
.comment-time { font-size: 0.68rem; color: var(--text-light); margin-top: 3px; }
.post-no-comments { text-align: center; color: var(--text-light); font-size: 0.82rem; margin: 14px 0 4px; }
.feed-empty { text-align: center; padding: 60px 20px; color: var(--text-light); background: var(--white); border-radius: var(--radius); box-shadow: var(--shadow-sm); }
.feed-empty i { font-size: 2.2rem; color: var(--navy-pale); margin-bottom: 12px; display: block; }

/* ===== LOADING OVERLAY ===== */
#page-loader {
    position: fixed;
    inset: 0;
    background: rgba(15, 45, 90, 0.72);
    backdrop-filter: blur(6px);
    -webkit-backdrop-filter: blur(6px);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 1.25rem;
}
#page-loader.show { display: flex; }
#page-loader .spinner {
    width: 56px;
    height: 56px;
    border: 4px solid rgba(255,255,255,0.25);
    border-top-color: #c9943a;
    border-radius: 50%;
    animation: spin 0.85s linear infinite;
}
#page-loader .loader-text {
    color: #fff;
    font-weight: 600;
    font-size: 1.05rem;
}
@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<div class="main-container">
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="text-center fw-bold text-dark">Announcements</h2>
        </div>
    </div>

    <div class="feed-wrap">
        <!-- COMPOSER -->
        <div class="card composer-card">
            <div class="composer-header">
                <div class="composer-avatar"><img src="icons/logo.png" alt="Barangay San Pedro"></div>
                <form method="post" enctype="multipart/form-data" class="composer-form show-loader" data-loader-msg="Publishing announcement...">
                    <textarea name="event" class="composer-textarea" placeholder="What's happening in Barangay San Pedro?"></textarea>
                    <div id="composerImagePreview" class="composer-image-preview"></div>
                    <div class="composer-toolbar">
                        <label class="composer-attach-btn">
                            <i class="fas fa-image"></i> Photo
                            <input type="file" name="announcement_img[]" accept="image/*" multiple hidden onchange="previewComposerImages(this)">
                        </label>
                        <input type="hidden" name="start_date" value="<?= $cdate ?>">
                        <input name="addedby" type="hidden" value="<?= $userdetails['surname'] ?>, <?= $userdetails['firstname'] ?> <?= $userdetails['mname'] ?>">
                        <input type="hidden" name="create_announce" value="1">
                        <button type="submit" name="create_announce" class="composer-post-btn">Post</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- FEED -->
        <div class="feed-heading">
            <h5><i class="fas fa-stream"></i> Barangay Feed</h5>
            <span class="feed-count-badge"><?= (int) $announcementcount ?> post<?= $announcementcount == 1 ? '' : 's' ?></span>
        </div>

        <div class="feed">
            <?php if (is_array($view) && count($view) > 0): ?>
                <?php foreach ($view as $row):
                    $ann_id       = $row['id_announcement'];
                    $hasImg       = !empty($row['image']);
                    $images       = $hasImg ? array_values(array_filter(array_map('trim', explode(',', $row['image'])))) : [];
                    $imgCount     = count($images);
                    $galleryClass = 'post-gallery-' . min(max($imgCount, 1), 3);
                    $reactions    = $bmis->get_reactions($ann_id);
                    $comments     = $bmis->get_comments($ann_id);
                    $totalReact   = 0;
                    if (is_array($reactions)) { foreach ($reactions as $r) { $totalReact += (int) $r['count']; } }
                    $commentCount = is_array($comments) ? count($comments) : 0;
                    $preview_safe = htmlspecialchars(str_replace(["\r\n", "\r", "\n"], ' ', $row['event']), ENT_QUOTES);
                ?>
                <div class="card post-card" id="post-<?= $ann_id ?>">
                    <div class="post-card-header">
                        <div class="post-avatar"><img src="icons/logo.png" alt="Barangay San Pedro"></div>
                        <div class="post-meta">
                            <div class="post-name">
                                Barangay San Pedro
                                <span class="badge-official">Official</span>
                            </div>
                            <div class="post-sub">
                                <?= htmlspecialchars(date('F j, Y', strtotime($row['start_date']))) ?>
                                &middot; Posted by <?= htmlspecialchars($row['addedby']) ?>
                            </div>
                        </div>
                        <button type="button" class="post-delete-btn" title="Delete post"
                                data-id="<?= htmlspecialchars($ann_id, ENT_QUOTES) ?>"
                                data-preview="<?= $preview_safe ?>"
                                onclick="openDeleteModal(this.dataset.id, this.dataset.preview)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>

                    <?php if (!empty($row['event'])): ?>
                    <div class="post-body"><?= nl2br(bmis_linkify_announcement($row['event'])) ?></div>
                    <?php endif; ?>

                    <?php if ($hasImg && $imgCount > 0): ?>
                    <div class="post-gallery <?= $galleryClass ?>" data-images='<?= htmlspecialchars(json_encode($images), ENT_QUOTES, 'UTF-8') ?>'>
                        <?php $shown = array_slice($images, 0, 3); ?>
                        <?php foreach ($shown as $i => $img): ?>
                            <div style="position:relative; height:100%;">
                                <img src="uploads/<?= htmlspecialchars($img) ?>" alt="Announcement image"
                                     data-index="<?= $i ?>"
                                     onclick="openImageViewer(this)">
                                <?php if ($i === 2 && $imgCount > 3): ?>
                                    <div class="post-gallery-more" onclick="openImageViewer(this.previousElementSibling)">+<?= $imgCount - 3 ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <div class="post-stats">
                        <span><i class="fas fa-heart"></i> <?= $totalReact ?> reaction<?= $totalReact == 1 ? '' : 's' ?></span>
                        <span class="post-comment-toggle" onclick="toggleComments(<?= $ann_id ?>)">
                            <?= $commentCount ?> comment<?= $commentCount == 1 ? '' : 's' ?>
                        </span>
                    </div>

                    <div class="post-comments" id="comments-<?= $ann_id ?>">
                        <?php if ($commentCount > 0): ?>
                            <?php foreach ($comments as $c): ?>
                            <div class="comment-item">
                                <div class="comment-author"><?= htmlspecialchars($c['full_name']) ?></div>
                                <div class="comment-text"><?= htmlspecialchars($c['comment_text']) ?></div>
                                <div class="comment-time"><?= htmlspecialchars($c['created_at']) ?></div>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="post-no-comments">No comments yet.</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="feed-empty">
                    <i class="fas fa-bullhorn"></i>
                    <p>No announcements posted yet. Be the first to share something with the barangay!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- IMAGE VIEWER -->
<div id="imageViewerModal">
    <div class="iv-stage">
        <button type="button" class="iv-close" onclick="closeImageViewer()" title="Close">
            <i class="fas fa-times"></i>
        </button>
        <button type="button" class="iv-nav iv-prev" id="ivPrevBtn" onclick="ivStep(-1)" title="Previous">
            <i class="fas fa-chevron-left"></i>
        </button>
        <img id="imageViewerImg" src="" alt="Announcement image">
        <button type="button" class="iv-nav iv-next" id="ivNextBtn" onclick="ivStep(1)" title="Next">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="iv-counter" id="ivCounter" style="display:none;"></div>
    </div>
</div>

<!-- HIDDEN DELETE FORM -->
<form id="deleteAnnouncementForm" method="POST" action="admn_announcement_crud.php" style="display:none;">
    <input type="hidden" name="id_announcement" id="deleteAnnouncementId">
    <input type="hidden" name="delete_announcement" value="1">
</form>

<!-- DELETE MODAL -->
<div id="deleteAnnouncementModal" class="bmis-modal-backdrop">
  <div class="bmis-modal-card">
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
      <div class="bmis-modal-icon" style="background:#fee2e2;">
        <i class="fas fa-trash-alt" style="color:#dc2626; font-size:18px;"></i>
      </div>
      <div>
        <p class="bmis-modal-title">Delete announcement</p>
        <p class="bmis-modal-sub">This action cannot be undone.</p>
      </div>
    </div>
    <hr style="margin:16px 0; border-color:#e5e7eb;">
    <div class="bmis-modal-info" style="background:#fef2f2; border:1.5px solid #fecaca;">
      <i class="fas fa-exclamation-triangle" style="color:#dc2626; font-size:20px; flex-shrink:0;"></i>
      <div>
        <p id="deleteAnnouncementPreview" style="font-size:13px; font-weight:600; color:#991b1b; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:280px;"></p>
        <p style="font-size:12px; color:#b91c1c; margin-top:2px;">This announcement will be permanently removed.</p>
      </div>
    </div>
    <div style="display:flex; gap:8px; justify-content:flex-end;">
      <button class="bmis-btn-cancel" onclick="closeDeleteModal()">Cancel</button>
      <button class="bmis-btn-confirm" style="background:linear-gradient(135deg,#dc2626,#ef4444);"
              onclick="showLoader('Deleting announcement...'); document.getElementById('deleteAnnouncementForm').submit();">
        <i class="fas fa-trash-alt" style="margin-right:5px;"></i> Yes, delete
      </button>
    </div>
  </div>
</div>

<!-- SUCCESS TOAST -->
<div id="bmisToast">
  <div id="bmisToastInner">
    <div id="bmisToastIcon">
      <i id="bmisToastIconI"></i>
    </div>
    <div style="flex:1; min-width:0;">
      <p id="bmisToastTitle" style="font-size:13px; font-weight:700; margin:0 0 2px;"></p>
      <p id="bmisToastMsg" style="font-size:12px; margin:0; color:#6b7280; line-height:1.4;"></p>
    </div>
    <button onclick="closeToast()" style="background:none; border:none; cursor:pointer; color:#9ca3af; font-size:16px; padding:0; flex-shrink:0; line-height:1;">
      <i class="fas fa-times"></i>
    </button>
  </div>
</div>

<!-- Loading Overlay -->
<div id="page-loader">
    <div class="spinner"></div>
    <div class="loader-text" id="loader-msg">Please wait...</div>
</div>

<script>
function showLoader(msg) {
    const loader = document.getElementById('page-loader');
    const text   = document.getElementById('loader-msg');
    if (text) text.textContent = msg || 'Please wait...';
    if (loader) loader.classList.add('show');
}
function hideLoader() {
    const loader = document.getElementById('page-loader');
    if (loader) loader.classList.remove('show');
}
window.addEventListener('pageshow', function () {
    hideLoader();
});
let loaderTimeout = null;
document.querySelectorAll('form.show-loader').forEach(function (form) {
    form.addEventListener('submit', function () {
        const msg = this.dataset.loaderMsg || 'Please wait...';
        showLoader(msg);
        if (loaderTimeout) clearTimeout(loaderTimeout);
        loaderTimeout = setTimeout(hideLoader, 12000);
        const btn = this.querySelector('button[type="submit"], input[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = 'Posting...';
        }
    });
});

// Image viewer
var ivImages = [];
var ivIndex  = 0;
function openImageViewer(imgEl) {
    var gallery = imgEl.closest('.post-gallery');
    try {
        ivImages = JSON.parse(gallery.dataset.images || '[]');
    } catch (e) {
        ivImages = [imgEl.getAttribute('src').replace(/^uploads\//, '')];
    }
    ivIndex = parseInt(imgEl.dataset.index, 10) || 0;
    ivRender();
    document.getElementById('imageViewerModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function ivRender() {
    if (!ivImages.length) return;
    document.getElementById('imageViewerImg').src = 'uploads/' + ivImages[ivIndex];
    var total   = ivImages.length;
    var hasMany = total > 1;
    document.getElementById('ivPrevBtn').style.display = hasMany ? 'flex' : 'none';
    document.getElementById('ivNextBtn').style.display = hasMany ? 'flex' : 'none';
    var counter = document.getElementById('ivCounter');
    if (hasMany) {
        counter.style.display = 'block';
        counter.textContent   = (ivIndex + 1) + ' / ' + total;
    } else {
        counter.style.display = 'none';
    }
}
function ivStep(dir) {
    if (!ivImages.length) return;
    ivIndex = (ivIndex + dir + ivImages.length) % ivImages.length;
    ivRender();
}
function closeImageViewer() {
    document.getElementById('imageViewerModal').classList.remove('open');
    document.body.style.overflow = '';
}
document.getElementById('imageViewerModal').addEventListener('click', function (e) {
    if (e.target === this) closeImageViewer();
});
document.addEventListener('keydown', function (e) {
    var modal = document.getElementById('imageViewerModal');
    if (!modal.classList.contains('open')) return;
    if (e.key === 'Escape') closeImageViewer();
    else if (e.key === 'ArrowLeft') ivStep(-1);
    else if (e.key === 'ArrowRight') ivStep(1);
});

// Delete modal
function openDeleteModal(id, preview) {
    document.getElementById('deleteAnnouncementId').value = id;
    document.getElementById('deleteAnnouncementPreview').textContent = preview;
    document.getElementById('deleteAnnouncementModal').classList.add('open');
}
function closeDeleteModal() {
    const modal = document.getElementById('deleteAnnouncementModal');
    modal.classList.remove('open');
    modal.style.display = '';
}
document.getElementById('deleteAnnouncementModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});

// Toast
var toastConfigs = {
    created: { type: 'success', title: 'Published', msg: 'Announcement published successfully.' },
    deleted: { type: 'delete',  title: 'Deleted',   msg: 'Announcement permanently deleted.' },
};
function showToast(cfg) {
    var isSuccess = cfg.type === 'success';
    var bg        = isSuccess ? '#f0fdf4' : '#fef2f2';
    var border    = isSuccess ? '#bbf7d0' : '#fecaca';
    var iconBg    = isSuccess ? '#d1fae5' : '#fee2e2';
    var iconColor = isSuccess ? '#059669' : '#dc2626';
    var iconCls   = isSuccess ? 'fa-check-circle' : 'fa-trash-alt';
    var inner = document.getElementById('bmisToastInner');
    inner.style.background = bg;
    inner.style.border     = '1.5px solid ' + border;
    var icon = document.getElementById('bmisToastIcon');
    icon.style.background  = iconBg;
    var iconI = document.getElementById('bmisToastIconI');
    iconI.className        = 'fas ' + iconCls;
    iconI.style.color      = iconColor;
    iconI.style.fontSize   = '16px';
    var titleEl = document.getElementById('bmisToastTitle');
    titleEl.textContent    = cfg.title;
    titleEl.style.color    = iconColor;
    document.getElementById('bmisToastMsg').textContent = cfg.msg;
    document.getElementById('bmisToast').style.display  = 'block';
    setTimeout(closeToast, 4500);
}
function closeToast() {
    var t = document.getElementById('bmisToast');
    t.style.opacity    = '0';
    t.style.transition = 'opacity 0.3s';
    setTimeout(function() { t.style.display = 'none'; t.style.opacity = ''; t.style.transition = ''; }, 300);
}
(function() {
    var params = new URLSearchParams(window.location.search);
    var key    = params.get('toast');
    if (key && toastConfigs[key]) {
        showToast(toastConfigs[key]);
        history.replaceState(null, '', window.location.pathname);
    }
})();

function toggleComments(id) {
    var el = document.getElementById('comments-' + id);
    if (el) el.classList.toggle('open');
}

function previewComposerImages(input) {
    var wrap = document.getElementById('composerImagePreview');
    wrap.innerHTML = '';
    if (!input.files || !input.files.length) return;
    Array.from(input.files).forEach(function (file) {
        if (!file.type.startsWith('image/')) return;
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            wrap.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/67a9b7069e.js" crossorigin="anonymous"></script>
<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>