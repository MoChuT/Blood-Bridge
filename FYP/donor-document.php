<?php require __DIR__ . '/includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Upload Document</title>
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Document upload</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Upload Document</h1><p>Store donor medical documents for administrative review.</p></div><span class="pill info">Process 5.0</span></div>
        <div class="panel-body">
          <form class="form-grid" method="post" action="actions.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="upload_document">
            <input type="hidden" name="back" value="donor-document.php">
            <label>Document type<select name="document_type"><option value="">Select document type</option><option>Medical certificate</option><option>Previous donation card</option><option>Identity document</option></select></label>
            <label>Upload date<input name="upload_date" type="date"></label>
            <label class="full">File<input name="document_file" type="file"></label>
            <label class="full">Notes<textarea name="notes"></textarea></label>
            <button class="btn-primary full" type="submit">Upload Document</button>
          </form>
        </div>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
