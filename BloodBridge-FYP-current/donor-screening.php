<?php require __DIR__ . '/includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Health Screening</title>
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Health screening</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a><a class="nav-link" href="donor-document.php">Upload Document</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Health Screening</h1><p>Answer basic eligibility questions before appointment booking.</p></div><span class="pill warn">Pending</span></div>
          <div class="panel-body">
            <form method="post" action="actions.php">
              <input type="hidden" name="action" value="screening">
              <input type="hidden" name="back" value="donor-screening.php">
              <input type="hidden" name="donor_key" value="<?= h((string)($_SESSION['donor_email'] ?? $_SESSION['donor_phone'] ?? '')); ?>">
              <div class="check-list">
                <label class="check-item"><input name="answers[]" value="age" type="checkbox"> Age between 18 and 60 years old</label>
                <label class="check-item"><input name="answers[]" value="weight" type="checkbox"> Weight is above 45kg</label>
                <label class="check-item"><input name="answers[]" value="health" type="checkbox"> No fever or illness today</label>
                <label class="check-item"><input name="answers[]" value="last_donation" type="checkbox"> Last donation was more than 3 months ago</label>
              </div>
              <div class="section-gap"><button class="btn-primary" type="submit">Save Screening Result</button></div>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Health Record Preview</h2><p>Screening data becomes part of the health record.</p></div><span class="pill info">D3</span></div>
          <div class="panel-body records">
            <div class="record-card"><div><strong>Eligibility status</strong><span>Waiting for final questionnaire completion.</span></div><span class="pill warn">Review</span></div>
            <div class="record-card"><div><strong>Result</strong><span>Administrative staff can verify health details before approval.</span></div><span class="pill info">Stored</span></div>
          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
