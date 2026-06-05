<?php
// ─── Session ─────────────────────────────────────────────────────────────────
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ─── Error Handling ───────────────────────────────────────────────────────────
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ─── Config & Model ───────────────────────────────────────────────────────────
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../models/UmkmModel.php';

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

// ─── Sidebar selector ─────────────────────────────────────────────────────────
$sidebar_file = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')
    ? 'sidebar_admin.php'
    : 'sidebar_user.php';

// ─── Pagination ───────────────────────────────────────────────────────────────
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 5;
if (!in_array($limit, [3, 5, 10])) $limit = 5;

$page   = max(1, (int) ($_GET['page'] ?? 1));
$search = $_GET['search'] ?? '';
$offset = ($page - 1) * $limit;

// ─── Data ─────────────────────────────────────────────────────────────────────
$umkmModel  = new UmkmModel($conn);
$total_data = $umkmModel->countByUser($id_user, $search);
$total_page = max(1, (int) ceil($total_data / $limit));
$data       = $umkmModel->getAllByUser($id_user, $limit, $offset, $search);

// ─── Popup helper ─────────────────────────────────────────────────────────────
function umkmStatusPopup(string $asset_path): void
{
    $status_key = $_GET['status'] ?? '';

    $popups = [
        'tambah_sukses' => ['icon' => 'sukses.png',      'title' => 'Berhasil<br>Menambahkan', 'msg' => 'UMKM Berhasil Ditambahkan'],
        'tambah_gagal'  => ['icon' => 'hapus_alert.png', 'title' => 'Gagal<br>Menambahkan',   'msg' => 'UMKM Gagal Ditambahkan'],
        'edit_sukses'   => ['icon' => 'sukses.png',      'title' => 'Berhasil<br>Memperbarui', 'msg' => 'UMKM Berhasil Diperbarui'],
        'edit_gagal'    => ['icon' => 'hapus_alert.png', 'title' => 'Gagal<br>Memperbarui',   'msg' => 'UMKM Gagal Diperbarui'],
        'hapus_sukses'  => ['icon' => 'hapus_alert.png', 'title' => 'Berhasil<br>Menghapus',  'msg' => 'UMKM Berhasil Dihapus'],
        'hapus_gagal'   => ['icon' => 'hapus_alert.png', 'title' => 'Gagal<br>Menghapus',    'msg' => 'UMKM Gagal Dihapus'],
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
    <title>Profile UMKM - UMKM Desa Gandoang</title>

    <!-- Bootstrap -->
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS UMKM -->
    <link href="<?= $asset_path ?>css/umkm.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../layouts/' . $sidebar_file; ?>
        <!-- Akhir Sidebar -->

        <!-- Main -->
        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>
            <!-- Akhir Navbar -->

            <!-- Content -->
            <div class="content">
                <div class="card-dashboard">

                    <!-- Header -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        <div>
                            <h2>Profile UMKM</h2>
                            <p>Kelola data UMKM yang terdaftar</p>
                        </div>
                    </div>
                    <!-- Akhir Header -->

                    <!-- Search & Show -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">

                        <form method="get">
                            Show
                            <select name="limit" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                <option value="3"  <?= ($limit == 3)  ? 'selected' : '' ?>>3</option>
                                <option value="5"  <?= ($limit == 5)  ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= ($limit == 10) ? 'selected' : '' ?>>10</option>
                            </select>
                            entries
                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        </form>

                        <form method="get" class="d-flex gap-2">
                            <input type="hidden" name="limit" value="<?= $limit ?>">
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari UMKM..."
                                value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn tombol_cari">Cari</button>
                        </form>

                    </div>
                    <!-- Akhir Search & Show -->

                    <!-- Tabel -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama UMKM</th>
                                    <th>Jenis Usaha</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4 text-muted">
                                            Belum ada data UMKM.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data as $i => $row): ?>
                                        <tr>
                                            <td><?= $offset + $i + 1 ?></td>
                                            <td><?= htmlspecialchars($row['nama_umkm']) ?></td>
                                            <td><?= htmlspecialchars($row['jenis_usaha'] ?? '-') ?></td>
                                            <td style="white-space:normal;text-align:left;max-width:200px;">
                                                <?= htmlspecialchars($row['alamat']) ?>
                                            </td>
                                            <td>
                                                <span class="status-badge <?= htmlspecialchars($row['status']) ?>">
                                                    <?= htmlspecialchars($row['status']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit_umkm.php?id=<?= $row['id_umkm'] ?>" class="btn btn-warning btn-sm">
                                                    <img src="<?= $asset_path ?>icon/edit.png" width="28" height="28">
                                                </a>
                                                <a href="hapus_umkm.php?id=<?= $row['id_umkm'] ?>" class="btn btn-danger btn-sm ms-1">
                                                    <img src="<?= $asset_path ?>icon/hapus.png" style="padding:4px" width="28" height="28">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Akhir Tabel -->

                    <br>

                    <!-- Pagination -->
                    <ul class="pagination custom-pagination justify-content-center mt-3 flex-wrap">

                        <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">Previous</a>
                        </li>

                        <?php
                        $start_page = 1;
                        if ($page >= 5) $start_page = (int) floor($page / 5) * 5;
                        $end_page = min($start_page + 4, $total_page);
                        for ($i = $start_page; $i <= $end_page; $i++): ?>
                            <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?= ($page >= $total_page) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">Next</a>
                        </li>

                    </ul>
                    <!-- Akhir Pagination -->

                    <!-- Tombol Tambah -->
                    <div class="d-flex justify-content-end mt-3">
                        <a href="tambah_umkm.php" class="btn" id="tambah">
                            + Tambah UMKM
                        </a>
                    </div>
                    <!-- Akhir Tombol Tambah -->

                </div>
                <!-- Akhir Card Dashboard -->

            </div>
            <!-- Akhir Content -->

        </div>
        <!-- Akhir Main -->

    </div>
    <!-- Akhir Wrapper -->

    <!-- Popup Notifikasi -->
    <?php umkmStatusPopup($asset_path); ?>
    <!-- Akhir Popup Notifikasi -->

    <!-- Bootstrap JS -->
    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>

    <!-- Bantuan JS (sidebar toggle dll) -->
    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <script>
        // Auto-dismiss popup setelah 4 detik
        setTimeout(function () {
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
