<?php
require_once __DIR__ . '/../../models/ProductModel.php';

class ProductController
{
    private $productModel;

    public function __construct($db)
    {
        $this->productModel = new ProductModel($db);
    }

    /**
     * Menampilkan daftar produk (Index)
     */
    public function index()
    {
        $search   = isset($_GET['search']) ? trim($_GET['search']) : '';
        $per_page = isset($_GET['show'])   ? (int)$_GET['show']   : 3;
        $page     = isset($_GET['page'])   ? (int)$_GET['page']   : 1;

        if ($per_page <= 0) $per_page = 3;
        if ($page <= 0) $page = 1;

        $offset = ($page - 1) * $per_page;

        $result      = $this->productModel->getPaginated($search, $per_page, $offset);
        $total_pages = ceil($result['total_count'] / $per_page);

        return [
            'products'     => $result['rows'],
            'search'       => $search,
            'per_page'     => $per_page,
            'current_page' => $page,
            'total_pages'  => $total_pages
        ];
    }

    /**
     * Proses Tambah Produk
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_umkm'     => $_POST['id_umkm'],
                'nama_produk' => htmlspecialchars($_POST['nama_produk']),
                'kategori'    => htmlspecialchars($_POST['kategori']),
                'harga'       => (int)$_POST['harga'],
                'deskripsi'   => htmlspecialchars($_POST['deskripsi']),
                'foto'        => $this->uploadFoto($_FILES['foto'])
            ];

            if ($this->productModel->create($data)) {
                header('Location: ../../views/products/index.php?status=success');
                exit;
            }
        }
    }

    /**
     * Ambil produk by ID
     */
    public function getProductById($id)
    {
        return $this->productModel->getById($id);
    }

    /**
     * Proses Update Produk
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldData = $this->productModel->getById($id);

            if ($_FILES['foto']['error'] === UPLOAD_ERR_NO_FILE) {
                $nama_foto_db = $oldData['foto'];
            } else {
                $nama_foto_db = $this->uploadFoto($_FILES['foto']);

                $oldFilePath = __DIR__ . '/../../asset/images/products/' . $oldData['foto'];
                if (!empty($oldData['foto']) && $oldData['foto'] !== 'default.jpg' && file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $data = [
                'id_umkm'     => $_POST['id_umkm'],
                'nama_produk' => htmlspecialchars($_POST['nama_produk']),
                'kategori'    => htmlspecialchars($_POST['kategori']),
                'harga'       => (int)$_POST['harga'],
                'deskripsi'   => htmlspecialchars($_POST['deskripsi']),
                'foto'        => $nama_foto_db
            ];

            if ($this->productModel->update($id, $data)) {
                header('Location: ../../views/products/index.php?status=updated');
                exit;
            }
        }
    }

    /**
     * Proses Hapus Produk
     */
    public function delete($id)
    {
        $oldData = $this->productModel->getById($id);

        if ($this->productModel->delete($id)) {
            header('Location: ../../views/products/index.php?status=deleted');
            exit;
        }
    }

    /**
     * Upload foto ke asset/images/products/
     */
    private function uploadFoto($file)
    {
        if ($file['error'] === UPLOAD_ERR_NO_FILE) return 'default.jpg';

        $ekstensi = pathinfo($file['name'], PATHINFO_EXTENSION);
        $namaBaru = uniqid('prod_', true) . '.' . $ekstensi;
        $targetDir = __DIR__ . '/../../asset/images/products/';

        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        move_uploaded_file($file['tmp_name'], $targetDir . $namaBaru);
        return $namaBaru;
    }
}
