<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

ob_start();

// ─── Auth Guard ───────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_user = $_SESSION['user_id'] ?? null;

if (!$id_user) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$controller   = new ProductController($conn);
$data         = $controller->index();

$products     = $data['products'];
$search       = $data['search'];
$per_page     = $data['per_page'];
$current_page = $data['current_page'];
$total_pages  = $data['total_pages'];

// Notifikasi status dari URL
$status = $_GET['status'] ?? null;

// ─── Popup helper ─────────────────────────────────────────────────────────────
function productStatusPopup(string $asset_path): void
{
    $status_key = $_GET['status'] ?? '';

    $popups = [
        'success' => ['icon' => 'sukses.png',      'title' => 'Berhasil<br>Menambahkan', 'msg' => 'Produk Berhasil Ditambahkan'],
        'updated' => ['icon' => 'sukses.png',      'title' => 'Berhasil<br>Memperbarui', 'msg' => 'Produk Berhasil Diperbarui'],
        'deleted' => ['icon' => 'hapus_alert.png', 'title' => 'Berhasil<br>Menghapus',  'msg' => 'Produk Berhasil Dihapus'],
    ];

    if (!isset($popups[$status_key])) return;

    $d = $popups[$status_key];
?>
    <div class="alert_sukses_menambah" id="statusPopup">
        <div class="box_sukses_menambah">
            <div class="icon_sukses_menambah">
                <img src="<?= $asset_path ?>icon/<?= htmlspecialchars($d['icon']) ?>" alt="Status">
            </div>
            <h2><?= $d['title'] ?></h2>
            <p><?= htmlspecialchars($d['msg']) ?></p>
            <a href="index.php" class="tombol_sukses_menambah">Tutup</a>
        </div>
    </div>
<?php
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - UMKM Gandoang</title>

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/products/products.css" rel="stylesheet">
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

            <div class="content">
                <div class="card-dashboard">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        <div>
                            <h2>Daftar Produk</h2>
                            <p>Kelola produk UMKM Anda di sini.</p>
                        </div>
                    </div>

                    <!-- TOOLBAR: Show Entries + Search -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3 mt-3">

                        <!-- Show entries -->
                        <form method="GET" class="d-flex align-items-center gap-2">
                            Show
                            <select name="show" id="show" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                <?php foreach ([3, 5, 10] as $opt): ?>
                                    <option value="<?= $opt ?>" <?= $per_page == $opt ? 'selected' : '' ?>><?= $opt ?></option>
                                <?php endforeach; ?>
                            </select>
                            entries
                            <?php if ($search): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>
                        </form>

                        <!-- Search -->
                        <form method="GET" class="d-flex gap-2">
                            <input type="hidden" name="show" value="<?= $per_page ?>">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari Produk..."
                                value="<?= htmlspecialchars($search) ?>"
                                autocomplete="off">
                            <button type="submit" class="btn tombol_cari">
                                Cari
                            </button>
                        </form>

                    </div>

                    <!-- Table -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Nama Umkm</th>
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
                                            <td><?= htmlspecialchars($row['nama_umkm']) ?></td>
                                            <td><?= htmlspecialchars($row['nama_produk']) ?></td>
                                            <td>Rp <?= number_format($row['harga'], 0, ',', '.') ?></td>
                                            <td><?= htmlspecialchars($row['kategori']) ?></td>
                                            <td>
                                                <a href="editProduct.php?id=<?= $row['id_produk'] ?>" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="deleteProduct.php?id=<?= $row['id_produk'] ?>" class="btn btn-danger btn-sm">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <ul class="pagination custom-pagination justify-content-center mt-4 flex-wrap">
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

                    <!-- Tombol Tambah -->
                    <div class="d-flex justify-content-end mt-3">
                        <a href="addProduct.php" class="btn" id="tambah">
                            + Tambah Produk
                        </a>
                    </div>

                </div>
                <!-- akhir card-dashboard -->
            </div>
        </div>
    </div>

    <!-- Popup Notifikasi -->
    <?php productStatusPopup($asset_path); ?>
    <!-- Akhir Popup Notifikasi -->

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
    <script>
        // Auto-dismiss popup setelah 4 detik
        setTimeout(function() {
            const popup = document.getElementById('statusPopup');
            if (popup) {
                popup.style.display = 'none';
                const url = new URL(window.location.href);
                url.searchParams.delete('status');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        }, 4000);
    </script>
    <?php ob_end_flush(); ?>
</body>

</html>