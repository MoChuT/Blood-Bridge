<?php require __DIR__ . '/includes/storage.php'; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Register Donor</title>
  <link rel="stylesheet" href="styles.css">
  <script src="app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Register donor</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor-login.php">Login</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Register Donor</h1><p>Submit donor details to create a new donor record.</p></div><span class="pill info">Process 1.0</span></div>
        <div class="panel-body">
          <form class="form-grid" method="post" action="actions.php">
            <input type="hidden" name="action" value="register">
            <input type="hidden" name="back" value="donor-register.php">
            <label>Full name<input name="full_name" required></label>
            <label>Blood type<select name="blood_type" required><option value="">Select blood type</option><option>A+</option><option>O+</option><option>B+</option><option>AB+</option><option>A-</option><option>O-</option></select></label>
            <label>Phone<input name="phone" required></label>
            <label>Email<input name="email" type="email" required></label>
            <label>Gender<select name="gender" required><option value="">Select gender</option><option>Male</option><option>Female</option></select></label>
            <label>Password<input name="password" type="password" required></label>
            <label>Date of birth<input name="date_of_birth" type="date"></label>
            <label>Address<input name="address"></label>
            <label>Emergency contact<input name="emergency_contact"></label>
            <button class="btn-primary full" type="submit">Submit Registration</button>
          </form>
        </div>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
