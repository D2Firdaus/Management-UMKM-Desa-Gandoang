<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$controller = new ProductController($conn);
$data = $controller->index();

$products     = $data['products'];
$search       = $data['search'];
$per_page     = $data['per_page'];
$current_page = $data['current_page'];
$total_pages  = $data['total_pages'];

$sidebar_file = (
    isset($_SESSION['user_role']) &&
    $_SESSION['user_role'] === 'admin'
)
? 'sidebar_admin.php'
: 'sidebar_user.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="../../asset/icon/bootstrap-icons.min.css">
    
    <!-- bootstrap -->
    <link href="<?= $asset_path ?>/boostrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">

</head>

<body>

    <div class="wrapper">

        <!-- sidebar -->
    <?php require_once __DIR__ . '/../layouts/' . $sidebar_file; ?>
        <!-- akhir sidebar -->
        <!-- Content -->
        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>
            <!-- Akhir Navbar -->
            <div class="content">
                <div class="card-dashboard">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        <div>
                            <h2>Detail Produk</h2>
                            <p>Daftar produk yang tersedia.</p>
                        </div>
                    </div>

                    <!-- search -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">

                        <form method="get">
                            Show
                            <select name="show" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                <?php foreach ([3, 5, 10, 25] as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $per_page == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                            entries
                            <?php if ($search): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>
                        </form>

                        <form method="get" class="d-flex gap-2">
                            <input type="hidden" name="show" value="<?= $per_page ?>">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari Produk..."
                                value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn tombol_cari">
                                Cari
                            </button>
                        </form>

                    </div>
                    <!-- akhir search -->

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Stok</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Loop produk -->
                            <?php foreach ($products as $row): ?>
                                <tr>
                                    <td><?= $row['id_produk'] ?></td>
                                    <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                    <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                    <td><?= $row['kategori'] ?></td>
                                    <td>
                                        <a href="edit.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm">
                                            <img src="<?= $asset_path ?>/icon/edit.png" width="30px" height="30px">
                                        </a>
                                        <a href="delete.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm">
                                            <img src="<?= $asset_path ?>icon/hapus.png" style="padding:5px" width="30px" height="30px">
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                    <ul class="pagination custom-pagination justify-content-center mt-4 flex-wrap">

                        <!-- tombol previous -->
                        <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $current_page - 1 ?>&show=<?= $per_page ?>">
                                Previous
                            </a>
                        </li>

                        <!-- nomor halaman -->
                        <?php 
                        $start_page = 1;
                        if ($current_page >= 5) {
                            $start_page = floor($current_page / 5) * 5;
                        }
                        $end_page = min($start_page + 5, $total_pages);
                        if ($start_page > 1 && $start_page > $total_pages) {
                            $start_page = max(1, $total_pages - 5);
                        }
                        for ($i = $start_page; $i <= $end_page; $i++) { 
                        ?>
                            <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&show=<?= $per_page ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php } ?>

                        <!-- tombol next -->
                        <li class="page-item <?= ($current_page == $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $current_page + 1 ?>&show=<?= $per_page ?>">
                                Next
                            </a>
                        </li>
                    </ul>
                    <!-- Akhir Pagination -->
                    <div class="d-flex justify-content-end">
                        <a href="addProduct.php" class="btn" id="tambah">
                            + Tambah Produk
                        </a>
                    </div>
                </div>
            </div>

        <script src="<?= $asset_path ?>js/bantuan.js"></script>

        <?php ob_end_flush(); ?>
</body>

</html>