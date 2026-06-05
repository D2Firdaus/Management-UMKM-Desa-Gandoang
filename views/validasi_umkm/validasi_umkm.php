<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi: harus login dan role admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
if (!in_array($limit, [3, 5, 10])) {
    $limit = 5;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$search = $_GET['search'] ?? '';
$offset = ($page - 1) * $limit;

$total_sql = "SELECT COUNT(*) AS total
              FROM umkm
              LEFT JOIN user AS pemilik ON umkm.id_user = pemilik.id_user
              LEFT JOIN user AS validator ON umkm.id_validator = validator.id_user
              WHERE (
                    umkm.nama_umkm LIKE :search
                    OR umkm.alamat LIKE :search
                    OR umkm.status LIKE :search
                    OR pemilik.nama LIKE :search
                    OR validator.nama LIKE :search
              )";

$total_stmt = $conn->prepare($total_sql);
$total_stmt->execute([
    ':search' => "%$search%"
]);

$total_row = $total_stmt->fetch(PDO::FETCH_ASSOC);
$total_data = $total_row['total'];
$total_page = ceil($total_data / $limit);

$sql = "SELECT 
            umkm.*,
            pemilik.nama AS nama_pemilik,
            validator.nama AS nama_validator
        FROM umkm
        LEFT JOIN user AS pemilik ON umkm.id_user = pemilik.id_user
        LEFT JOIN user AS validator ON umkm.id_validator = validator.id_user
        WHERE (
            umkm.nama_umkm LIKE :search
            OR umkm.alamat LIKE :search
            OR umkm.status LIKE :search
            OR pemilik.nama LIKE :search
            OR validator.nama LIKE :search
        )
        ORDER BY umkm.id_umkm DESC
        LIMIT $limit OFFSET $offset";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':search' => "%$search%"
]);

$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validasi UMKM - Admin</title>

    <!-- bootstrap -->
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">
</head>

<body>

    <!-- pembungkus utama -->
    <div class="wrapper">

        <!-- sidebar -->
        <?php require_once __DIR__ . '/../layouts/sidebar_admin.php'; ?>
        <!-- akhir sidebar -->

        <!-- main -->
        <div class="main">

            <!-- navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>
            <!-- akhir navbar -->

            <!-- content -->
            <div class="content">
                <div class="card-dashboard">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        <div>
                            <h2>Validasi UMKM</h2>
                            <p>Verifikasi & Validasi Data Profil Pelaku UMKM Gandoang</p>
                        </div>
                    </div>

                    <!-- search -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">

                        <form method="get">
                            Show
                            <select name="limit" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                <option value="3" <?= ($limit == 3) ? 'selected' : '' ?>>3</option>
                                <option value="5" <?= ($limit == 5) ? 'selected' : '' ?>>5</option>
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
                            <button type="submit" class="btn tombol_cari">
                                Cari
                            </button>
                        </form>

                    </div>
                    <!-- akhir search -->

                    <!-- tabel -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>ID UMKM</th>
                                    <th>Nama UMKM</th>
                                    <th>Alamat</th>
                                    <th>Pemilik</th>
                                    <th>Validator</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-4 text-muted">Tidak ada data UMKM.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td><?= $row['id_umkm']; ?></td>
                                            <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                                            <td><?= htmlspecialchars($row['alamat']); ?></td>
                                            <td><?= htmlspecialchars($row['nama_pemilik'] ?? 'Tidak diketahui'); ?></td>
                                            <td><?= $row['id_validator'] ? htmlspecialchars($row['nama_validator']) : 'Belum divalidasi'; ?></td>
                                            <td>
                                                <span class="<?= ($row['status'] === 'aktif') ? 'disetujui' : (($row['status'] === 'nonaktif') ? 'ditolak' : 'pending') ?>">
                                                    <?= ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit_validasi_umkm.php?id=<?= $row['id_umkm'] ?>" class="btn btn-warning btn-sm" title="Validasi">
                                                    <img src="<?= $asset_path ?>/icon/edit.png" width="30px" height="30px">
                                                </a>
                                                <a href="hapus_validasi_umkm.php?id=<?= $row['id_umkm'] ?>" class="btn btn-danger btn-sm" title="Hapus">
                                                    <img src="<?= $asset_path ?>icon/hapus.png" style="padding:5px" width="30px" height="30px">
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- akhir tabel -->

                    <br>

                    <!-- pagination -->
                    <div class="d-flex flex-column align-items-center mt-4">
                        <ul class="pagination custom-pagination justify-content-center">
                            <!-- tombol previous -->
                            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">
                                    Previous
                                </a>
                            </li>

                            <!-- nomor halaman -->
                             <?php 
                             $start_page = 1;
                             if ($page >= 5) {
                                 $start_page = floor($page / 5) * 5;
                             }
                             $end_page = min($start_page + 5, $total_page);
                             if ($start_page > 1 && $start_page > $total_page) {
                                 $start_page = max(1, $total_page - 5);
                             }
                             for ($i = $start_page; $i <= $end_page; $i++) { 
                             ?>
                                 <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                     <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">
                                         <?= $i ?>
                                     </a>
                                 </li>
                             <?php } ?>

                            <!-- tombol next -->
                            <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&search=<?= urlencode($search) ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- akhir pagination -->

                </div>
            </div>
            <!-- akhir content -->

        </div>
        <!-- akhir main -->

    </div>
    <!-- akhir pembungkus utama -->

    <!-- alert validasi sukses -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'validasi_sukses'): ?>
        <div class="alert_sukses_menambah">
            <div class="box_sukses_menambah">
                <div class="icon_sukses_menambah">
                    <img src="<?= $asset_path ?>/icon/sukses.png" alt="Sukses">
                </div>
                <h2>Berhasil Validasi</h2>
                <p>Status UMKM Berhasil Divalidasi</p>
                <a href="validasi_umkm.php" class="tombol_sukses_menambah">Tutup</a>
            </div>
        </div>
    <?php endif; ?>

    <!-- alert sukses menghapus -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'hapus_sukses'): ?>
        <div class="alert_sukses_menambah">
            <div class="box_sukses_menambah">
                <div class="icon_sukses_menambah">
                    <img src="<?= $asset_path ?>icon/hapus_alert.png" alt="Sukses">
                </div>
                <h2>Berhasil Menghapus</h2>
                <p>Data UMKM Berhasil Dihapus</p>
                <a href="validasi_umkm.php" class="tombol_sukses_menambah">Tutup</a>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= $asset_path ?>/js/bantuan.js"></script>
</body>

</html>
