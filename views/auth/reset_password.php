<?php session_start();
if (!isset($_SESSION['reset_verified']) || !isset($_SESSION['reset_email'])) {
    header('Location: forgot_password.php');
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
    <title>Reset Password - UMKM Gandoang</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
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

        .card .logo {
            text-align: center;
            margin-bottom: 1rem;
            justify-content: center;
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
            min-width: 0;
            width: 100%;
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
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: #4f6b49;
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
            <div class="logo">
                <img src="<?= $asset_path ?>images/logo_form.png" alt="UMKM Gandoang" style="height:55px;">
            </div>

            <!-- password illustration -->
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="<?= $asset_path ?>images/password.png" style="width: 140px; height: auto;">
            </div>

            <h1>Buat Password Baru</h1>
            <p class="subtitle">Masukkan password baru untuk akun Anda</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="<?= $auth_controller_path ?>AuthController.php?action=resetPassword" method="POST">
                <div class="form-group">
                    <label for="password">Password Baru</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <img src="<?= $asset_path ?>images/gembok.png" style="width: 18px; height: 18px; opacity: 0.6;">
                        </span>
                        <input type="password" name="password" id="password" placeholder="Minimal 8 karakter" required>
                        <button type="button" class="toggle-pw" onclick="togglePassword('password')">
                            <img src="<?= $asset_path ?>images/hide_eye.png" id="eye-icon-password" style="width: 20px; height: 20px; opacity: 0.6;">
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <img src="<?= $asset_path ?>images/gembok.png" style="width: 18px; height: 18px; opacity: 0.6;">
                        </span>
                        <input type="password" name="confirm_password" id="confirm_password" placeholder="Ulangi password" required>
                        <button type="button" class="toggle-pw" onclick="togglePassword('confirm_password')">
                            <img src="<?= $asset_path ?>images/hide_eye.png" id="eye-icon-confirm_password" style="width: 20px; height: 20px; opacity: 0.6;">
                        </button>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Simpan Password</button>
            </form>
        </div>
    </div>
    <?php include '../layouts/footer.php'; ?>
    <script>
        function togglePassword(id) {
            var p = document.getElementById(id);
            var icon = document.getElementById('eye-icon-' + id);
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