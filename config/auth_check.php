<?php
// Include file ini di halaman yang butuh login
// Contoh: require_once __DIR__ . '/../config/auth_check.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ' . dirname($_SERVER['SCRIPT_NAME']) . '/../views/login.php');
    exit;
}
