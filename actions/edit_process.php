<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kode_barang = $_POST['kode_barang'];
    $nama_komponen = $_POST['nama_komponen'];
    $jumlah = $_POST['jumlah'];
    $lokasi_simpan = $_POST['lokasi_simpan'];

    $stmt = mysqli_prepare($conn, "UPDATE komponen SET nama_komponen = ?, jumlah = ?, lokasi_simpan = ? WHERE kode_barang = ?");
    mysqli_stmt_bind_param($stmt, "siss", $nama_komponen, $jumlah, $lokasi_simpan, $kode_barang);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        header("Location: ../pages/edit_komponen.php?msg=Data berhasil diupdate");
        exit;
    } else {
        echo "Gagal menyimpan perubahan.";
    }
} else {
    echo "Metode tidak diizinkan.";
}
