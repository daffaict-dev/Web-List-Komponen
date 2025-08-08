<?php
require_once '../includes/auth.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Dashboard - Komponen Management</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: auto;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .btn-group a {
            padding: 12px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
        }

        .btn-group a:hover {
            background-color: #0056b3;
        }

        .logout {
            margin-top: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Selamat Datang, <?php echo $_SESSION['username']; ?>!</h2>
        <p>Role Anda: <strong><?php echo $_SESSION['role']; ?></strong></p>

        <div class="btn-group">
            <a href="cek_komponen.php">🔍 Cek Komponen</a>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="bon_komponen.php">📦 Bon Komponen</a>
                <a href="edit_komponen.php">✏️ Edit Komponen</a>
                <a href="history_bon.php">🕓 Riwayat Pengebonan</a>
            <?php endif; ?>
        </div>

        <div class="logout">
            <a href="../actions/logout.php" style="color:red;">🚪 Logout</a>
        </div>
    </div>
</body>

</html>