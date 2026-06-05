<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../models/ProfileModel.php';

class ProfileController
{
    private ProfileModel $profileModel;

    public function __construct(PDO $conn)
    {
        $this->profileModel = new ProfileModel($conn);
    }

    private function checkAuth(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id_user = $_SESSION['user_id'] ?? null;

        if (!$id_user) {
            $_SESSION['error'] = 'Silakan login terlebih dahulu.';
            header('Location: ../views/auth/login.php');
            exit;
        }

        return (int) $id_user;
    }

    public function save(): void
    {
        $id_user = $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ../views/profile/index.php');
            exit;
        }

        $nama = trim($_POST['nama'] ?? '');
        $status = trim($_POST['status'] ?? 'aktif');
        $nik = trim($_POST['nik'] ?? '');
        $no_hp = trim($_POST['no_hp'] ?? '');
        $no_kk = trim($_POST['no_kk'] ?? '');

        if ($nama === '' || $nik === '' || $no_hp === '' || $no_kk === '') {
            header('Location: ../views/profile/index.php?status=profil_gagal');
            exit;
        }

        $oldProfile = $this->profileModel->getProfileByUserId($id_user);

        $uploadDir = __DIR__ . '/../storage/private/images/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $foto_ktp = null;
        $foto_kk = null;

        if (isset($_FILES['foto_ktp']) && $_FILES['foto_ktp']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath   = $_FILES['foto_ktp']['tmp_name'];
            $fileName      = $_FILES['foto_ktp']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, ['jpg', 'jpeg', 'png'], true)) {
                header('Location: ../views/profile/index.php?status=format_salah_ktp');
                exit;
            }

            if ($_FILES['foto_ktp']['size'] > 2097152) {
                header('Location: ../views/profile/index.php?status=file_terlalu_besar_ktp');
                exit;
            }

            // Nama file acak (hex 32 karakter) agar tidak bisa ditebak
            $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $destPath    = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $foto_ktp = $newFileName;

                if ($oldProfile && !empty($oldProfile['foto_ktp'])) {
                    $oldPrivate = $uploadDir . $oldProfile['foto_ktp'];
                    $oldLegacy  = __DIR__ . '/../asset/images/' . $oldProfile['foto_ktp'];
                    @unlink(file_exists($oldPrivate) ? $oldPrivate : $oldLegacy);
                }
            }
        }

        if (isset($_FILES['foto_kk']) && $_FILES['foto_kk']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath   = $_FILES['foto_kk']['tmp_name'];
            $fileName      = $_FILES['foto_kk']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExtension, ['jpg', 'jpeg', 'png'], true)) {
                header('Location: ../views/profile/index.php?status=format_salah_kk');
                exit;
            }

            if ($_FILES['foto_kk']['size'] > 2097152) {
                header('Location: ../views/profile/index.php?status=file_terlalu_besar_kk');
                exit;
            }

            // Nama file acak (hex 32 karakter) agar tidak bisa ditebak
            $newFileName = bin2hex(random_bytes(16)) . '.' . $fileExtension;
            $destPath    = $uploadDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $destPath)) {
                $foto_kk = $newFileName;

                if ($oldProfile && !empty($oldProfile['foto_kk'])) {
                    $oldPrivate = $uploadDir . $oldProfile['foto_kk'];
                    $oldLegacy  = __DIR__ . '/../asset/images/' . $oldProfile['foto_kk'];
                    @unlink(file_exists($oldPrivate) ? $oldPrivate : $oldLegacy);
                }
            }
        }

        $success = $this->profileModel->saveProfile(
            $id_user,
            $nama,
            $status,
            $nik,
            $no_hp,
            $no_kk,
            $foto_ktp,
            $foto_kk
        );

        if ($success) {
            $_SESSION['user_nama'] = $nama;
            header('Location: ../views/profile/index.php?status=profil_sukses');
            exit;
        }

        header('Location: ../views/profile/index.php?status=profil_gagal');
        exit;
    }

    public function deleteFile(): void
    {
        $id_user = $this->checkAuth();
        $fileType = $_GET['file_type'] ?? '';

        if ($this->profileModel->deleteProfileFile($id_user, $fileType)) {
            header('Location: ../views/profile/index.php?status=hapus_file_sukses');
            exit;
        }

        header('Location: ../views/profile/index.php?status=hapus_file_gagal');
        exit;
    }
}

if (__FILE__ === realpath($_SERVER['SCRIPT_FILENAME'])) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    require_once __DIR__ . '/../config/koneksi.php';

    $controller = new ProfileController($conn);
    $action = $_GET['action'] ?? '';

    match ($action) {
        'save' => $controller->save(),
        'deleteFile' => $controller->deleteFile(),
        default => header('Location: ../views/profile/index.php'),
    };

    exit;
}