<?php
ob_start();
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

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

    <!-- Font Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <!-- bootstrap -->
    <link href="<?= $asset_path ?>/boostrap/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- css -->
    <link href="<?= $asset_path ?>css/bantuan.css" rel="stylesheet">

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
                            <p>Lacak riwayat perjalanan dan status UMKM Anda.</p>
                        </div>
                    </div>

                    <!-- konten dan form journey akan diisi di sini -->

                </div>
            </div>
        </div>

    </div>

    <script src="<?= $asset_path ?>js/bantuan.js"></script>

    <?php ob_end_flush(); ?>
</body>

</html>
