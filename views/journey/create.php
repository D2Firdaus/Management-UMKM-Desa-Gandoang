<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../controllers/journeyControllers/JourneyController.php';

$journeyController = new JourneyController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $journeyController->store();
}

$umkm_list = $journeyController->create();

$sidebar_file = (
    isset($_SESSION['user_role']) &&
    $_SESSION['user_role'] === 'admin'
)
? 'sidebar_admin.php'
: 'sidebar_user.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Journey - UMKM Gandoang</title>

    <!-- bootstrap -->
    <link href="<?= $asset_path ?>/boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= $asset_path ?>icon/bootstrap-icons.min.css">
    
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">
    <!-- custom journey css -->
    <link href="<?= $asset_path ?>css/journey.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">

        <!-- sidebar -->
        <?php require_once __DIR__ . '/../layouts/' . $sidebar_file; ?>
        <!-- akhir sidebar -->
        
        <!-- Content -->
        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>
            <!-- Akhir Navbar -->
            
            <div class="content">
                <div class="form-container">
                    <div class="form-header text-center mb-4">
                        <h3 class="form-title">Form Tambah Journey</h3>
                    </div>

                    <form action="create.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="row align-items-center mb-3">
                            <div class="col-md-3">
                                <label for="tanggal" class="form-label mb-0 fw-bold">Tanggal</label>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex align-items-center">
                                    <span class="me-3">:</span>
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" onclick="this.showPicker()" required>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label for="deskripsi" class="form-label mb-0 fw-bold mt-2">Deksripsi</label>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex">
                                    <span class="me-3 mt-2">:</span>
                                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" placeholder="Pendirian UMKM" required></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center mb-3">
                            <div class="col-md-3">
                                <label for="foto" class="form-label mb-0 fw-bold">Upload Foto</label>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex align-items-center">
                                    <span class="me-3">:</span>
                                    <!-- Custom file input button -->
                                    <div class="file-upload-wrapper w-100 d-flex flex-column flex-md-row gap-3">
                                        <button type="button" class="btn btn-upload-custom d-flex align-items-center justify-content-center" onclick="document.getElementById('foto').click()">
                                            <i class="bi bi-cloud-arrow-up-fill me-2"></i>
                                            Upload
                                        </button>
                                        <input type="file" name="foto" id="foto" class="d-none" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(this)">
                                        
                                        <!-- Image Preview Box -->
                                        <div id="imagePreviewContainer" class="d-none flex-grow-1">
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="image-preview-box align-items-center p-2 border rounded d-flex bg-white flex-grow-0" style="min-width: 250px;">
                                                    <img id="imagePreview" src="#" alt="Preview" class="preview-img me-3" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                                    <div class="preview-info" style="min-width: 0;">
                                                        <div id="fileName" class="fw-bold file-name-text text-truncate" style="font-size: 14px;"></div>
                                                        <div id="fileSize" class="text-muted small" style="font-size: 12px;"></div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-outline-danger d-flex align-items-center justify-content-center" id="btnRemoveImage" onclick="removeImage()" style="width: 38px; height: 38px; padding: 0; flex-shrink: 0;">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row align-items-center mb-4">
                            <div class="col-md-3">
                                <label for="id_umkm" class="form-label mb-0 fw-bold">Pilih UMKM</label>
                            </div>
                            <div class="col-md-9">
                                <div class="d-flex align-items-center">
                                    <span class="me-3">:</span>
                                    <select name="id_umkm" id="id_umkm" class="form-select w-auto" required>
                                        <?php foreach($umkm_list as $umkm): ?>
                                            <option value="<?= $umkm['id_umkm'] ?>"><?= htmlspecialchars($umkm['nama_umkm']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-5">
                            <a href="index.php" class="btn btn-batal px-5 py-2">Batal</a>
                            <button type="submit" class="btn btn-simpan px-4 py-2 d-flex align-items-center">
                                                <i class="bi bi-floppy-fill me-2"></i>
                                                Simpan
                                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- js scripts -->
    <script src="<?= $asset_path ?>/boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
    <script src="<?= $asset_path ?>js/journey.js"></script>

    <?php ob_end_flush(); ?>
</body>
</html>
