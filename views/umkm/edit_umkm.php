<?php
// ─── Session ─────────────────────────────────────────────────────────────────
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ─── Config & Model ───────────────────────────────────────────────────────────
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../models/UmkmModel.php';

// ─── Auth Guard ───────────────────────────────────────────────────────────────
$id_user = (int) ($_SESSION['user_id'] ?? 0);
if (!$id_user) {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}

// ─── Ambil data UMKM ──────────────────────────────────────────────────────────
$id_umkm = (int) ($_GET['id'] ?? 0);
if (!$id_umkm) {
    header('Location: index.php');
    exit;
}

$umkmModel = new UmkmModel($conn);
$umkm      = $umkmModel->getByIdAndUser($id_umkm, $id_user);

if (!$umkm) {
    // UMKM tidak ditemukan atau bukan milik user ini
    header('Location: index.php');
    exit;
}

// ─── Sidebar selector ─────────────────────────────────────────────────────────
$sidebar_file = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin')
    ? 'sidebar_admin.php'
    : 'sidebar_user.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit UMKM - UMKM Desa Gandoang</title>

    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/umkm.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <!-- Sidebar -->
        <?php require_once __DIR__ . '/../layouts/' . $sidebar_file; ?>

        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">
                <div class="card-dashboard">

                    <form action="<?= $umkm_controller_path ?>proses_edit_umkm.php" method="post" id="formEditUmkm">

                        <h2 class="judul-form">Form Edit UMKM</h2>

                        <div class="form-umkm">

                            <!-- Nama UMKM (read-only sebagai info) -->
                            <label>Nama UMKM</label>
                            <span>:</span>
                            <input
                                type="text"
                                value="<?= htmlspecialchars($umkm['nama_umkm']) ?>"
                                readonly
                                class="form-control">

                            <!-- No ID UMKM -->
                            <label>No ID UMKM</label>
                            <span>:</span>
                            <input
                                type="text"
                                value="<?= htmlspecialchars((string) $umkm['id_umkm']) ?>"
                                readonly
                                class="form-control">

                            <!-- Hidden fields -->
                            <input type="hidden" name="id_umkm" value="<?= $umkm['id_umkm'] ?>">

                            <!-- Nama UMKM (editable) -->
                            <label for="nama_umkm_edit">Nama UMKM</label>
                            <span>:</span>
                            <input
                                type="text"
                                name="nama_umkm"
                                id="nama_umkm_edit"
                                class="form-control"
                                value="<?= htmlspecialchars($umkm['nama_umkm']) ?>"
                                required>

                            <!-- Jenis Usaha -->
                            <label for="jenis_usaha_edit">Jenis Usaha</label>
                            <span>:</span>
                            <input
                                type="text"
                                name="jenis_usaha"
                                id="jenis_usaha_edit"
                                class="form-control"
                                value="<?= htmlspecialchars($umkm['jenis_usaha'] ?? '') ?>"
                                placeholder="Contoh: kuliner, perdagangan, jasa..."
                                required>

                            <!-- Alamat -->
                            <label for="alamat_edit">Alamat</label>
                            <span>:</span>
                            <textarea
                                name="alamat"
                                id="alamat_edit"
                                class="form-control"
                                required><?= htmlspecialchars($umkm['alamat']) ?></textarea>

                        </div>

                        <div class="tombol">
                            <a href="index.php" class="tombol_batal">Batal</a>

                            <button type="submit" class="tombol_simpan">
                                <img src="<?= $asset_path ?>icon/simpan.png" style="padding:4px" width="28" height="28" alt="simpan">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>

    <!-- Popup gagal -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'gagal'): ?>
    <div class="alert_sukses_menambah" id="popupGagal">
        <div class="box_sukses_menambah">
            <div class="icon_sukses_menambah">
                <img src="<?= $asset_path ?>icon/hapus_alert.png" alt="Gagal">
            </div>
            <h2>Gagal<br>Memperbarui</h2>
            <p>UMKM Gagal Diperbarui</p>
            <button onclick="document.getElementById('popupGagal').style.display='none'" class="tombol_sukses_menambah">
                Tutup
            </button>
        </div>
    </div>
    <?php endif; ?>

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <?php ob_end_flush(); ?>
</body>

</html>
