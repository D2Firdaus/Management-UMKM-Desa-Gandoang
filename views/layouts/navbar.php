<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>

<?php require_once __DIR__ . '/../../config/path_config.php'; ?>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;900&display=swap" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Poppins', sans-serif;
    }

    nav {
        padding: 0.8rem 3rem;
        width: 100%;
        background-color: #2d5a3f;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .logo a {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .logo img {
        height: 40px;
        width: auto;
    }

    .menu a {
        text-decoration: none;
        color: white;
        font-size: 1rem;
        font-weight: 600;
    }

    .auth {
        display: flex;
        gap: 0.8rem;
    }

    .auth a {
        text-decoration: none;
        color: #2d5a3f;
        font-size: 0.9rem;
        font-weight: 600;
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        border: 2px solid white;
        background: white;
    }

    .auth a:hover {
        background: transparent;
        color: white;
    }
</style>

<nav>
    <div class="logo">
        <a href="<?= BASE_URL ?>index.php">
            <img src="<?= $asset_path ?>images/logo.png" alt="UMKM Gandoang">
        </a>
    </div>
    <div class="menu">
        <a href="#">Lihat Product</a>
    </div>
    <div class="auth">
        <?php if ($is_logged_in): ?>
            <a href="<?= $auth_controller_path ?>logout.php">Logout</a>
        <?php else: ?>
            <a href="<?= $view_path ?>auth/login.php">Masuk</a>
            <a href="<?= $view_path ?>auth/register.php">Daftar</a>
        <?php endif; ?>
    </div>
</nav>