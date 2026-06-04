<?php
declare(strict_types=1);

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Config
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../models/UmkmModel.php';

// Auth guard
$id_user = (int) ($_SESSION['user_id'] ?? 0);
if (!$id_user) {
    header('Location: ' . BASE_URL . 'views/auth/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . BASE_URL . 'views/umkm/index.php');
    exit;
}

$id_umkm = (int) ($_POST['id_umkm'] ?? 0);

if (!$id_umkm) {
    header('Location: ' . BASE_URL . 'views/umkm/index.php');
    exit;
}

try {
    $umkmModel = new UmkmModel($conn);
    $ok = $umkmModel->softDelete($id_umkm, $id_user);

    if ($ok) {
        header('Location: ' . BASE_URL . 'views/umkm/index.php?status=hapus_sukses');
    } else {
        header('Location: ' . BASE_URL . 'views/umkm/hapus_umkm.php?id=' . $id_umkm . '&status=gagal');
    }
} catch (Exception $e) {
    header('Location: ' . BASE_URL . 'views/umkm/hapus_umkm.php?id=' . $id_umkm . '&status=gagal');
}
exit;
