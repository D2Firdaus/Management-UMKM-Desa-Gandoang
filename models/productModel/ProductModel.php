<?php

class ProductModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
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

    public function update($id, $data)
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

    public function delete($id)
    {
        $sql = "UPDATE produk SET status = 'dihapus' WHERE id_produk = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function search($keyword)
    {
        $sql = "SELECT * FROM produk WHERE (nama_produk LIKE :keyword OR kategori LIKE :keyword) AND status != 'dihapus'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':keyword' => "%$keyword%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPaginated($search = '', $per_page = 3, $offset = 0)
    {
        if ($search !== '') {
            $total_sql = "SELECT COUNT(*) FROM produk WHERE nama_produk LIKE :keyword AND status != 'dihapus'";
            $stmt_total = $this->db->prepare($total_sql);
            $stmt_total->execute([':keyword' => "%$search%"]);
        } else {
            $total_sql = "SELECT COUNT(*) FROM produk WHERE status != 'dihapus'";
            $stmt_total = $this->db->query($total_sql);
        }
        $total_rows = $stmt_total->fetchColumn();

        if ($search !== '') {
            $sql = "SELECT * FROM produk WHERE nama_produk LIKE :keyword AND status != 'dihapus' ORDER BY id_produk ASC LIMIT $per_page OFFSET $offset";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':keyword' => "%$search%"]);
        } else {
            $sql = "SELECT * FROM produk WHERE status != 'dihapus' ORDER BY id_produk ASC LIMIT $per_page OFFSET $offset";
            $stmt = $this->db->query($sql);
        }

        return [
            'rows' => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total_count' => $total_rows
        ];
    }
}
