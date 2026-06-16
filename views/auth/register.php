<?php session_start(); ?>
<?php require_once __DIR__ . '/../../config/path_config.php'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - UMKM Gandoang</title>
    <style>
        * {
            font-family: Arial, sans-serif;
        }

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

        .login-link {
            text-align: center;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: #555;
            font-weight: 500;
        }

        .login-link a {
            color: #1e3d2b;
            font-weight: 700;
            text-decoration: none;
        }

        .login-link a:hover {
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
        }

        @media (max-width: 768px) {
            .main {
                padding: 1.5rem 1rem;
            }

            .card {
                padding: 2rem 1.5rem;
                border-radius: 20px;
            }

            .card h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>
    <?php include '../layouts/navbar.php'; ?>
    <div class="main">
        <div class="card">
            <div class="logo">
                <img src="<?= $asset_path ?>images/logo_form.png" alt="UMKM Gandoang" style="height:55px;">
            </div>

            <!-- umkm illustration -->
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="<?= $asset_path ?>images/umkm.png" style="width: 140px; height: auto;">
            </div>

            <h1>Daftar Akun!</h1>
            <p class="subtitle">Buat akun untuk memulai kelola usaha Anda dengan lebih mudah</p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="<?= $auth_controller_path ?>AuthController.php?action=register" method="POST">
                <div class="form-group">
                    <label for="nama">Nama Pemilik Usaha (Sesuai KTP)</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <img src="<?= $asset_path ?>images/profile_form.png" style="width: 18px; height: 18px; opacity: 0.6;">
                        </span>
                        <input type="text" name="nama" id="nama" placeholder="Masukan Nama" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <span class="icon">
                            <img src="<?= $asset_path ?>images/email.png" style="width: 18px; height: 18px; opacity: 0.6;">
                        </span>
                        <input type="email" name="email" id="email" placeholder="Masukkan Email" required>
                    </div>
                </div>
                <button type="submit" class="btn-submit">Lanjutkan</button>
            </form>
            <p class="login-link">Sudah punya akun? <a href="login.php">Masuk</a></p>
        </div>
    </div>
    <?php include '../layouts/footer.php'; ?>
</body>

</html>