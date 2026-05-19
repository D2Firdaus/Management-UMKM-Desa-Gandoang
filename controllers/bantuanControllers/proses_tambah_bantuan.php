<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_umkm = $_POST['id_umkm'];
    $jenis = $_POST['jenis'];
    $deskripsi = $_POST['deskripsi'];
    $prioritas = $_POST['prioritas'];

    $status = "pending";
    $catatan = null;
    $tanggal_validasi = null;
    $tanggal_pengajuan = date('Y-m-d');

    $sql = "INSERT INTO bantuan 
            (id_umkm, jenis, prioritas, status, catatan, deskripsi, tanggal_validasi,tanggal_pengajuan)
            VALUES 
            (:id_umkm, :jenis, :prioritas, :status, :catatan, :deskripsi, :tanggal_validasi, :tanggal_pengajuan)";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':id_umkm' => $id_umkm,
        ':jenis' => $jenis,
        ':prioritas' => $prioritas,
        ':status' => $status,
        ':catatan' => $catatan,
        ':deskripsi' => $deskripsi,
        ':tanggal_validasi' => $tanggal_validasi,
        ':tanggal_pengajuan' => $tanggal_pengajuan
    ]);

    header("Location: ../../views/bantuan/index.php?status=tambah_sukses");
    exit;
}
