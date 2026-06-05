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

// Limit settings: 25, 50, 100
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 25;
if (!in_array($limit, [25, 50, 100])) {
    $limit = 25;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}

$search = $_GET['search'] ?? '';
$filter_bulan = $_GET['bulan'] ?? '';
$offset = ($page - 1) * $limit;

// Ambil daftar bulan yang tersedia untuk filter
$bulan_query = $conn->query("
    SELECT DISTINCT DATE_FORMAT(action_time, '%Y-%m') AS bulan 
    FROM bantuan_history 
    ORDER BY bulan DESC
");
$daftar_bulan = $bulan_query->fetchAll(PDO::FETCH_COLUMN);

// Setup query where clauses
$where_clauses = ["1 = 1"];
$params = [];

if ($search !== '') {
    $where_clauses[] = "(
        h.jenis LIKE :search
        OR h.prioritas LIKE :search
        OR h.status LIKE :search
        OR h.deskripsi LIKE :search
        OR h.catatan LIKE :search
        OR umkm.nama_umkm LIKE :search
        OR validator.nama LIKE :search
    )";
    $params[':search'] = "%$search%";
}

if ($filter_bulan !== '' && preg_match('/^\d{4}-\d{2}$/', $filter_bulan)) {
    $where_clauses[] = "DATE_FORMAT(h.action_time, '%Y-%m') = :bulan";
    $params[':bulan'] = $filter_bulan;
}

$where_sql = implode(" AND ", $where_clauses);

// Count total data
$total_sql = "
    SELECT COUNT(*) AS total
    FROM bantuan_history h
    LEFT JOIN umkm ON h.id_umkm = umkm.id_umkm
    LEFT JOIN user AS validator ON h.id_validator = validator.id_user
    WHERE $where_sql
";
$total_stmt = $conn->prepare($total_sql);
$total_stmt->execute($params);
$total_row = $total_stmt->fetch(PDO::FETCH_ASSOC);
$total_data = $total_row['total'];
$total_page = max(1, ceil($total_data / $limit));

// Fetch main data
$sql = "
    SELECT 
        h.*,
        umkm.nama_umkm,
        validator.nama AS nama_validator
    FROM bantuan_history h
    LEFT JOIN umkm ON h.id_umkm = umkm.id_umkm
    LEFT JOIN user AS validator ON h.id_validator = validator.id_user
    WHERE $where_sql
    ORDER BY h.id_history DESC
    LIMIT $limit OFFSET $offset
