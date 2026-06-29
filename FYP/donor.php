<?php
require __DIR__ . '/includes/storage.php';

$slots = read_records('appointment_slots');
$appointments = read_records('appointments');
$today = date('Y-m-d');
$availableSlots = 0;
$nextEventDate = null;
$latestAppointment = null;

foreach ($slots as $slot) {
    if (($slot['status'] ?? 'Open') !== 'Open') {
        continue;
    }

    $slotDate = (string)($slot['slot_date'] ?? '');
    if ($slotDate === '' || $slotDate < $today) {
        continue;
    }

    $availableSlots++;
    if ($nextEventDate === null || $slotDate < $nextEventDate) {
        $nextEventDate = $slotDate;
    }
}

foreach ($appointments as $appointment) {
    if (
        !empty($_SESSION['donor_email']) &&
        strtolower((string)($appointment['donor_email'] ?? '')) === strtolower((string)$_SESSION['donor_email'])
    ) {
        $latestAppointment = $appointment;
        break;
    }
}

$nextEventLabel = $nextEventDate ? date('M d, Y', strtotime($nextEventDate)) : 'No upcoming event';
$statusLabel = $availableSlots > 0 ? 'Open' : 'Closed';

if ($latestAppointment) {
    $appointmentDate = (string)($latestAppointment['preferred_date'] ?? '');
    if ($appointmentDate !== '') {
        $nextEventLabel = date('M d, Y', strtotime($appointmentDate));
    }
    $statusLabel = (string)($latestAppointment['status'] ?? 'Pending approval');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donor Portal</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand">
          <span class="brand-mark"></span>
          <span>
            <strong class="brand-title">Blood Bridge</strong>
            <span class="brand-subtitle">Donor portal</span>
          </span>
        </div>
        <nav class="nav-actions">
          <a class="nav-link" href="donor-announcements.php">Announcements</a>
          <a class="nav-link" href="index.php">Function Map</a>
        </nav>
      </div>
    </header>

    <main class="container">
      <?= flash_markup(); ?>
      <section class="hero">
        <div>
          <h1>Thank you for being a blood donor.</h1>
          <p>Your willingness to donate helps support patients and blood donation drives. Use this portal to keep your information updated and continue your donation journey.</p>
        </div>
        <div class="hero-card">
          <div class="hero-stat">
            <div><span>Next event</span><strong><?= h($nextEventLabel); ?></strong></div>
            <div><span>Available slots</span><strong><?= h((string)$availableSlots); ?></strong></div>
            <div><span>Status</span><strong><?= h($statusLabel); ?></strong></div>
          </div>
        </div>
      </section>

      <section class="grid grid-3 section-gap">
        <a class="function-card" href="donor-profile.php"><span class="card-icon">P</span><h2>Modify Profile</h2><p>Submit updated donor contact and profile details.</p></a>
        <a class="function-card" href="donor-screening.php"><span class="card-icon">H</span><h2>Health Screening</h2><p>Submit questionnaire answers for eligibility.</p></a>
        <a class="function-card" href="donor-document.php"><span class="card-icon">D</span><h2>Upload Document</h2><p>Upload medical or identity documents.</p></a>
        <a class="function-card" href="donor-appointment.php"><span class="card-icon">A</span><h2>Do Appointment</h2><p>Select preferred date and time.</p></a>
        <a class="function-card" href="donor-history.php"><span class="card-icon">90</span><h2>Donation History</h2><p>View past donation records and eligibility countdown.</p></a>
        <a class="function-card" href="donor-alerts.php"><span class="card-icon">AL</span><h2>Alert Inbox</h2><p>View emergency alerts matching your blood type.</p></a>
      </section>
    </main>
  </div>
</body>
</html>
