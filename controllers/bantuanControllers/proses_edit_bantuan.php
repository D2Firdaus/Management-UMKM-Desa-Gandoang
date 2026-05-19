<?php
require_once __DIR__ . '/../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id_kebutuhan = $_POST['id_kebutuhan'];
    $id_umkm = $_POST['id_umkm'];
    $jenis = $_POST['jenis'];
    $deskripsi = $_POST['deskripsi'];
    $prioritas = $_POST['prioritas'];

    $sql = "UPDATE bantuan SET
                id_umkm = :id_umkm,
                jenis = :jenis,
                deskripsi = :deskripsi,
                prioritas = :prioritas
            WHERE id_kebutuhan = :id_kebutuhan";

    $stmt = $conn->prepare($sql);

    $stmt->execute([
        ':id_umkm' => $id_umkm,
        ':jenis' => $jenis,
        ':deskripsi' => $deskripsi,
        ':prioritas' => $prioritas,
        ':id_kebutuhan' => $id_kebutuhan
    ]);

    header("Location: ../../views/bantuan/index.php?status=edit_sukses");
    exit;
} else {
    header("Location: ../../views/bantuan/index.php");
    exit;
}
