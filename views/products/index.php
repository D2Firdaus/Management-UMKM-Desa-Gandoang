<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$controller   = new ProductController($conn);
$data         = $controller->index();

$products     = $data['products'];
$search       = $data['search'];
$per_page     = $data['per_page'];
$current_page = $data['current_page'];
$total_pages  = $data['total_pages'];

// Notifikasi status dari URL
$status = $_GET['status'] ?? null;
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
    <link href="<?= $asset_path ?>/css/products/products.css" rel="stylesheet">

    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/products.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">

        <!-- sidebar -->
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>
        <!-- akhir sidebar -->

        <!-- Content -->
        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>
            <!-- Akhir Navbar -->
            <div class="content rounded-5">
                <div class="card-header">
                    <h1 class="fs-2 fw-bold">Detail Produk</h1>
                    <p class="fs-5">Daftar produk yang tersedia.</p>
                </div>
                <?php if ($status === 'success'): ?>
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        Produk berhasil ditambahkan!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($status === 'updated'): ?>
                    <div class="alert alert-info alert-dismissible fade show mt-3" role="alert">
                        Produk berhasil diupdate!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php elseif ($status === 'deleted'): ?>
                    <div class="alert alert-warning alert-dismissible fade show mt-3" role="alert">
                        Produk berhasil dihapus!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <!-- TOOLBAR: Show Entries + Search -->
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">
                    <!-- Show entries -->
                    <form method="GET" class="show-entries d-flex align-items-center gap-2">
                        <label for="show">Show</label>
                        <select name="show" id="show" onchange="this.form.submit()">
                            <?php foreach ([3, 5, 10] as $opt): ?>
                                <option value="<?= $opt ?>" <?= $per_page == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                            <?php endforeach; ?>
                        </select>
                        <label>entries</label>
                        <?php if ($search): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <?php endif; ?>
                    </form>

                    <!-- Search -->
                    <form method="GET" class="search-form">
                        <div class="input-group justify-content-center align-items-center border border-1 m-3 border-black rounded-3">
                            <input
                                type="text"
                                name="search"
                                class="input-group-text text-start input-search"
                                placeholder="Cari Produk..."
                                value="<?= htmlspecialchars($search) ?>"
                                autocomplete="off">
                            <button class="input-group-text bg-white border-0" onclick="this.form.submit()">
                                <i class="bi bi-search"></i>
                            </button>
                            <input type="hidden" name="show" value="<?= $per_page ?>">
                            <input type="hidden" name="page" value="1">
                        </div>
                    </form>

                </div>

                <!-- Table -->
                <div class="table-responsive mt-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Nama Produk</th>
                                <th scope="col">Harga</th>
                                <th scope="col">Kategori</th>
                                <th scope="col">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($products)): ?>
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($products as $row): ?>
                                    <tr>
                                        <td><?= $row['id_produk'] ?></td>
                                        <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                        <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                        <td><?= htmlspecialchars($row['kategori']) ?></td>
                                        <td>
                                            <a href="editProduct.php?id=<?= $row['id_produk'] ?>" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                            <a href="deleteProduct.php?id=<?= $row['id_produk'] ?>" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <ul class="pagination custom-pagination justify-content-center mt-4">
                        <!-- tombol previous -->
                        <li class="page-item <?= ($current_page == 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $current_page - 1 ?>&show=<?= $per_page ?>&search=<?= urlencode($search) ?>">Previous</a>
                        </li>
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&show=<?= $per_page ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $current_page + 1 ?>&show=<?= $per_page ?>&search=<?= urlencode($search) ?>">Next</a>
                        </li>
                    </ul>
                    <!-- Akhir Pagination -->
                    <!-- Add Button -->
                    <div class="d-flex justify-content-end">
                        <a href="addProduct.php" class="btn" id="tambah">
                            + Tambah Produk
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
    <?php ob_end_flush(); ?>
</body>

</html>