<?php
ob_start();

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$id_user = $_SESSION['user_id'] ?? null;

if (!$id_user) {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}

$productController = new ProductController($conn);
$umkm_list         = $productController->getUmkmList((int) $id_user);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - UMKM Gandoang</title>

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="<?= $asset_path ?>boostrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= $asset_path ?>css/products/addProducts.css">
    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
</head>

<body>
    <div class="wrapper">
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>

        <div class="main">
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">
                <div class="form-card shadow-sm">
                    <h2 class="text-center fw-bold mb-5" style="color: #65835e;">Form Tambah Produk</h2>

                    <?php if (isset($_GET['status']) && $_GET['status'] === 'image_required'): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <strong>Gagal!</strong> Foto produk wajib diupload minimal 1 foto.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $product_controller_path ?>AddProduct.php" method="POST" enctype="multipart/form-data" id="form-tambah">

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Nama Produk :</label>
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
                                    <input type="file" name="foto[]" id="foto" class="d-none" onchange="previewFiles()" multiple accept=".jpg,.jpeg,.png,.webp">
                                    <div id="preview-container" class="d-flex flex-wrap flex-row gap-2 mt-2"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Pilih UMKM :</label>
                            <div class="col-sm-9">
                                <select name="id_umkm" class="form-select bg-light" style="width: 200px;" required>
                                    <option value="" disabled selected>-- Pilih UMKM --</option>
                                    <?php foreach ($umkm_list as $umkm): ?>
                                        <option value="<?= htmlspecialchars($umkm['id_umkm']) ?>">
                                            <?= htmlspecialchars($umkm['nama_umkm']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <?php if (empty($umkm_list)): ?>
                                        <option value="" disabled>Belum ada UMKM aktif</option>
                                    <?php endif; ?>
                                </select>
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
                                <button type="submit" class="btn btn-simpan fw-bold d-flex align-items-center gap-2">
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
        let kumpulanFile = new DataTransfer();

        function previewFiles() {
            const input = document.querySelector('#foto');
            const container = document.querySelector('#preview-container');
            const fileBaru = input.files;

            Array.from(fileBaru).forEach(file => {
                if (kumpulanFile.items.length < 3) {
                    kumpulanFile.items.add(file);
                } else {
                    alert('Maksimal foto yang diperbolehkan hanya 3 file!');
                }
            });

            input.files = kumpulanFile.files;
            renderPreview();
        }

        function renderPreview() {
            const container = document.querySelector('#preview-container');
            container.innerHTML = '';

            Array.from(kumpulanFile.files).forEach((file, index) => {
                const reader = new FileReader();

                reader.onload = function(e) {
                    let namaTeks = file.name;
                    if (namaTeks.length > 7) {
                        namaTeks = namaTeks.substring(0, 7) + '...';
                    }
                    const previewBox = document.createElement('div');
                    previewBox.className = "preview-box d-flex align-items-center bg-white p-2 border rounded shadow-sm gap-3";
                    previewBox.innerHTML = `
                        <img src="${e.target.result}" style="width: 50px; height: 40px; object-fit: cover;" class="rounded">
                        <div class="flex-grow-1" style="min-width: 0;">
                            <span class="d-block fw-bold text-truncate small">${namaTeks}</span>
                            <span class="text-muted small">${(file.size / 1024).toFixed(1)} Kb</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeSingleFile(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    container.appendChild(previewBox);
                };

                reader.readAsDataURL(file);
            });
        }

        function removeSingleFile(index) {
            const input = document.querySelector('#foto');
            const kantongBaru = new DataTransfer();

            Array.from(kumpulanFile.files).forEach((file, i) => {
                if (i !== index) {
                    kantongBaru.items.add(file);
                }
            });

            kumpulanFile = kantongBaru;
            input.files = kumpulanFile.files;
            renderPreview();
        }

        document.querySelector('#form-tambah').addEventListener('submit', function(e) {
            if (kumpulanFile.files.length === 0) {
                e.preventDefault();
                alert('Silakan upload minimal 1 foto produk!');
            }
        });
    </script>
    <?php ob_end_flush(); ?>
</body>

</html>