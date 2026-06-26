<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}

$documents = read_records('documents');
$pendingCount = 0;
foreach ($documents as $document) {
    if (($document['status'] ?? 'Pending review') === 'Pending review') {
        $pendingCount++;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Document Verification</title>
  <link rel="stylesheet" href="../styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Document verification</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a><a class="nav-link" href="donor-records.php">Donor Records</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Document Verification</h1><p>Review uploaded donor files and update document status.</p></div><span class="pill warn"><?= $pendingCount; ?> pending</span></div>
        <div class="panel-body records">
          <?php foreach ($documents as $document): ?>
            <div class="record-card">
              <div>
                <strong><?= h((string)($document['donor_name'] ?? 'Unknown donor')); ?> - <?= h((string)($document['document_type'] ?? 'Document')); ?></strong>
                <span>
                  <?= h((string)($document['donor_email'] ?? 'No email')); ?>
                  <?php if (!empty($document['file_name'])): ?>
                    | <a href="../uploads/<?= h((string)$document['file_name']); ?>" target="_blank">Open file</a>
                  <?php endif; ?>
                </span>
              </div>

              <div>
                <span class="pill <?= ($document['status'] ?? '') === 'Approved' ? 'ok' : (($document['status'] ?? '') === 'Rejected' ? 'bad' : 'warn'); ?>">
                  <?= h((string)($document['status'] ?? 'Pending review')); ?>
                </span>
                <form method="post" action="../actions.php" style="display:inline;">
                  <input type="hidden" name="action" value="update_document_status">
                  <input type="hidden" name="back" value="admin/documents.php">
                  <input type="hidden" name="document_id" value="<?= h((string)$document['id']); ?>">
                  <input type="hidden" name="status" value="Approved">
                  <button type="submit" class="pill ok" style="border:none; cursor:pointer;">Approve</button>
                </form>
                <form method="post" action="../actions.php" style="display:inline;">
                  <input type="hidden" name="action" value="update_document_status">
                  <input type="hidden" name="back" value="admin/documents.php">
                  <input type="hidden" name="document_id" value="<?= h((string)$document['id']); ?>">
                  <input type="hidden" name="status" value="Rejected">
                  <button type="submit" class="pill bad" style="border:none; cursor:pointer;">Reject</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
          <?php if (count($documents) === 0): ?>
            <p>No uploaded documents yet.</p>
          <?php endif; ?>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
