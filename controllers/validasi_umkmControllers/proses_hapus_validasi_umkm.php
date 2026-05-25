<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Proteksi: harus login dan role admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_umkm = $_POST['id_umkm'];

    try {
        $stmt = $conn->prepare("DELETE FROM umkm WHERE id_umkm = ?");

        if ($stmt->execute([$id_umkm])) {
            header("Location: ../../views/validasi_umkm/validasi_umkm.php?status=hapus_sukses");
        } else {
            header("Location: ../../views/validasi_umkm/validasi_umkm.php?status=hapus_gagal");
        }
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus UMKM: " . $e->getMessage();
        header("Location: ../../views/validasi_umkm/validasi_umkm.php");
        exit;
    }
} else {
    header("Location: ../../views/validasi_umkm/validasi_umkm.php");
    exit;
}
