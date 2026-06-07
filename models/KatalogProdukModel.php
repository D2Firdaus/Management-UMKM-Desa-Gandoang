<?php

declare(strict_types=1);

class KatalogProdukModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getKategori(): array
    {
        $sql = "SELECT DISTINCT kategori
                FROM produk
                WHERE status != 'dihapus'
                  AND kategori IS NOT NULL
                  AND kategori != ''
                ORDER BY kategori ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getRekomendasi(int $limit = 12): array
    {
        $sql = "SELECT id_produk, nama_produk, kategori, harga, foto
                FROM produk
                WHERE status != 'dihapus'
                ORDER BY RAND()
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated(string $kategori = '', int $limit = 6, int $offset = 0): array
    {
        $hasFilter = ($kategori !== '' && $kategori !== 'Semua kategori');

        if ($hasFilter) {
            $countSql = "SELECT COUNT(*) FROM produk
                         WHERE status != 'dihapus' AND kategori = :kategori";
            $stmtCount = $this->db->prepare($countSql);
            $stmtCount->execute([':kategori' => $kategori]);
        } else {
            $countSql  = "SELECT COUNT(*) FROM produk WHERE status != 'dihapus'";
            $stmtCount = $this->db->query($countSql);
        }
        $total = (int) $stmtCount->fetchColumn();

        // --- ROWS ---
        if ($hasFilter) {
            $sql  = "SELECT id_produk, nama_produk, kategori, harga, foto
                     FROM produk
                     WHERE status != 'dihapus' AND kategori = :kategori
                     ORDER BY id_produk DESC
                     LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':kategori', $kategori);
            $stmt->bindValue(':limit',    $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset',  $offset, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql  = "SELECT id_produk, nama_produk, kategori, harga, foto
                     FROM produk
                     WHERE status != 'dihapus'
                     ORDER BY id_produk DESC
                     LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit',   $limit,  PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
        }

        return [
            'rows'        => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total_count' => $total,
        ];
    }
}
