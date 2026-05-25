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
    header("Location: validasi_bantuan.php");
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
    header("Location: validasi_bantuan.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Validasi Bantuan - Admin</title>

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

                    <form action="../../controllers/validasi_bantuanControllers/proses_edit_validasi_bantuan.php" method="post">

                        <h2 class="judul-form">Form Validasi Ajukan Bantuan</h2>

                        <div style="margin-bottom: 35px; font-size: 20px;">
                            No ID Kebutuhan : <?= htmlspecialchars($bantuan['id_kebutuhan']); ?><br>
                            Nama UMKM : <?= htmlspecialchars($bantuan['nama_umkm']); ?><br>
                            Jenis Bantuan : <?= htmlspecialchars($bantuan['jenis']); ?><br>
                            Deskripsi Kebutuhan : <?= htmlspecialchars($bantuan['deskripsi']); ?><br>
                            Prioritas : <?= ucfirst(htmlspecialchars($bantuan['prioritas'])); ?><br>
                            Tanggal Pengajuan : <?= htmlspecialchars($bantuan['tanggal_pengajuan']); ?>
                        </div>

                        <input type="hidden" name="id_kebutuhan" value="<?= $bantuan['id_kebutuhan']; ?>">

                        <div class="form-bantuan">

                            <label>Status Validasi</label>
                            <span>:</span>
                            <div class="prioritas" style="gap: 20px;">
                                <label style="font-weight: 500;">
                                    <input
                                        type="radio" name="status"
                                        value="pending"
                                        <?= ($bantuan['status'] == 'pending') ? 'checked' : ''; ?>
                                        required>
                                    Pending
                                </label>

                                <label style="font-weight: 500; color: #198754;">
                                    <input
                                        type="radio" name="status"
                                        value="disetujui"
                                        <?= ($bantuan['status'] == 'disetujui') ? 'checked' : ''; ?>>
                                    Disetujui
                                </label>

                                <label style="font-weight: 500; color: #dc3545;">
                                    <input
                                        type="radio" name="status"
                                        value="ditolak"
                                        <?= ($bantuan['status'] == 'ditolak') ? 'checked' : ''; ?>>
                                    Ditolak
                                </label>
                            </div>

                            <label>Catatan Admin</label>
                            <span>:</span>
                            <textarea
                                name="catatan"
                                class="form-control"
                                placeholder="Masukkan catatan atau alasan persetujuan/penolakan..."
                                style="height: 120px; border-radius: 8px;"
                                required><?= htmlspecialchars($bantuan['catatan'] ?? ''); ?></textarea>

                        </div>

                        <div class="tombol" style="margin-top: 30px;">
                            <a href="validasi_bantuan.php" class="tombol_batal">
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
