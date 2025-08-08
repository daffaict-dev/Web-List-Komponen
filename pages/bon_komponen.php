<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

$kode_barang = isset($_GET['kode_barang']) ? $_GET['kode_barang'] : '';
$komponen = null;

if ($kode_barang) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM komponen WHERE kode_barang = ? OR nama_komponen = ?");
    mysqli_stmt_bind_param($stmt, "ss", $kode_barang, $kode_barang);
    mysqli_stmt_execute($stmt);
    $komponen = mysqli_stmt_get_result($stmt)->fetch_assoc();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Bon Komponen</title>
    <script src="../assets/js/jquery-3.7.1.min.js"></script>
    <style>
        body {
            font-family: sans-serif;
            padding: 20px;
        }

        input,
        textarea {
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

        .back-btn {
            margin-top: 20px;
        }

        .autocomplete-box {
            border: 1px solid #ccc;
            background: white;
            max-height: 150px;
            overflow-y: auto;
            position: absolute;
            z-index: 999;
            width: 100%;
        }

        .autocomplete-box div {
            padding: 8px;
            cursor: pointer;
        }

        .autocomplete-box div:hover {
            background: #f0f0f0;
        }
    </style>
</head>

<body>
    <h2>📦 Bon Komponen</h2>

    <form method="GET" action="">
        <label>Kode / Nama Komponen:</label>
        <input type="text" id="search_input" name="kode_barang" autocomplete="off" required value="<?php echo htmlspecialchars($kode_barang); ?>">
        <div class="autocomplete-box" id="suggestion_box"></div>
        <button type="submit">Cari Komponen</button>
    </form>

    <?php if ($komponen): ?>
        <form method="POST" action="../actions/bon_process.php">
            <input type="hidden" name="kode_barang" value="<?php echo htmlspecialchars($komponen['kode_barang']); ?>">
            <input type="hidden" name="nama_komponen" value="<?php echo htmlspecialchars($komponen['nama_komponen']); ?>">

            <p><strong>Nama Komponen:</strong> <?php echo htmlspecialchars($komponen['nama_komponen']); ?></p>
            <p><strong>Jumlah Tersedia:</strong> <?php echo htmlspecialchars($komponen['jumlah']); ?></p>
            <p><strong>Lokasi Simpan:</strong> <?php echo htmlspecialchars($komponen['lokasi_simpan']); ?></p>

            <label>Nama Pengebon:</label>
            <input type="text" name="nama_pengebon" required>

            <label>Jumlah yang diambil:</label>
            <input type="number" name="jumlah" min="1" max="<?php echo htmlspecialchars($komponen['jumlah']); ?>" required>

            <label>Keperluan:</label>
            <textarea name="keperluan" required></textarea>

            <button type="submit">Simpan Bon</button>
        </form>
    <?php elseif ($kode_barang): ?>
        <p style="color:red;">Komponen tidak ditemukan!</p>
    <?php endif; ?>

    <div class="back-btn">
        <a href="dashboard.php">← Kembali ke Dashboard</a>
    </div>

    <script>
        $(document).ready(function() {
            $('#search_input').on('input', function() {
                let keyword = $(this).val();
                if (keyword.length > 1) {
                    $.ajax({
                        url: '../ajax/search_komponen.php',
                        method: 'POST',
                        data: {
                            keyword: keyword
                        },
                        success: function(data) {
                            $('#suggestion_box').html(data).show();
                        }
                    });
                } else {
                    $('#suggestion_box').hide();
                }
            });

            $(document).on('click', '#suggestion_box div', function() {
                const kode = $(this).data('kode');
                $('#search_input').val(kode);
                $('#suggestion_box').hide();
            });
        });
    </script>
</body>

</html>