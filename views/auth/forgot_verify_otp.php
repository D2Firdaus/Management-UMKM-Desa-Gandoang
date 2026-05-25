<?php session_start();
if (!isset($_SESSION['reset_email'])) {
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
    <title>Verifikasi OTP - UMKM Gandoang</title>
    <style>
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
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .card .logo {
            text-align: center;
            margin-bottom: 1rem;
        }

        .card h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #1e3d2b;
            margin: 0 0 0.5rem;
        }

        .card .subtitle {
            font-size: 0.9rem;
            color: #777;
            margin-bottom: 0.2rem;
            line-height: 1.5;
            font-weight: 500;
        }

        .card .email-display {
            font-size: 0.95rem;
            font-weight: 700;
            color: #1e3d2b;
            margin-bottom: 2rem;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 0.6rem;
            margin-bottom: 1.2rem;
        }

        .otp-inputs input {
            width: 48px;
            height: 52px;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 700;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            outline: none;
            font-family: 'Poppins', sans-serif;
            transition: border-color 0.2s;
        }

        .otp-inputs input:focus {
            border-color: #65835e;
        }

        .timer {
            font-size: 0.85rem;
            color: #555;
            margin-bottom: 1.5rem;
            font-weight: 500;
        }

        .timer span {
            color: #65835e;
            font-weight: 700;
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
            font-family: 'Poppins', sans-serif;
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: #4f6b49;
        }

        .resend {
            font-size: 0.9rem;
            color: #555;
            margin-top: 2rem;
            font-weight: 500;
        }

        .resend a {
            color: #1e3d2b;
            font-weight: 700;
            text-decoration: none;
        }

        .resend a:hover {
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
    </style>
</head>

<body>
    <div class="main">
        <div class="card">
            <div class="logo">
                <img src="<?= $asset_path ?>images/logo_form.png" alt="UMKM Gandoang" style="height:55px;">
            </div>

            <!-- otp illustration -->
            <div style="text-align: center; margin-bottom: 1.5rem;">
                <img src="<?= $asset_path ?>images/otp.png" style="width: 140px; height: auto;">
            </div>

            <h1>Verifikasi OTP</h1>
            <p class="subtitle">Masukkan kode OTP yang dikirim ke</p>
            <p class="email-display"><?= htmlspecialchars($_SESSION['reset_email']) ?></p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="<?= $auth_controller_path ?>AuthController.php?action=forgotVerifyOtp" method="POST">
                <div class="otp-inputs">
                    <input type="text" name="otp1" maxlength="1" required autofocus>
                    <input type="text" name="otp2" maxlength="1" required>
                    <input type="text" name="otp3" maxlength="1" required>
                    <input type="text" name="otp4" maxlength="1" required>
                    <input type="text" name="otp5" maxlength="1" required>
                    <input type="text" name="otp6" maxlength="1" required>
                </div>
                <p class="timer">Kirim Ulang Dalam <span id="countdown">00:47</span> detik</p>
                <button type="submit" class="btn-submit">Verifikasi</button>
            </form>
            <p class="resend">Belum menerima kode? <a href="../../controllers/OtpController.php?action=resendForgot">Kirim Ulang</a></p>
        </div>
    </div>
    <?php include '../layouts/footer.php'; ?>
    <script>
        document.querySelectorAll('.otp-inputs input').forEach((input, i, inputs) => {
            input.addEventListener('input', () => {
                if (input.value && i < inputs.length - 1) inputs[i + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && i > 0) inputs[i - 1].focus();
            });
        });
        let time = 47;
        const cd = document.getElementById('countdown');
        const timer = setInterval(() => {
            time--;
            cd.textContent = '00:' + String(time).padStart(2, '0');
            if (time <= 0) clearInterval(timer);
        }, 1000);
    </script>
</body>

</html>