<?php session_start(); ?>
<?php require_once __DIR__ . '/../../config/path_config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - UMKM Gandoang</title>
    <style>
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }

        .main {
            flex: 1;
            background: url('<?= $asset_path ?>images/backround_desa.png') center/cover no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
        }

        .card {
            background: white;
            border-radius: 35px;
            padding: 3rem 2.5rem;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            position: relative;
            overflow: hidden;
        }

        .card-leaf {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 130px;
            pointer-events: none;
            z-index: 1;
        }

        .card .logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .card h1 {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e3d2b;
            margin: 0 0 0.5rem;
        }

        .card .subtitle {
            text-align: center;
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 2rem;
            line-height: 1.5;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 1.2rem;
            position: relative;
            z-index: 2;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
            color: #1e3d2b;
        }

        .input-wrap {
            display: flex;
            align-items: center;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            padding: 0.8rem 1.2rem;
            gap: 0.8rem;
            background: white;
            transition: border-color 0.2s;
        }

        .input-wrap:focus-within {
            border-color: #65835e;
        }

        .input-wrap input {
            border: none;
            outline: none;
            flex: 1;
            font-size: 0.95rem;
            color: #333;
            font-family: 'Poppins', sans-serif;
        }

        .input-wrap .icon {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .toggle-pw {
            cursor: pointer;
            border: none;
            background: none;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }

        .help-link {
            display: block;
            font-size: 0.85rem;
            color: #555;
            margin-top: 0.6rem;
            text-decoration: none;
            font-weight: 500;
        }

        .help-link:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 0.9rem;
            background: #65835e;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            margin-top: 1.5rem;
            font-family: 'Poppins', sans-serif;
            position: relative;
            z-index: 2;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: #4f6b49;
        }

        .forgot {
            display: block;
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
            color: #555;
            text-decoration: underline;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .register-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #555;
            font-weight: 500;
            position: relative;
            z-index: 2;
        }

        .register-link a {
            color: #1e3d2b;
            font-weight: 700;
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }

        .error-msg {
            background: #ffe0e0;
            color: #c00;
            padding: 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
            text-align: center;
            position: relative;
            z-index: 2;
        }

        @media (max-width: 768px) {
            .main { padding: 1.5rem 1rem; }
            .card { padding: 2rem 1.5rem; border-radius: 20px; }
            .card h1 { font-size: 1.5rem; }
        }
    </style>
</head>

<body>
    <?php include '../layouts/navbar.php'; ?>
    <div class="main">
        <div class="card">
            <!-- daun decoration -->
            <img src="<?= $asset_path ?>images/daun.png" class="card-leaf">

            <div class="logo">
                <img src="<?= $asset_path ?>images/logo_form.png" alt="UMKM Gandoang" style="height:55px;">
            </div>
            <h1>Selamat Datang!</h1>
            <p class="subtitle">Masuk untuk mengelola usaha UMKM Anda dengan lebih mudah</p>

            <?php if (isset($_SESSION['success'])): ?>
                <div style="background:#e0ffe0;color:#060;padding:0.6rem;border-radius:6px;font-size:0.8rem;margin-bottom:1rem;text-align:center;position:relative;z-index:2;"><?= htmlspecialchars($_SESSION['success']);
                                                                                                                                                                             unset($_SESSION['success']); ?></div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="<?= $auth_controller_path ?>AuthController.php?action=login" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <img src="<?= $asset_path ?>images/profile_form.png" style="width: 18px; height: 18px; opacity: 0.6;">
                        </span>
                        <input type="email" name="email" id="email" placeholder="Masukkan Email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <img src="<?= $asset_path ?>images/gembok.png" style="width: 18px; height: 18px; opacity: 0.6;">
                        </span>
                        <input type="password" name="password" id="password" placeholder="Masukkan Kata Sandi" required>
                        <button type="button" class="toggle-pw" onclick="togglePassword()">
                            <img src="<?= $asset_path ?>images/hide_eye.png" id="eye-icon" style="width: 20px; height: 20px; opacity: 0.6;">
                        </button>
                    </div>
                    <a href="https://api.whatsapp.com/send/?phone=6281312333735&text=Halo%2C+saya+butuh+bantuan+login+pada+umkm!&type=phone_number&app_absent=0" class="help-link">Butuh Bantuan?</a>
                </div>
                <button type="submit" class="btn-submit">Masuk</button>
                <a href="forgot_password.php" class="forgot">Lupa Password</a>
            </form>
            <p class="register-link">Belum punya akun? <a href="register.php">Daftar</a></p>
        </div>
    </div>
    <?php include '../layouts/footer.php'; ?>
    <script>
        function togglePassword() {
            var p = document.getElementById('password');
            var icon = document.getElementById('eye-icon');
            if (p.type === 'password') {
                p.type = 'text';
                icon.style.opacity = '1';
            } else {
                p.type = 'password';
                icon.style.opacity = '0.6';
            }
        }
    </script>
</body>

</html>