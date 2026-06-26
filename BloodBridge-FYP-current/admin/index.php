<?php require __DIR__ . '/../includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Portal</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand">
          <span class="brand-mark"></span>
          <span>
            <strong class="brand-title">Blood Bridge</strong>
            <span class="brand-subtitle">Admin portal</span>
          </span>
        </div>
        <nav class="nav-actions">
          <a class="nav-link" href="../index.php">Function Map</a>
        </nav>
      </div>
    </header>

    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-3">
        <div class="metric"><span>Registered donors</span><strong>128</strong></div>
        <div class="metric"><span>Appointments today</span><strong>34</strong></div>
        <div class="metric"><span>Blood bags ready</span><strong>252</strong></div>
      </section>

      <section class="grid grid-3 section-gap">
        <a class="function-card" href="donor-records.php"><span class="card-icon">DR</span><h2>Donor Records</h2><p>View donor profile and health details.</p></a>
        <a class="function-card" href="appointments.php"><span class="card-icon">AP</span><h2>Appointment Approval</h2><p>Approve or reject appointment requests.</p></a>
        <a class="function-card" href="inventory.php"><span class="card-icon">BI</span><h2>Blood Inventory</h2><p>Add, edit, delete and view inventory records.</p></a>
        <a class="function-card" href="announcements.php"><span class="card-icon">AN</span><h2>Announcements</h2><p>Manage public event information.</p></a>
        <a class="function-card" href="donations.php"><span class="card-icon">DN</span><h2>Donation Records</h2><p>Store donation history and result data.</p></a>
        <a class="function-card" href="alerts.php"><span class="card-icon">AL</span><h2>Matching Alerts</h2><p>Notify suitable donors when stock is low.</p></a>
      </section>
    </main>
  </div>
</body>
</html>
