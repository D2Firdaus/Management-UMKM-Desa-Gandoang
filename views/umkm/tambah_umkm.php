<?php
// ─── Session ─────────────────────────────────────────────────────────────────
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ─── Config ───────────────────────────────────────────────────────────────────
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

// ─── Auth Guard ───────────────────────────────────────────────────────────────
$id_user = (int) ($_SESSION['user_id'] ?? 0);
if (!$id_user) {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
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
    <title>Tambah UMKM - UMKM Desa Gandoang</title>

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

                    <form action="<?= $umkm_controller_path ?>proses_tambah_umkm.php" method="post" id="formTambahUmkm">

                        <h2 class="judul-form">Form Tambah UMKM</h2>

                        <div class="form-umkm">

                            <label for="nama_umkm">Nama UMKM</label>
                            <span>:</span>
                            <input
                                type="text"
                                name="nama_umkm"
                                id="nama_umkm"
                                class="form-control"
                                placeholder="Masukkan nama UMKM"
                                required>

                            <label for="jenis_usaha">Jenis Usaha</label>
                            <span>:</span>
                            <input
                                type="text"
                                name="jenis_usaha"
                                id="jenis_usaha"
                                class="form-control"
                                placeholder="Contoh: kuliner, perdagangan, jasa..."
                                required>

                            <label for="alamat">Alamat</label>
                            <span>:</span>
                            <textarea
                                name="alamat"
                                id="alamat"
                                class="form-control"
                                placeholder="Masukkan alamat lengkap UMKM"
                                required></textarea>

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

    <!-- Popup gagal (jika redirect kembali dengan ?status=gagal) -->
    <?php if (isset($_GET['status']) && $_GET['status'] === 'gagal'): ?>
    <div class="alert_sukses_menambah" id="popupGagal">
        <div class="box_sukses_menambah">
            <div class="icon_sukses_menambah">
                <img src="<?= $asset_path ?>icon/hapus_alert.png" alt="Gagal">
            </div>
            <h2>Gagal<br>Menyimpan</h2>
            <p>UMKM Gagal Ditambahkan</p>
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
