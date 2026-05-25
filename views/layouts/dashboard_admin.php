<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Proteksi: harus login dan role admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

// Data ringkasan admin
$total_pelaku = $conn->query("SELECT COUNT(*) FROM user WHERE role = 'umkm'")->fetchColumn();
$total_umkm   = $conn->query("SELECT COUNT(*) FROM umkm")->fetchColumn();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UMKM Gandoang</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">

    <style>
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #2d2d2d;
            margin-bottom: 18px;
        }

        .stat-grid-admin {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-box-admin {
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            background: #edf1e6;
            box-shadow: 0 1px 6px rgba(0,0,0,0.01);
        }

        .stat-box-admin .stat-number-admin {
            font-size: 54px;
            font-weight: 700;
            color: #000;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .stat-box-admin .stat-label-admin {
            font-size: 16px;
            color: #222;
            font-weight: 600;
        }

        .chart-grid-admin {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 20px;
            margin-top: 20px;
        }

        .chart-card-admin {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            border: 1px solid #eef0e5;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
            min-height: 380px;
        }

        .doughnut-chart {
            width: 190px;
            height: 190px;
            border-radius: 50%;
            background: conic-gradient(#e2edd5 0% 50%, #fcfbf0 50% 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.02);
        }

        .doughnut-inner {
            width: 110px;
            height: 110px;
            background: white;
            border-radius: 50%;
        }

        @media (max-width: 768px) {
            .stat-grid-admin { grid-template-columns: 1fr; gap: 15px; }
            .chart-grid-admin { grid-template-columns: 1fr; gap: 15px; }
        }
    </style>
</head>

<body>
    <div class="wrapper">

        <!-- Sidebar Admin -->
        <?php require_once __DIR__ . '/sidebar_admin.php'; ?>

        <!-- Main -->
        <div class="main">

            <!-- Navbar -->
            <?php require_once __DIR__ . '/navbar_user.php'; ?>

            <!-- Content -->
            <div class="content">

                <div class="card-dashboard">

                    <div class="stat-grid-admin">
                        <div class="stat-box-admin">
                            <div class="stat-number-admin"><?= $total_pelaku ?></div>
                            <div class="stat-label-admin">Total Pelaku UMKM</div>
                        </div>
                        <div class="stat-box-admin">
                            <div class="stat-number-admin"><?= $total_umkm ?></div>
                            <div class="stat-label-admin">Total UMKM Terdaftar</div>
                        </div>
                    </div>

                    <!-- Visualizations Side-by-Side Grid -->
                    <div class="chart-grid-admin">
                        <!-- Left Chart (Bar Chart) -->
                        <div class="chart-card-admin">
                            <div class="chart-year" style="color: #65835e; font-weight: 600; font-size: 16px; margin-bottom: 20px;">2026</div>
                            <div style="display: flex; width: 100%; height: 200px; align-items: flex-end; position: relative;">
                                <!-- Y Axis Labels -->
                                <div style="display: flex; flex-direction: column; justify-content: space-between; height: 100%; font-size: 12px; color: #555; padding-right: 12px; text-align: right; width: 75px; border-right: 1px solid #ccc; padding-bottom: 5px;">
                                    <div>$800,000</div>
                                    <div>$600,000</div>
                                    <div>$400,000</div>
                                    <div>$200,000</div>
                                    <div>$100,000</div>
                                </div>
                                
                                <!-- Bars Area -->
                                <div style="flex: 1; display: flex; justify-content: space-around; align-items: flex-end; height: 100%; position: relative; border-bottom: 1px solid #ccc; padding-bottom: 0;">
                                    <!-- Bar 1 (Pertanian) -->
                                    <div style="display: flex; flex-direction: column; align-items: center; width: 65px; height: 100%; justify-content: flex-end;">
                                        <span style="font-size: 11px; font-weight: 700; color: #000; margin-bottom: 4px;">$742,000</span>
                                        <div style="height: 90%; width: 40px; background: #e2edd5; border-radius: 6px 6px 0 0;"></div>
                                    </div>
                                    <!-- Bar 2 (Peternakan) -->
                                    <div style="display: flex; flex-direction: column; align-items: center; width: 65px; height: 100%; justify-content: flex-end;">
                                        <span style="font-size: 11px; font-weight: 700; color: #000; margin-bottom: 4px;">$542,000</span>
                                        <div style="height: 65%; width: 40px; background: #fcfbf0; border-radius: 6px 6px 0 0; border: 1px solid #f2edd5;"></div>
                                    </div>
                                </div>
                            </div>
                            <!-- X Axis Labels -->
                            <div style="display: flex; width: 100%; justify-content: flex-end; margin-top: 5px;">
                                <div style="width: calc(100% - 75px); display: flex; justify-content: space-around; font-size: 12px; font-weight: 600; color: #333;">
                                    <div style="width: 65px; text-align: center;">Pertanian</div>
                                    <div style="width: 65px; text-align: center;">Peternakan</div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Chart (Doughnut Chart) -->
                        <div class="chart-card-admin">
                            <div class="doughnut-chart">
                                <div class="doughnut-inner"></div>
                                <div style="position: absolute; left: 30px; top: 85px; font-size: 12px; font-weight: 700; color: #000;">50%</div>
                                <div style="position: absolute; right: 30px; top: 85px; font-size: 12px; font-weight: 700; color: #000;">50%</div>
                            </div>
                            <!-- Legend -->
                            <div style="display: flex; gap: 20px; margin-top: 25px; font-size: 13px; font-weight: 600; color: #333;">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="display: inline-block; width: 25px; height: 12px; background: #e2edd5; border-radius: 3px;"></span>
                                    Pertanian
                                </div>
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span style="display: inline-block; width: 25px; height: 12px; background: #fcfbf0; border: 1px solid #f2edd5; border-radius: 3px;"></span>
                                    Peternakan
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>
</body>

</html>
