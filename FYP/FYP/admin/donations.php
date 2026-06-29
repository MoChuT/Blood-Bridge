<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}
$donations = read_records('donation_records');
$appointments = read_records('appointments');
?>
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
              <label>Donor email<input name="donor_email" type="email"></label>
              <label class="full">Appointment ID<select name="appointment_id"><option value="">Select appointment</option><?php foreach ($appointments as $appointment): ?><option value="<?= h((string)$appointment['id']); ?>">APT-<?= h((string)$appointment['id']); ?> - <?= h((string)($appointment['donor_name'] ?? 'Unknown donor')); ?> - <?= h((string)($appointment['status'] ?? 'Pending')); ?></option><?php endforeach; ?></select></label>
              <label>Blood type
  <select name="blood_type">
    <option value="">Select blood type</option>
    <option>A+</option>
    <option>A-</option>
    <option>B+</option>
    <option>B-</option>
    <option>AB+</option>
    <option>AB-</option>
    <option>O+</option>
    <option>O-</option>
  </select>
</label>
              <label>Donation date<input name="donation_date" type="date"></label>
              <label>Result<select name="result"><option value="">Select result</option><option>Completed</option><option>Deferred</option><option>Cancelled</option></select></label>
              <button class="btn-primary full" type="submit">Save Donation Record</button>
            </form>
          </div>
        </div>
        <aside class="panel">
    <div class="panel-header">
        <div>
            <h2>Recent Records</h2>
            <p>Donation history stored in D7.</p>
        </div>
        <span class="pill ok">Stored</span>
    </div>

    <div class="panel-body records">

        <?php foreach ($donations as $donation): ?>

        <div class="record-card">
            <div>
                <strong>
                    REC-<?= h((string)$donation['id']); ?> -
                    <?= h((string)$donation['donor_name']); ?>
                </strong>

                <span>
                    Appointment <?= h((string)($donation['appointment_id'] ?? 'N/A')); ?>,
                    <?= h((string)($donation['donor_email'] ?? 'No email')); ?>,
                    <?= h((string)$donation['result']); ?> donation,
                    <?= h((string)$donation['blood_type']); ?>
                </span>
            </div>

            <span class="pill <?= ($donation['result'] ?? '') === 'Completed' ? 'ok' : 'warn'; ?>">
                <?= h((string)$donation['result']); ?>
            </span>
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
