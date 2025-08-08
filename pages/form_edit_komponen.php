<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$kode_barang = $_GET['kode_barang'] ?? '';
if (!$kode_barang) {
    echo "Kode barang tidak ditemukan.";
    exit;
}

$stmt = mysqli_prepare($conn, "SELECT * FROM komponen WHERE kode_barang = ?");
mysqli_stmt_bind_param($stmt, "s", $kode_barang);
mysqli_stmt_execute($stmt); 
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data tidak ditemukan.";
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Komponen</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }

        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
        }
    </style>
</head>

<body>

    <h2>✏️ Edit Komponen</h2>

    <form method="POST" action="../actions/edit_process.php">
        <input type="hidden" name="kode_barang" value="<?= htmlspecialchars($data['kode_barang']) ?>">

        <label>Nama Komponen</label>
        <input type="text" name="nama_komponen" value="<?= htmlspecialchars($data['nama_komponen']) ?>" required>

        <label>Jumlah</label>
        <input type="number" name="jumlah" value="<?= $data['jumlah'] ?>" required>

        <label>Lokasi Simpan</label>
        <input type="text" name="lokasi_simpan" value="<?= htmlspecialchars($data['lokasi_simpan']) ?>" required>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <br>
    <a href="edit_komponen.php">← Kembali</a>

</body>

</html>