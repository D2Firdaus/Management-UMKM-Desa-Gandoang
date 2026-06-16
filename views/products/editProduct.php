<?php
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$controller = new ProductController($conn);
$product    = $controller->getProductById($id);

if (!$product) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk - UMKM Gandoang</title>

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
                    <h2 class="text-center fw-bold mb-5" style="color: #65835e;">Form Update Produk</h2>

                    <?php if (isset($_GET['status']) && $_GET['status'] === 'image_required'): ?>
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <strong>Gagal!</strong> Foto produk wajib diupload minimal 1 foto.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?= $product_controller_path ?>EditProduct.php?id=<?= $product['id_produk'] ?>" method="POST" enctype="multipart/form-data" id="form-edit">

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Nama Produk :</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_produk" class="form-control bg-light"
                                    value="<?= htmlspecialchars($product['nama_produk']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Kategori :</label>
                            <div class="col-sm-9">
                                <input type="text" name="kategori" class="form-control bg-light"
                                    value="<?= htmlspecialchars($product['kategori']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Harga :</label>
                            <div class="col-sm-9">
                                <input type="number" name="harga" class="form-control bg-light"
                                    value="<?= htmlspecialchars($product['harga']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Upload Foto :</label>
                            <div class="col-sm-9">
                                <div class="upload-area bg-light">
                                    <label for="foto" class="btn btn-sm btn-light border">
                                        <i class="bi bi-cloud-upload"></i> Upload
                                    </label>
                                    <input type="file" name="foto[]" id="foto" class="d-none"
                                        accept="image/png,image/jpeg,image/jpg,image/webp"
                                        onchange="previewFiles()" multiple>

                                    <div id="preview-container" class="d-flex flex-row flex-wrap gap-2 mt-2">
                                        <div id="old-preview-container" class="d-flex flex-row flex-wrap gap-2">
                                            <?php
                                            if (!empty($product['foto']) && $product['foto'] !== 'default.jpg') {
                                                $foto_lama = explode(',', $product['foto']);
                                                foreach ($foto_lama as $index => $img) {
                                                    $img_trimmed  = trim($img);
                                                    if (empty($img_trimmed)) continue;
                                                    $nama_pendek  = (strlen($img_trimmed) > 7) ? substr($img_trimmed, 0, 7) . '...' : $img_trimmed;
                                                    $filePath     = __DIR__ . '/../../storage/images/products/' . $img_trimmed;
                                                    $file_size_kb = file_exists($filePath) ? number_format(filesize($filePath) / 1024, 1) : '0.0';
                                            ?>
                                                    <div class="preview-box d-flex align-items-center bg-white p-2 border rounded shadow-sm gap-2"
                                                        style="flex: 1 1 calc(33.333% - 10px); min-width: 150px; max-width: 100%;">
                                                        <input type="hidden" name="existing_foto[]" value="<?= htmlspecialchars($img_trimmed) ?>" required>
                                                        <img src="../../storage/images/products/<?= htmlspecialchars($img_trimmed) ?>"
                                                            style="width: 50px; height: 40px; object-fit: cover;" class="rounded">
                                                        <div class="flex-grow-1" style="min-width: 0;">
                                                            <span class="d-block fw-bold text-truncate small"><?= htmlspecialchars($nama_pendek) ?></span>
                                                            <span class="text-muted small"><?= $file_size_kb ?> Kb</span>
                                                        </div>
                                                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeOldFile(this)">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                            <?php
                                                }
                                            }
                                            ?>
                                        </div>
                                        <div id="new-preview-container" class="d-flex flex-row flex-wrap gap-2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Pilih UMKM :</label>
                            <div class="col-sm-9">
                                <select name="id_umkm" class="form-select bg-light" style="width: 200px;">
                                    <option value="1" <?= ($product['id_umkm'] == 1) ? 'selected' : ''; ?>>Konveksi</option>
                                    <option value="2" <?= ($product['id_umkm'] == 2) ? 'selected' : ''; ?>>Kuliner</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-3 form-label text-end pt-2">Deskripsi :</label>
                            <div class="col-sm-9">
                                <textarea name="deskripsi" class="form-control bg-light" rows="4"><?= htmlspecialchars($product['deskripsi']) ?></textarea>
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
            const fileBaru = input.files;
            const oldFilesCount = document.querySelectorAll('#old-preview-container .preview-box').length;

            Array.from(fileBaru).forEach(file => {
                if (oldFilesCount + kumpulanFile.items.length < 3) {
                    kumpulanFile.items.add(file);
                } else {
                    alert('Maksimal total foto yang diperbolehkan hanya 3 file!');
                }
            });

            input.files = kumpulanFile.files;
            renderPreview();
            checkEmptyPreview();
        }

        function renderPreview() {
            const container = document.querySelector('#new-preview-container');
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
                        <button type="button" class="btn btn-sm btn-outline-danger border-0" onclick="removeNewFile(${index})">
                            <i class="bi bi-trash"></i>
                        </button>
                    `;
                    container.appendChild(previewBox);
                };

                reader.readAsDataURL(file);
            });
        }

        function removeNewFile(index) {
            const input = document.querySelector('#foto');
            const kantongBaru = new DataTransfer();

            Array.from(kumpulanFile.files).forEach((file, i) => {
                if (i !== index) kantongBaru.items.add(file);
            });

            kumpulanFile = kantongBaru;
            input.files = kumpulanFile.files;
            renderPreview();
            checkEmptyPreview();
        }

        function removeOldFile(buttonElement) {
            buttonElement.closest('.preview-box').remove();
            checkEmptyPreview();
        }

        function checkEmptyPreview() {
            const oldFilesCount = document.querySelectorAll('#old-preview-container .preview-box').length;
            const newFilesCount = kumpulanFile.files.length;
            const defaultPreview = document.querySelector('#default-preview');

            if (oldFilesCount === 0 && newFilesCount === 0) {
                if (defaultPreview) defaultPreview.classList.remove('d-none');
            } else {
                if (defaultPreview) defaultPreview.classList.add('d-none');
            }
        }

        document.querySelector('#form-edit').addEventListener('submit', function(e) {
            const oldFilesCount = document.querySelectorAll('#old-preview-container .preview-box').length;
            const newFilesCount = kumpulanFile.files.length;

            if (oldFilesCount === 0 && newFilesCount === 0) {
                e.preventDefault();
                alert('Silakan upload minimal 1 foto produk!');
            }
        });
    </script>
</body>

</html>