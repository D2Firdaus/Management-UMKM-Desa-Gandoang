<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

$umkm_result = $conn->query("SELECT id_umkm, nama_umkm FROM umkm");
$umkm_data = $umkm_result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Tambah Bantuan</title>

    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/tambah_bantuan.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">
        <?php 
        $sidebar_file = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'sidebar_admin.php' : 'sidebar_user.php';
        require_once __DIR__ . '/../layouts/' . $sidebar_file; 
        ?>

        <div class="main">
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">
                <div class="card-dashboard">

                    <form action="<?= $bantuan_controller_path ?>proses_tambah_bantuan.php" method="post">
                        <h2 class="judul-form">Form Tambah Bantuan</h2>

                        <div class="form-bantuan">
                            <label>Jenis</label> <span>:</span>
                            <input type="text" name="jenis" class="form-control" required>

                            <label>Deksripsi</label> <span>:</span>
                            <textarea name="deskripsi" class="form-control" required></textarea>

                            <label>Pilih UMKM</label> <span>:</span>

                            <select name="id_umkm" class="form-select" required>
                                <option value="">Pilih UMKM</option>
                                <?php foreach ($umkm_data as $umkm): ?>
                                    <option value="<?= $umkm['id_umkm']; ?>">
                                        <?= htmlspecialchars($umkm['nama_umkm']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <label>Prioritas</label> <span>:</span>

                            <div class="prioritas">
                                <label>
                                    <input type="radio" name="prioritas" value="tinggi" required>
                                    Tinggi
                                </label>

                                <label>
                                    <input type="radio" name="prioritas" value="sedang">
                                    Sedang
                                </label>

                                <label>
                                    <input type="radio" name="prioritas" value="rendah">
                                    Rendah
                                </label>

                            </div>

                        </div>

                        <div class="tombol">
                            <a href="index.php" class="tombol_batal">
                                Batal
                            </a>

                            <button type="submit" class="tombol_simpan">
                                <img src="<?= $asset_path ?>icon/simpan.png" style="padding:5px" width="30px" height="30px">
                                Simpan
                            </button>
                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script src="<?= $asset_path ?>js/bantuan.js"></script>

</body>

</html>