<?php

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../controllers/journeyControllers/JourneyController.php';

ob_start();

// ─── Auth Guard ───────────────────────────────────────────────────────────────
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_user = $_SESSION['user_id'] ?? null;

if (!$id_user) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}

$journeyController = new JourneyController($conn);

// Proses hapus jika ada request POST action delete
if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id_journey'])) {
    $journeyController->delete((int)$_POST['id_journey']);
}

// Ambil data untuk index
$data = $journeyController->index();
$journeys = $data['journeys'];
$search = $data['search'];
$per_page = $data['per_page'];
$current_page = $data['current_page'];
$total_pages = $data['total_pages'];

// ─── Popup helper ─────────────────────────────────────────────────────────────
function journeyStatusPopup(string $asset_path): void
{
    $status_key = $_GET['status'] ?? '';

    $popups = [
        'tambah_sukses' => [
            'icon'  => '<i class="bi bi-check-circle-fill mb-3" style="font-size: 64px; color: #28a745;"></i>',
            'title' => 'Berhasil<br>Menambahkan',
            'msg'   => 'Data Journey Berhasil Ditambahkan'
        ],
        'edit_sukses' => [
            'icon'  => '<i class="bi bi-check-circle-fill mb-3" style="font-size: 64px; color: #28a745;"></i>',
            'title' => 'Berhasil<br>Memperbarui',
            'msg'   => 'Data Journey Berhasil Diperbarui'
        ],
        'hapus_sukses' => [
            'icon'  => '<i class="bi bi-check-circle-fill mb-3" style="font-size: 64px; color: #28a745;"></i>',
            'title' => 'Berhasil<br>Menghapus',
            'msg'   => 'Data Journey Berhasil Dihapus'
        ],
        'error' => [
            'icon'  => '<i class="bi bi-x-circle-fill mb-3" style="font-size: 64px; color: #dc3545;"></i>',
            'title' => 'Terjadi<br>Kesalahan',
            'msg'   => 'Data tidak ditemukan atau gagal diproses.'
        ],
    ];

    if (!isset($popups[$status_key])) return;

    $d = $popups[$status_key];
    ?>
    <div class="alert_sukses_menambah" id="statusPopup">
        <div class="box_sukses_menambah text-center">
            <div class="icon_sukses_menambah">
                <?= $d['icon'] ?>
            </div>
            <h2><?= $d['title'] ?></h2>
            <p><?= htmlspecialchars($d['msg']) ?></p>
            <a href="index.php" class="tombol_sukses_menambah" style="text-decoration:none;">Tutup</a>
        </div>
    </div>
    <style>
        /* Minimal style adjustments to make svg look like the original img */
        .box_sukses_menambah { padding: 30px; }
        .icon_sukses_menambah { margin-bottom: 15px; }
    </style>
    <?php
}

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
    <title>Journey - UMKM Gandoang</title>

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
                <div class="card-dashboard">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        <div>
                            <h2>Journey</h2>
                            <p>Kelola Timeline Perjalanan UMKM Anda</p>
                        </div>
                    </div>



                    <div class="table-controls d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
                        <form method="GET" class="d-flex align-items-center mb-2 mb-md-0">
                            <label class="me-2">Show</label>
                            <select name="show" class="form-select form-select-sm d-inline-block w-auto me-2" onchange="this.form.submit()">
                                <option value="3" <?= $per_page == 3 ? 'selected' : '' ?>>3</option>
                                <option value="5" <?= $per_page == 5 ? 'selected' : '' ?>>5</option>
                                <option value="10" <?= $per_page == 10 ? 'selected' : '' ?>>10</option>
                            </select>
                            <label>entries</label>
                            <?php if (!empty($search)): ?>
                                <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                            <?php endif; ?>
                        </form>

                        <form method="GET" class="search-form position-relative">
                            <input type="text" name="search" class="form-control form-control-sm pe-4" placeholder="Cari journey...." value="<?= htmlspecialchars($search) ?>">
                            <?php if (!empty($per_page)): ?>
                                <input type="hidden" name="show" value="<?= $per_page ?>">
                            <?php endif; ?>
                            <button type="submit" class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-1 bg-transparent border-0 text-muted">
                                <i class="bi bi-search"></i>
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>nama_umkm</th>
                                    <th>Tanggal</th>
                                    <th>Deskripsi</th>
                                    <th>foto</th>
                                    <th>aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($journeys) > 0): ?>
                                    <?php 
                                    $no = ($current_page - 1) * $per_page + 1;
                                    foreach ($journeys as $row): 
                                    ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= htmlspecialchars($row['nama_umkm']) ?></td>
                                            <td><?= date('d-m-Y', strtotime($row['tanggal'])) ?></td>
                                            <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                                            <td>
                                                <img src="<?= BASE_URL ?>storage/images/journey/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Journey" class="journey-photo" data-bs-toggle="modal" data-bs-target="#imageModal" onclick="document.getElementById('modalImage').src=this.src" style="cursor: pointer; max-width: 100px;">
                                            </td>
                                            <td>
                                                <a href="edit.php?id=<?= $row['id_journey'] ?>" class="btn btn-warning btn-sm btn-icon text-white">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                                <a href="hapus.php?id=<?= $row['id_journey'] ?>" class="btn btn-danger btn-sm btn-icon">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Data journey tidak ditemukan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="position-relative d-flex justify-content-center align-items-center mt-3 flex-column flex-md-row">
                        <!-- Pagination -->
                        <?php if (count($journeys) > 0): ?>
                        <nav aria-label="Page navigation" class="mb-3 mb-md-0">
                            <ul class="pagination pagination-sm m-0 custom-pagination">
                                <?php if ($current_page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $current_page - 1 ?>&show=<?= $per_page ?>&search=<?= urlencode($search) ?>">previous</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">previous</span>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i == $current_page) ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&show=<?= $per_page ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($current_page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?= $current_page + 1 ?>&show=<?= $per_page ?>&search=<?= urlencode($search) ?>">next</a>
                                    </li>
                                <?php else: ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">next</span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>

                        <!-- Button Tambah Journey -->
                        <div class="position-absolute end-0 d-none d-md-block">
                            <a href="create.php" class="btn btn-success btn-tambah-journey px-4">
                                + Tambah Journey
                            </a>
                        </div>
                        <div class="d-md-none w-100 text-center">
                            <a href="create.php" class="btn btn-success btn-tambah-journey px-4 w-100">
                                + Tambah Journey
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

    <!-- Modal Image -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
          <div class="modal-body text-center position-relative p-0">
            <button type="button" class="btn-close btn-close-white position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close" style="z-index: 1055; background-color: rgba(0,0,0,0.5); border-radius: 50%;"></button>
            <img id="modalImage" src="" class="img-fluid rounded" alt="Enlarged Image" style="max-height: 90vh;">
          </div>
        </div>
      </div>
    </div>

    <!-- Popup Notifikasi -->
    <?php journeyStatusPopup($asset_path); ?>

    <!-- js scripts -->
    <script src="<?= $asset_path ?>/boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <script>
        // Auto-dismiss popup setelah 4 detik
        setTimeout(function () {
            const popup = document.getElementById('statusPopup');
            if (popup) {
                popup.style.display = 'none';
                const url = new URL(window.location.href);
                url.searchParams.delete('status');
                window.history.replaceState({}, document.title, url.pathname + url.search);
            }
        }, 4000);
    </script>

    <?php ob_end_flush(); ?>
</body>

</html>
