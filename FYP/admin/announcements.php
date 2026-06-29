<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}

$announcements = read_records('announcements');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Manage Announcements</title>
  <link rel="stylesheet" href="../styles.css">
  <script src="../app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Announcement management</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Manage Announcement</h1><p>Add, edit, delete and view announcement records.</p></div><span class="pill info">Process 7.0</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="../actions.php">
              <input type="hidden" name="action" value="announcement">
              <input type="hidden" name="back" value="admin/announcements.php">
              <label class="full">Title<input name="title"></label>
              <label>Status<select name="status"><option value="">Select status</option><option>Published</option><option>Draft</option><option>Deleted</option></select></label>
              <label>Date<input name="event_date" type="date"></label>
              <label class="full">Details<textarea name="details"></textarea></label>
              <button class="btn-primary full" type="submit">Save Announcement</button>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Announcement Records</h2><p>Latest published notices.</p></div><span class="pill ok">Published</span></div>
          <div class="panel-body records">
            <?php foreach ($announcements as $item): ?>
              <div class="record-card">
                <div><strong><?= h((string)($item['title'] ?? 'Announcement')); ?></strong><span><?= h((string)($item['details'] ?? '')); ?></span></div>
                <span class="pill ok"><?= h((string)($item['status'] ?? 'Published')); ?></span>
                <form method="post" action="../actions.php" style="display:inline;">
    <input type="hidden" name="action" value="delete_announcement">
    <input type="hidden" name="back" value="admin/announcements.php">
    <input type="hidden" name="announcement_id" value="<?= h((string)$item['id']); ?>">
    <button type="submit" class="pill bad" style="border:none; cursor:pointer;">
        Delete
    </button>
</form>
              </div>
            <?php endforeach; ?>
          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
