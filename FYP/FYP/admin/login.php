<?php require __DIR__ . '/../includes/storage.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

<div class="container">
    <?= flash_markup(); ?>

    <div class="panel">
        <div class="panel-header">
            <h1>Admin Login</h1>
        </div>

        <div class="panel-body">
            
            <form method="post" action="../actions.php">

                <input type="hidden" name="action" value="admin_login">
                <input type="hidden" name="back" value="admin/login.php">

                <label>
                    Username
                    <input type="text" name="username" required>
                </label>

                <label>
                    Password
                    <input type="password" name="password" required>
                </label>

                <button type="submit" class="btn-primary" style="margin-top:15px;">
    Login
</button>

            </form>
        </div>
    </div>
</div>

</body>
</html>