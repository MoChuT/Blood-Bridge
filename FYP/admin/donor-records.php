<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}

$donors = read_records('donors');
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donor Records</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Donor records</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a><a class="nav-link" href="appointments.php">Appointments</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Donor Records</h1><p>View donor details, health status and document status.</p></div><span class="pill info">D1</span></div>
        <div class="panel-body records">
          <?php foreach ($donors as $donor): ?>
            <div class="record-card">
              <div>
                <strong><?= h((string)($donor['full_name'] ?? 'Unnamed donor')); ?></strong>
                <span>
                  <?= h((string)($donor['blood_type'] ?? 'Unknown')); ?> donor,
                  <?= h((string)($donor['email'] ?? 'No email')); ?>
                </span>
              </div>

              <?php if (($donor['status'] ?? '') !== 'Verified'): ?>
                <form method="post" action="../actions.php" style="display:inline;">
                  <input type="hidden" name="action" value="verify_donor">
                  <input type="hidden" name="back" value="admin/donor-records.php">
                  <input type="hidden" name="donor_id" value="<?= h((string)($donor['id'] ?? '')); ?>">

                  <button type="submit" class="pill warn" style="border:none; cursor:pointer;">
                    <?= h((string)($donor['status'] ?? 'Pending verification')); ?>
                  </button>
                </form>
              <?php else: ?>
                <span class="pill ok">Verified</span>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>