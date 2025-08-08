<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

// Pagination config
$limit = 25;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$start = ($page - 1) * $limit;

// Search
$search = isset($_GET['search']) ? $_GET['search'] : '';
$search_sql = $search ? "WHERE kode_barang LIKE '%$search%' OR nama_komponen LIKE '%$search%'" : "";

// Query total
$total_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM komponen $search_sql");
$total_data = mysqli_fetch_assoc($total_result)['total'];
$total_pages = ceil($total_data / $limit);

// Query data
$sql = "SELECT * FROM komponen $search_sql LIMIT $start, $limit";
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cek Komponen</title>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #999;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        .search-form {
            margin-bottom: 15px;
        }

        .pagination {
            margin-top: 15px;
        }

        .pagination a {
            margin-right: 8px;
            text-decoration: none;
            color: #007bff;
        }

        .pagination .current {
            font-weight: bold;
            color: #000;
        }

        .back-btn {
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <h2>🔍 Cek Komponen</h2>

    <form method="GET" class="search-form">
        <input type="text" name="search" placeholder="Cari nama atau kode komponen..." value="<?php echo htmlspecialchars($search); ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Barang</th>
                <th>Nama Komponen</th>
                <th>Satuan</th>
                <th>Jumlah</th>
                <th>Lokasi Simpan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php $no = $start + 1; ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo $row['kode_barang']; ?></td>
                        <td><?php echo $row['nama_komponen']; ?></td>
                        <td><?php echo $row['satuan']; ?></td>
                        <td><?php echo $row['jumlah']; ?></td>
                        <td><?php echo $row['lokasi_simpan']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Tidak ada data.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <div class="pagination">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <?php if ($i == $page): ?>
                <span class="current"><?php echo $i; ?></span>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            <?php endif; ?>
        <?php endfor; ?>
    </div>

    <div class="back-btn">
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>

</body>

</html>