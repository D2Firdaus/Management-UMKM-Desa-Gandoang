<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Proteksi: harus login dan role admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../../views/auth/login.php');
    exit;
}

require_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_kebutuhan = $_POST['id_kebutuhan'];

    try {
        $stmt = $conn->prepare(
            "UPDATE bantuan 
             SET status = ? 
             WHERE id_kebutuhan = ?"
        );

        if ($stmt->execute(['dihapus', $id_kebutuhan])) {
            header("Location: ../../views/validasi_bantuan/validasi_bantuan.php?status=hapus_sukses");
        } else {
            header("Location: ../../views/validasi_bantuan/validasi_bantuan.php?status=hapus_gagal");
        }
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal menghapus validasi bantuan: " . $e->getMessage();
        header("Location: ../../views/validasi_bantuan/validasi_bantuan.php");
        exit;
    }
} else {
    header("Location: ../../views/validasi_bantuan/validasi_bantuan.php");
    exit;
}
