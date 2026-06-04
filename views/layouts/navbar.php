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
        padding: 1rem 3rem;
        width: 100%;
        background: linear-gradient(to right, #0B1615, #162E2B);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
    }

    .logo a {
        display: flex;
        align-items: center;
        text-decoration: none;
    }

    .logo img {
        height: 50px;
        width: auto;
    }

    .nav-links {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 2rem;
        flex: 1;
    }

    .menu a {
        text-decoration: none;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        transition: opacity 0.2s;
    }

    .menu a:hover {
        opacity: 0.8;
    }

    .auth-desktop {
        display: flex;
        gap: 1rem;
        flex-shrink: 0;
    }

    .auth-desktop a {
        text-decoration: none;
        color: white;
        font-size: 0.95rem;
        font-weight: 700;
        padding: 0.5rem 1.8rem;
        border-radius: 25px;
        border: 2px solid #A38E34;
        background: transparent;
        box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.5);
        transition: all 0.2s;
    }

    .auth-desktop a:hover {
        background: #A38E34;
        color: #0B1615;
        border-color: #A38E34;
        box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.6);
    }

    .auth-mobile {
        display: none;
    }

    .hamburger-main {
        width: 35px;
        height: 35px;
        border: none;
        background: none;
        display: none;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 5px;
        cursor: pointer;
    }

    .hamburger-main span {
        width: 25px;
        height: 3px;
        background: white;
        border-radius: 5px;
        transition: 0.3s;
    }

    .hamburger-main.active span:nth-child(1) {
        transform: rotate(45deg) translate(5px, 6px);
    }

    .hamburger-main.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger-main.active span:nth-child(3) {
        transform: rotate(-45deg) translate(5px, -6px);
    }

    @media (max-width: 768px) {
        nav {
            padding: 0.75rem 1.25rem;
        }

        .logo img {
            height: 26px;
        }

        .hamburger-main {
            display: flex;
        }

        .nav-links {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: linear-gradient(to right, #0B1615, #162E2B);
            flex-direction: column;
            padding: 1.5rem;
            gap: 1.5rem;
            box-shadow: 0 10px 15px rgba(0,0,0,0.5);
            z-index: 1000;
        }

        .nav-links.active {
            display: flex;
            animation: slideDown 0.3s ease forwards;
        }

        .menu {
            width: 100%;
            text-align: center;
        }

        .auth-desktop {
            display: none;
        }

        .auth-mobile {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            width: 100%;
            margin-top: 0.5rem;
        }

        .auth-mobile a {
            text-decoration: none;
            color: white;
            font-size: 0.95rem;
            font-weight: 700;
            padding: 0.5rem 1.8rem;
            border-radius: 25px;
            border: 2px solid #A38E34;
            background: transparent;
            box-shadow: 0px 4px 4px rgba(0, 0, 0, 0.5);
            transition: all 0.2s;
            width: 100%;
            max-width: 250px;
            text-align: center;
        }

        .auth-mobile a:hover {
            background: #A38E34;
            color: #0B1615;
            border-color: #A38E34;
            box-shadow: 0px 6px 8px rgba(0, 0, 0, 0.6);
        }
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<nav>
    <div class="logo">
        <a href="<?= $base_url ?>/index.php">
            <img src="<?= $asset_path ?>images/logo_navbar.png" alt="UMKM Gandoang">
        </a>
    </div>
    
    <button class="hamburger-main" id="hamburger-main">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="nav-links" id="nav-links-main">
        <div class="menu">
            <a href="<?= $view_path ?>products/index.php">Lihat Product</a>
        </div>
        <div class="auth-mobile">
            <?php if ($is_logged_in): ?>
                <a href="<?= $auth_controller_path ?>logout.php">Logout</a>
            <?php else: ?>
                <a href="<?= $view_path ?>auth/login.php">Masuk</a>
                <a href="<?= $view_path ?>auth/register.php">Daftar</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="auth-desktop">
        <?php if ($is_logged_in): ?>
            <a href="<?= $auth_controller_path ?>logout.php">Logout</a>
        <?php else: ?>
            <a href="<?= $view_path ?>auth/login.php">Masuk</a>
            <a href="<?= $view_path ?>auth/register.php">Daftar</a>
        <?php endif; ?>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburgerMain = document.getElementById('hamburger-main');
        const navLinksMain = document.getElementById('nav-links-main');
        
        if (hamburgerMain && navLinksMain) {
            hamburgerMain.addEventListener('click', function() {
                hamburgerMain.classList.toggle('active');
                navLinksMain.classList.toggle('active');
            });
        }
    });
</script>