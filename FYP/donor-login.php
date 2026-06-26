<?php require __DIR__ . '/includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Donor Login</title>
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Donor login</span></span></div>
        <nav class="nav-actions"></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid">
        <div class="panel">
          <div class="panel-header"><div><h1>Donor Login</h1><p>Login using the email address or phone number used during registration.</p></div></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="actions.php">
              <input type="hidden" name="action" value="login">
              <input type="hidden" name="back" value="donor-login.php">
              <label class="full">Email or phone number<input name="login_identifier" required></label>
              <label class="full">Password<input name="password" type="password" required></label>
              <button class="btn-primary full" type="submit">Login</button>
            </form>
          </div>
        </div>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
