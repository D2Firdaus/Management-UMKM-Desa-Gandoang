<?php

session_start();

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

$current_path = dirname($_SERVER['PHP_SELF']);

// Fungsi untuk mengecek apakah folder saat ini mengandung nama kategori
function isFolderActive($folderName)
{
    global $current_path;
    // Mengembalikan 'active' jika path mengandung nama folder tersebut
    return str_contains($current_path, $folderName) ? 'active' : '';
}

// $id_user = $_SESSION['id_user'];

// $sql = "SELECT
// user.nama,
// profile.foto
// FROM user

// JOIN profile
// ON user.id_user = profile.id_user

// WHERE user.id_user = :id_user";

// $stmt = $conn->prepare($sql);

// $stmt->execute([
// ':id_user' => $id_user
// ]);

// $user = $stmt->fetch(PDO::FETCH_ASSOC);

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

    .sidebar_atas {
        display: flex;
        gap: 15px;
        align-items: center;
        margin-bottom: 40px;
    }

    .foto_profil {
        width: 60px;
        height: 60px;
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
    }

    .isi_menu a {
        text-decoration: none;
        color: #65835e;
        padding: 14px 18px;
        border-radius: 12px;
        transition: 0.3s;
        font-size: 16px;
    }

    .isi_menu a:hover,
    .isi_menu a.active {
        background: #6d8d69;
        color: white;
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
</style>

<div class="sidebar">

    <!-- pembungkus utama -->
    <div>

        <!-- awal sidebar atas -->
        <div class="sidebar_atas">
            <?php /* <img src="<?= $asset_path ?>images/<?= $user['foto']; ?>" class="foto_profil"> */ ?>
            <img src="<?= $asset_path ?>/images/arif.jpg" class="foto_profil">

            <div>
                <?php /* <h2><?= htmlspecialchars($user['nama']); ?></h2> */ ?>
                <h2>Udin Petot</h2>
                <p>Your Personal Account</p>
            </div>
        </div>
        <!-- akhir sidebar atas -->

        <!-- awal isi menu -->
        <div class="isi_menu">

            <a href="#" class="<?= isFolderActive('dashboard') ?>">
                <img src="<?= $asset_path ?>icon/home.png" style="padding:5px" width="30px" height="30px">
                Dashboard
            </a>

            <a href="#" class="<?= isFolderActive('profile') ?>">
                <img src="<?= $asset_path ?>icon/profile.png" style="padding:5px" width="30px" height="30px">
                Profile
            </a>

            <a href="#" class="<?= isFolderActive('umkm') ?>">
                <img src="<?= $asset_path ?>icon/umkm.png" style="padding:5px" width="30px" height="30px">
                Profile UMKM
            </a>

            <a href="#" class="<?= isFolderActive('products') ?>">
                <img src="<?= $asset_path ?>icon/produk.png" style="padding:5px" width="30px" height="30px">
                Detail Produk
            </a>

            <a href="#" class="<?= isFolderActive('bantuan') ?>">
                <img src="<?= $asset_path ?>icon/bantuan.png" style="padding:5px" width="30px" height="30px">
                Ajukan Bantuan
            </a>

            <a href="#" class="<?= isFolderActive('journey') ?>">
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