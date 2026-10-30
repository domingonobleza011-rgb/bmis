<?php
// Turn http(s):// and www. links in a post into tappable links (safe: text is escaped first)
if (!function_exists('sk_linkify')) {
    function sk_linkify($text) {
        $safe = htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
        $safe = preg_replace_callback(
            '~(?<![\w@/])((?:https?://|www\.)[^\s<]+)~iu',
            function ($m) {
                $url = $m[1]; $trail = '';
                // keep trailing punctuation outside the link
                while ($url !== '' && preg_match('~[.,;:!?)\]\'"]$|&quot;$|&#0?39;$~', $url)) {
                    if (preg_match('~(&quot;|&#0?39;)$~', $url, $q)) { $trail = $q[1] . $trail; $url = substr($url, 0, -strlen($q[1])); }
                    else { $trail = substr($url, -1) . $trail; $url = substr($url, 0, -1); }
                }
                if ($url === '') return $m[0];
                $href = preg_match('~^https?://~i', $url) ? $url : 'https://' . $url;
                return '<a href="' . $href . '" target="_blank" rel="noopener noreferrer" class="post-link">' . $url . '</a>' . $trail;
            },
            $safe
        );
        return nl2br($safe);
    }
}
?>

<?php include(ROOT_PATH . '/dashboard_sidebar_start_sk.php'); ?>
<style>
:root { --sk:#1a4480;--gold:#c9943a;--sk-pale:#e8eef8;--gold-pale:#fdf3e3; }
.page-header { background:linear-gradient(135deg,#1a6b3a,#2dab5f);color:#fff;border-radius:16px;padding:26px 30px;margin-bottom:24px;display:flex;align-items:center;gap:16px;box-shadow:0 6px 24px rgba(26,107,58,.2); }
.page-header .hdr-icon { width:60px;height:60px;border-radius:14px;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;font-size:1.7rem;flex-shrink:0; }
.page-header h2 { margin:0;font-size:1.5rem;font-weight:700; }
.page-header p  { margin:4px 0 0;opacity:.82;font-size:.88rem; }
.panel { background:#fff;border-radius:14px;padding:22px 24px;box-shadow:0 2px 8px rgba(26,107,58,.07);border:1.5px solid #e8ecf0; }

.post-card { background:#fff;border-radius:16px;padding:24px 26px;box-shadow:0 2px 14px rgba(0,0,0,.07);border:1.5px solid #e8ecf0;position:relative;transition:transform .15s,box-shadow .15s; }
.post-card:hover { transform:translateY(-2px);box-shadow:0 8px 28px rgba(0,0,0,.12); }
.post-card.pinned { border:2px solid var(--gold);background:linear-gradient(135deg,#fffdf5,#fff); }
.pinned-ribbon { position:absolute;top:-1px;right:14px;background:var(--gold);color:#fff;font-size:.68rem;font-weight:800;padding:3px 10px 5px;border-radius:0 0 8px 8px;letter-spacing:.8px;text-transform:uppercase; }
.post-type-badge { display:inline-block;border-radius:7px;padding:3px 10px;font-size:.73rem;font-weight:700;margin-bottom:10px; }
.type-announcement { background:#e8f5ed;color:#1a6b3a; }
.type-opportunity   { background:#e8f0fe;color:#1967d2; }
.type-reminder      { background:#fdf3e3;color:#c9943a; }
.type-achievement   { background:#f0eafe;color:#6200ea; }
.type-general       { background:#f0f4f8;color:#555; }
.post-title { font-size:1.1rem;font-weight:700;color:#1a6b3a;margin-bottom:8px;line-height:1.4; }
.post-content { overflow-wrap:anywhere; font-size:.9rem;color:#444;line-height:1.65;margin-bottom:12px; }
.post-meta { font-size:.76rem;color:#999;display:flex;flex-wrap:wrap;gap:10px; }
.post-meta span { display:flex;align-items:center;gap:4px; }
.post-actions { display:flex;gap:8px;margin-top:14px;flex-wrap:wrap; }
.action-btn { border:none;border-radius:7px;padding:5px 12px;font-size:.8rem;font-weight:600;cursor:pointer;transition:all .15s; }
.edit-btn { background:#e8f0fe;color:#1967d2; }
.edit-btn:hover { background:#1967d2;color:#fff; }
.del-btn { background:#fde8e8;color:#c0392b; }
.del-btn:hover { background:#c0392b;color:#fff; }
.pin-btn  { background:#fdf3e3;color:#c9943a; }
.pin-btn:hover { background:#c9943a;color:#fff; }
.unpin-btn { background:#e8f5ed;color:#1a6b3a; }
.unpin-btn:hover { background:#1a6b3a;color:#fff; }

.btn-sk { background:#1a6b3a;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:.875rem;font-weight:600;transition:all .2s; }
.btn-sk:hover { background:#145230;color:#fff; }
.filter-tab { padding:7px 16px;border-radius:8px;border:1.5px solid #e8ecf0;font-size:.82rem;font-weight:600;cursor:pointer;background:#fff;color:#666;text-decoration:none;transition:all .15s; }
.filter-tab:hover,.filter-tab.active { border-color:#1a6b3a;background:#1a6b3a;color:#fff; }
.modal-header { background:#1a6b3a;color:#fff;border-radius:12px 12px 0 0; }
.modal-header .btn-close { filter:invert(1); }
.alert-success-custom { background:#e8f5ed;color:#1a6b3a;border:1.5px solid #1a6b3a;border-radius:10px;padding:10px 18px;font-size:.875rem;font-weight:600;margin-bottom:16px; }
.post-content a.post-link { color:#1967d2;text-decoration:underline;word-break:break-all;cursor:pointer; }
.post-content a.post-link:hover { color:#0f2d5a; }
.post-head { display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px; }
.post-head .post-type-badge { margin-bottom:0; }
.post-head .dots-btn { margin:-6px -6px -6px 0; }
.dots-btn { width:40px;height:40px;border:0;border-radius:50%;background:#eef2f8;color:#1a4480;display:inline-flex;align-items:center;justify-content:center;font-size:1rem; }
.dots-btn:active, .dots-btn[aria-expanded="true"] { background:#1a4480;color:#fff; }
.sk-dots .dropdown-menu { min-width:150px;border-radius:12px;padding:6px; }
.sk-dots .dropdown-item { border-radius:8px;padding:10px 12px;font-size:.9rem; }
</style>

<div class="container-fluid">
    <div class="page-header">
        <div class="hdr-icon"><i class="fas fa-bullhorn"></i></div>
        <div>
            <h2>SK Announcements Portal</h2>
            <p>Publish and manage official SK announcements and youth-led community updates</p>
        </div>
    </div>

    <?php if (isset($_GET['success'])): ?>
    <div class="alert-success-custom"><i class="fas fa-check-circle me-2"></i>
        <?= $_GET['success']==='added'?'Announcement posted!':($_GET['success']==='updated'?'Announcement updated!':'Announcement deleted.') ?>
    </div>
    <?php endif; ?>

    <div class="panel mb-4">
        <div class="d-flex flex-wrap gap-2 align-items-center justify-content-between">
            <div class="d-flex flex-wrap gap-2">
                <a href="sk_announcements.php" class="filter-tab <?= !$type_filter?'active':'' ?>">All</a>
                <?php foreach ($post_types as $pt): ?>
                <a href="sk_announcements.php?type=<?= urlencode($pt) ?>" class="filter-tab <?= $type_filter===$pt?'active':'' ?>"><?= $pt ?></a>
                <?php endforeach; ?>
            </div>
            <button class="btn-sk btn" data-bs-toggle="modal" data-bs-target="#addPostModal"><i class="fas fa-plus me-1"></i> New Announcement</button>
        </div>
    </div>

    <?php if (empty($posts)): ?>
    <div class="panel text-center py-5">
        <i class="fas fa-bullhorn fa-3x text-muted mb-3"></i>
        <p class="text-muted">No announcements yet. Post the first one!</p>
    </div>
    <?php else: ?>
    <div class="row g-4">
    <?php foreach ($posts as $post): 
        $tc_map = ['Announcement'=>'type-announcement','Opportunity'=>'type-opportunity','Reminder'=>'type-reminder','Achievement'=>'type-achievement','General'=>'type-general'];
        $tc = $tc_map[$post['post_type']] ?? 'type-general';
    ?>
    <div class="col-md-6 col-xl-4">
        <div class="post-card <?= $post['is_pinned']?'pinned':'' ?>">
            <?php if ($post['is_pinned']): ?><div class="pinned-ribbon"><i class="fas fa-thumbtack me-1"></i>Pinned</div><?php endif; ?>
            <div class="post-head">
                <span class="post-type-badge <?= $tc ?>"><?= htmlspecialchars($post['post_type']) ?></span>
                <div class="dropdown d-md-none sk-dots">
                    <button type="button" class="dots-btn" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Actions"><i class="fas fa-ellipsis-v"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><button type="button" class="dropdown-item" onclick="skPostAct(this,'.edit-btn')"><i class="fas fa-edit fa-fw me-2"></i>Edit</button></li>
                        <li><button type="button" class="dropdown-item" onclick="skPostAct(this,'[name=toggle_pin]')"><i class="fas fa-thumbtack fa-fw me-2"></i><?= $post['is_pinned']?'Unpin':'Pin' ?></button></li>
                        <li><button type="button" class="dropdown-item text-danger" onclick="skPostAct(this,'.del-btn')"><i class="fas fa-trash fa-fw me-2"></i>Delete</button></li>
                    </ul>
                </div>
            </div>
            <div class="post-title"><?= htmlspecialchars($post['post_title']) ?></div>
            <div class="post-content"><?= sk_linkify($post['post_content']) ?></div>
            <div class="post-meta">
                <span><i class="fas fa-user"></i><?= htmlspecialchars($post['posted_by']) ?></span>
                <span><i class="fas fa-calendar"></i><?= date('M d, Y', strtotime($post['date_posted'])) ?></span>
                <span><i class="fas fa-clock"></i><?= date('h:i A', strtotime($post['date_posted'])) ?></span>
            </div>
            <div class="post-actions">
                <button class="action-btn edit-btn"
                    data-bs-toggle="modal" data-bs-target="#editPostModal"
                    data-id="<?= $post['id_post'] ?>"
                    data-title="<?= htmlspecialchars($post['post_title'],ENT_QUOTES) ?>"
                    data-content="<?= htmlspecialchars($post['post_content'],ENT_QUOTES) ?>"
                    data-type="<?= htmlspecialchars($post['post_type'],ENT_QUOTES) ?>"
                    data-pinned="<?= $post['is_pinned'] ?>"
                ><i class="fas fa-edit"></i> Edit</button>
                <form method="POST" class="d-inline">
                    <input type="hidden" name="id_post" value="<?= $post['id_post'] ?>">
                    <button type="submit" name="toggle_pin" class="action-btn <?= $post['is_pinned']?'unpin-btn':'pin-btn' ?>">
                        <i class="fas fa-thumbtack"></i> <?= $post['is_pinned']?'Unpin':'Pin' ?>
                    </button>
                </form>
                <button class="action-btn del-btn"
                    data-bs-toggle="modal" data-bs-target="#delPostModal"
                    data-id="<?= $post['id_post'] ?>"
                    data-title="<?= htmlspecialchars($post['post_title'],ENT_QUOTES) ?>"
                ><i class="fas fa-trash"></i></button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<script>
function skPostAct(item, sel) {   // reuse the existing Edit / Pin / Delete controls of this card
    var box = item.closest('.post-card'), b = box && box.querySelector('.post-actions ' + sel);
    if (b) b.click();
}
</script>
<!-- Add Post Modal -->
<div class="modal fade" id="addPostModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><i class="fas fa-plus me-2"></i>New Announcement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-semibold">Title</label><input name="post_title" class="form-control" placeholder="Announcement title…" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Content</label><textarea name="post_content" class="form-control" rows="5" placeholder="Write your announcement here…" required></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-semibold">Type</label>
            <select name="post_type" class="form-select" required>
              <?php foreach($post_types as $pt): ?><option><?= $pt ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="is_pinned" id="add_pin" value="1">
              <label class="form-check-label fw-semibold" for="add_pin"><i class="fas fa-thumbtack text-warning me-1"></i> Pin this announcement</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="add_post" class="btn-sk btn"><i class="fas fa-paper-plane me-1"></i> Publish</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Post Modal -->
<div class="modal fade" id="editPostModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Announcement</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form method="POST">
      <input type="hidden" name="id_post" id="ep_id">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label fw-semibold">Title</label><input name="post_title" id="ep_title" class="form-control" required></div>
        <div class="mb-3"><label class="form-label fw-semibold">Content</label><textarea name="post_content" id="ep_content" class="form-control" rows="5" required></textarea></div>
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label fw-semibold">Type</label>
            <select name="post_type" id="ep_type" class="form-select" required>
              <?php foreach($post_types as $pt): ?><option><?= $pt ?></option><?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-6 d-flex align-items-end">
            <div class="form-check mb-2">
              <input class="form-check-input" type="checkbox" name="is_pinned" id="ep_pinned" value="1">
              <label class="form-check-label fw-semibold" for="ep_pinned"><i class="fas fa-thumbtack text-warning me-1"></i> Pin this announcement</label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="submit" name="edit_post" class="btn-sk btn"><i class="fas fa-save me-1"></i> Update</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Delete Post Modal -->
<div class="modal fade" id="delPostModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header" style="background:#c0392b;"><h5 class="modal-title text-white">Delete Announcement</h5><button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button></div>
      <div class="modal-body">Delete: <strong id="del_post_title"></strong>?</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form method="POST"><input type="hidden" name="id_post" id="del_post_id">
          <button type="submit" name="delete_post" class="btn btn-danger"><i class="fas fa-trash me-1"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
document.getElementById('editPostModal').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    document.getElementById('ep_id').value       = b.dataset.id;
    document.getElementById('ep_title').value    = b.dataset.title;
    document.getElementById('ep_content').value  = b.dataset.content;
    document.getElementById('ep_type').value     = b.dataset.type;
    document.getElementById('ep_pinned').checked = b.dataset.pinned === '1';
});
document.getElementById('delPostModal').addEventListener('show.bs.modal', function(e) {
    const b = e.relatedTarget;
    document.getElementById('del_post_id').value           = b.dataset.id;
    document.getElementById('del_post_title').textContent  = b.dataset.title;
});

// Show the shared sidebar loading screen (from dashboard_sidebar_end.php)
// for every feature on this page: the filter tabs, and the Publish/Update/
// Delete/Pin-Unpin forms — all of which reload the page. Sidebar links are
// skipped here since dashboard_sidebar_end.php already handles those, and
// modal-trigger buttons are left alone since they don't navigate.
document.querySelectorAll('a[href]').forEach(function (link) {
    if (link.closest('.sidebar')) return;
    link.addEventListener('click', function (e) {
        if (e.defaultPrevented || e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1) return;
        var href = link.getAttribute('href');
        if (!href || href === '#' || href.indexOf('javascript:') === 0) return;
        if (link.hasAttribute('data-bs-toggle') || link.hasAttribute('data-bs-dismiss')) return;
        if (link.target && link.target !== '' && link.target !== '_self') return;
        if (typeof showPageNavLoader === 'function') showPageNavLoader('Loading...');
    });
});

document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
        if (form.checkValidity && !form.checkValidity()) return;
        if (typeof showPageNavLoader === 'function') showPageNavLoader('Saving...');
    });
});
</script>

<?php include(ROOT_PATH . '/dashboard_sidebar_end.php'); ?>