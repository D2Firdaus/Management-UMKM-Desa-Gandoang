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
    $status = $_POST['status'];
    $id_validator = $_SESSION['user_id'];

    try {
        $sql = "UPDATE umkm SET
                    status = :status,
                    id_validator = :id_validator
                WHERE id_umkm = :id_umkm";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':id_validator' => $id_validator,
            ':id_umkm' => $id_umkm
        ]);

        header("Location: ../../views/validasi_umkm/validasi_umkm.php?status=validasi_sukses");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal memperbarui validasi UMKM: " . $e->getMessage();
        header("Location: ../../views/validasi_umkm/edit_validasi_umkm.php?id=" . $id_umkm);
        exit;
    }
} else {
    header("Location: ../../views/validasi_umkm/validasi_umkm.php");
    exit;
}
