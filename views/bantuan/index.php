<?php



// Koneksi Database dan Path
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
// Akhir Koneksi Database dan Path



// Session
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
// Akhir Auth Guard



// Error Handling
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// Akhir Error Handling



// Pagination
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
// Akhir Pagination



// Total Data (Pagination)
$total_sql = "SELECT COUNT(*) AS total
              FROM bantuan
              LEFT JOIN umkm ON bantuan.id_umkm = umkm.id_umkm
              LEFT JOIN user AS pengaju ON umkm.id_user = pengaju.id_user
              LEFT JOIN user AS validator ON umkm.id_validator = validator.id_user
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
// Akhir Total Data (Pagination)



// Menyiapkan Query SQL Total Data
$total_stmt = $conn->prepare($total_sql);
// Akhir Menyiapkan Query SQL Total Data



// Mengisi Nilai Search ke Query SQL Total Data
$total_stmt->execute([
    ':search' => "%$search%"
]);
// Akhir Mengisi Nilai Search ke Query SQL Total Data



// Mengambil Hasil Total Data dari Database
$total_row = $total_stmt->fetch(PDO::FETCH_ASSOC);
// Akhir Mengambil Hasil Total Data dari Database



// Menghitung Total Data dan Total Halaman
$total_data = $total_row['total'];
$total_page = ceil($total_data / $limit);
// Akhir Menghitung Total Data dan Total Halaman



// Query Data Tabel
$sql = "SELECT 
            bantuan.*,
            umkm.nama_umkm,
            pengaju.nama AS nama_pengaju,
            validator.nama AS nama_validator
        FROM bantuan
        LEFT JOIN umkm ON bantuan.id_umkm = umkm.id_umkm
        LEFT JOIN user AS pengaju ON umkm.id_user = pengaju.id_user
        LEFT JOIN user AS validator ON umkm.id_validator = validator.id_user
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
        LIMIT $limit OFFSET $offset";
// Akhir Query Data Tabel



// Menyiapkan Query SQL Tabel Data
$stmt = $conn->prepare($sql);
// Akhir Menyiapkan Query SQL Tabel Data



// Mengisi Nilai Search ke Query SQL Tabel Data
$stmt->execute([
    ':search' => "%$search%"
]);
// Akhir Mengisi Nilai Search ke Query SQL Tabel Data



// Mengambil Semua Data dari Database
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Akhir Mengambil Semua Data dari Database

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Ajukan Bantuan</title>

    <!-- bootstrap -->
    <link href="<?= $asset_path ?>/boostrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- css -->
    <link href="<?= $asset_path ?>/css/bantuan.css" rel="stylesheet">

</head>

