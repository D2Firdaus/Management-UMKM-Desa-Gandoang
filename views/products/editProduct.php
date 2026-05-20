<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../controllers/productControllers/ProductController.php';

$id = $_GET['id'];
if (!$id) {
    header("Location: index.php");
    exit;
}

$controller = new ProductController($conn);
$product = $controller->getProductById($id);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Produk</title>

    <!-- Font Google -->
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
                    <h2 class="text-center fw-bold mb-5" style="color: #65835e;">Form Update Produk</h2>

                    <form action="<?= $product_controller_path ?>EditProduct.php?id=<?= $product['id_produk'] ?>" method="POST" enctype="multipart/form-data">

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Nama Product :</label>
                            <div class="col-sm-9">
                                <input type="text" name="nama_produk" class="form-control bg-light" placeholder="Asep Jalaludin" value="<?= htmlspecialchars($product['nama_produk']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Kategori :</label>
                            <div class="col-sm-9">
                                <input type="text" name="kategori" class="form-control bg-light" placeholder="perternakan" value="<?= htmlspecialchars($product['kategori']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Harga :</label>
                            <div class="col-sm-9">
                                <input type="number" name="harga" class="form-control bg-light" placeholder="10.000" value="<?= htmlspecialchars($product['harga']) ?>" required>
                            </div>
                        </div>

                        <div class="row mb-3 align-items-center">
                            <label class="col-sm-3 form-label text-end">Upload Foto :</label>
                            <div class="col-sm-9">
                                <div class="upload-area bg-light">
                                    <label for="foto" class="btn btn-sm btn-light border">
                                        <i class="bi bi-cloud-upload"></i> Upload
                                    </label>
                                    <input type="file" name="foto" id="foto" class="d-none" accept="image/png, image/jpeg, image/jpg, image/webp" onchange="previewFile()">

                                    <div id="file-preview" class="preview-box">
                                        <img src="../../asset/images/products/<?= htmlspecialchars($product['foto']) ?>" id="img-placeholder" style="width: 40px; height: 30px; object-fit: cover;">
                                        <div>
                                            <span id="file-name" class="d-block fw-bold">Jeruk.JPG</span>
                                            <span id="file-size" class="text-muted">156 Kb</span>
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
                                <select name="id_umkm" class="form-select bg-light" style="width: 200px;">
                                    <option value="1" <?= ($product['id_umkm'] == 1) ? 'selected' : ''; ?>>Konveksi</option>
                                    <option value="2" <?= ($product['id_umkm'] == 2) ? 'selected' : ''; ?>>Kuliner</option>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <label class="col-sm-3 form-label text-end pt-2">Deksripsi :</label>
                            <div class="col-sm-9">
                                <textarea name="deskripsi" class="form-control bg-light" rows="4" placeholder="Good"><?= htmlspecialchars($product['deskripsi']) ?></textarea>
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
                reader.onload = function(e) {
                    imgPlaceholder.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

        function removeFile() {
            document.querySelector('#foto').value = '';
            document.querySelector('#file-preview').classList.add('d-none');
        }
    </script>
</body>

</html>