<?php

class ProductModel
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getById(string $id)
    {
        $sql = "SELECT * FROM produk WHERE id_produk = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data)
    {
        $sql = "INSERT INTO produk (id_umkm, nama_produk, kategori, harga, deskripsi, foto) 
                VALUES (:id_umkm, :nama_produk, :kategori, :harga, :deskripsi, :foto)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id_umkm'     => $data['id_umkm'],
            ':nama_produk' => $data['nama_produk'],
            ':kategori'    => $data['kategori'],
            ':harga'       => $data['harga'],
            ':deskripsi'   => $data['deskripsi'],
            ':foto'        => $data['foto']
        ]);
    }

    public function update(string $id, array $data)
    {
        $sql = "UPDATE produk SET 
                nama_produk = :nama_produk, 
                kategori = :kategori, 
                harga = :harga, 
                deskripsi = :deskripsi, 
                foto = :foto 
                WHERE id_produk = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id'          => $id,
            ':nama_produk' => $data['nama_produk'],
            ':kategori'    => $data['kategori'],
            ':harga'       => $data['harga'],
            ':deskripsi'   => $data['deskripsi'],
            ':foto'        => $data['foto']
        ]);
    }

    public function delete(string $id)
    {
        $sql = "UPDATE produk SET status = 'dihapus' WHERE id_produk = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function search(string $keyword)
    {
        $sql = "SELECT * FROM produk WHERE (nama_produk LIKE :keyword OR kategori LIKE :keyword) AND status != 'dihapus'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ambil daftar UMKM milik user untuk opsi dropdown.
     * Hanya UMKM dengan status 'aktif' yang ditampilkan.
     *
     * @param int $id_user ID user yang sedang login
     * @return array Daftar UMKM [ ['id_umkm' => ..., 'nama_umkm' => ...], ... ]
     */
    public function getAllUmkmByUser(int $id_user): array
    {
        $sql = "SELECT id_umkm, nama_umkm
                FROM umkm
                WHERE id_user = :id_user AND status = 'aktif'
                ORDER BY nama_umkm ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated(string $search = '', int $per_page = 3, int $offset = 0)
    {
        if ($search !== '') {
            $total_sql = "SELECT COUNT(*) FROM produk JOIN umkm ON produk.id_umkm = umkm.id_umkm WHERE produk.nama_produk LIKE :keyword AND produk.status != 'dihapus'";
            $stmt_total = $this->db->prepare($total_sql);
            $stmt_total->execute([':keyword' => "%$search%"]);
        } else {
            $total_sql = "SELECT COUNT(*) FROM produk JOIN umkm ON produk.id_umkm = umkm.id_umkm WHERE produk.status != 'dihapus'";
            $stmt_total = $this->db->query($total_sql);
        }
        $total_rows = $stmt_total->fetchColumn();

        if ($search !== '') {
            $sql = "SELECT * FROM produk JOIN umkm ON produk.id_umkm = umkm.id_umkm WHERE produk.nama_produk LIKE :keyword AND produk.status != 'dihapus' ORDER BY umkm.nama_umkm ASC LIMIT $per_page OFFSET $offset";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':keyword' => "%$search%"]);
        } else {
            $sql = "SELECT * FROM produk JOIN umkm ON produk.id_umkm = umkm.id_umkm WHERE produk.status != 'dihapus' ORDER BY umkm.nama_umkm ASC LIMIT $per_page OFFSET $offset";
            $stmt = $this->db->query($sql);
        }

        return [
            'rows' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total_count' => $total_rows
        ];
    }
}
