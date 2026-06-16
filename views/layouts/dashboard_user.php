<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Proteksi: harus login dulu
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ../auth/login.php');
    exit;
}

// Jika admin tersasar ke sini, arahkan ke dashboard admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    header('Location: dashboard_admin.php');
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

$id_user = $_SESSION['user_id'];

// Total UMKM milik user ini
$stmt = $conn->prepare("SELECT COUNT(*) FROM umkm WHERE id_user = :id_user");
$stmt->execute([':id_user' => $id_user]);
$total_umkm = $stmt->fetchColumn();

// Total produk dari semua UMKM milik user ini
$stmt = $conn->prepare("
    SELECT COUNT(*) FROM produk
    LEFT JOIN umkm ON produk.id_umkm = umkm.id_umkm
    WHERE umkm.id_user = :id_user AND produk.status = 'aktif'
");
$stmt->execute([':id_user' => $id_user]);
$total_produk = $stmt->fetchColumn();

// Data UMKM milik user beserta jumlah produknya
$stmt = $conn->prepare("
    SELECT umkm.id_umkm, umkm.nama_umkm, COUNT(produk.id_produk) AS jumlah_produk
    FROM umkm
    LEFT JOIN produk ON umkm.id_umkm = produk.id_umkm
    WHERE umkm.id_user = :id_user AND produk.status = 'aktif'
    GROUP BY umkm.id_umkm, umkm.nama_umkm
    ORDER BY umkm.id_umkm ASC
");
$stmt->execute([':id_user' => $id_user]);
$umkm_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Warna untuk setiap kartu UMKM (bergantian)
$card_colors = [
    ['bg' => '#e8f2e8', 'num' => '#4a7c59'],
    ['bg' => '#e8eaf6', 'num' => '#3949ab'],
    ['bg' => '#fff8e1', 'num' => '#f57f17'],
    ['bg' => '#fce4ec', 'num' => '#c62828'],
    ['bg' => '#e0f2f1', 'num' => '#00695c'],
];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Gandoang</title>

    <!-- Bootstrap -->
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">

    <style>
        /* Judul Section */
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 18px;
        }

        /* Grid Kartu Statistik */
        .stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .stat-box {
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
        }

        .stat-box .stat-number {
            font-size: 48px;
            font-weight: 700;
            line-height: 1;
            margin-bottom: 10px;
        }

        .stat-box .stat-label {
            font-size: 14px;
            color: #555;
            font-weight: 500;
        }

        /* Warna Kartu Rekapitulasi */
        .stat-box.green {
            background: #e8f2e8;
        }

        .stat-box.green .stat-number {
            color: #4a7c59;
        }

        .stat-box.orange {
            background: #fdf3e7;
        }

        .stat-box.orange .stat-number {
            color: #e07b00;
        }

        /* Divider */
        .section-divider {
            border: none;
            border-top: 1px solid #f0ede5;
            margin: 10px 0 25px;
        }

        /* UMKM Section */
        .umkm-section {
            margin-bottom: 20px;
        }

        .umkm-name {
            font-size: 16px;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 14px;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #aaa;
            font-size: 15px;
        }

        .empty-state .empty-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }
    </style>
</head>

<body>
    <div class="wrapper">

        <!-- Sidebar -->
        <?php require_once __DIR__ . '/sidebar_user.php'; ?>

        <!-- Main -->
        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/navbar_user.php'; ?>

            <!-- Content -->
            <div class="content">

                <div class="card-dashboard">

                    <!-- Rekapitulasi -->
                    <div class="section-title">Rekapitulasi</div>
                    <div class="stat-grid">
                        <div class="stat-box green">
                            <div class="stat-number"><?= $total_umkm ?></div>
                            <div class="stat-label">UMKM</div>
                        </div>
                        <div class="stat-box orange">
                            <div class="stat-number"><?= $total_produk ?></div>
                            <div class="stat-label">Produk Terdaftar</div>
                        </div>
                    </div>

                    <hr class="section-divider">

                    <!-- Per UMKM -->
                    <?php if (empty($umkm_list)): ?>
                        <div class="empty-state">
                            <p>Anda belum memiliki UMKM yang terdaftar.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($umkm_list as $index => $umkm): ?>
                            <?php $color = $card_colors[$index % count($card_colors)]; ?>
                            <div class="umkm-section">
                                <div class="umkm-name"><?= htmlspecialchars($umkm['nama_umkm']) ?></div>
                                <div class="stat-grid">
                                    <div class="stat-box" style="background: <?= $color['bg'] ?>;">
                                        <div class="stat-number" style="color: <?= $color['num'] ?>;">
                                            <?= $umkm['jumlah_produk'] ?>
                                        </div>
                                        <div class="stat-label">Produk Terdaftar</div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>

            </div>
            <!-- Akhir Content -->

        </div>
        <!-- Akhir Main -->

    </div>

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
</body>

</html>