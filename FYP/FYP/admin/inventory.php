<?php
require __DIR__ . '/../includes/storage.php';

if (empty($_SESSION['admin_logged_in'])) {
    redirect_to('login.php');
}
?>
<?php
$inventory = read_records('inventory_updates');

$stock = [
    'A+' => 0,
    'O+' => 0,
    'B+' => 0,
    'AB+' => 0,
    'A-' => 0,
    'O-' => 0,
    'B-' => 0,
    'AB-' => 0,
];


foreach ($inventory as $row) {
    $type = $row['blood_type'] ?? '';
    $qty = (int)($row['quantity_update'] ?? 0);

    if (isset($stock[$type])) {
        $stock[$type] += $qty;
    }
}
function stock_percent(int $qty): int
{
    $max = 80; // 80 bags = 100%
    $percent = round(($qty / $max) * 100);

    return min(100, max(0, (int)$percent));
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Blood Inventory</title>
  <link rel="stylesheet" href="../styles.css">
  <script src="../app.js" defer></script>
</head>
<body>
  <div class="page-shell">
    <header class="topbar">
      <div class="topbar-inner">
        <div class="brand"><span class="brand-mark"></span><span><strong class="brand-title">Blood Bridge</strong><span class="brand-subtitle">Blood inventory</span></span></div>
        <nav class="nav-actions"><a class="nav-link" href="index.php">Admin Portal</a><a class="nav-link" href="alerts.php">Matching Alerts</a></nav>
      </div>
    </header>
    <main class="container">
      <?= flash_markup(); ?>
      <section class="grid grid-2">
        <div class="panel">
          <div class="panel-header"><div><h1>Blood Inventory</h1><p>Add, edit, delete and view blood stock records.</p></div><span class="pill bad">O- Critical</span></div>
          <div class="panel-body">
            <form class="form-grid" method="post" action="../actions.php">
              <input type="hidden" name="action" value="inventory">
              <input type="hidden" name="back" value="admin/inventory.php">
              <label>Blood type<select name="blood_type"><option value="">Select blood type</option><option>A+</option><option>O+</option><option>B+</option><option>AB+</option><option>A-</option><option>O-</option><option>B-</option><option>AB-</option></select></label>
              <label>Quantity update<input name="quantity_update" type="number"></label>
              <label class="full">Last updated<input name="last_updated" type="datetime-local"></label>
              <button class="btn-primary full" type="submit">Save Inventory Record</button>
            </form>
          </div>
        </div>
        <aside class="panel">
          <div class="panel-header"><div><h2>Current Stock</h2><p>Quantity by blood type.</p></div><span class="pill info">D6</span></div>
          <div class="panel-body inventory-grid">
            <div class="inventory-item">
    <div class="blood-type">A+</div>
    <div>
      <?php $percent = stock_percent($stock['A+']); ?>
        <strong><?= $stock['A+'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['O+']); ?>
            <div class="inventory-item">
    <div class="blood-type">O+</div>
    <div>
        <strong><?= $stock['O+'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['B+']); ?>
            <div class="inventory-item">
    <div class="blood-type">B+</div>
    <div>
        <strong><?= $stock['B+'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['O-']); ?>
            <div class="inventory-item">
    <div class="blood-type">O-</div>
    <div>
        <strong><?= $stock['O-'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['AB+']); ?>
            <div class="inventory-item">
    <div class="blood-type">AB+</div>
    <div>
        <strong><?= $stock['AB+'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['A-']); ?>
            <div class="inventory-item">
    <div class="blood-type">A-</div>
    <div>
        <strong><?= $stock['A-'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['A-']); ?>
            <div class="inventory-item">
    <div class="blood-type">A-</div>
    <div>
        <strong><?= $stock['A-'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>
<?php $percent = stock_percent($stock['AB-']); ?>
            <div class="inventory-item">
    <div class="blood-type">AB-</div>
    <div>
        <strong><?= $stock['AB-'] ?> bags</strong>
        <div class="bar">
            <i style="width: <?= $percent ?>%;"></i>
        </div>
    </div>
    <span class="pill <?= $percent < 30 ? 'bad' : 'ok' ?>">
        <?= $percent ?>%
    </span>
</div>

          </div>
        </aside>
      </section>
    </main>
  </div>
  <div class="toast"></div>
</body>
</html>
