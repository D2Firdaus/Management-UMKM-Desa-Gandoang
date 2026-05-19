<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

$umkm_result = $conn->query("SELECT id_umkm, nama_umkm FROM umkm");
$umkm_data = $umkm_result->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Bantuan</title>

    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $asset_path ?>css/tambah_bantuan.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>

        <div class="main">

            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">

                <div class="card-dashboard">

                    <form action="<?= $bantuan_controller_path ?>proses_edit_bantuan.php" method="post">

                        <h2 class="judul-form">Form Edit Ajukan Bantuan</h2>

                        <div style="margin-bottom: 35px; font-size: 20px;">
                            Deskripsi Bantuan : <?= htmlspecialchars($bantuan['deskripsi']); ?><br>
                            No ID Kebutuhan : <?= htmlspecialchars($bantuan['id_kebutuhan']); ?><br>
                            Nama UMKM : <?= htmlspecialchars($bantuan['nama_umkm']); ?>
                        </div>

                        <input type="hidden" name="id_kebutuhan" value="<?= $bantuan['id_kebutuhan']; ?>">

                        <div class="form-bantuan">

                            <label>Jenis</label>
                            <span>:</span>
                            <input
                                type="text"
                                name="jenis"
                                class="form-control"
                                value="<?= htmlspecialchars($bantuan['jenis']); ?>"
                                required>

                            <label>Deskripsi</label>
                            <span>:</span>
                            <textarea
                                name="deskripsi"
                                class="form-control"
                                required><?= htmlspecialchars($bantuan['deskripsi']); ?></textarea>

                            <label>Pilih UMKM</label>
                            <span>:</span>
                            <select name="id_umkm" class="form-select" required>

                                <?php foreach ($umkm_data as $umkm): ?>
                                    <option
                                        value="<?= $umkm['id_umkm']; ?>"
                                        <?= ($umkm['id_umkm'] == $bantuan['id_umkm']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($umkm['nama_umkm']); ?>
                                    </option>
                                <?php endforeach; ?>

                            </select>

                            <label>Prioritas</label>
                            <span>:</span>

                            <div class="prioritas">

                                <label>
                                    <input
                                        type="radio" name="prioritas"
                                        value="tinggi"
                                        <?= ($bantuan['prioritas'] == 'tinggi') ? 'checked' : ''; ?>
                                        required>
                                    Tinggi
                                </label>

                                <label>
                                    <input
                                        type="radio"
                                        name="prioritas"
                                        value="sedang"
                                        <?= ($bantuan['prioritas'] == 'sedang') ? 'checked' : ''; ?>>
                                    Sedang
                                </label>

                                <label>
                                    <input
                                        type="radio"
                                        name="prioritas"
                                        value="rendah"
                                        <?= ($bantuan['prioritas'] == 'rendah') ? 'checked' : ''; ?>>
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