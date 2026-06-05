<?php
require_once __DIR__ . '/../../models/JourneyModel.php';
require_once __DIR__ . '/../../models/UmkmModel.php';

class JourneyController
{
    private JourneyModel $journeyModel;
    private UmkmModel $umkmModel;

    public function __construct(PDO $db)
    {
        $this->journeyModel = new JourneyModel($db);
        $this->umkmModel = new UmkmModel($db);
    }

    public function index()
    {
        $id_user = $_SESSION['user_id'] ?? 0;
        
        $search   = isset($_GET['search']) ? trim($_GET['search']) : '';
        $per_page = isset($_GET['show'])   ? (int)$_GET['show']    : 3;
        $page     = isset($_GET['page'])   ? (int)$_GET['page']    : 1;
        $offset   = ($page - 1) * $per_page;

        $rows = $this->journeyModel->getPaginatedByUser($id_user, $search, $per_page, $offset);
        $total_count = $this->journeyModel->countByUser($id_user, $search);
        
        $total_pages = ceil($total_count / $per_page);

        return [
            'journeys'     => $rows,
            'search'       => $search,
            'per_page'     => $per_page,
            'current_page' => $page,
            'total_pages'  => $total_pages
        ];
    }

    public function create()
    {
        $id_user = $_SESSION['user_id'] ?? 0;
        return $this->umkmModel->getAllDropdownByUser($id_user);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'id_umkm'   => $_POST['id_umkm'],
                'tanggal'   => $_POST['tanggal'],
                'deskripsi' => htmlspecialchars($_POST['deskripsi']),
                'foto'      => $this->uploadFoto($_FILES['foto'])
            ];

            if ($this->journeyModel->create($data)) {
                header('Location: ../../views/journey/index.php?status=success');
                exit;
            }
        }
    }

    public function edit(int $id)
    {
        $id_user = $_SESSION['user_id'] ?? 0;
        $journey = $this->journeyModel->getById($id, $id_user);
        $umkm_list = $this->umkmModel->getAllDropdownByUser($id_user);
        
        return [
            'journey' => $journey,
            'umkm_list' => $umkm_list
        ];
    }

    public function update(int $id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_user = $_SESSION['user_id'] ?? 0;
            $oldData = $this->journeyModel->getById($id, $id_user);

            if (!$oldData) {
                header('Location: ../../views/journey/index.php?status=error');
                exit;
            }

            if (!isset($_FILES['foto']['error']) || $_FILES['foto']['error'] === 4) {
                $nama_foto_db = $oldData['foto'];
            } else {
                $nama_foto_db = $this->uploadFoto($_FILES['foto']);

                if ($oldData['foto'] !== 'default.jpg' && !empty($oldData['foto'])) {
                    $oldFilePath = __DIR__ . '/../../asset/images/journey/' . trim($oldData['foto']);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }
            }

            $data = [
                'id_umkm'   => $_POST['id_umkm'],
                'tanggal'   => $_POST['tanggal'],
                'deskripsi' => htmlspecialchars($_POST['deskripsi']),
                'foto'      => $nama_foto_db
            ];

            if ($this->journeyModel->update($id, $data)) {
                header('Location: ../../views/journey/index.php?status=updated');
                exit;
            }
        }
    }

    public function delete(int $id)
    {
        $id_user = $_SESSION['user_id'] ?? 0;
        $oldData = $this->journeyModel->getById($id, $id_user);

        if ($oldData) {
            if ($oldData['foto'] !== 'default.jpg' && !empty($oldData['foto'])) {
                $oldFilePath = __DIR__ . '/../../asset/images/journey/' . trim($oldData['foto']);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            if ($this->journeyModel->delete($id)) {
                header('Location: ../../views/journey/index.php?status=deleted');
                exit;
            }
        } else {
            header('Location: ../../views/journey/index.php?status=error');
            exit;
        }
    }

    private function uploadFoto(array $file): string
    {
        if (!isset($file['name']) || $file['error'] === 4) {
            return 'default.jpg';
        }

        $targetDir = __DIR__ . '/../../asset/images/journey/';
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $namaFile   = $file['name'];
        $tmpName    = $file['tmp_name'];
        $ekstensi   = strtolower(pathinfo($namaFile, PATHINFO_EXTENSION));
        
        $ekstensiDiperbolehkan = ['jpg', 'jpeg', 'png', 'webp'];
        if (in_array($ekstensi, $ekstensiDiperbolehkan)) {
            $namaBaru   = uniqid() . '.' . $ekstensi;
            
            if (move_uploaded_file($tmpName, $targetDir . $namaBaru)) {
                return $namaBaru;
            }
        }

        return 'default.jpg';
    }
}
