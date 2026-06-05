<?php

declare(strict_types=1);

class OtpController
{
    /**
     * Generate dan kirim OTP ke email.
     * Menyimpan kode dan waktu kadaluarsa di session.
     *
     * @param string $email   Alamat email tujuan
     * @param string $subject Subjek email
     */
    public function send(string $email, string $subject): void
    {
        require_once __DIR__ . '/../../config/otp_config.php';

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $_SESSION['otp_code']   = $USE_REAL_OTP ? $otp : '123456';
        $_SESSION['otp_expiry'] = time() + ($OTP_EXPIRY_MINUTES * 60);

        if ($USE_REAL_OTP) {
            // Load vendor autoload
            $autoload_paths = [
                __DIR__ . '/../../vendor/autoload.php',  // Direct path
                dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php'  // Fallback
            ];

            $autoload_found = false;
            foreach ($autoload_paths as $path) {
                if (file_exists($path)) {
                    require_once $path;
                    $autoload_found = true;
                    break;
                }
            }

            if (!$autoload_found) {
                die('Error: Composer dependencies tidak ditemukan. Jalankan "composer install" di hosting Anda atau upload vendor folder.');
            }

            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host       = $SMTP_HOST;
                $mail->SMTPAuth   = true;
                $mail->Username   = $SMTP_USER;
                $mail->Password   = $SMTP_PASS;
                $mail->SMTPSecure = 'tls';
                $mail->Port       = $SMTP_PORT;

                $mail->setFrom('noreply@umkmgandoang.test', $SMTP_FROM_NAME);
                $mail->addAddress($email);
                $mail->Subject = $subject;
                $mail->Body    = "Kode OTP Anda: $otp\n\nKode berlaku selama $OTP_EXPIRY_MINUTES menit.";

                $mail->send();
            } catch (Exception $e) {
                die('Gagal mengirim OTP: ' . $e->getMessage());
            }
        }
    }

    /**
     * Kirim ulang OTP untuk proses registrasi.
     * Menggantikan: proses/resend_otp.php
     */
    public function resend(): void
    {
        if (!isset($_SESSION['reg_email'])) {
            header('Location: ../views/auth/register.php');
            exit;
        }

        $this->send($_SESSION['reg_email'], 'Kode OTP Registrasi - UMKM Gandoang');

        header('Location: ../views/auth/verify_otp.php');
        exit;
    }

    /**
     * Kirim ulang OTP untuk proses reset password.
     * Menggantikan: proses/resend_forgot_otp.php
     */
    public function resendForgot(): void
    {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: ../views/auth/forgot_password.php');
            exit;
        }

        $this->send($_SESSION['reset_email'], 'Kode OTP Reset Password - UMKM Gandoang');

        header('Location: ../views/auth/forgot_verify_otp.php');
        exit;
    }
}

// ---- Dispatch: hanya berjalan jika file ini diakses langsung via URL ----
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $controller = new OtpController();
    $action     = $_GET['action'] ?? '';

    match ($action) {
        'resend'      => $controller->resend(),
        'resendForgot' => $controller->resendForgot(),
        default       => header('Location: ../views/auth/login.php'),
    };
    exit;
}
