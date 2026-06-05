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

$id_umkm    = (int) ($_POST['id_umkm']    ?? 0);
$nama_umkm  = trim($_POST['nama_umkm']    ?? '');
$jenis_usaha = trim($_POST['jenis_usaha'] ?? '');
$alamat     = trim($_POST['alamat']       ?? '');

if (!$id_umkm || $nama_umkm === '' || $alamat === '') {
    header('Location: ' . BASE_URL . 'views/umkm/edit_umkm.php?id=' . $id_umkm . '&status=gagal');
    exit;
}

try {
    $umkmModel = new UmkmModel($conn);
    $ok = $umkmModel->update($id_umkm, $id_user, $nama_umkm, $jenis_usaha, $alamat);

    if ($ok) {
        header('Location: ' . BASE_URL . 'views/umkm/index.php?status=edit_sukses');
    } else {
        header('Location: ' . BASE_URL . 'views/umkm/edit_umkm.php?id=' . $id_umkm . '&status=gagal');
    }
} catch (Exception $e) {
    header('Location: ' . BASE_URL . 'views/umkm/edit_umkm.php?id=' . $id_umkm . '&status=gagal');
}
exit;
