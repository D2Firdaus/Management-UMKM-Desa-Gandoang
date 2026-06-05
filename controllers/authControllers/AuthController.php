<?php

declare(strict_types=1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require_once __DIR__ . '/../../models/UserModel.php';


class AuthController
{
    private UserModel $userModel;

    public function __construct(PDO $conn)
    {
        $this->userModel = new UserModel($conn);
    }

    /**
     * Proses login.
     * Menggantikan: proses/proses_login.php
     */
    public function login(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/login.php');
            exit;
        }

        $email    = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email dan kata sandi wajib diisi.';
            header('Location: ../../views/auth/login.php');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header('Location: ../../views/auth/login.php');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Email atau kata sandi salah.';
            header('Location: ../../views/auth/login.php');
            exit;
        }

        if ($user['status'] !== 'aktif') {
            $_SESSION['error'] = 'Akun Anda belum aktif. Hubungi admin.';
            header('Location: ../../views/auth/login.php');
            exit;
        }

        $_SESSION['user_id']    = $user['id_user'];
        $_SESSION['user_nama']  = $user['nama'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role']  = $user['role'];
        $_SESSION['logged_in']  = true;

        if ($user['role'] === 'admin') {
            header('Location: ../../views/layouts/dashboard_admin.php');
        } else {
            header('Location: ../../views/layouts/dashboard_user.php');
        }
        exit;
    }

    /**
     * Proses registrasi awal (nama + email).
     * Menggantikan: proses/proses_register.php
     */
    public function register(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/register.php');
            exit;
        }

        $nama  = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($nama) || empty($email)) {
            $_SESSION['error'] = 'Nama dan email wajib diisi.';
            header('Location: ../../views/auth/register.php');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header('Location: ../../views/auth/register.php');
            exit;
        }

        if ($this->userModel->emailExists($email)) {
            $_SESSION['error'] = 'Email sudah terdaftar. Silakan masuk.';
            header('Location: ../../views/auth/register.php');
            exit;
        }

        $_SESSION['reg_nama']  = $nama;
        $_SESSION['reg_email'] = $email;

        $otp = new OtpController();
        $otp->send($email, 'Kode OTP Registrasi - UMKM Gandoang');

        header('Location: ../../views/auth/verify_otp.php');
        exit;
    }

    /**
     * Verifikasi OTP saat registrasi.
     * Menggantikan: proses/proses_verify_otp.php
     */
    public function verifyOtp(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/register.php');
            exit;
        }

        if (!isset($_SESSION['reg_email'], $_SESSION['otp_code'])) {
            header('Location: ../../views/auth/register.php');
            exit;
        }

        $otp_input = '';
        for ($i = 1; $i <= 6; $i++) {
            $otp_input .= $_POST["otp$i"] ?? '';
        }

        if (time() > ($_SESSION['otp_expiry'] ?? 0)) {
            $_SESSION['error'] = 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.';
            header('Location: ../../views/auth/verify_otp.php');
            exit;
        }

        if ($otp_input !== $_SESSION['otp_code']) {
            $_SESSION['error'] = 'Kode OTP salah. Silakan coba lagi.';
            header('Location: ../../views/auth/verify_otp.php');
            exit;
        }

        $_SESSION['otp_verified'] = true;
        unset($_SESSION['otp_code'], $_SESSION['otp_expiry']);

        header('Location: ../../views/auth/create_password.php');
        exit;
    }

    /**
     * Simpan password baru setelah OTP registrasi berhasil.
     * Menggantikan: proses/proses_create_password.php
     */
    public function createPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/register.php');
            exit;
        }

        if (!isset($_SESSION['otp_verified'], $_SESSION['reg_email'])) {
            header('Location: ../../views/auth/register.php');
            exit;
        }

        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Password wajib diisi.';
            header('Location: ../../views/auth/create_password.php');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password minimal 8 karakter.';
            header('Location: ../../views/auth/create_password.php');
            exit;
        }

        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $_SESSION['error'] = 'Password harus kombinasi huruf dan angka.';
            header('Location: ../../views/auth/create_password.php');
            exit;
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
            header('Location: ../../views/auth/create_password.php');
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->create($_SESSION['reg_nama'], $_SESSION['reg_email'], $hashed);

        unset($_SESSION['reg_nama'], $_SESSION['reg_email'], $_SESSION['otp_verified']);

        $_SESSION['success'] = 'Registrasi berhasil! Silakan masuk.';
        header('Location: ../../views/auth/login.php');
        exit;
    }

    /**
     * Proses permintaan reset password (kirim OTP ke email).
     * Menggantikan: proses/proses_forgot_password.php
     */
    public function forgotPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'Format email tidak valid.';
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        $user = $this->userModel->findByEmail($email);

        if (!$user) {
            $_SESSION['error'] = 'Email tidak terdaftar.';
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        $_SESSION['reset_email']   = $email;
        $_SESSION['reset_user_id'] = $user['id_user'];

        $otp = new OtpController();
        $otp->send($email, 'Kode OTP Reset Password - UMKM Gandoang');

        header('Location: ../../views/auth/forgot_verify_otp.php');
        exit;
    }

    /**
     * Verifikasi OTP saat forgot password.
     * Menggantikan: proses/proses_forgot_verify_otp.php
     */
    public function forgotVerifyOtp(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        if (!isset($_SESSION['reset_email'], $_SESSION['otp_code'])) {
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        $otp_input = '';
        for ($i = 1; $i <= 6; $i++) {
            $otp_input .= $_POST["otp$i"] ?? '';
        }

        if (time() > ($_SESSION['otp_expiry'] ?? 0)) {
            $_SESSION['error'] = 'Kode OTP sudah kadaluarsa. Silakan kirim ulang.';
            header('Location: ../../views/auth/forgot_verify_otp.php');
            exit;
        }

        if ($otp_input !== $_SESSION['otp_code']) {
            $_SESSION['error'] = 'Kode OTP salah. Silakan coba lagi.';
            header('Location: ../../views/auth/forgot_verify_otp.php');
            exit;
        }

        $_SESSION['reset_verified'] = true;
        unset($_SESSION['otp_code'], $_SESSION['otp_expiry']);

        header('Location: ../../views/auth/reset_password.php');
        exit;
    }

    /**
     * Simpan password baru setelah OTP reset berhasil.
     * Menggantikan: proses/proses_reset_password.php
     */
    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        if (!isset($_SESSION['reset_verified'], $_SESSION['reset_email'])) {
            header('Location: ../../views/auth/forgot_password.php');
            exit;
        }

        $password         = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($password) || empty($confirm_password)) {
            $_SESSION['error'] = 'Password wajib diisi.';
            header('Location: ../../views/auth/reset_password.php');
            exit;
        }

        if (strlen($password) < 8) {
            $_SESSION['error'] = 'Password minimal 8 karakter.';
            header('Location: ../../views/auth/reset_password.php');
            exit;
        }

        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            $_SESSION['error'] = 'Password harus kombinasi huruf dan angka.';
            header('Location: ../../views/auth/reset_password.php');
            exit;
        }

        if ($password !== $confirm_password) {
            $_SESSION['error'] = 'Konfirmasi password tidak cocok.';
            header('Location: ../../views/auth/reset_password.php');
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $this->userModel->updatePassword($_SESSION['reset_email'], $hashed);

        unset($_SESSION['reset_email'], $_SESSION['reset_user_id'], $_SESSION['reset_verified']);

        $_SESSION['success'] = 'Password berhasil direset! Silakan masuk.';
        header('Location: ../../views/auth/login.php');
        exit;
    }
}

// ---- Dispatch: hanya berjalan jika file ini diakses langsung via URL ----
if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../../config/koneksi.php';
    require_once __DIR__ . '/OtpController.php';

    $controller = new AuthController($conn);
    $action     = $_GET['action'] ?? '';

    match ($action) {
        'login'           => $controller->login(),
        'register'        => $controller->register(),
        'verifyOtp'       => $controller->verifyOtp(),
        'createPassword'  => $controller->createPassword(),
        'forgotPassword'  => $controller->forgotPassword(),
        'forgotVerifyOtp' => $controller->forgotVerifyOtp(),
        'resetPassword'   => $controller->resetPassword(),
        default           => header('Location: ../../views/auth/login.php'),
    };
    exit;
}
