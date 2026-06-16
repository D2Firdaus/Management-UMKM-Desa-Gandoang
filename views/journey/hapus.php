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
require_once __DIR__ . '/../../models/JourneyModel.php';

// ─── Auth Guard ───────────────────────────────────────────────────────────────
$id_user = (int) ($_SESSION['user_id'] ?? 0);
if (!$id_user) {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}

// ─── Ambil data Journey ──────────────────────────────────────────────────────────
$id_journey = (int) ($_GET['id'] ?? 0);
if (!$id_journey) {
    header('Location: index.php');
    exit;
}

$journeyModel = new JourneyModel($conn);
$journey = $journeyModel->getById($id_journey, $id_user);

if (!$journey) {
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
    <title>Hapus Journey - UMKM Desa Gandoang</title>

    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
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

                    <form action="index.php" method="post">

                        <h2 class="judul-form">Form Hapus Journey</h2>

                        <input type="hidden" name="id_journey" value="<?= $journey['id_journey'] ?>">
                        <input type="hidden" name="action" value="delete">

                        <!-- Info singkat Journey yang akan dihapus -->
                        <div class="hapus-info">
                            Deskripsi &nbsp;&nbsp;&nbsp;: <?= htmlspecialchars($journey['deskripsi']) ?><br>
                            Tanggal &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <?= date('d F Y', strtotime($journey['tanggal'])) ?><br>
                            Nama UMKM : <?= htmlspecialchars($journey['nama_umkm']) ?>
                        </div>

                        <!-- Pesan konfirmasi -->
                        <div class="hapus-konfirmasi">
                            Hapus Data Journey ?<br>
                            Setelah Dihapus Maka Akan<br>
                            Hilang Dari Daftar
                        </div>

                        <div class="tombol">
                            <a href="index.php" class="tombol_batal">Batal</a>

                            <button type="submit" class="tombol_hapus d-flex align-items-center justify-content-center gap-2" style="padding: 10px 20px;">
                                <i class="bi bi-trash fs-5"></i>
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
        <div class="box_sukses_menambah text-center">
            <div class="icon_sukses_menambah mb-3">
                <i class="bi bi-x-circle-fill" style="font-size: 64px; color: #dc3545;"></i>
            </div>
            <h2>Gagal<br>Menghapus</h2>
            <p>Data Journey Gagal Dihapus</p>
            <button onclick="document.getElementById('popupGagal').style.display='none'" class="tombol_sukses_menambah" style="border:none;">
                Tutup
            </button>
        </div>
    </div>
    <style>
        .box_sukses_menambah { padding: 30px; }
    </style>
    <?php endif; ?>

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <?php ob_end_flush(); ?>
</body>

</html>
