<?php
require_once __DIR__ . '/../../models/productModel/ProductModel.php';

class ProductController
{
    private $productModel;

    public function __construct($db)
    {
        // Inisialisasi Model dengan koneksi database
        $this->productModel = new ProductModel($db);
    }

    /**
     * Menampilkan daftar produk (Index)
     */
    public function index()
    {
        // Ambil parameter dari URL
        $search   = isset($_GET['search']) ? trim($_GET['search']) : '';
        $per_page = isset($_GET['show'])   ? (int)$_GET['show']    : 3;
        $page     = isset($_GET['page'])   ? (int)$_GET['page']    : 1;
        $offset   = ($page - 1) * $per_page;

        // Panggil Model
        $result = $this->productModel->getPaginated($search, $per_page, $offset);

        // Hitung total halaman
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
                'nama_produk' => $_POST['nama_produk'],
                'kategori'    => $_POST['kategori'],
                'harga'       => $_POST['harga'],
                'deskripsi'   => $_POST['deskripsi'],
                'foto'        => $this->uploadFoto($_FILES['foto'])
            ];

            if ($this->productModel->create($data)) {
                header('Location: ../../views/products/index.php?status=success');
                exit;
            }
        }
    }

    public function getProductById($id)
    {
        return $this->productModel->getById($id);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldData = $this->productModel->getById($id);

            if ($_FILES['foto']['error'] === 4) {
                $nama_foto_db = $oldData['foto'];
            } else {
                $nama_foto_db = $this->uploadFoto($_FILES['foto']);

                $oldFilePath = __DIR__ . '/../../asset/images/products/' . $oldData['foto'];
                if ($oldData['foto'] !== 'default.jpg' && file_exists($oldFilePath)) {
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

    public function delete($id)
    {
        $oldData = $this->productModel->getById($id);

        if ($this->productModel->delete($id)) {
            header('Location: ../../views/products/index.php?status=deleted');
            exit;
        }
    }

    private function uploadFoto($file)
    {
        if ($file['error'] === 4) return 'default.jpg';

        $namaFile   = $file['name'];
        $tmpName    = $file['tmp_name'];
        $ekstensi   = pathinfo($namaFile, PATHINFO_EXTENSION);
        $namaBaru   = uniqid() . '.' . $ekstensi;

        $targetDir = __DIR__ . '/../../asset/images/products/';

        if (!@mkdir($targetDir, 0755, true)) {
        }

        move_uploaded_file($tmpName, $targetDir . $namaBaru);
        return $namaBaru;
    }
}
