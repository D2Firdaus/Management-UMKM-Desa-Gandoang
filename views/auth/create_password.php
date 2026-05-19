<?php session_start();
if (!isset($_SESSION['otp_verified'])) {
    header('Location: register.php');
    exit;
}
?>
<?php include '../layouts/navbar.php'; ?>
<?php require_once __DIR__ . '/../../config/path_config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Password - UMKM Gandoang</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .main {
            flex: 1;
            background: url('../asset/images/bg-desa.png') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .card .logo {
            text-align: center;
        }

        .card .logo span {
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 2px;
            color: #2d5a3f;
            display: block;
        }

        .card .logo h2 {
            font-size: 1.6rem;
            color: #2d5a3f;
            margin: 0;
        }

        .card h1 {
            text-align: center;
            font-size: 1.4rem;
            color: #2d5a3f;
            margin: 1rem 0 0.3rem;
        }

        .card .subtitle {
            text-align: center;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .input-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 0.7rem 1rem;
            gap: 0.6rem;
        }

        .input-wrap input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 0.9rem;
            font-family: 'Poppins', sans-serif;
        }

        .input-wrap .icon {
            color: #999;
            font-size: 1.1rem;
        }

        .toggle-pw {
            cursor: pointer;
            color: #999;
            border: none;
            background: none;
            font-size: 1.1rem;
        }

        .hint {
            font-size: 0.75rem;
            color: #2d5a3f;
            margin-top: 0.4rem;
        }

        .btn-submit {
            width: 100%;
            padding: 0.8rem;
            background: #2d5a3f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            margin-top: 1rem;
            font-family: 'Poppins', sans-serif;
        }

        .btn-submit:hover {
            background: #1e3d2b;
        }

        .error-msg {
            background: #ffe0e0;
            color: #c00;
            padding: 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="card">
            <div class="logo"><img src="<?= $asset_path ?>images/logo.png" alt="UMKM Gandoang" style="height:60px;"></div>
            <h1>Buat Password</h1>
            <p class="subtitle">Buat password untuk akun Anda</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="<?= $auth_controller_path ?>AuthController.php?action=createPassword" method="POST">
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <span class="icon">&#128274;</span>
                        <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required>
                        <button type="button" class="toggle-pw" onclick="toggle('password')">&#128065;</button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Kata Sandi</label>
                    <div class="input-wrap">
                        <span class="icon">&#128274;</span>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Masukkan Kata Sandi" required>
                        <button type="button" class="toggle-pw" onclick="toggle('confirm_password')">&#128065;</button>
                    </div>
                    <p class="hint">Minimal 8 karakter dengan kombinasi huruf dan angka</p>
                </div>
                <button type="submit" class="btn-submit">Simpan Password</button>
            </form>
        </div>
    </div>
    <?php include '../layouts/footer.php'; ?>
    <script>
        function toggle(id) {
            var p = document.getElementById(id);
            p.type = p.type === 'password' ? 'text' : 'password';
        }
    </script>
</body>

</html>