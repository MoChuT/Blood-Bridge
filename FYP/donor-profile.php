<?php
require __DIR__ . '/includes/storage.php';
$donors = read_records('donors');
$currentDonor = [];
foreach ($donors as $donor) {
    if (
        (!empty($_SESSION['donor_email']) && ($donor['email'] ?? '') === $_SESSION['donor_email']) ||
        (!empty($_SESSION['donor_phone']) && ($donor['phone'] ?? '') === $_SESSION['donor_phone'])
    ) {
        $currentDonor = $donor;
        break;
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Modify Profile</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Modify profile</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="donor.php">Donor Portal</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="panel">
        <div class="panel-header"><div><h1>Modify Profile</h1><p>Submit updated donor information for administrative review.</p></div><span class="pill info">Profile</span></div>
        <div class="panel-body">
          <form class="form-grid" method="post" action="actions.php">
            <input type="hidden" name="action" value="update_profile">
            <input type="hidden" name="back" value="donor-profile.php">
            <label>Full name<input name="full_name" value="<?= h((string)($currentDonor['full_name'] ?? '')); ?>" required></label>
            <label>Blood type<select name="blood_type"><option value="">Select blood type</option><?php foreach (['A+','O+','B+','AB+','A-','O-','B-','AB-'] as $type): ?><option <?= (($currentDonor['blood_type'] ?? '') === $type) ? 'selected' : ''; ?>><?= h($type); ?></option><?php endforeach; ?></select></label>
            <label>Phone<input name="phone" value="<?= h((string)($currentDonor['phone'] ?? '')); ?>"></label>
            <label>Email<input name="email" type="email" value="<?= h((string)($currentDonor['email'] ?? '')); ?>"></label>
            <label>Gender<select name="gender"><option value="">Select gender</option><?php foreach (['Male','Female'] as $gender): ?><option <?= (($currentDonor['gender'] ?? '') === $gender) ? 'selected' : ''; ?>><?= h($gender); ?></option><?php endforeach; ?></select></label>
            <label>Emergency contact<input name="emergency_contact" value="<?= h((string)($currentDonor['emergency_contact'] ?? '')); ?>"></label>
            <label class="full">Address<input name="address" value="<?= h((string)($currentDonor['address'] ?? '')); ?>"></label>
            <button class="btn-primary full" type="submit">Submit Profile Update</button>
          </form>
        </div>
      </section>
    </main>
  </div>
</body>
</html>
