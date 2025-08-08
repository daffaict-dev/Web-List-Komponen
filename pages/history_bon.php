<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// --- Konfigurasi pagination dan pencarian ---
$limit = 10;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Query pencarian
$search_query = "";
$params = [];

if (!empty($search)) {
    $search_query = "WHERE nama_pengebon LIKE ? OR kode_barang LIKE ? OR nama_komponen LIKE ?";
    $search_like = "%$search%";
    $params = [$search_like, $search_like, $search_like];
}

// Hitung total data
$count_stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM bon_komponen $search_query");
if (!empty($search_query)) {
    mysqli_stmt_bind_param($count_stmt, "sss", ...$params);
}
mysqli_stmt_execute($count_stmt);
mysqli_stmt_bind_result($count_stmt, $total);
mysqli_stmt_fetch($count_stmt);
mysqli_stmt_close($count_stmt);

$total_pages = ceil($total / $limit);

// Ambil data bon
$stmt = mysqli_prepare($conn, "
    SELECT * FROM bon_komponen 
    $search_query 
    ORDER BY tanggal DESC
    LIMIT ? OFFSET ?
");

if (!empty($search_query)) {
    $params[] = $limit;
    $params[] = $offset;
    mysqli_stmt_bind_param($stmt, "sssii", ...$params);
} else {
    mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Riwayat Bon Komponen</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background: #f2f2f2;
        }

        input[type="text"] {
            padding: 8px;
            width: 300px;
        }

        button {
            padding: 8px 16px;
        }

        .pagination a {
            margin: 0 5px;
            text-decoration: none;
            padding: 6px 12px;
            border: 1px solid #ccc;
            color: black;
        }

        .pagination a.active {
            background: #007bff;
            color: white;
        }

        .back {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <h2>📜 Riwayat Bon Komponen</h2>

    <form method="GET" action="">
        <input type="text" name="search" placeholder="Cari nama/kode komponen..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Nama Pengebon</th>
            <th>Kode Barang</th>
            <th>Nama Komponen</th>
            <th>Jumlah</th>
            <th>Keperluan</th>
        </tr>
        <?php
        $no = $offset + 1;
        while ($row = mysqli_fetch_assoc($result)) : ?>
            <tr>
                <td><?= $no++; ?></td>
                <td><?= htmlspecialchars($row['tanggal']); ?></td>
                <td><?= htmlspecialchars($row['nama_pengebon']); ?></td>
                <td><?= htmlspecialchars($row['kode_barang']); ?></td>
                <td><?= htmlspecialchars($row['nama_komponen']); ?></td>
                <td><?= htmlspecialchars($row['jumlah']); ?></td>
                <td><?= htmlspecialchars($row['keperluan']); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a class="<?= ($i == $page) ? 'active' : '' ?>" href="?search=<?= urlencode($search); ?>&page=<?= $i ?>"><?= $i ?></a>
        <?php endfor; ?>
    </div>

    <div class="back">
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>

</body>

</html>