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
    $status = $_POST['status'];
    $catatan = $_POST['catatan'];
    $tanggal_validasi = date('Y-m-d'); // Tanggal hari ini

    $id_validator = $_SESSION['user_id'];

    try {
        $sql = "UPDATE bantuan SET
                    status = :status,
                    catatan = :catatan,
                    tanggal_validasi = :tanggal_validasi,
                    id_validator = :id_validator
                WHERE id_kebutuhan = :id_kebutuhan";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':catatan' => $catatan,
            ':tanggal_validasi' => $tanggal_validasi,
            ':id_validator' => $id_validator,
            ':id_kebutuhan' => $id_kebutuhan
        ]);

        header("Location: ../../views/validasi_bantuan/validasi_bantuan.php?status=validasi_sukses");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = "Gagal memperbarui validasi: " . $e->getMessage();
        header("Location: ../../views/validasi_bantuan/edit_validasi_bantuan.php?id=" . $id_kebutuhan);
        exit;
    }
} else {
    header("Location: ../../views/validasi_bantuan/validasi_bantuan.php");
    exit;
}
