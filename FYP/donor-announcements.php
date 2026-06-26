<?php
require __DIR__ . '/includes/storage.php';

$announcements = read_records('announcements');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Announcements</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Announcements</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a><a class="nav-link" href="donor-appointment.php">Book Appointment</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Announcements</h1><p>Published event information and donor reminders.</p></div><span class="pill ok">Updated</span></div>
        <div class="panel-body records">
          <?php foreach ($announcements as $item): ?>
            <div class="record-card">
              <div><strong><?= h((string)($item['title'] ?? 'Announcement')); ?></strong><span><?= h((string)($item['details'] ?? '')); ?></span></div>
              <span class="pill ok"><?= h((string)($item['status'] ?? 'Published')); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
