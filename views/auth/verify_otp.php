<?php session_start();
if (!isset($_SESSION['reg_email'])) {
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
    <title>Verifikasi Email - UMKM Gandoang</title>
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
            font-size: 1.4rem;
            color: #2d5a3f;
            margin: 1rem 0 0.3rem;
        }

        .card .subtitle {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.2rem;
        }

        .card .email-display {
            font-size: 0.85rem;
            font-weight: 600;
            color: #333;
            margin-bottom: 1.5rem;
        }

        .otp-inputs {
            display: flex;
            justify-content: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .otp-inputs input {
            width: 45px;
            height: 50px;
            text-align: center;
            font-size: 1.3rem;
            font-weight: 700;
            border: 1px solid #ddd;
            border-radius: 8px;
            outline: none;
            font-family: 'Poppins', sans-serif;
        }

        .otp-inputs input:focus {
            border-color: #2d5a3f;
        }

        .timer {
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 1rem;
        }

        .timer span {
            color: #2d5a3f;
            font-weight: 600;
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
            font-family: 'Poppins', sans-serif;
        }

        .btn-submit:hover {
            background: #1e3d2b;
        }

        .resend {
            font-size: 0.85rem;
            color: #666;
            margin-top: 1rem;
        }

        .resend a {
            color: #2d5a3f;
            font-weight: 600;
            text-decoration: underline;
        }

        .error-msg {
            background: #ffe0e0;
            color: #c00;
            padding: 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <div class="main">
        <div class="card">
            <div class="logo"><img src="<?= $asset_path ?>images/logo.png" alt="UMKM Gandoang" style="height:60px;"></div>
            <h1>Verifikasi Email</h1>
            <p class="subtitle">Kami telah mengirimkan kode OTP ke email</p>
            <p class="email-display"><?= htmlspecialchars($_SESSION['reg_email']) ?></p>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-msg"><?= htmlspecialchars($_SESSION['error']);
                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="<?= $auth_controller_path ?>AuthController.php?action=verifyOtp" method="POST">
                <div class="otp-inputs">
                    <input type="text" name="otp1" maxlength="1" required autofocus>
                    <input type="text" name="otp2" maxlength="1" required>
                    <input type="text" name="otp3" maxlength="1" required>
                    <input type="text" name="otp4" maxlength="1" required>
                    <input type="text" name="otp5" maxlength="1" required>
                    <input type="text" name="otp6" maxlength="1" required>
                </div>
                <p class="timer">Kirim Ulang Dalam <span id="countdown">00:47</span> detik</p>
                <button type="submit" class="btn-submit">Lanjutkan</button>
            </form>
            <p class="resend">Belum menerima kode? <a href="<?= $auth_controller_path ?>OtpController.php?action=resend">Kirim Ulang</a></p>
        </div>
    </div>
    <?php include '../layouts/footer.php'; ?>
    <script>
        // Auto-focus next input
        document.querySelectorAll('.otp-inputs input').forEach((input, i, inputs) => {
            input.addEventListener('input', () => {
                if (input.value && i < inputs.length - 1) inputs[i + 1].focus();
            });
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && !input.value && i > 0) inputs[i - 1].focus();
            });
        });
        // Countdown timer
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