<?php
require __DIR__ . '/includes/storage.php';

if (empty($_SESSION['donor_email'])) {
    redirect_to('donor-login.php');
}

$donorEmail = (string)($_SESSION['donor_email'] ?? '');
$donorName = (string)($_SESSION['donor_name'] ?? '');
$donations = read_records('donation_records');
$myDonations = [];
$lastCompletedDate = null;

foreach ($donations as $donation) {
    $sameEmail = !empty($donation['donor_email']) && strtolower((string)$donation['donor_email']) === strtolower($donorEmail);
    $sameName = !empty($donation['donor_name']) && strtolower((string)$donation['donor_name']) === strtolower($donorName);

    if ($sameEmail || $sameName) {
        $myDonations[] = $donation;

        if (($donation['result'] ?? '') === 'Completed' && !empty($donation['donation_date'])) {
            $date = strtotime((string)$donation['donation_date']);
            if ($date !== false && ($lastCompletedDate === null || $date > $lastCompletedDate)) {
                $lastCompletedDate = $date;
            }
        }
    }
}

$daysRemaining = 0;
$nextEligible = 'Ready now';
if ($lastCompletedDate !== null) {
    $eligibleDate = strtotime('+90 days', $lastCompletedDate);
    $today = strtotime(date('Y-m-d'));
    $daysRemaining = max(0, (int)ceil(($eligibleDate - $today) / 86400));
    $nextEligible = date('M d, Y', $eligibleDate);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donation History</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Donation history</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Eligibility Tracker</h1><p>90-day donation interval based on your latest completed donation.</p></div><span class="pill <?= $daysRemaining === 0 ? 'ok' : 'warn'; ?>"><?= $daysRemaining === 0 ? 'Eligible' : 'Waiting'; ?></span></div>
          <div class="panel-body">
            <div class="metric">
              <span>Days remaining</span>
              <strong><?= h((string)$daysRemaining); ?></strong>
            </div>
            <div class="section-gap record-card">
              <div>
                <strong>Next eligible date</strong>
                <span><?= h($nextEligible); ?></span>
              </div>
              <span class="pill info">90 days</span>
            </div>
          </div>
        </div>

        <aside class="panel">
          <div class="panel-header"><div><h2>Donation History</h2><p>Your past donation results.</p></div><span class="pill info"><?= count($myDonations); ?> record(s)</span></div>
          <div class="panel-body records">
            <?php foreach ($myDonations as $donation): ?>
              <div class="record-card">
                <div>
                  <strong><?= h((string)($donation['donation_date'] ?? 'No date')); ?></strong>
                  <span><?= h((string)($donation['blood_type'] ?? 'Unknown')); ?>, <?= h((string)($donation['result'] ?? 'Pending')); ?></span>
                </div>
                <span class="pill <?= ($donation['result'] ?? '') === 'Completed' ? 'ok' : 'warn'; ?>"><?= h((string)($donation['result'] ?? 'Pending')); ?></span>
              </div>
            <?php endforeach; ?>
            <?php if (count($myDonations) === 0): ?>
              <p>No donation history recorded yet.</p>
            <?php endif; ?>
          </div>
        </aside>
      </section>
    </main>
  </div>
</body>
</html>
