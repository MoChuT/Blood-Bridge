<?php require __DIR__ . '/../includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Matching Alerts</title>
  <link rel="stylesheet" href="../styles.css">
  <script src="../app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Matching alerts</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a><a class="nav-link" href="inventory.php">Inventory</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Matching Alert</h1><p>Notify suitable donors when inventory is low.</p></div><span class="pill bad">Critical O-</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="../actions.php">
              <input type="hidden" name="action" value="alert">
              <input type="hidden" name="back" value="admin/alerts.php">
              <label>Needed blood type<select name="blood_type"><option value="">Select blood type</option><option>O-</option><option>A+</option><option>O+</option><option>B+</option></select></label>
              <label>Radius<select name="radius"><option value="">Select radius</option><option>10 km</option><option>25 km</option><option>50 km</option></select></label>
              <label class="full">Message<textarea name="message"></textarea></label>
              <button class="btn-primary full" type="submit">Send Matching Alert</button>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Matched Donors</h2><p>Suitable donors based on blood type and location.</p></div><span class="pill info">7 sent</span></div>
          <div class="panel-body records">
            <div class="record-card"><div><strong>Gan Hui Min</strong><span>O- donor, 6 km from event venue.</span></div><span class="pill ok">Matched</span></div>
            <div class="record-card"><div><strong>Lee Wen Kai</strong><span>O- donor, 9 km from event venue.</span></div><span class="pill ok">Matched</span></div>
          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
