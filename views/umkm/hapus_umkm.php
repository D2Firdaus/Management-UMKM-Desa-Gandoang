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
    <title>Hapus UMKM - UMKM Desa Gandoang</title>

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

                    <form action="<?= $umkm_controller_path ?>proses_hapus_umkm.php" method="post">

                        <h2 class="judul-form">Form Hapus UMKM</h2>

                        <input type="hidden" name="id_umkm" value="<?= $umkm['id_umkm'] ?>">

                        <!-- Info singkat UMKM yang akan dihapus -->
                        <div class="hapus-info">
                            Nama UMKM &nbsp;: <?= htmlspecialchars($umkm['nama_umkm']) ?><br>
                            No ID UMKM : <?= htmlspecialchars((string) $umkm['id_umkm']) ?>
                        </div>

                        <!-- Pesan konfirmasi -->
                        <div class="hapus-konfirmasi">
                            Hapus Profile UMKM ?<br>
                            Setelah Dihapus Maka Akan<br>
                            Hilang Dari Daftar
                        </div>

                        <div class="tombol">
                            <a href="index.php" class="tombol_batal">Batal</a>

                            <button type="submit" class="tombol_hapus">
                                <img src="<?= $asset_path ?>icon/hapus.png" style="padding:4px" width="28" height="28" alt="hapus">
                                Hapus
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
            <h2>Gagal<br>Menghapus</h2>
            <p>UMKM Gagal Dihapus</p>
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
