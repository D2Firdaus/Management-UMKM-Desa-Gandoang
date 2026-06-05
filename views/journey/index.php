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

// Proses hapus jika ada parameter delete
if (isset($_GET['delete'])) {
    $journeyController->delete((int)$_GET['delete']);
}

// Ambil data untuk index
$data = $journeyController->index();
$journeys = $data['journeys'];
$search = $data['search'];
$per_page = $data['per_page'];
$current_page = $data['current_page'];
$total_pages = $data['total_pages'];

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

                    <?php if (isset($_GET['status'])): ?>
                        <?php if ($_GET['status'] === 'success'): ?>
                            <div class="alert alert-success">Data journey berhasil ditambahkan!</div>
                        <?php elseif ($_GET['status'] === 'updated'): ?>
                            <div class="alert alert-success">Data journey berhasil diperbarui!</div>
                        <?php elseif ($_GET['status'] === 'deleted'): ?>
                            <div class="alert alert-success">Data journey berhasil dihapus!</div>
                        <?php elseif ($_GET['status'] === 'error'): ?>
                            <div class="alert alert-danger">Terjadi kesalahan. Data tidak ditemukan atau gagal diproses.</div>
                        <?php endif; ?>
                    <?php endif; ?>

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
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                                  <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    <div class="table-responsive bg-light p-3 rounded shadow-sm border border-success-subtle mb-3">
                        <table class="table table-borderless table-hover text-center align-middle mb-0 journey-table">
                            <thead class="text-secondary table-light-green">
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
                                                <img src="<?= $asset_path ?>images/journey/<?= htmlspecialchars($row['foto']) ?>" alt="Foto Journey" class="journey-photo">
                                            </td>
                                            <td>
                                                <a href="edit.php?id=<?= $row['id_journey'] ?>" class="btn btn-warning btn-sm btn-icon text-white">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                                                    </svg>
                                                </a>
                                                <a href="index.php?delete=<?= $row['id_journey'] ?>" class="btn btn-danger btn-sm btn-icon" onclick="return confirm('Yakin ingin menghapus journey ini?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                                      <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z"/>
                                                      <path d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z"/>
                                                    </svg>
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

    <!-- js scripts -->
    <script src="<?= $asset_path ?>/boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <?php ob_end_flush(); ?>
</body>

</html>
