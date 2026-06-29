<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}

$appointments = read_records('appointments');
$slots = read_records('appointment_slots');

$pendingCount = 0;
foreach ($appointments as $appointment) {
    if (($appointment['status'] ?? '') === 'Pending approval') {
        $pendingCount++;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Appointment Approval</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Appointment approval</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a></nav>
      </div>
    </header>

    <main class="container">
      <?= flash_markup(); ?>

      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Available Date, Time & Venue</h1><p>Add available appointment sessions for donors.</p></div><span class="pill info">Slots</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="../actions.php">
              <input type="hidden" name="action" value="add_slot">
              <input type="hidden" name="back" value="admin/appointments.php">

              <label>Date<input name="slot_date" type="date" required></label>
              <label>Time<input name="slot_time" type="time" required></label>
              <label class="full">Venue<input name="venue" required></label>

              <button class="btn-primary full" type="submit">Add Available Session</button>
            </form>
          </div>
        </div>

        <aside class="panel">
          <div class="panel-header"><div><h2>Current Available Sessions</h2><p>Sessions shown to donors.</p></div><span class="pill ok"><?= count($slots) ?> listed</span></div>
          <div class="panel-body records">
            <?php foreach ($slots as $slot): ?>
              <div class="record-card">
                <div>
                  <strong><?= h((string)($slot['slot_date'] ?? 'No date')); ?>, <?= h((string)($slot['slot_time'] ?? 'No time')); ?></strong>
                  <span><?= h((string)($slot['venue'] ?? 'No venue')); ?></span>
                </div>

                <form method="post" action="../actions.php" style="display:inline;">
                  <input type="hidden" name="action" value="delete_slot">
                  <input type="hidden" name="back" value="admin/appointments.php">
                  <input type="hidden" name="slot_id" value="<?= h((string)$slot['id']); ?>">
                  <button type="submit" class="pill bad" style="border:none; cursor:pointer;">Delete</button>
                </form>
              </div>
            <?php endforeach; ?>

            <?php if (count($slots) === 0): ?>
              <p>No available sessions added yet.</p>
            <?php endif; ?>
          </div>
        </aside>
      </section>

      <section class="panel section-gap">
        <div class="panel-header"><div><h2>Pending Requests</h2><p>Appointments awaiting staff action.</p></div><span class="pill warn"><?= $pendingCount ?> pending</span></div>
        <div class="panel-body records">
          <?php foreach ($appointments as $appointment): ?>
            <?php if (($appointment['status'] ?? '') !== 'Pending approval') continue; ?>

            <div class="record-card">
              <div>
                <strong>APT-<?= h((string)$appointment['id']); ?> - <?= h((string)($appointment['donor_name'] ?? 'Unknown donor')); ?></strong>
                <span>
                  <?= h((string)($appointment['preferred_date'] ?? 'No date')); ?>,
                  <?= h((string)($appointment['preferred_time'] ?? 'No time')); ?>,
                  <?= h((string)($appointment['venue'] ?? 'No venue')); ?>
                </span>
              </div>

              <div>
                <form method="post" action="../actions.php" style="display:inline;">
                  <input type="hidden" name="action" value="update_appointment_status">
                  <input type="hidden" name="back" value="admin/appointments.php">
                  <input type="hidden" name="appointment_id" value="<?= h((string)$appointment['id']); ?>">
                  <input type="hidden" name="status" value="Approved">
                  <button type="submit" class="pill ok" style="border:none; cursor:pointer;">Approve</button>
                </form>

                <form method="post" action="../actions.php" style="display:inline;">
                  <input type="hidden" name="action" value="update_appointment_status">
                  <input type="hidden" name="back" value="admin/appointments.php">
                  <input type="hidden" name="appointment_id" value="<?= h((string)$appointment['id']); ?>">
                  <input type="hidden" name="status" value="Rejected">
                  <button type="submit" class="pill warn" style="border:none; cursor:pointer;">Reject</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>

          <?php if ($pendingCount === 0): ?>
            <p>No pending appointment requests.</p>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
