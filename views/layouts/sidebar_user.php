<?php

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

?>

<style>
    .sidebar {
        width: 280px;
        min-width: 280px;
        height: 100vh;
        background: #f8f7eb;
        padding: 25px;
        transition: 0.3s;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        overflow: hidden;
    }

    .sidebar.close {
        margin-left: -280px;
    }

    /* Overlay backdrop untuk mobile */
    .sidebar-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.4);
        z-index: 199;
    }

    .sidebar-overlay.show {
        display: block;
    }

    .sidebar_atas {
        display: flex;
        gap: 3px;
        align-items: center;
        margin-bottom: 40px;
    }

    .foto_profil {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        object-fit: cover;
    }

    .sidebar_atas h2 {
        color: #65835e;
        font-size: 24px;
        margin-bottom: 5px;
    }

    .sidebar_atas p {
        font-size: 14px;
        color: #444;
        margin-bottom: 0;
    }

    .isi_menu {
        display: flex;
        flex-direction: column;
        gap: 15px;
        position: relative;
    }

    .isi_menu a {
        position: relative;
        text-decoration: none;
        color: #65835e;
        padding: 14px 18px;
        border-radius: 12px;
        transition: 0.3s;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .isi_menu a:hover,
    .isi_menu a.active {
        background: #6d8d69;
        color: white;
    }

    .isi_menu a.active::before {
        content: '';
        position: absolute;
        left: -18px;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 35px;
        background-color: #65835e;
        border-radius: 4px;
    }

    /* Gambar Bawah Sidebar */
    .sidebar_bawah {
        margin-left: -25px;
        margin-right: -25px;
        margin-bottom: -25px;
    }

    .sidebar_bawah img {
        width: 100%;
        display: block;
    }
    /* Akhir Gambar Bawah Sidebar */

    /* === RESPONSIVE MOBILE === */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            margin-left: 0;
            z-index: 1000;
            overflow-y: auto;
            width: 280px !important;
            min-width: 280px !important;
            padding: 25px !important;
        }

        .sidebar.close {
            margin-left: -280px;
        }

        .sidebar-close-btn {
            display: block;
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 32px;
            color: #65835e;
            cursor: pointer;
            line-height: 1;
            z-index: 1010;
        }
    }

    /* Desktop close button and overlay hidden */
    @media (min-width: 769px) {
        .sidebar-close-btn {
            display: none;
        }
        .sidebar-overlay {
            display: none !important;
        }
    }
</style>

<div class="sidebar">
    <!-- Close button for mobile -->
    <button class="sidebar-close-btn" id="sidebarCloseBtn">&times;</button>

    <!-- pembungkus utama -->
    <div>

        <!-- awal sidebar atas -->
        <div class="sidebar_atas">
            <img src="<?= $asset_path ?>/images/profile.png" class="foto_profil">

            <div>
                <h2><?= htmlspecialchars($_SESSION['user_nama'] ?? 'Pengguna') ?></h2>
                <p>Your Personal Account</p>
            </div>
        </div>
        <!-- akhir sidebar atas -->

        <!-- awal isi menu -->
        <?php
        $current_uri = $_SERVER['REQUEST_URI'];
        $is_dashboard = strpos($current_uri, '/views/layouts/dashboard_user.php') !== false;
        ?>
        <div class="isi_menu">

            <a href="<?= $view_path ?>layouts/dashboard_user.php" class="<?= $is_dashboard ? 'active' : '' ?>">
                <img src="<?= $asset_path ?>icon/home.png" style="padding:5px" width="30px" height="30px">
                Dashboard
            </a>

            <a href="<?= $view_path ?>profile/index.php" class="<?= strpos($current_uri, '/views/profile') !== false ? 'active' : '' ?>">
                <img src="<?= $asset_path ?>icon/profile.png" style="padding:5px" width="30px" height="30px">
                Profile
            </a>

            <a href="<?= $view_path ?>umkm/index.php" class="<?= strpos($current_uri, '/views/umkm') !== false ? 'active' : '' ?>">
                <img src="<?= $asset_path ?>icon/umkm.png" style="padding:5px" width="30px" height="30px">
                Profile UMKM
            </a>

            <a href="<?= $view_path ?>products/index.php" class="<?= strpos($current_uri, '/views/products') !== false ? 'active' : '' ?>">
                <img src="<?= $asset_path ?>icon/produk.png" style="padding:5px" width="30px" height="30px">
                Detail Produk
            </a>

            <a href="<?= $view_path ?>bantuan/index.php" class="<?= strpos($current_uri, '/views/bantuan') !== false ? 'active' : '' ?>">
                <img src="<?= $asset_path ?>icon/bantuan.png" style="padding:5px" width="30px" height="30px">
                Ajukan Bantuan
            </a>

            <a href="<?= $view_path ?>journey/index.php" class="<?= strpos($current_uri, '/views/journey') !== false ? 'active' : '' ?>">
                <img src="<?= $asset_path ?>icon/journey.png" style="padding:5px" width="30px" height="30px">
                Journey
            </a>

        </div>
        <!-- akhir isi menu -->

    </div>

    <!-- bawah -->
    <div class="sidebar_bawah">
        <img src="<?= $asset_path ?>images/sidebar.png">
    </div>
    <!-- akhir bawah -->

</div>
<!-- akhir pembungkus utama -->