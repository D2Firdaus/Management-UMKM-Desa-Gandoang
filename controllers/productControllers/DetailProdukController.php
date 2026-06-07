<?php
require_once __DIR__ . '/../../models/DetailProdukModel.php';
require_once __DIR__ . '/../../config/Encryption.php';
require_once __DIR__ . '/../../config/path_config.php';

class DetailProdukController
{
    private DetailProdukModel $model;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->model = new DetailProdukModel($db);
    }

    public function show(string $productId)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $dbProduct = $this->model->getProductById($productId);
        $umkmInfo = null;

        if ($dbProduct) {
            // Fetch UMKM info & owner phone number
            $umkmInfo = $this->model->getUmkmInfoByUmkmId($dbProduct['id_umkm']);

            $no_hp = '';
            $nama_umkm = 'UMKM Gandoang';
            if ($umkmInfo) {
                $nama_umkm = $umkmInfo['nama_umkm'];
                if (!empty($umkmInfo['no_hp'])) {
                    $no_hp = Encryption::decrypt($umkmInfo['no_hp']);
                }
            }

            // Normalise phone number to WhatsApp format
            if (!empty($no_hp)) {
                $no_hp = preg_replace('/[^0-9]/', '', $no_hp);
                if (str_starts_with($no_hp, '0')) {
                    $no_hp = '62' . substr($no_hp, 1);
                }
            } else {
                $no_hp = '6281234567890'; // fallback WhatsApp
            }

            // Process images
            $fotoList = explode(',', $dbProduct['foto']);
            $gambar_utama = !empty($fotoList[0]) && trim($fotoList[0]) !== 'default.jpg'
                ? BASE_URL . 'storage/images/products/' . trim($fotoList[0])
                : BASE_URL . 'asset/images/default.jpg';

            $gambar_lain = [];
            if (count($fotoList) > 1) {
                for ($i = 1; $i < count($fotoList); $i++) {
                    if (!empty($fotoList[$i]) && trim($fotoList[$i]) !== 'default.jpg') {
                        $gambar_lain[] = BASE_URL . 'storage/images/products/' . trim($fotoList[$i]);
                    }
                }
            }

            $product = [
                'id' => $dbProduct['id_produk'],
                'nama' => $dbProduct['nama_produk'],
                'kategori' => $dbProduct['kategori'],
                'deskripsi' => $dbProduct['deskripsi'],
                'harga' => $dbProduct['harga'],
                'harga_coret' => $dbProduct['harga'] * 1.3,
                'diskon' => '30%',
                'terjual' => '50+',
                'rating' => '4.8',
                'ulasan' => 15,
                'gambar_utama' => $gambar_utama,
                'gambar_lain' => $gambar_lain,
                'pilihan' => ['Original'],
                'no_hp' => $no_hp,
                'nama_umkm' => $nama_umkm
            ];
        } else {
            // Fallback dummy
            $product = [
                'id' => $productId,
                'nama' => 'Sepatu Lokal Gandoang Premium',
                'kategori' => 'Fashion',
                'deskripsi' => 'Sepatu buatan lokal Gandoang dengan material kulit sintetis premium yang awet dan tahan lama. Cocok digunakan untuk berbagai acara, mulai dari kasual hingga semi-formal. Memiliki sol yang empuk dan tidak licin, memberikan kenyamanan maksimal untuk pemakaian seharian.',
                'harga' => 300000,
                'harga_coret' => 1000000,
                'diskon' => '70%',
                'terjual' => '1.5 Jt',
                'rating' => '4.8',
                'ulasan' => 287,
                'gambar_utama' => BASE_URL . 'asset/images/product_detail_1.png',
                'gambar_lain' => [
                    BASE_URL . 'asset/images/product_detail_2.png',
                    BASE_URL . 'asset/images/product_detail_3.png'
                ],
                'pilihan' => [
                    'Hitam 40',
                    'Hitam 41',
                    'Cokelat 40',
                    'Cokelat 41'
                ],
                'no_hp' => '6281234567890',
                'nama_umkm' => 'UMKM Gandoang'
            ];
        }

        // Require view
        require_once __DIR__ . '/../../views/products/detail-produk.php';
    }
}
