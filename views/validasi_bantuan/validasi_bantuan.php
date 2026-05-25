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
              FROM bantuan
              LEFT JOIN umkm ON bantuan.id_umkm = umkm.id_umkm
              LEFT JOIN user AS pengaju ON umkm.id_user = pengaju.id_user
              LEFT JOIN user AS validator ON bantuan.id_validator = validator.id_user
              WHERE bantuan.status != 'dihapus'
              AND (
                    bantuan.jenis LIKE :search
                    OR bantuan.prioritas LIKE :search
                    OR bantuan.status LIKE :search
                    OR bantuan.catatan LIKE :search
                    OR bantuan.deskripsi LIKE :search
                    OR umkm.nama_umkm LIKE :search
                    OR pengaju.nama LIKE :search
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
            bantuan.*,
            umkm.nama_umkm,
            pengaju.nama AS nama_pengaju,
            validator.nama AS nama_validator
        FROM bantuan
        LEFT JOIN umkm 
            ON bantuan.id_umkm = umkm.id_umkm
        LEFT JOIN user AS pengaju
            ON umkm.id_user = pengaju.id_user
        LEFT JOIN user AS validator
            ON bantuan.id_validator = validator.id_user
        WHERE bantuan.status != 'dihapus'
        AND (
            bantuan.jenis LIKE :search
            OR bantuan.prioritas LIKE :search
            OR bantuan.status LIKE :search
            OR bantuan.catatan LIKE :search
            OR bantuan.deskripsi LIKE :search
            OR umkm.nama_umkm LIKE :search
            OR pengaju.nama LIKE :search
            OR validator.nama LIKE :search
        )
        ORDER BY bantuan.id_kebutuhan DESC
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
    <title>Validasi Bantuan - Admin</title>

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
                            <h2>Validasi Bantuan</h2>
                            <p>Verifikasi & Validasi Pengajuan Bantuan UMKM</p>
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
                                placeholder="Cari Pengajuan..."
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
                                    <th>ID Kebutuhan</th>
                                    <th>Nama UMKM</th>
                                    <th>Prioritas</th>
                                    <th>Pemilik / Pengaju</th>
                                    <th>Validator</th>
                                    <th>Jenis Bantuan</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Tanggal Validasi</th>
                                    <th>Catatan</th>
                                    <th>Deskripsi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data)): ?>
                                    <tr>
                                        <td colspan="12" class="text-center py-4 text-muted">Tidak ada data pengajuan bantuan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td><?= $row['id_kebutuhan']; ?></td>
                                            <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                                            <td>
                                                <span class="badge bg-<?= $row['prioritas'] === 'tinggi' ? 'danger' : ($row['prioritas'] === 'sedang' ? 'warning' : 'secondary') ?>">
                                                    <?= ucfirst($row['prioritas']); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['nama_pengaju'] ?? 'Tidak diketahui'); ?></td>
                                            <td><?= ($row['status'] !== 'pending' && $row['id_validator']) ? htmlspecialchars($row['nama_validator'] ?? '') : 'Belum divalidasi'; ?></td>
                                            <td><?= htmlspecialchars($row['jenis']); ?></td>
                                            <td><?= $row['tanggal_pengajuan']; ?></td>
                                            <td><?= $row['tanggal_validasi'] ?? '-'; ?></td>
                                            <td><?= $row['catatan'] ? htmlspecialchars($row['catatan']) : 'Belum ada catatan'; ?></td>
                                            <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                            <td>
                                                <span class="<?= $row['status']; ?>">
                                                    <?= ucfirst($row['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="edit_validasi_bantuan.php?id=<?= $row['id_kebutuhan'] ?>" class="btn btn-warning btn-sm" title="Validasi">
                                                    <img src="<?= $asset_path ?>/icon/edit.png" width="30px" height="30px">
                                                </a>
                                                <a href="hapus_validasi_bantuan.php?id=<?= $row['id_kebutuhan'] ?>" class="btn btn-danger btn-sm" title="Hapus">
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
                            <?php for ($i = 1; $i <= $total_page; $i++) { ?>
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
                <p>Pengajuan Bantuan Berhasil Divalidasi</p>
                <a href="validasi_bantuan.php" class="tombol_sukses_menambah">Tutup</a>
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
                <p>Pengajuan Bantuan Berhasil Dihapus</p>
                <a href="validasi_bantuan.php" class="tombol_sukses_menambah">Tutup</a>
            </div>
        </div>
    <?php endif; ?>

    <script src="<?= $asset_path ?>/js/bantuan.js"></script>
</body>

</html>
