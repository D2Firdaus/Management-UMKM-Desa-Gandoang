<?php
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: bantuan.php");
    exit;
}

$sql = "SELECT 
            bantuan.*,
            umkm.nama_umkm
        FROM bantuan
        LEFT JOIN umkm 
            ON bantuan.id_umkm = umkm.id_umkm
        WHERE bantuan.id_kebutuhan = :id";

$stmt = $conn->prepare($sql);
$stmt->execute([
    ':id' => $id
]);

$bantuan = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bantuan) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Bantuan</title>

    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/tambah_bantuan.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <? echo __DIR__; ?>
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>
        <div class="main">

            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">

                <div class="card-dashboard">

                    <form action="<?= $bantuan_controller_path ?>proses_hapus_bantuan.php" method="post">
                        <h2 class="judul-form">Form Hapus Bantuan</h2>

                        <div style="margin-bottom: 90px; font-size: 20px;">
                            Nama UMKM : <?= htmlspecialchars($bantuan['nama_umkm']); ?><br>
                            Deskripsi Kebutuhan : <?= htmlspecialchars($bantuan['deskripsi']); ?>
                        </div>

                        <input type="hidden" name="id_kebutuhan" value="<?= $bantuan['id_kebutuhan']; ?>">

                        <div style="text-align: center; font-size: 26px; margin-bottom: 120px;">
                            Hapus Pengajuan Bantuan Untuk Kebutuhan ?<br>
                            Setelah Dihapus Maka Akan Hilang Dari Daftar
                        </div>

                        <div class="tombol">

                            <a href="index.php" class="tombol_batal">
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