";
$stmt = $conn->prepare($sql);
$stmt->execute($params);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper Nama Bulan Indo
function get_bulan_indo(string $date_str): string {
    $parts = explode('-', $date_str);
    if (count($parts) !== 2) return $date_str;
    $tahun = $parts[0];
    $bulan = (int)$parts[1];
    
    $nama_bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    
    return ($nama_bulan[$bulan] ?? 'Bulan') . ' ' . $tahun;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Perubahan Bantuan - Admin</title>

    <!-- bootstrap -->
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">
</head>

<body>

    <div class="wrapper">

        <!-- sidebar -->
        <?php require_once __DIR__ . '/../layouts/sidebar_admin.php'; ?>

        <div class="main">

            <!-- navbar -->
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <!-- content -->
            <div class="content">
                <div class="card-dashboard">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        <div>
                            <h2>Riwayat Perubahan Bantuan</h2>
                            <p>Log riwayat perubahan data pengajuan bantuan UMKM</p>
                        </div>
                    </div>

                    <!-- Filter & Search Panel -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center align-items-start mb-4 gap-3">
                        
                        <form method="get" class="d-flex align-items-center gap-3 flex-wrap">
                            <div>
                                Show
                                <select name="limit" class="form-select d-inline-block w-auto" onchange="this.form.submit()">
                                    <option value="25" <?= ($limit == 25) ? 'selected' : '' ?>>25</option>
                                    <option value="50" <?= ($limit == 50) ? 'selected' : '' ?>>50</option>
                                    <option value="100" <?= ($limit == 100) ? 'selected' : '' ?>>100</option>
                                </select>
                                entries
                            </div>
                            
                            <!-- Filter Bulan -->
                            <div class="d-flex align-items-center gap-2">
                                <label for="bulan" class="mb-0">Bulan:</label>
                                <select name="bulan" id="bulan" class="form-select w-auto" onchange="this.form.submit()">
                                    <option value="">Semua Bulan</option>
                                    <?php foreach ($daftar_bulan as $b): ?>
                                        <option value="<?= $b ?>" <?= ($filter_bulan === $b) ? 'selected' : '' ?>>
                                            <?= get_bulan_indo($b) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        </form>

                        <form method="get" class="d-flex gap-2">
                            <input type="hidden" name="limit" value="<?= $limit ?>">
                            <input type="hidden" name="bulan" value="<?= htmlspecialchars($filter_bulan) ?>">

                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                placeholder="Cari Log Riwayat..."
                                value="<?= htmlspecialchars($search) ?>">

                            <button type="submit" class="btn tombol_cari">
                                Cari
                            </button>
                        </form>

                    </div>

                    <!-- tabel -->
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>ID Log</th>
                                    <th>ID Kebutuhan</th>
                                    <th>Nama UMKM</th>
                                    <th>Jenis Bantuan</th>
                                    <th>Prioritas</th>
                                    <th>Deskripsi Kebutuhan</th>
                                    <th>Status</th>
                                    <th>Validator</th>
                                    <th>Catatan</th>
                                    <th>Tipe Aksi</th>
                                    <th>Waktu Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($data)): ?>
                                    <tr>
                                        <td colspan="11" class="text-center py-4 text-muted">Tidak ada data riwayat bantuan.</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($data as $row): ?>
                                        <tr>
                                            <td><?= $row['id_history']; ?></td>
                                            <td><?= $row['id_kebutuhan']; ?></td>
                                            <td><?= htmlspecialchars($row['nama_umkm'] ?? 'UMKM Telah Dihapus'); ?></td>
                                            <td><?= htmlspecialchars($row['jenis'] ?? '-'); ?></td>
                                            <td>
                                                <?php $prioritas = $row['prioritas'] ?? ''; ?>
                                                <span class="badge bg-<?= $prioritas === 'tinggi' ? 'danger' : ($prioritas === 'sedang' ? 'warning' : 'secondary') ?>">
                                                    <?= $prioritas ? ucfirst($prioritas) : '-'; ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['deskripsi'] ?? '-'); ?></td>
                                            <td>
                                                <?php $status = $row['status'] ?? ''; ?>
                                                <span class="<?= htmlspecialchars($status); ?>">
                                                    <?= $status ? ucfirst($status) : '-'; ?>
                                                </span>
                                            </td>
                                            <td><?= $row['id_validator'] ? htmlspecialchars($row['nama_validator'] ?? '') : 'Belum divalidasi'; ?></td>
                                            <td><?= $row['catatan'] ? htmlspecialchars($row['catatan']) : 'Tidak ada catatan'; ?></td>
                                            <td>
                                                <?php $action_type = $row['action_type'] ?? 'INSERT'; ?>
                                                <span class="badge bg-<?= $action_type === 'SOFT_DELETE' ? 'danger' : ($action_type === 'UPDATE' ? 'warning' : 'info') ?>">
                                                    <?= htmlspecialchars($action_type); ?>
                                                </span>
                                            </td>
                                            <td><?= htmlspecialchars($row['action_time'] ?? ''); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <br>

                    <!-- pagination -->
                    <div class="d-flex flex-column align-items-center mt-4">
                        <ul class="pagination custom-pagination justify-content-center">
                            <!-- tombol previous -->
                            <li class="page-item <?= ($page == 1) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>&limit=<?= $limit ?>&bulan=<?= urlencode($filter_bulan) ?>&search=<?= urlencode($search) ?>">
                                    Previous
                                </a>
                            </li>

                             <!-- nomor halaman -->
                             <?php 
                             $start_page = 1;
                             if ($page >= 5) {
                                 $start_page = floor($page / 5) * 5;
                             }
                             $end_page = min($start_page + 5, $total_page);
                             if ($start_page > 1 && $start_page > $total_page) {
                                 $start_page = max(1, $total_page - 5);
                             }
                             for ($i = $start_page; $i <= $end_page; $i++) { 
                             ?>
                                 <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
                                     <a class="page-link" href="?page=<?= $i ?>&limit=<?= $limit ?>&bulan=<?= urlencode($filter_bulan) ?>&search=<?= urlencode($search) ?>">
                                         <?= $i ?>
                                     </a>
                                 </li>
                             <?php } ?>

                            <!-- tombol next -->
                            <li class="page-item <?= ($page == $total_page) ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>&limit=<?= $limit ?>&bulan=<?= urlencode($filter_bulan) ?>&search=<?= urlencode($search) ?>">
                                    Next
                                </a>
                            </li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>

    </div>

    <script src="<?= $asset_path ?>/js/bantuan.js"></script>
</body>

</html>
