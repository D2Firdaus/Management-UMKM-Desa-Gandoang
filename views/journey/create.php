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
                                    <input type="date" name="tanggal" id="tanggal" class="form-control" required>
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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up-fill me-2" viewBox="0 0 16 16">
                                              <path d="M8 2a5.53 5.53 0 0 0-3.594 1.342c-.766.66-1.321 1.52-1.464 2.383C1.266 6.095 0 7.555 0 9.318 0 11.366 1.708 13 3.781 13h8.906C14.502 13 16 11.57 16 9.773c0-1.636-1.242-2.969-2.834-3.194C12.923 3.999 10.69 2 8 2zm2.354 5.146a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2z"/>
                                            </svg>
                                            Upload
                                        </button>
                                        <input type="file" name="foto" id="foto" class="d-none" accept=".jpg,.jpeg,.png,.webp" onchange="previewImage(this)">
                                        
                                        <!-- Image Preview Box -->
                                        <div id="imagePreviewContainer" class="image-preview-box d-none align-items-center flex-grow-1 p-2 border rounded">
                                            <img id="imagePreview" src="#" alt="Preview" class="preview-img me-3">
                                            <div class="preview-info flex-grow-1">
                                                <div id="fileName" class="fw-bold file-name-text text-truncate"></div>
                                                <div id="fileSize" class="text-muted small"></div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="removeImage()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                  <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                                </svg>
                                            </button>
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-floppy-fill me-2" viewBox="0 0 16 16">
                                  <path d="M0 1.5A1.5 1.5 0 0 1 1.5 0H3v5.5A1.5 1.5 0 0 0 4.5 7h7A1.5 1.5 0 0 0 13 5.5V0h.086a1.5 1.5 0 0 1 1.06.44l1.415 1.414A1.5 1.5 0 0 1 16 2.914V14.5a1.5 1.5 0 0 1-1.5 1.5H14v-5.5A1.5 1.5 0 0 0 12.5 9h-9A1.5 1.5 0 0 0 2 10.5V16h-.5A1.5 1.5 0 0 1 0 14.5v-13Z"/>
                                  <path d="M3 16h10v-5.5a.5.5 0 0 0-.5-.5h-9a.5.5 0 0 0-.5.5V16Zm9-16H4v5.5a.5.5 0 0 0 .5.5h7a.5.5 0 0 0 .5-.5V0ZM9 1h2v4H9V1Z"/>
                                </svg>
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
