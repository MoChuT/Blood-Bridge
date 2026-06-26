<?php require __DIR__ . '/../includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Appointment Approval</title>
  <link rel="stylesheet" href="../styles.css">
  <script src="../app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Appointment approval</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a><a class="nav-link" href="donor-records.php">Donor Records</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Appointment Approval</h1><p>Approve or reject donor appointment requests.</p></div><span class="pill info">Process 6.0</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="../actions.php">
              <input type="hidden" name="action" value="approval">
              <input type="hidden" name="back" value="admin/appointments.php">
              <label class="full">Appointment<select name="appointment"><option value="">Select appointment</option><option>APT-1007 - Tan Zhi Qian</option><option>APT-1008 - Gan Hui Min</option><option>APT-1009 - Kang Shu Yi</option></select></label>
              <label>Decision<select name="decision"><option value="">Select decision</option><option>Approved</option><option>Rejected</option></select></label>
              <label>Reviewed by<select name="reviewed_by"><option value="">Select reviewer</option><option>Administrative Staff</option><option>Blood Drive Officer</option></select></label>
              <label class="full">Remarks<textarea name="remarks"></textarea></label>
              <button class="btn-primary full" type="submit">Save Decision</button>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Pending Requests</h2><p>Appointments awaiting staff action.</p></div><span class="pill warn">12 pending</span></div>
          <div class="panel-body records">
            <div class="record-card"><div><strong>APT-1007</strong><span>Tan Zhi Qian, May 12, 11:30 AM</span></div><span class="pill warn">Pending</span></div>
            <div class="record-card"><div><strong>APT-1010</strong><span>Nur Aina, May 13, 02:00 PM</span></div><span class="pill warn">Pending</span></div>
          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
