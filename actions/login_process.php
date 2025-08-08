<?php
session_start();
require_once '../includes/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($user = mysqli_fetch_assoc($result)) {
    // Tanpa hashing
    if ($password === $user['password']) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        header("Location: ../pages/dashboard.php");
        exit;
    } else {
        $_SESSION['error'] = "Password salah!";
    }
} else {
    $_SESSION['error'] = "Username tidak ditemukan!";
}

header("Location: ../auth/login.php");
exit;
