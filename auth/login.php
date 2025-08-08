<?php session_start(); ?>
<!DOCTYPE html>
<html>

<head>
    <title>Login - Komponen Management</title>
</head>

<body>
    <h2>Login</h2>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="../actions/login_process.php" method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>

</html>