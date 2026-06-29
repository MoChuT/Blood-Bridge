<?php
require __DIR__ . '/includes/storage.php';

if (empty($_SESSION['donor_email'])) {
    redirect_to('donor-login.php');
}

$donors = read_records('donors');
$alerts = read_records('matching_alerts');
$donorBloodType = '';

foreach ($donors as $donor) {
    if (strtolower((string)($donor['email'] ?? '')) === strtolower((string)($_SESSION['donor_email'] ?? ''))) {
        $donorBloodType = (string)($donor['blood_type'] ?? '');
        break;
    }
}

$myAlerts = [];
foreach ($alerts as $alert) {
    if (($alert['blood_type'] ?? '') === $donorBloodType) {
        $myAlerts[] = $alert;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alert Inbox</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Alert inbox</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Emergency Alert Notifications</h1><p>Alerts that match your registered blood type.</p></div><span class="pill bad"><?= h($donorBloodType ?: 'No type'); ?></span></div>
        <div class="panel-body records">
          <?php foreach ($myAlerts as $alert): ?>
            <div class="record-card">
              <div>
                <strong><?= h((string)($alert['blood_type'] ?? 'Blood request')); ?> needed</strong>
                <span><?= h((string)($alert['message'] ?? 'Please contact the blood donation team.')); ?></span>
              </div>
              <span class="pill info"><?= h((string)($alert['radius'] ?? 'Alert')); ?></span>
            </div>
          <?php endforeach; ?>
          <?php if (count($myAlerts) === 0): ?>
            <p>No matching emergency alerts at the moment.</p>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
