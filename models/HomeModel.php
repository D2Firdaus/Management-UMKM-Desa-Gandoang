<?php

declare(strict_types=1);

class HomeModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getUmkmLokasi(): array
    {
        $sql = "SELECT
                    id_umkm,
                    nama_umkm,
                    jenis_usaha,
                    alamat,
                    latitude,
                    longitude
                FROM umkm
                WHERE status = 'aktif'
                  AND latitude  IS NOT NULL
                  AND longitude IS NOT NULL
                  AND latitude  != ''
                  AND longitude != ''
                ORDER BY nama_umkm ASC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRandomProducts(int $limit = 4): array
    {
        $sql = "SELECT p.id_produk, p.nama_produk, p.kategori, p.harga, p.foto 
                FROM produk p
                JOIN umkm u ON p.id_umkm = u.id_umkm
                WHERE p.status = 'aktif' AND u.status = 'aktif'
                ORDER BY RAND()";
        
        $stmt = $this->db->query($sql);
        $rawProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $productsToShow = [];
        if (!empty($rawProducts)) {
            $count = count($rawProducts);
            for ($i = 0; $i < $limit; $i++) {
                $prod = $rawProducts[$i % $count];
                
                $fotoList = explode(',', $prod['foto']);
                $firstFoto = trim($fotoList[0] ?? '');
                if (empty($firstFoto) || $firstFoto === 'default.jpg') {
                    $imgUrl = BASE_URL . 'storage/images/products/default.jpg';
                } else {
                    $imgUrl = BASE_URL . 'storage/images/products/' . $firstFoto;
                }
                
                $productsToShow[] = [
                    'id_produk'   => $prod['id_produk'],
                    'nama_produk' => $prod['nama_produk'],
                    'kategori'    => $prod['kategori'],
                    'gambar'      => $imgUrl
                ];
            }
        }
        return $productsToShow;
    }
}
