<?php
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = '%' . $_POST['keyword'] . '%';
    $stmt = mysqli_prepare($conn, "SELECT nama_komponen, kode_barang FROM komponen WHERE nama_komponen LIKE ? OR kode_barang LIKE ? LIMIT 10");
    mysqli_stmt_bind_param($stmt, "ss", $keyword, $keyword);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        $nama = htmlspecialchars($row['nama_komponen']);
        $kode = htmlspecialchars($row['kode_barang']);
        echo "<div data-kode=\"$kode\">$nama ($kode)</div>";
    }
}
