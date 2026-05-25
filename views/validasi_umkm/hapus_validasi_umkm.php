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

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: validasi_umkm.php");
    exit;
}

$sql = "SELECT 
            umkm.*,
            pemilik.nama AS nama_pemilik
        FROM umkm
        LEFT JOIN user AS pemilik ON umkm.id_user = pemilik.id_user
        WHERE umkm.id_umkm = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

$umkm = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$umkm) {
    header("Location: validasi_umkm.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Validasi UMKM - Admin</title>

    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/tambah_bantuan.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <!-- sidebar -->
        <?php require_once __DIR__ . '/../layouts/sidebar_admin.php'; ?>
        <!-- akhir sidebar -->

        <div class="main">

            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">

                <div class="card-dashboard">

                    <form action="../../controllers/validasi_umkmControllers/proses_hapus_validasi_umkm.php" method="post">
                        <h2 class="judul-form">Form Hapus Validasi UMKM</h2>

                        <div style="margin-bottom: 90px; font-size: 20px;">
                            Nama UMKM : <?= htmlspecialchars($umkm['nama_umkm']); ?><br>
                            Nama Pemilik : <?= htmlspecialchars($umkm['nama_pemilik'] ?? 'Tidak diketahui'); ?><br>
                            Alamat : <?= htmlspecialchars($umkm['alamat']); ?>
                        </div>

                        <input type="hidden" name="id_umkm" value="<?= $umkm['id_umkm']; ?>">

                        <div style="text-align: center; font-size: 26px; margin-bottom: 120px;">
                            Hapus Pendaftaran UMKM ?<br>
                            Setelah Dihapus Maka Akan Hilang Dari Daftar
                        </div>

                        <div class="tombol">
                            <a href="validasi_umkm.php" class="tombol_batal">
                                Batal
                            </a>

                            <button type="submit" name="submit" class="tombol_simpan">
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
