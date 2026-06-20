<?php
require_once __DIR__ . '/../../models/ProductModel.php';


class ProductController
{
    private ProductModel $productModel;

    public function __construct(PDO $db)
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->productModel = new ProductModel($db);
    }

    public function getUmkmList(int $id_user): array
    {
        return $this->productModel->getAllUmkmByUser($id_user);
    }

    public function index(int $id_user)
    {
        $search   = isset($_GET['search']) ? trim($_GET['search']) : '';
        $per_page = isset($_GET['show'])   ? (int)$_GET['show']    : 3;
        $page     = isset($_GET['page'])   ? (int)$_GET['page']    : 1;
        $offset   = ($page - 1) * $per_page;

        $result = $this->productModel->getPaginated($id_user, $search, $per_page, $offset);

        $total_pages = ceil($result['total_count'] / $per_page);

        return [
            'products'     => $result['rows'],
            'search'       => $search,
            'per_page'     => $per_page,
            'current_page' => $page,
            'total_pages'  => $total_pages
        ];
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['user_id'] ?? null;
            if (!$id_user) {
                header('Location: ../../views/auth/login.php');
                exit;
            }

            $id_umkm = $_POST['id_umkm'] ?? null;
            $umkm_list = $this->getUmkmList((int)$id_user);
            $allowed_umkm_ids = array_column($umkm_list, 'id_umkm');
            if (!$id_umkm || !in_array($id_umkm, $allowed_umkm_ids)) {
                header('Location: ../../views/products/index.php');
                exit;
            }

            $foto = $this->uploadFoto($_FILES['foto']);
            
            if ($foto === 'default.jpg') {
                header('Location: ../../views/products/addProduct.php?status=image_required');
                exit;
            }

            $data = [
                'id_umkm'     => $id_umkm,
                'nama_produk' => htmlspecialchars($_POST['nama_produk']),
                'kategori'    => htmlspecialchars($_POST['kategori']),
                'harga'       => (int)$_POST['harga'],
                'deskripsi'   => htmlspecialchars($_POST['deskripsi']),
                'foto'        => $foto
            ];

            if ($this->productModel->create($data)) {
                header('Location: ../../views/products/index.php?status=success');
                exit;
            }
        }
    }

    public function getProductById(string $id): array|false
    {
        return $this->productModel->getById($id);
    }

    public function getProductByIdAndUser(string $id, int $id_user): array|false
    {
        return $this->productModel->getByIdAndUser($id, $id_user);
    }

    public function update(string $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['user_id'] ?? null;
            if (!$id_user) {
                header('Location: ../../views/auth/login.php');
                exit;
            }

            $oldData = $this->productModel->getByIdAndUser($id, (int)$id_user);
            if (!$oldData) {
                header('Location: ../../views/products/index.php');
                exit;
            }

            $id_umkm = $_POST['id_umkm'] ?? null;
            $umkm_list = $this->getUmkmList((int)$id_user);
            $allowed_umkm_ids = array_column($umkm_list, 'id_umkm');
            if (!$id_umkm || !in_array($id_umkm, $allowed_umkm_ids)) {
                header('Location: ../../views/products/index.php');
                exit;
            }

            // Ambil file lama yang tidak dihapus oleh user
            $existing_fotos = $_POST['existing_foto'] ?? [];

            // Proses upload file baru jika ada
            $new_foto_db = '';
            if (isset($_FILES['foto']['error']) && $_FILES['foto']['error'][0] !== 4) {
                $new_foto_db = $this->uploadFoto($_FILES['foto']);
            }

            $new_fotos_arr = (!empty($new_foto_db) && $new_foto_db !== 'default.jpg') ? explode(',', $new_foto_db) : [];
            $combined_fotos = array_merge($existing_fotos, $new_fotos_arr);

            if (empty($combined_fotos)) {
                header('Location: ../../views/products/editProduct.php?id=' . $id . '&status=image_required');
                exit;
            } else {
                $nama_foto_db = implode(',', $combined_fotos);
            }

            // Hapus file fisik dari storage jika file lama tersebut dihapus oleh user
            if ($oldData['foto'] !== 'default.jpg' && !empty($oldData['foto'])) {
                $oldImages = explode(',', $oldData['foto']);
                foreach ($oldImages as $oldImage) {
                    $oldImageTrimmed = trim($oldImage);
                    if (!empty($oldImageTrimmed) && !in_array($oldImageTrimmed, $existing_fotos)) {
                        $oldFilePath = __DIR__ . '/../../storage/images/products/' . $oldImageTrimmed;
                        if (file_exists($oldFilePath)) {
                            unlink($oldFilePath);
                        }
                    }
                }
            }

            $data = [
                'id_umkm'     => $id_umkm,
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

    public function delete(string $id)
    {
        $id_user = $_SESSION['user_id'] ?? null;
        if (!$id_user) {
            header('Location: ../../views/auth/login.php');
            exit;
        }

        $oldData = $this->productModel->getByIdAndUser($id, (int)$id_user);
        if (!$oldData) {
            header('Location: ../../views/products/index.php');
            exit;
        }

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

        $targetDir = __DIR__ . '/../../storage/images/products/';
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
