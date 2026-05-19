<?php
session_start();
$is_logged_in = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
?>
<?php include 'views/layouts/navbar.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - UMKM Gandoang</title>
    <style>
        body { margin: 0; min-height: 100vh; display: flex; flex-direction: column; font-family: 'Poppins', sans-serif; }
        .main { flex: 1; padding: 2rem 3rem; }
        .welcome { background: #2d5a3f; color: white; padding: 2rem; border-radius: 12px; margin-bottom: 2rem; }
        .welcome h1 { margin: 0 0 0.5rem; font-size: 1.5rem; }
        .welcome p { margin: 0; opacity: 0.9; }
        .btn-logout { display: inline-block; margin-top: 1rem; padding: 0.5rem 1.5rem; background: white; color: #2d5a3f; border-radius: 8px; text-decoration: none; font-weight: 600; font-size: 0.85rem; }
    </style>
</head>
<body>
    <div class="main">
        <div class="welcome">
            <?php if ($is_logged_in): ?>
                <h1>Selamat Datang, <?= htmlspecialchars($_SESSION['user_nama']) ?>!</h1>
                <p>Anda berhasil masuk ke Sistem Manajemen UMKM Desa Gandoang.</p>
                <a href="/Management_UMKM_Desa_Gandoang/controllers/logout.php" class="btn-logout">Logout</a>
            <?php else: ?>
                <h1>Selamat Datang di UMKM Desa Gandoang!</h1>
                <p>Silakan <a href="/Management_UMKM_Desa_Gandoang/views/auth/login.php" style="color:white;font-weight:700;">masuk</a> atau <a href="/Management_UMKM_Desa_Gandoang/views/auth/register.php" style="color:white;font-weight:700;">daftar</a> untuk mengakses fitur lengkap.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'views/layouts/footer.php'; ?>
</body>
</html>
