<?php require_once __DIR__ . '/../../config/path_config.php'; ?>

<style>
    /* Navbar */
    .navbar {
        height: 70px;
        min-height: 70px;
        background: #6d8d69;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
        color: white;
    }

    /* Akhir Navbar */

    /* Tombol Hamburger  */
    .hamburger {
        width: 45px;
        height: 45px;
        border: none;
        background: none;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap: 6px;
        cursor: pointer;
    }

    .hamburger span {
        width: 30px;
        height: 4px;
        background: white;
        border-radius: 5px;
        transition: 0.3s;
    }

    .hamburger.active span:nth-child(1) {
        transform: rotate(44deg) translate(7px, 7px);
    }

    .hamburger.active span:nth-child(2) {
        opacity: 0;
    }

    .hamburger.active span:nth-child(3) {
        transform: rotate(-45deg) translate(7px, -7px);
    }

    .tombol_logout,
    .tombol_logout a {
        font-size: 18px;
        text-decoration: none;
        color: #fafbfa;
    }

    .tombol_logout a img {
        margin-right: 1rem;
    }

    /* Akhir Tombol Hamburger */
</style>

<div class="navbar">
    <button class="hamburger">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <div class="tombol_logout">
        <a href="<?= $auth_controller_path ?>logout.php">
            <img src="<?= $asset_path ?>icon/log_out.png" width="25px" height="25px">
            Logout
        </a>
    </div>
</div>