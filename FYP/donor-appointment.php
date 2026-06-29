<?php
require __DIR__ . '/includes/storage.php';

if (empty($_SESSION['donor_email'])) {
    redirect_to('donor-login.php');
}

$allSlots = read_records('appointment_slots');
$appointments = read_records('appointments');
$today = date('Y-m-d');
$slots = [];

foreach ($allSlots as $slot) {
    $slotDate = (string)($slot['slot_date'] ?? '');
    if (($slot['status'] ?? 'Open') === 'Open' && $slotDate !== '' && $slotDate >= $today) {
        $slots[] = $slot;
    }
}

$myAppointments = [];
foreach ($appointments as $appointment) {
    if (($appointment['donor_email'] ?? '') === ($_SESSION['donor_email'] ?? '')) {
        $myAppointments[] = $appointment;
    }
}

$myAppointments = array_slice($myAppointments, 0, 3);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Do Appointment</title>
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Appointment booking</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a></nav>
      </div>
    </header>

    <main class="container">
      <?= flash_markup(); ?>

      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header">
            <div>
              <h1>Do Appointment</h1>
              <p>Select an available date, time and venue for donation.</p>
            </div>
            <span class="pill info">Process 8.0</span>
          </div>

          <div class="panel-body">
            <form class="form-grid" method="post" action="actions.php">
              <input type="hidden" name="action" value="appointment">
              <input type="hidden" name="back" value="donor-appointment.php">

              <label class="full">Available session
                <select name="slot_id" required>
                  <option value="">Select available session</option>

                  <?php foreach ($slots as $slot): ?>
                    <option value="<?= h((string)$slot['id']); ?>">
                      <?= h((string)$slot['slot_date']); ?> -
                      <?= h((string)$slot['slot_time']); ?> -
                      <?= h((string)$slot['venue']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </label>

              <button class="btn-primary full" type="submit">Request Appointment</button>
            </form>
          </div>
        </div>

        <aside class="panel">
          <div class="panel-header">
            <div>
              <h2>Available Slots</h2>
              <p>Current available donation sessions.</p>
            </div>
            <span class="pill ok"><?= count($slots) ?> open</span>
          </div>

          <div class="panel-body records">
            <?php foreach ($slots as $slot): ?>
              <div class="record-card">
                <div>
                  <strong>
                    <?= h((string)$slot['slot_date']); ?>,
                    <?= h((string)$slot['slot_time']); ?>
                  </strong>
                  <span><?= h((string)$slot['venue']); ?></span>
                </div>
                <span class="pill ok">Available</span>
              </div>
            <?php endforeach; ?>

            <?php if (count($slots) === 0): ?>
              <p>No available sessions yet.</p>
            <?php endif; ?>

            <hr style="margin:18px 0;">

            <div style="margin-bottom:10px;">
              <h3 style="margin:0 0 4px 0; font-size:16px;">My Appointment Status</h3>
              <p style="font-size:13px; color:#666; margin:0;">Latest appointment updates.</p>
            </div>

            <?php foreach ($myAppointments as $appointment): ?>
              <div class="record-card">
                <div>
                  <strong>
                    <?= h((string)$appointment['preferred_date']); ?>,
                    <?= h((string)$appointment['preferred_time']); ?>
                  </strong>
                  <span><?= h((string)$appointment['venue']); ?></span>
                </div>

                <span class="pill <?= ($appointment['status'] ?? '') === 'Approved' ? 'ok' : 'warn'; ?>">
                  <?= h((string)$appointment['status']); ?>
                </span>
              </div>
            <?php endforeach; ?>

            <?php if (count($myAppointments) === 0): ?>
              <p>No appointment request yet.</p>
            <?php endif; ?>
          </div>
        </aside>
      </section>
    </main>
  </div>

  <div class="toast"></div>
</body>
</html>
