<?php

class DetailProdukModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getProductById(string $id)
    {
        $sql = "SELECT * FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUmkmInfoByUmkmId(string $id_umkm)
    {
        $sql = "SELECT u.nama_umkm, u.alamat, u.latitude, u.longitude, p.no_hp 
                FROM umkm u 
                LEFT JOIN profile p ON u.id_user = p.id_user 
                WHERE u.id_umkm = :id_umkm LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_umkm' => $id_umkm]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
