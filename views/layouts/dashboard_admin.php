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
$total_bantuan = $conn->query("SELECT COUNT(*) FROM bantuan WHERE status != 'dihapus'")->fetchColumn();
$total_bantuan_valid = $conn->query("SELECT COUNT(*) FROM bantuan WHERE status IN ('disetujui', 'ditolak')")->fetchColumn();

// Fungsi untuk membatasi jumlah data grafik batang (maksimal 7 item)
// Item di bawah batas minimal (kurang dari 2) atau di luar top 6 akan digabung menjadi "Lainnya"
function limit_chart_data(array $raw_data, int $limit = 7, int $min_value = 2): array
{
    arsort($raw_data);

    $processed = [];
    $others_sum = 0;

    foreach ($raw_data as $label => $val) {
        if ($label === 'Lainnya') {
            $others_sum += $val;
            continue;
        }

        if ($val < $min_value) {
            $others_sum += $val;
        } else {
            $processed[$label] = $val;
        }
    }

    if (empty($processed) && !empty($raw_data)) {
        $processed = $raw_data;
        $others_sum = 0;
    }

    if (count($processed) > $limit) {
        $top_items = [];
        $count = 0;
        foreach ($processed as $label => $val) {
            if ($count < ($limit - 1)) {
                $top_items[$label] = $val;
                $count++;
            } else {
                $others_sum += $val;
            }
        }
        $processed = $top_items;
    }

    if ($others_sum > 0) {
        if (isset($processed['Lainnya'])) {
            $processed['Lainnya'] += $others_sum;
        } else {
            $processed['Lainnya'] = $others_sum;
        }
    }

    return $processed;
}

// Query untuk Distribusi UMKM Berdasarkan Jenis Usaha
$jenis_usaha_query = $conn->query("SELECT jenis_usaha, COUNT(*) as jumlah FROM umkm GROUP BY jenis_usaha ORDER BY jumlah DESC");
$jenis_usaha_data = [];
while ($row = $jenis_usaha_query->fetch(PDO::FETCH_ASSOC)) {
    $label = ucwords(strtolower(trim($row['jenis_usaha'])));
    if ($label === '') {
        $label = 'Lainnya';
    }
    $jenis_usaha_data[$label] = (int)$row['jumlah'];
}
$jenis_usaha_data = limit_chart_data($jenis_usaha_data, 7, 2);

// Query untuk Status Validasi UMKM
$status_query = $conn->query("SELECT status, COUNT(*) as jumlah FROM umkm GROUP BY status");
$status_counts = ['Pending' => 0, 'Aktif' => 0, 'Nonaktif' => 0];
while ($row = $status_query->fetch(PDO::FETCH_ASSOC)) {
    if ($row['status'] === 'pending') {
        $status_counts['Pending'] = (int)$row['jumlah'];
    } elseif ($row['status'] === 'aktif') {
        $status_counts['Aktif'] = (int)$row['jumlah'];
    } elseif ($row['status'] === 'nonaktif') {
        $status_counts['Nonaktif'] = (int)$row['jumlah'];
    }
}

// Query untuk Distribusi Bantuan Berdasarkan Jenis Bantuan
$jenis_bantuan_query = $conn->query("SELECT jenis, COUNT(*) as jumlah FROM bantuan WHERE status != 'dihapus' GROUP BY jenis ORDER BY jumlah DESC");
$jenis_bantuan_data = [];
while ($row = $jenis_bantuan_query->fetch(PDO::FETCH_ASSOC)) {
    $label = ucwords(strtolower(trim($row['jenis'])));
    if ($label === '') {
        $label = 'Lainnya';
    }
    $jenis_bantuan_data[$label] = (int)$row['jumlah'];
}
$jenis_bantuan_data = limit_chart_data($jenis_bantuan_data, 7, 2);

