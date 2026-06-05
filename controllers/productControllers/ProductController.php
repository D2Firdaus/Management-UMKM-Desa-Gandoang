<?php
require_once __DIR__ . '/../../models/ProductModel.php';


class ProductController
{
    private ProductModel $productModel;

    public function __construct(PDO $db)
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
                'nama_produk' => htmlspecialchars($_POST['nama_produk']),
                'kategori'    => htmlspecialchars($_POST['kategori']),
                'harga'       => (int)$_POST['harga'],
                'deskripsi'   => htmlspecialchars($_POST['deskripsi']),
                // Mengirimkan array $_FILES['foto'] ke fungsi upload multiple
                'foto'        => $this->uploadFoto($_FILES['foto'])
            ];

            if ($this->productModel->create($data)) {
                header('Location: ../../views/products/index.php?status=success');
                exit;
            }
        }
    }

    public function getProductById(int $id): array|false
    {
        return $this->productModel->getById($id);
    }

    public function update(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $oldData = $this->productModel->getById($id);

            // Cek apakah user tidak mengupload foto baru sama sekali
            // Pada multiple upload, jika kosong, elemen pertama error-nya bernilai 4
            if (!isset($_FILES['foto']['error']) || $_FILES['foto']['error'][0] === 4) {
                $nama_foto_db = $oldData['foto'];
            } else {
                // Upload foto-foto baru
                $nama_foto_db = $this->uploadFoto($_FILES['foto']);

                // Hapus foto-foto lama dari storage folder
                if ($oldData['foto'] !== 'default.jpg' && !empty($oldData['foto'])) {
                    // Pecah string nama file yang dipisah koma menjadi array
                    $oldImages = explode(',', $oldData['foto']);
                    foreach ($oldImages as $oldImage) {
                        $oldFilePath = __DIR__ . '/../../asset/images/products/' . trim($oldImage);
                        if (file_exists($oldFilePath) && !empty($oldImage)) {
                            unlink($oldFilePath);
                        }
                    }
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

    public function delete(int $id)
    {
        $oldData = $this->productModel->getById($id);

        if ($this->productModel->delete($id)) {
            header('Location: ../../views/products/index.php?status=deleted');
            exit;
        }
    }

    private function uploadFoto(array $files): string
    {
        if (!isset($files['name']) || (is_array($files['error']) && $files['error'][0] === 4)) {
            return 'default.jpg';
        }

        $targetDir = __DIR__ . '/../../asset/images/products/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $uploadedFiles = [];
        if (is_array($files['name'])) {
            $totalFiles = count($files['name']);
            
            $limit = min($totalFiles, 3);

            for ($i = 0; $i < $limit; $i++) {
                if ($files['error'][$i] === 0) {
                    $namaFile   = $files['name'][$i];
                    $tmpName    = $files['tmp_name'][$i];
                    $ekstensi   = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
                    
                    $ekstensiDiperbolehkan = ['jpg', 'jpeg', 'png', 'webp'];
                    if (in_array($ekstensi, $ekstensiDiperbolehkan)) {
                        $namaBaru   = uniqid() . '_' . $i . '.' . $ekstensi;
                        
                        if (move_uploaded_file($tmpName, $targetDir . $namaBaru)) {
                            $uploadedFiles[] = $namaBaru;
                        }
                    }
                }
            }
        } else {
            if ($files['error'] === 0) {
                $namaFile   = $files['name'];
                $tmpName    = $files['tmp_name'];
                $ekstensi   = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
                $namaBaru   = uniqid() . '.' . $ekstensi;
                
                if (move_uploaded_file($tmpName, $targetDir . $namaBaru)) {
                    $uploadedFiles[] = $namaBaru;
                }
            }
        }

        if (!empty($uploadedFiles)) {
            return implode(',', $uploadedFiles);
        }

        return 'default.jpg';
    }
}
