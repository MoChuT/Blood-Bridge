<?php require __DIR__ . '/includes/storage.php'; ?>
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
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a><a class="nav-link" href="donor-announcements.php">Announcements</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Do Appointment</h1><p>Select preferred date and time for donation.</p></div><span class="pill info">Process 8.0</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="actions.php">
              <input type="hidden" name="action" value="appointment">
              <input type="hidden" name="back" value="donor-appointment.php">
              <label>Preferred date<input name="preferred_date" type="date"></label>
              <label>Preferred time<select name="preferred_time"><option value="">Select time</option><option value="09:00">09:00 AM</option><option value="11:30">11:30 AM</option><option value="14:00">02:00 PM</option></select></label>
              <label class="full">Venue<select name="venue"><option value="">Select venue</option><option>MMU Main Hall</option><option>Clinic Mobile Unit</option></select></label>
              <button class="btn-primary full" type="submit">Request Appointment</button>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Available Slots</h2><p>Current event availability.</p></div><span class="pill ok">Open</span></div>
          <div class="panel-body records">
            <div class="record-card"><div><strong>May 12, 2026, 09:00 AM</strong><span>MMU Main Hall, 8 slots left</span></div><span class="pill ok">Available</span></div>
            <div class="record-card"><div><strong>May 12, 2026, 11:30 AM</strong><span>MMU Main Hall, 3 slots left</span></div><span class="pill warn">Limited</span></div>
            <div class="record-card"><div><strong>May 13, 2026, 02:00 PM</strong><span>Clinic Mobile Unit, 12 slots left</span></div><span class="pill ok">Available</span></div>
          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
