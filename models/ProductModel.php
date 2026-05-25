<?php

class ProductModel
{
    private $conn;

    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function getAllProducts($search = '', $limit = 3, $offset = 0)
    {
        $sql = "SELECT * FROM produk ";
        $params = [];

        if (!empty($search)) {
            $sql .= "WHERE nama_produk LIKE :search OR kategori LIKE :search ";
            $params[':search'] = "%$search%";
        }

        $sql .= "ORDER BY id_produk DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $params[':search'], PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countAllProducts($search = '')
    {
        $sql = "SELECT COUNT(*) as total FROM produk ";
        $params = [];

        if (!empty($search)) {
            $sql .= "WHERE nama_produk LIKE :search OR kategori LIKE :search ";
            $params[':search'] = "%$search%";
        }

        $stmt = $this->conn->prepare($sql);

        if (!empty($search)) {
            $stmt->bindValue(':search', $params[':search'], PDO::PARAM_STR);
        }

        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }
}