// Query untuk Status Validasi Pengajuan Bantuan
$status_bantuan_query = $conn->query("SELECT status, COUNT(*) as jumlah FROM bantuan WHERE status != 'dihapus' GROUP BY status");
$status_bantuan_counts = ['Pending' => 0, 'Disetujui' => 0, 'Ditolak' => 0];
while ($row = $status_bantuan_query->fetch(PDO::FETCH_ASSOC)) {
    if ($row['status'] === 'pending') {
        $status_bantuan_counts['Pending'] = (int)$row['jumlah'];
    } elseif ($row['status'] === 'disetujui') {
        $status_bantuan_counts['Disetujui'] = (int)$row['jumlah'];
    } elseif ($row['status'] === 'ditolak') {
        $status_bantuan_counts['Ditolak'] = (int)$row['jumlah'];
    }
}

$json_jenis_labels = json_encode(array_keys($jenis_usaha_data));
$json_jenis_values = json_encode(array_values($jenis_usaha_data));

$json_status_labels = json_encode(array_keys($status_counts));
$json_status_values = json_encode(array_values($status_counts));

$json_jenis_bantuan_labels = json_encode(array_keys($jenis_bantuan_data));
$json_jenis_bantuan_values = json_encode(array_values($jenis_bantuan_data));

$json_status_bantuan_labels = json_encode(array_keys($status_bantuan_counts));
$json_status_bantuan_values = json_encode(array_values($status_bantuan_counts));
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - UMKM Gandoang</title>

    <!-- Bootstrap -->
    <link href="<?= $asset_path ?>boostrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">

    <style>
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #65835e;
            margin-bottom: 18px;
        }

        .stat-grid-admin {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .stat-box-admin {
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            background: #edf1e6;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.01);
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
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.04);
            border: 1px solid #eef0e5;
            display: flex;
            flex-direction: column;
            align-items: stretch;
            position: relative;
            min-height: 380px;
        }

        @media (max-width: 768px) {
            .stat-grid-admin {
                grid-template-columns: 1fr;
                gap: 15px;
            }

            .chart-grid-admin {
                grid-template-columns: 1fr;
                gap: 15px;
            }
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

                    <!-- Top Statistics Summary Cards -->
                    <div class="stat-grid-admin">
                        <div class="stat-box-admin">
                            <div class="stat-number-admin"><?= $total_pelaku ?></div>
                            <div class="stat-label-admin">Total Pelaku UMKM</div>
                        </div>
                        <div class="stat-box-admin">
                            <div class="stat-number-admin"><?= $total_umkm ?></div>
                            <div class="stat-label-admin">Total UMKM Terdaftar</div>
                        </div>
                        <div class="stat-box-admin">
                            <div class="stat-number-admin"><?= $total_bantuan ?></div>
                            <div class="stat-label-admin">Total Pengajuan Bantuan</div>
                        </div>
                        <div class="stat-box-admin">
                            <div class="stat-number-admin"><?= $total_bantuan_valid ?></div>
                            <div class="stat-label-admin">Bantuan Divalidasi</div>
                        </div>
                    </div>

                    <!-- Visualizations Section: UMKM -->
                    <div class="section-title mt-4 mb-3" style="font-size: 20px; border-bottom: 2px solid #edf1e6; padding-bottom: 8px;">
                        Visualisasi Data Profil UMKM
                    </div>

                    <div class="chart-grid-admin">
                        <!-- Left Chart (Bar Chart - UMKM) -->
                        <div class="chart-card-admin">
                            <h3 class="section-title text-center mb-3">Distribusi UMKM Berdasarkan Jenis Usaha</h3>
                            <div style="position: relative; flex-grow: 1; min-height: 250px; width: 100%;">
                                <canvas id="barChart"></canvas>
                            </div>
                        </div>

                        <!-- Right Chart (Doughnut Chart - UMKM) -->
                        <div class="chart-card-admin">
                            <h3 class="section-title text-center mb-3">Status Validasi UMKM</h3>
                            <div style="position: relative; flex-grow: 1; min-height: 250px; width: 100%; display: flex; justify-content: center; align-items: center;">
                                <canvas id="doughnutChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Visualizations Section: Bantuan -->
                    <div class="section-title mt-5 mb-3" style="font-size: 20px; border-bottom: 2px solid #edf1e6; padding-bottom: 8px;">
                        Visualisasi Pengajuan Bantuan
                    </div>

                    <div class="chart-grid-admin mb-4">
                        <!-- Left Chart (Bar Chart - Bantuan) -->
                        <div class="chart-card-admin">
                            <h3 class="section-title text-center mb-3">Distribusi Bantuan Berdasarkan Jenis Bantuan</h3>
                            <div style="position: relative; flex-grow: 1; min-height: 250px; width: 100%;">
                                <canvas id="barChartBantuan"></canvas>
                            </div>
                        </div>

                        <!-- Right Chart (Doughnut Chart - Bantuan) -->
                        <div class="chart-card-admin">
                            <h3 class="section-title text-center mb-3">Status Validasi Pengajuan Bantuan</h3>
                            <div style="position: relative; flex-grow: 1; min-height: 250px; width: 100%; display: flex; justify-content: center; align-items: center;">
                                <canvas id="doughnutChartBantuan"></canvas>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= $asset_path ?>boostrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <script>
        // Data dari PHP - UMKM
        const jenisLabels = <?= $json_jenis_labels ?>;
        const jenisValues = <?= $json_jenis_values ?>;
        const statusLabels = <?= $json_status_labels ?>;
        const statusValues = <?= $json_status_values ?>;

        // Data dari PHP - Bantuan
        const jenisBantuanLabels = <?= $json_jenis_bantuan_labels ?>;
        const jenisBantuanValues = <?= $json_jenis_bantuan_values ?>;
        const statusBantuanLabels = <?= $json_status_bantuan_labels ?>;
        const statusBantuanValues = <?= $json_status_bantuan_values ?>;

        // Color Palette (Soft & Varied)
        const softPalette = [
            '#638b69', // Sage Green
            '#FCB41A', // Soft Gold/Orange
            '#85a5af', // Soft Muted Blue/Teal
            '#ff6861', // Soft Coral/Red
            '#A38E34', // Soft Olive/Yellow-Brown
            '#a992b8', // Soft Lilac/Purple
            '#d8a47f' // Soft Peach/Copper
        ];

        // Bar Chart - UMKM (Jenis Usaha)
        const ctxBar = document.getElementById('barChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: jenisLabels,
                datasets: [{
                    label: 'Jumlah UMKM',
                    data: jenisValues,
                    backgroundColor: softPalette.slice(0, jenisLabels.length),
                    borderColor: softPalette.slice(0, jenisLabels.length),
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#edf1e6' // background header table
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Doughnut Chart - UMKM (Status Validasi)
        const ctxDoughnut = document.getElementById('doughnutChart').getContext('2d');
        new Chart(ctxDoughnut, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusValues,
                    backgroundColor: [
                        '#feeec2', // Pending (background status pending)
                        '#618764', // Aktif / Disetujui (status disetujui)
                        '#ff6861' // Nonaktif / Ditolak (status ditolak)
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                weight: '600'
                            },
                            padding: 15
                        }
                    }
                },
                cutout: '65%'
            }
        });

        // Bar Chart - Bantuan (Jenis Bantuan)
        const ctxBarBantuan = document.getElementById('barChartBantuan').getContext('2d');
        new Chart(ctxBarBantuan, {
            type: 'bar',
            data: {
                labels: jenisBantuanLabels,
                datasets: [{
                    label: 'Jumlah Pengajuan',
                    data: jenisBantuanValues,
                    backgroundColor: softPalette.slice(0, jenisBantuanLabels.length),
                    borderColor: softPalette.slice(0, jenisBantuanLabels.length),
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            precision: 0
                        },
                        grid: {
                            color: '#edf1e6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Doughnut Chart - Bantuan (Status Validasi Bantuan)
        const ctxDoughnutBantuan = document.getElementById('doughnutChartBantuan').getContext('2d');
        new Chart(ctxDoughnutBantuan, {
            type: 'doughnut',
            data: {
                labels: statusBantuanLabels,
                datasets: [{
                    data: statusBantuanValues,
                    backgroundColor: [
                        '#feeec2', // Pending
                        '#618764', // Disetujui
                        '#ff6861' // Ditolak
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                weight: '600'
                            },
                            padding: 15
                        }
                    }
                },
                cutout: '65%'
            }
        });
    </script>
</body>

</html>