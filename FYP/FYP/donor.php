<?php require __DIR__ . '/includes/storage.php'; ?>
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
          <a class="nav-link" href="index.php">Function Map</a>
        </nav>
      </div>
    </header>

    <main class="container">
      <?= flash_markup(); ?>
      <section class="hero">
        <div>
          <h1>Manage your blood donation journey.</h1>
          <p>Update your profile, complete screening, upload supporting documents, view announcements, track donation history, and request an appointment from one portal.</p>
        </div>
        <div class="hero-card">
          <div class="hero-stat">
            <div><span>Next event</span><strong>May 12, 2026</strong></div>
            <div><span>Available slots</span><strong>23</strong></div>
            <div><span>Status</span><strong>Open</strong></div>
          </div>
        </div>
      </section>

      <section class="grid grid-3 section-gap">
        <a class="function-card" href="donor-profile.php"><span class="card-icon">P</span><h2>Modify Profile</h2><p>Submit updated donor contact and profile details.</p></a>
        <a class="function-card" href="donor-announcements.php"><span class="card-icon">N</span><h2>Announcements</h2><p>View event notices and reminders.</p></a>
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
