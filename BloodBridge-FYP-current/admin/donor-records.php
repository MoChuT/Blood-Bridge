<?php
require __DIR__ . '/../includes/storage.php';
$donors = read_records('donors', [
    ['full_name' => 'Tan Zhi Qian', 'blood_type' => 'A+', 'status' => 'Pending verification'],
    ['full_name' => 'Kang Shu Yi', 'blood_type' => 'A+', 'status' => 'Verified'],
    ['full_name' => 'Gan Hui Min', 'blood_type' => 'O-', 'status' => 'Matched'],
    ['full_name' => 'Nur Aina', 'blood_type' => 'B+', 'status' => 'Review'],
]);
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
                <span><?= h((string)($donor['blood_type'] ?? 'Unknown')); ?> donor, <?= h((string)($donor['email'] ?? $donor['status'] ?? 'Pending')); ?></span>
              </div>
              <span class="pill warn"><?= h((string)($donor['status'] ?? 'Pending')); ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
