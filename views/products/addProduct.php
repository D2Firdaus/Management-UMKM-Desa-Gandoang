<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

$user_id = $_SESSION['user_id'] ?? null;
$user_role = $_SESSION['user_role'] ?? 'umkm';

if ($user_id) {
    if ($user_role === 'admin') {
        $stmt = $conn->prepare("SELECT id_umkm, nama_umkm FROM umkm ORDER BY nama_umkm ASC");
        $stmt->execute();
    } else {
        $stmt = $conn->prepare("SELECT id_umkm, nama_umkm FROM umkm WHERE id_user = :id_user ORDER BY nama_umkm ASC");
        $stmt->execute([':id_user' => $user_id]);
    }
    $umkms = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $stmt = $conn->prepare("SELECT id_umkm, nama_umkm FROM umkm ORDER BY nama_umkm ASC");
    $stmt->execute();
    $umkms = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= $asset_path ?>boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $asset_path ?>css/products/addProducts.css">
    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>

        <div class="main">
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content container-fluid">
                <div class="form-card shadow-sm">
                    <h2 class="text-center fw-bold mb-5" style="color: #65835e;">Form Tambah Produk</h2>

                    <form action="<?= $product_controller_path ?>AddProduct.php" method="POST" enctype="multipart/form-data">

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Nama Product :</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_produk" class="form-control bg-light" placeholder="Nama produk" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Kategori :</label>
                            <div class="col-sm-9">
                                <input type="text" name="kategori" class="form-control bg-light" placeholder="Contoh: Kuliner, Konveksi" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Harga :</label>
                            <div class="col-sm-9">
                                <input type="number" name="harga" class="form-control bg-light" placeholder="10000" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Upload Foto :</label>
                            <div class="col-sm-9">
                                <div class="upload-area bg-light">
                                    <label for="foto" class="btn btn-sm btn-light border">
                                        <i class="bi bi-cloud-upload"></i> Upload
                                    </label>
                                    <input type="file" name="foto" id="foto" class="d-none"
                                           accept="image/png, image/jpeg, image/jpg, image/webp"
                                           onchange="previewFile()">

                                    <div id="file-preview" class="preview-box d-none">
                                        <img src="" id="img-placeholder" style="width: 40px; height: 30px; object-fit: cover;">
                                        <div>
                                            <span id="file-name" class="d-block fw-bold"></span>
                                            <span id="file-size" class="text-muted"></span>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-outline-danger ms-auto border-0" onclick="removeFile()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Pilih UMKM :</label>
                            <div class="col-sm-9">
                                <?php if (empty($umkms)): ?>
                                    <div class="text-danger small mb-1">
                                        Anda belum mendaftarkan UMKM. 
                                        <a href="<?= $view_path ?>umkm/index.php" class="text-decoration-underline text-danger">Daftarkan UMKM</a> terlebih dahulu.
                                    </div>
                                    <select name="id_umkm" class="form-select bg-light" style="width: 200px;" disabled required>
                                        <option value="">Tidak ada UMKM</option>
                                    </select>
                                <?php else: ?>
                                    <select name="id_umkm" class="form-select bg-light" style="width: 200px;" required>
                                        <?php foreach ($umkms as $umkm): ?>
                                            <option value="<?= $umkm['id_umkm'] ?>"><?= htmlspecialchars($umkm['nama_umkm']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-3 form-label text-end pt-2">Deskripsi :</label>
                            <div class="col-sm-9">
                                <textarea name="deskripsi" class="form-control bg-light" rows="4" placeholder="Deskripsi produk..."></textarea>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-9 offset-sm-3 d-flex justify-content-between">
                                <a href="index.php" class="btn btn-batal fw-bold">Batal</a>
                                <button type="submit" class="btn btn-simpan fw-bold d-flex align-items-center gap-2" <?= empty($umkms) ? 'disabled' : '' ?>>
                                    <i class="bi bi-floppy"></i> Simpan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const file = document.querySelector('#foto').files[0];
            const preview = document.querySelector('#file-preview');
            const imgPlaceholder = document.querySelector('#img-placeholder');
            const name = document.querySelector('#file-name');
            const size = document.querySelector('#file-size');

            if (file) {
                preview.classList.remove('d-none');
                name.innerText = file.name;
                size.innerText = (file.size / 1024).toFixed(1) + ' Kb';

                const reader = new FileReader();
                reader.onload = function(e) { imgPlaceholder.src = e.target.result; }
                reader.readAsDataURL(file);
            }
        }

        function removeFile() {
            document.querySelector('#foto').value = '';
            document.querySelector('#file-preview').classList.add('d-none');
        }
    </script>

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
    <?php ob_end_flush(); ?>
</body>

</html>
