<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_POST['submit'])) {

    $id_kebutuhan = $_POST['id_kebutuhan'];

    $stmt = $conn->prepare(
        "UPDATE bantuan 
         SET status = ? 
         WHERE id_kebutuhan = ?"
    );

    if ($stmt->execute(['dihapus', $id_kebutuhan])) {
        header("Location: ../../views/bantuan/index.php?status=hapus_sukses");
    } else {
        header("Location: ../../views/bantuan/index.php?status=hapus_gagal");
    }

    exit;
} else {
    header("Location: ../../views/bantuan/index.php?status=invalid");
    exit;
}
