<?php
declare(strict_types=1);

/**
 * FileController
 *
 * Melayani file sensitif (foto KTP / foto KK) secara aman:
 *   - Hanya pengguna yang sudah login yang bisa mengakses file miliknya sendiri.
 *   - File disimpan di storage/private/ yang dilindungi .htaccess (tidak bisa diakses langsung).
 *   - Fallback ke asset/images/ untuk mendukung file lama (legacy upload).
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autentikasi: hanya user yang sudah login
$id_user = $_SESSION['user_id'] ?? null;
if (!$id_user) {
    http_response_code(403);
    header('Content-Type: text/plain');
    exit('403 Forbidden – Silakan login terlebih dahulu.');
}

require_once __DIR__ . '/../config/koneksi.php';
require_once __DIR__ . '/../models/ProfileModel.php';

// Validasi parameter tipe file (whitelist ketat)
$fileType = $_GET['type'] ?? '';
if (!in_array($fileType, ['foto_ktp', 'foto_kk'], true)) {
    http_response_code(400);
    exit;
}

// Ambil profil milik user yang sedang login (bukan user lain)
$profileModel = new ProfileModel($conn);
$profile      = $profileModel->getProfileByUserId((int) $id_user);

if (!$profile || empty($profile[$fileType])) {
    http_response_code(404);
    exit;
}

$filename = $profile[$fileType];

// Cari file: private storage dulu, fallback ke legacy asset/images
$privatePath = __DIR__ . '/../storage/private/' . $filename;
$legacyPath  = __DIR__ . '/../asset/images/'    . $filename;

if (file_exists($privatePath)) {
    $filePath = $privatePath;
} elseif (file_exists($legacyPath)) {
    $filePath = $legacyPath;
} else {
    http_response_code(404);
    exit;
}

// Tentukan MIME type berdasarkan ekstensi (whitelist)
$ext  = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
$mime = match ($ext) {
    'jpg', 'jpeg' => 'image/jpeg',
    'png'         => 'image/png',
    default       => 'application/octet-stream',
};

// Kirim file ke browser dengan header keamanan
header('Content-Type: '   . $mime);
header('Content-Length: ' . filesize($filePath));
header('Cache-Control: private, max-age=3600');
header('X-Content-Type-Options: nosniff');
header('Content-Disposition: inline');
readfile($filePath);
exit;
