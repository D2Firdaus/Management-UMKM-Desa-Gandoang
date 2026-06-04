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
        $sql  = "SELECT * FROM produk WHERE id_produk = :id";
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
                    kategori    = :kategori,
                    harga       = :harga,
                    deskripsi   = :deskripsi,
                    foto        = :foto
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
        $sql  = "DELETE FROM produk WHERE id_produk = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getPaginated($search = '', $per_page = 3, $offset = 0)
    {
        if ($search !== '') {
            $total_sql  = "SELECT COUNT(*) FROM produk WHERE (nama_produk LIKE :keyword OR kategori LIKE :keyword)";
            $stmt_total = $this->db->prepare($total_sql);
            $stmt_total->execute([':keyword' => "%$search%"]);
        } else {
            $total_sql  = "SELECT COUNT(*) FROM produk";
            $stmt_total = $this->db->query($total_sql);
        }
        $total_rows = $stmt_total->fetchColumn();

        if ($search !== '') {
            $sql  = "SELECT * FROM produk WHERE (nama_produk LIKE :keyword OR kategori LIKE :keyword)
                     ORDER BY id_produk DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':keyword', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':limit',   (int)$per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset',  (int)$offset,   PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $sql  = "SELECT * FROM produk ORDER BY id_produk DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':limit',  (int)$per_page, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset,   PDO::PARAM_INT);
            $stmt->execute();
        }

        return [
            'rows'        => $stmt->fetchAll(PDO::FETCH_ASSOC),
            'total_count' => $total_rows
        ];
    }

    public function countAllProducts($search = '')
    {
        if ($search !== '') {
            $sql  = "SELECT COUNT(*) as total FROM produk WHERE (nama_produk LIKE :search OR kategori LIKE :search)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':search' => "%$search%"]);
        } else {
            $sql  = "SELECT COUNT(*) as total FROM produk";
            $stmt = $this->db->query($sql);
        }
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
