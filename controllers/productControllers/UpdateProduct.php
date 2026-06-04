<?php
session_start();

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . $view_path . 'products/index.php');
    exit;
}

$id_produk   = (int)($_POST['id_produk'] ?? 0);
$nama_produk = trim($_POST['nama_produk'] ?? '');
$kategori    = trim($_POST['kategori'] ?? '');
$harga       = (int)($_POST['harga'] ?? 0);
$id_umkm     = (int)($_POST['id_umkm'] ?? 0);
$deskripsi   = trim($_POST['deskripsi'] ?? '');
$foto_lama   = trim($_POST['foto_lama'] ?? '');

if ($id_produk <= 0) {
    header('Location: ' . $view_path . 'products/index.php');
    exit;
}

// Proses upload foto baru (jika ada)
$foto = $foto_lama; // default pakai foto lama
if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = __DIR__ . '/../../storage/products/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true);
    }

    $ext  = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
    $foto = uniqid('prod_', true) . '.' . $ext;
    $dest = $upload_dir . $foto;

    if (!move_uploaded_file($_FILES['foto']['tmp_name'], $dest)) {
        $_SESSION['error'] = 'Gagal mengupload foto baru.';
        header('Location: ' . $view_path . 'products/editProduct.php?id=' . $id_produk);
        exit;
    }

    // Hapus foto lama jika ada
    if (!empty($foto_lama)) {
        $old_path = $upload_dir . $foto_lama;
        if (file_exists($old_path)) {
            unlink($old_path);
        }
    }
}

try {
    $sql  = "UPDATE produk SET
                nama_produk = :nama_produk,
                kategori    = :kategori,
                harga       = :harga,
                foto        = :foto,
                id_umkm     = :id_umkm,
                deskripsi   = :deskripsi
             WHERE id_produk = :id_produk";
    $stmt = $conn->prepare($sql);
    $stmt->execute([
        ':nama_produk' => $nama_produk,
        ':kategori'    => $kategori,
        ':harga'       => $harga,
        ':foto'        => $foto,
        ':id_umkm'     => $id_umkm,
        ':deskripsi'   => $deskripsi,
        ':id_produk'   => $id_produk,
    ]);

    $_SESSION['success'] = 'Produk berhasil diupdate!';
    header('Location: ' . $view_path . 'products/index.php');
    exit;

} catch (PDOException $e) {
    $_SESSION['error'] = 'Gagal mengupdate produk: ' . $e->getMessage();
    header('Location: ' . $view_path . 'products/editProduct.php?id=' . $id_produk);
    exit;
}
