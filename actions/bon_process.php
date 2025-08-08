<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $kode_barang = $_POST['kode_barang'];
    $nama_komponen = $_POST['nama_komponen'];
    $nama_pengebon = $_POST['nama_pengebon'];
    $jumlah = $_POST['jumlah'];
    $keperluan = $_POST['keperluan'];
    $tanggal = date("Y-m-d H:i:s");

    // Simpan ke tabel bon_komponen
    $stmt = mysqli_prepare($conn, "INSERT INTO bon_komponen (kode_barang, nama_komponen, nama_pengebon, jumlah, keperluan, tanggal) VALUES (?, ?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssiss", $kode_barang, $nama_komponen, $nama_pengebon, $jumlah, $keperluan, $tanggal);
    mysqli_stmt_execute($stmt);

    // Kurangi stok di tabel komponen
    $update = mysqli_prepare($conn, "UPDATE komponen SET jumlah = jumlah - ? WHERE kode_barang = ?");
    mysqli_stmt_bind_param($update, "is", $jumlah, $kode_barang);
    mysqli_stmt_execute($update);

    header("Location: ../pages/bon_komponen.php?kode_barang=$kode_barang&success=1");
    exit;
}
