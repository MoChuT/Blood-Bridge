<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}

$donorId = (int)($_GET['id'] ?? 0);
$donors = read_records('donors');
$healthRecords = read_records('health_records');
$documents = read_records('documents');
$donor = null;

foreach ($donors as $item) {
    if ((int)($item['id'] ?? 0) === $donorId) {
        $donor = $item;
        break;
    }
}

if (!$donor) {
    flash('Donor not found', 'The selected donor record could not be found.', 'bad');
    redirect_to('donor-records.php');
}

$donorEmail = (string)($donor['email'] ?? '');
$donorPhone = (string)($donor['phone'] ?? '');
$latestHealth = null;

foreach ($healthRecords as $record) {
    if (($record['donor_key'] ?? '') === $donorEmail || ($record['donor_key'] ?? '') === $donorPhone) {
        $latestHealth = $record;
        break;
    }
}

$donorDocuments = [];
foreach ($documents as $document) {
    if (($document['donor_email'] ?? '') === $donorEmail) {
        $donorDocuments[] = $document;
    }
}

$answerLabels = [
    'age' => 'Age between 18 and 60 years old',
    'weight' => 'Weight is above 45kg',
    'health' => 'No fever or illness today',
    'last_donation' => 'Last donation was more than 3 months ago',
];
$answers = is_array($latestHealth['answers'] ?? null) ? $latestHealth['answers'] : [];
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donor Detail</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Donor detail</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor-records.php">Donor Records</a><a class="nav-link" href="index.php">Admin Portal</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1><?= h((string)($donor['full_name'] ?? 'Unnamed donor')); ?></h1><p>Review donor profile before approval.</p></div><span class="pill <?= ($donor['status'] ?? '') === 'Verified' ? 'ok' : (($donor['status'] ?? '') === 'Rejected' ? 'bad' : 'warn'); ?>"><?= h((string)($donor['status'] ?? 'Pending verification')); ?></span></div>
          <div class="panel-body records">
            <div class="record-card"><div><strong>Blood type</strong><span><?= h((string)($donor['blood_type'] ?? 'Unknown')); ?></span></div></div>
            <div class="record-card"><div><strong>Email</strong><span><?= h($donorEmail ?: 'No email'); ?></span></div></div>
            <div class="record-card"><div><strong>Phone</strong><span><?= h($donorPhone ?: 'No phone'); ?></span></div></div>
            <div class="record-card"><div><strong>Gender</strong><span><?= h((string)($donor['gender'] ?? 'No gender')); ?></span></div></div>
            <div class="record-card"><div><strong>Address</strong><span><?= h((string)($donor['address'] ?? 'No address')); ?></span></div></div>
            <div class="record-card"><div><strong>Emergency contact</strong><span><?= h((string)($donor['emergency_contact'] ?? 'No emergency contact')); ?></span></div></div>
          </div>
        </div>

        <aside class="panel">
          <div class="panel-header"><div><h2>Health Screening Answers</h2><p>Latest donor questionnaire result.</p></div><span class="pill info"><?= $latestHealth ? 'Submitted' : 'No record'; ?></span></div>
          <div class="panel-body records">
            <?php foreach ($answerLabels as $key => $label): ?>
              <div class="record-card">
                <div><strong><?= h($label); ?></strong><span><?= in_array($key, $answers, true) ? 'Yes' : 'No'; ?></span></div>
                <span class="pill <?= in_array($key, $answers, true) ? 'ok' : 'warn'; ?>"><?= in_array($key, $answers, true) ? 'Passed' : 'Missing'; ?></span>
              </div>
            <?php endforeach; ?>
            <div class="record-card">
              <div><strong>Eligibility status</strong><span><?= h((string)($latestHealth['eligibility_status'] ?? 'No screening submitted yet')); ?></span></div>
              <span class="pill <?= (($latestHealth['eligibility_status'] ?? '') === 'Eligible') ? 'ok' : 'warn'; ?>"><?= h((string)($latestHealth['eligibility_status'] ?? 'Pending')); ?></span>
            </div>
            <div class="record-card">
              <div><strong>Result</strong><span><?= h((string)($latestHealth['result'] ?? 'Waiting for review')); ?></span></div>
            </div>
          </div>
        </aside>
      </section>

      <section class="panel section-gap">
        <div class="panel-header"><div><h2>Documents</h2><p>Uploaded files connected to this donor.</p></div><span class="pill info"><?= count($donorDocuments); ?> file(s)</span></div>
        <div class="panel-body records">
          <?php foreach ($donorDocuments as $document): ?>
            <div class="record-card">
              <div>
                <strong><?= h((string)($document['document_type'] ?? 'Document')); ?></strong>
                <span><?php if (!empty($document['file_name'])): ?><a href="../uploads/<?= h((string)$document['file_name']); ?>" target="_blank">Open file</a><?php else: ?>No file<?php endif; ?></span>
              </div>
              <span class="pill <?= ($document['status'] ?? '') === 'Approved' ? 'ok' : (($document['status'] ?? '') === 'Rejected' ? 'bad' : 'warn'); ?>"><?= h((string)($document['status'] ?? 'Pending review')); ?></span>
            </div>
          <?php endforeach; ?>
          <?php if (count($donorDocuments) === 0): ?>
            <p>No uploaded documents for this donor.</p>
          <?php endif; ?>
        </div>
      </section>

      <section class="panel section-gap">
        <div class="panel-header"><div><h2>Approval Decision</h2><p>Approve or decline this donor after reviewing details and screening answers.</p></div></div>
        <div class="panel-body">
          <form method="post" action="../actions.php" style="display:inline-block; margin-right:10px;">
            <input type="hidden" name="action" value="verify_donor">
            <input type="hidden" name="back" value="admin/donor-detail.php?id=<?= h((string)$donorId); ?>">
            <input type="hidden" name="donor_id" value="<?= h((string)$donorId); ?>">
            <button class="btn-primary" type="submit">Approve Donor</button>
          </form>
          <form method="post" action="../actions.php" style="display:inline-block;">
            <input type="hidden" name="action" value="reject_donor">
            <input type="hidden" name="back" value="admin/donor-detail.php?id=<?= h((string)$donorId); ?>">
            <input type="hidden" name="donor_id" value="<?= h((string)$donorId); ?>">
            <button class="btn-secondary" type="submit">Decline Donor</button>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
