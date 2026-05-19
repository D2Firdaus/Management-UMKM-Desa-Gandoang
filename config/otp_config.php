<?php
// Toggle OTP: true = kirim email beneran via SMTP, false = OTP dummy "123456"
$USE_REAL_OTP = true;

// Konfigurasi SMTP (Gmail)
$SMTP_HOST = 'smtp.gmail.com';
$SMTP_PORT = 587;
$SMTP_FROM_NAME = 'UMKM Gandoang';

// Load credentials dari file lokal (JANGAN di-push ke git)
// Buat file config/smtp_credentials.php dengan isi:
// <?php $SMTP_USER = 'email@gmail.com'; $SMTP_PASS = 'app-password';
if (file_exists(__DIR__ . '/smtp_credentials.php')) {
    require_once __DIR__ . '/smtp_credentials.php';
} else {
    $SMTP_USER = 'aldiwijaya201205@gmail.com';
    $SMTP_PASS = 'momt phaj dbve ggoc';
}

// OTP Settings
$OTP_LENGTH = 6;
$OTP_EXPIRY_MINUTES = 5;