<body>

    <!-- pembungkus utama -->
    <div class="wrapper">

        <!-- sidebar -->
        <?php 
        $sidebar_file = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'sidebar_admin.php' : 'sidebar_user.php';
        require_once __DIR__ . '/../layouts/' . $sidebar_file; 
        ?>
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
                            <h2>Detail Bantuan</h2>
                            <p>Verifikasi Bantuan Yang Diajukan Oleh UMKM</p>
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
                                    <th>id_kebutuhan</th>
                                    <th>nama_umkm</th>
                                    <th>prioritas</th>
                                    <th>nama_pengaju</th>
                                    <th>nama_validator</th>
                                    <th>jenis_bantuan</th>
                                    <th>tanggal_pengajuan</th>
                                    <th>tanggal_validasi</th>
                                    <th>Catatan</th>
                                    <th>deskripsi</th>
                                    <th>status</th>
                                    <th>aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><?= $row['id_kebutuhan']; ?></td>
                                        <td><?= htmlspecialchars($row['nama_umkm']); ?></td>
                                        <td><?= htmlspecialchars($row['prioritas']); ?></td>
                                        <td><?= htmlspecialchars($row['nama_pengaju'] ?? 'Tidak diketahui'); ?></td>
                                        <td><?= $row['tanggal_validasi'] ? htmlspecialchars($row['nama_validator']) : 'Belum divalidasi'; ?></td>
                                        <td><?= htmlspecialchars($row['jenis']); ?></td>
                                        <td><?= $row['tanggal_pengajuan']; ?></td>
                                        <td><?= $row['tanggal_validasi'] ?? 'Null'; ?></td>
                                        <td><?= $row['catatan'] ? htmlspecialchars($row['catatan']) : 'Belum ada catatan'; ?></td>
                                        <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                        <td>
                                            <span class="<?= $row['status']; ?>">
                                                <?= $row['status']; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="edit_bantuan.php?id=<?= $row['id_kebutuhan'] ?>" class="btn btn-warning btn-sm">
                                                <img src="<?= $asset_path ?>/icon/edit.png" width="30px" height="30px">
                                            </a>

                                            <a href="hapus_bantuan.php?id=<?= $row['id_kebutuhan'] ?>" class="btn btn-danger btn-sm">
                                                <img src="<?= $asset_path ?>icon/hapus.png" style="padding:5px" width="30px" height="30px">
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- akhir tabel -->
                    <br>

                    <!-- awal dari pagination -->
                    <ul class="pagination custom-pagination justify-content-center mt-4 flex-wrap">

                        <!-- tombol previous -->
                        <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>">
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
                                <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php } ?>

                        <!-- tombol next -->
                        <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
                            <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>">
                                Next
                            </a>
                        </li>

                    </ul>
                    <!-- akhir dari pagination -->

                    <!-- tambah -->
                    <div class="d-flex justify-content-end">
                        <a href="tambah_bantuan.php" class="btn" id="tambah">
                            + Tambah Pengajuan
                        </a>
                    </div>
                    <!-- akhir tambah -->

                </div>
                <!-- akhir card dashboard -->

            </div>
            <!-- akhir content -->

        </div>
        <!-- akhir main -->

    </div>
    <!-- akhir pembungkus utama -->

    <!-- alert sukses -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'tambah_sukses'): ?>
        <div class="alert_sukses_menambah">
            <div class="box_sukses_menambah">
                <div class="icon_sukses_menambah">
                    <img src="<?= $asset_path ?>/icon/sukses.png" alt="Sukses">
                </div>
                <h2>Berhasil Menambahkan</h2>
                <p>Pengajuan Bantuan Berhasil Ditambahkan</p>
                <a href="index.php" class="tombol_sukses_menambah">
                    Tutup
                </a>
            </div>
        </div>
    <?php endif; ?>
    <!-- akhir alert sukses -->


    <!-- alert sukses mengedit -->

    <!-- alert edit sukses -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'edit_sukses'): ?>
        <div class="alert_sukses_menambah">
            <div class="box_sukses_menambah">
                <div class="icon_sukses_menambah">
                    <img src="<?= $asset_path ?>/icon/sukses.png" alt="Sukses">
                </div>
                <h2>Berhasil Mengedit</h2>
                <p>Pengajuan Bantuan Berhasil Diperbarui</p>
                <a href="index.php" class="tombol_sukses_menambah">Tutup</a>
            </div>
        </div>
    <?php endif; ?>
    <!-- akhir alert edit sukses -->

    <!-- alert sukses menghapus bantuan -->
    <?php if (isset($_GET['status']) && $_GET['status'] == 'hapus_sukses'): ?>
        <div class="alert_sukses_menambah">
            <div class="box_sukses_menambah">
                <div class="icon_sukses_menambah">
                    <img src="<?= $asset_path ?>icon/hapus_alert.png" alt="Sukses">
                </div>
                <h2>Berhasil Menghapus</h2>
                <p>Pengajuan Bantuan Berhasil Dihapus</p>
                <a href="index.php" class="tombol_sukses_menambah">Tutup</a>
            </div>
        </div>
    <?php endif; ?>
    <!-- akhir alert sukses menghapus bantuan -->

    <script src="<?= $asset_path ?>/js/bantuan.js"></script>

</body>

</html>