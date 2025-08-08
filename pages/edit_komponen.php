<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Konfigurasi pagination
$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$search_sql = "";
$params = [];
if ($search !== '') {
    $search_sql = "WHERE nama_komponen LIKE ? OR kode_barang LIKE ?";
    $search_param = "%{$search}%";
    $params = [$search_param, $search_param];
}

// Hitung total data
$count_sql = "SELECT COUNT(*) as total FROM komponen $search_sql";
$count_stmt = mysqli_prepare($conn, $count_sql);
if ($search !== '') {
    mysqli_stmt_bind_param($count_stmt, "ss", ...$params);
}
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$total_rows = mysqli_fetch_assoc($count_result)['total'];
$total_pages = ceil($total_rows / $limit);

        // Ambil data komponen dengan search + pagination
        $sql = "SELECT * FROM komponen $search_sql ORDER BY nama_komponen ASC LIMIT ? OFFSET ?";
        $stmt = mysqli_prepare($conn, $sql);
        if ($search !== '') {
            $bind_values = array_merge($params, [$limit, $offset]);
            mysqli_stmt_bind_param($stmt, "ssii", ...$bind_values);
        } else {
            mysqli_stmt_bind_param($stmt, "ii", $limit, $offset);
        }

        mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .actions a {
            margin-right: 5px;
            text-decoration: none;
            padding: 5px 10px;
            background: #007bff;
            color: white;
            border-radius: 3px;
        }

        .actions a:hover {
            background: #0056b3;
        }

        .search-box {
            margin-top: 10px;
            margin-bottom: 20px;
        }

        .pagination a {
            padding: 6px 10px;
            text-decoration: none;
            border: 1px solid #ccc;
            margin: 0 3px;
            border-radius: 3px;
            color: #333;
        }

        .pagination a.active {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }

        .success {
            color: green;
        }
    </style>
</head>

<body>
    <?php if (isset($_GET['msg'])): ?>
    <div style="background-color:#d4edda; padding:10px; margin-bottom:15px; border:1px solid #c3e6cb; color:#155724;">
        <?= htmlspecialchars($_GET['msg']) ?>
    </div>
    <?php endif; ?>


    <h2>✏️ Edit Komponen</h2>

    <?php if (isset($_GET['msg'])): ?>
        <p class="success"><?= htmlspecialchars($_GET['msg']) ?></p>
    <?php endif; ?>

    <form class="search-box" method="get" action="">
        <input type="text" name="search" placeholder="Cari nama/kode barang" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Cari</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Kode Barang</th>
                <th>Nama Komponen</th>
                <th>Jumlah</th>
                <th>Lokasi Simpan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['kode_barang']) ?></td>
                        <td><?= htmlspecialchars($row['nama_komponen']) ?></td>
                        <td><?= $row['jumlah'] ?></td>
                        <td><?= htmlspecialchars($row['lokasi_simpan']) ?></td>
                        <td class="actions">
                            <a href="form_edit_komponen.php?kode_barang=<?= urlencode($row['kode_barang']) ?>">Edit</a>
                            <a href="../actions/delete_process.php?kode_barang=<?= urlencode($row['kode_barang']) ?>" onclick="return confirm('Yakin ingin menghapus komponen ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="5">Data tidak ditemukan.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination" style="margin-top: 20px;">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?search=<?= urlencode($search) ?>&page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>">
                <?= $i ?>
            </a>
        <?php endfor; ?>
    </div>

    <div style="margin-top: 20px;">
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>
</body>

</html>