<?php require __DIR__ . '/../includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donation Records</title>
  <link rel="stylesheet" href="../styles.css">
  <script src="../app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Donation records</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a><a class="nav-link" href="inventory.php">Inventory</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Donation Record</h1><p>Add, edit, delete and view donation result records.</p></div><span class="pill info">Process 11.0</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="../actions.php">
              <input type="hidden" name="action" value="donation">
              <input type="hidden" name="back" value="admin/donations.php">
              <label>Donor name<input name="donor_name"></label>
              <label>Blood type<select name="blood_type"><option value="">Select blood type</option><option>A+</option><option>O-</option><option>B+</option></select></label>
              <label>Donation date<input name="donation_date" type="date"></label>
              <label>Result<select name="result"><option value="">Select result</option><option>Completed</option><option>Deferred</option><option>Cancelled</option></select></label>
              <button class="btn-primary full" type="submit">Save Donation Record</button>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Recent Records</h2><p>Donation history stored in D7.</p></div><span class="pill ok">Stored</span></div>
          <div class="panel-body records">
            <div class="record-card"><div><strong>REC-2201 - Kang Shu Yi</strong><span>Completed donation, A+.</span></div><span class="pill ok">Completed</span></div>
            <div class="record-card"><div><strong>REC-2202 - Gan Hui Min</strong><span>Deferred after screening, O-.</span></div><span class="pill warn">Deferred</span></div>
          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
