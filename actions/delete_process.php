<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

$kode_barang = $_GET['kode_barang'] ?? '';

if (!$kode_barang) {
    header("Location: ../pages/edit_komponen.php?msg=Kode+barang+tidak+ditemukan");
    exit;
}

// Siapkan dan eksekusi statement hapus
$stmt = mysqli_prepare($conn, "DELETE FROM komponen WHERE kode_barang = ?");
mysqli_stmt_bind_param($stmt, "s", $kode_barang);
$success = mysqli_stmt_execute($stmt);

// Redirect ke halaman edit_komponen dengan pesan
if ($success) {
    header("Location: ../pages/edit_komponen.php?msg=Berhasil+hapus");
} else {
    header("Location: ../pages/edit_komponen.php?msg=Gagal+hapus+komponen");
}
exit;
