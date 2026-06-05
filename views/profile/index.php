<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/koneksi.php';
require_once __DIR__ . '/../../config/path_config.php';
require_once __DIR__ . '/../../models/ProfileModel.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$id_user = $_SESSION['user_id'] ?? null;

if (!$id_user) {
    $_SESSION['error'] = 'Silakan login terlebih dahulu.';
    header('Location: ' . $base_url . '/views/auth/login.php');
    exit;
}

$profileModel = new ProfileModel($conn);
$profile = $profileModel->getProfileByUserId((int) $id_user);

$nama = $profile['nama'] ?? $_SESSION['user_nama'] ?? '';
$email = $profile['email'] ?? $_SESSION['user_email'] ?? '';
$status = $profile['status'] ?? 'aktif';
$nik = $profile['nik'] ?? '';
$no_hp = $profile['no_hp'] ?? '';
$no_kk = $profile['no_kk'] ?? '';
$foto_ktp = $profile['foto_ktp'] ?? '';
$foto_kk = $profile['foto_kk'] ?? '';

function getFileSizeFormatted(string $filename): string
{
    $privatePath = __DIR__ . '/../../storage/private/' . $filename;
    $legacyPath  = __DIR__ . '/../../asset/images/'    . $filename;
    $filePath    = file_exists($privatePath) ? $privatePath : $legacyPath;

    if (file_exists($filePath)) {
        $bytes = filesize($filePath);

        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 0) . ' Kb';
        }

        return $bytes . ' B';
    }

    return '0 Kb';
}

function statusPopup(): void
{
    global $base_url;
    if (!isset($_GET['status'])) {
        return;
    }

    $status = $_GET['status'];

    $popupData = [
        'profil_sukses' => [
            'icon'    => 'sukses.png',
            'title'   => 'Berhasil<br>Menyimpan',
            'message' => 'Profil Berhasil Disimpan',
        ],
        'profil_gagal' => [
            'icon'    => 'hapus_alert.png',
            'title'   => 'Gagal<br>Menyimpan',
            'message' => 'Profil Gagal Disimpan',
        ],
        'hapus_file_sukses' => [
            'icon'    => 'sukses.png',
            'title'   => 'Berhasil<br>Menghapus',
            'message' => 'Berkas Profil Berhasil Dihapus',
        ],
        'hapus_file_gagal' => [
            'icon'    => 'hapus_alert.png',
            'title'   => 'Gagal<br>Menghapus',
            'message' => 'Berkas Profil Gagal Dihapus',
        ],
        'file_terlalu_besar_ktp' => [
            'icon'    => 'hapus_alert.png',
            'title'   => 'File<br>Terlalu Besar',
            'message' => 'Foto KTP melebihi batas maksimal 1 MB',
        ],
        'file_terlalu_besar_kk' => [
            'icon'    => 'hapus_alert.png',
            'title'   => 'File<br>Terlalu Besar',
            'message' => 'Foto KK melebihi batas maksimal 1 MB',
        ],
        'format_salah_ktp' => [
            'icon'    => 'hapus_alert.png',
            'title'   => 'Format<br>Tidak Valid',
            'message' => 'Foto KTP harus berformat JPG, JPEG, atau PNG',
        ],
        'format_salah_kk' => [
            'icon'    => 'hapus_alert.png',
            'title'   => 'Format<br>Tidak Valid',
            'message' => 'Foto KK harus berformat JPG, JPEG, atau PNG',
        ],
    ];

    if (!isset($popupData[$status])) {
        return;
    }

    $data = $popupData[$status];
    ?>
    <div class="alert_sukses_menambah">
        <div class="box_sukses_menambah">
            <div class="icon_sukses_menambah">
                <img src="<?= $base_url ?>asset/icon/<?= htmlspecialchars($data['icon']) ?>" alt="Status">
            </div>

            <h2><?= $data['title'] ?></h2>
            <p><?= htmlspecialchars($data['message']) ?></p>

            <a href="index.php" class="tombol_sukses_menambah">Tutup</a>
        </div>
    </div>
    <?php
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - UMKM Desa Gandoang</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap" rel="stylesheet">

    <link href="<?= $base_url ?>asset/boostrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $base_url ?>asset/css/profile.css" rel="stylesheet">
</head>

<body>
    <div class="wrapper">
        <?php require_once __DIR__ . '/../layouts/sidebar_user.php'; ?>

        <div class="main">
            <?php require_once __DIR__ . '/../layouts/navbar_user.php'; ?>

            <div class="content">
                <div class="card-dashboard">
                    <div class="profile-header">
                        <div class="profile-header-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                                viewBox="0 0 16 16">
                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6" />
                            </svg>
                        </div>

                        <div class="profile-header-text">
                            <h2>Profile</h2>
                            <p>Kelola informasi profile dan data diri Anda</p>
                        </div>
                    </div>

                    <form id="profileForm" action="<?= $base_url ?>controllers/ProfileController.php?action=save" method="POST"
                        enctype="multipart/form-data">
                        <div class="form-grid">

                            <div class="form-label">Nama</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <input type="text" name="nama" class="form-control-input"
                                    value="<?= htmlspecialchars($nama) ?>" required
                                    placeholder="Masukkan Nama Sesuai KTP">
                            </div>

                            <div class="form-label">No. HP</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <input type="text" name="no_hp" class="form-control-input"
                                    value="<?= htmlspecialchars($no_hp) ?>" required
                                    placeholder="Contoh: 081234567890">
                            </div>

                            <div class="form-label">Email</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <input type="email" class="form-control-input"
                                    value="<?= htmlspecialchars($email) ?>" readonly
                                    placeholder="Email Anda">
                            </div>

                            <div class="form-label">NIK</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <input type="text" name="nik" class="form-control-input"
                                    value="<?= htmlspecialchars($nik) ?>" required maxlength="16"
                                    inputmode="numeric" pattern="[0-9]{16}"
                                    placeholder="Masukkan 16 digit NIK">
                            </div>

                            <div class="form-label">No. KK</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <input type="text" name="no_kk" class="form-control-input"
                                    value="<?= htmlspecialchars($no_kk) ?>" required maxlength="16"
                                    inputmode="numeric" pattern="[0-9]{16}"
                                    placeholder="Masukkan 16 digit No Kartu Keluarga">
                            </div>

                            <div class="form-label">Foto KTP</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <div class="file-upload-row">
                                    <div class="upload-btn-wrapper">
                                        <button class="btn-upload" type="button">
                                            <span>☁</span>
                                            Upload
                                        </button>
                                        <input type="file" name="foto_ktp" accept="image/jpeg,image/jpg,image/png"
                                            id="input_ktp" onchange="updateFileName('ktp')">
                                    </div>

                                    <?php
                                    $ktpExists = !empty($foto_ktp) && (
                                        file_exists(__DIR__ . '/../../storage/private/' . $foto_ktp) ||
                                        file_exists(__DIR__ . '/../../asset/images/' . $foto_ktp)
                                    );
                                    if ($ktpExists): ?>
                                        <div class="file-preview-card" id="preview_ktp_container">
                                            <div class="file-preview-info">
                                                <img src="<?= $base_url ?>controllers/FileController.php?type=foto_ktp"
                                                    class="file-preview-thumbnail" alt="Foto KTP">
                                                <div class="file-preview-details">
                                                    <span class="file-preview-name" title="Dokumen KTP tersimpan">
                                                        KTP Tersimpan
                                                    </span>
                                                    <span class="file-preview-size">
                                                        <?= getFileSizeFormatted($foto_ktp) ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <a href="<?= $base_url ?>controllers/ProfileController.php?action=deleteFile&file_type=foto_ktp"
                                                class="btn-delete-file"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto KTP ini?')">
                                                🗑
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="file-preview-card d-none" id="new_preview_ktp">
                                        <div class="file-preview-info">
                                            <img id="new_ktp_thumb" src="" alt="Preview KTP"
                                                class="file-preview-thumbnail" style="display:none;">
                                            <div class="file-preview-details">
                                                <span class="file-preview-name" id="new_ktp_name">File terpilih</span>
                                                <span class="file-preview-size" id="new_ktp_size">Belum diunggah</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-label">Foto KK</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <div class="file-upload-row">
                                    <div class="upload-btn-wrapper">
                                        <button class="btn-upload" type="button">
                                            <span>☁</span>
                                            Upload
                                        </button>
                                        <input type="file" name="foto_kk" accept="image/jpeg,image/jpg,image/png"
                                            id="input_kk" onchange="updateFileName('kk')">
                                    </div>

                                    <?php
                                    $kkExists = !empty($foto_kk) && (
                                        file_exists(__DIR__ . '/../../storage/private/' . $foto_kk) ||
                                        file_exists(__DIR__ . '/../../asset/images/' . $foto_kk)
                                    );
                                    if ($kkExists): ?>
                                        <div class="file-preview-card" id="preview_kk_container">
                                            <div class="file-preview-info">
                                                <img src="<?= $base_url ?>controllers/FileController.php?type=foto_kk"
                                                    class="file-preview-thumbnail" alt="Foto KK">
                                                <div class="file-preview-details">
                                                    <span class="file-preview-name" title="Dokumen KK tersimpan">
                                                        KK Tersimpan
                                                    </span>
                                                    <span class="file-preview-size">
                                                        <?= getFileSizeFormatted($foto_kk) ?>
                                                    </span>
                                                </div>
                                            </div>

                                            <a href="<?= $base_url ?>controllers/ProfileController.php?action=deleteFile&file_type=foto_kk"
                                                class="btn-delete-file"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus foto KK ini?')">
                                                🗑
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <div class="file-preview-card d-none" id="new_preview_kk">
                                        <div class="file-preview-info">
                                            <img id="new_kk_thumb" src="" alt="Preview KK"
                                                class="file-preview-thumbnail" style="display:none;">
                                            <div class="file-preview-details">
                                                <span class="file-preview-name" id="new_kk_name">File terpilih</span>
                                                <span class="file-preview-size" id="new_kk_size">Belum diunggah</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-label">Status</div>
                            <div class="form-colon">:</div>
                            <div class="form-control-wrap">
                                <select name="status" class="select-status">
                                    <option value="aktif" <?= $status === 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                    <option value="nonaktif" <?= $status === 'nonaktif' ? 'selected' : '' ?>>Nonaktif</option>
                                </select>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn-submit">
                                    <img src="<?= $base_url ?>asset/icon/simpan.png" alt="Simpan" class="icon-simpan">
                                    Simpan
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Validasi Client-Side -->
    <div class="alert_sukses_menambah" id="validationModal" style="display:none;">
        <div class="box_sukses_menambah">
            <div class="icon_sukses_menambah">
                <img src="<?= $base_url ?>asset/icon/hapus_alert.png" alt="Validasi Gagal">
            </div>
            <h2>Validasi<br>Gagal</h2>
            <p id="validationModalMessage">-</p>
            <button type="button"
                    onclick="document.getElementById('validationModal').style.display='none'"
                    class="tombol_sukses_menambah"
                    style="cursor:pointer;border:none;font-family:inherit;">
                Tutup
            </button>
        </div>
    </div>

    <?php statusPopup(); ?>

    <script src="<?= $base_url ?>asset/boostrap/js/bootstrap.bundle.min.js"></script>

    <script>
        // ─── Preview gambar yang baru dipilih ────────────────────────────────────
        function updateFileName(type) {
            const input        = document.getElementById('input_' + type);
            const previewCard  = document.getElementById('new_preview_' + type);
            const nameSpan     = document.getElementById('new_' + type + '_name');
            const sizeSpan     = document.getElementById('new_' + type + '_size');
            const thumbImg     = document.getElementById('new_' + type + '_thumb');
            const oldContainer = document.getElementById('preview_' + type + '_container');

            if (!input || !previewCard || !nameSpan) return;

            if (oldContainer) oldContainer.classList.add('d-none');

            if (input.files && input.files[0]) {
                const file = input.files[0];
                nameSpan.textContent = file.name;

                if (sizeSpan) {
                    sizeSpan.textContent = file.size >= 1048576
                        ? (file.size / 1048576).toFixed(2) + ' MB'
                        : Math.round(file.size / 1024) + ' Kb';
                }

                if (thumbImg) {
                    thumbImg.src = URL.createObjectURL(file);
                    thumbImg.style.display = 'block';
                }

                previewCard.classList.remove('d-none');
            } else {
                previewCard.classList.add('d-none');
                if (oldContainer) oldContainer.classList.remove('d-none');
            }
        }

        // ─── Batasi NIK & No. KK: hanya angka, maks 16 karakter ─────────────────
        ['nik', 'no_kk'].forEach(function (fieldName) {
            const el = document.querySelector('input[name="' + fieldName + '"]');
            if (!el) return;
            el.addEventListener('input', function () {
                this.value = this.value.replace(/\D/g, '').slice(0, 16);
            });
            el.addEventListener('keypress', function (e) {
                if (!/[0-9]/.test(e.key) && e.key !== 'Enter') {
                    e.preventDefault();
                }
            });
        });

        // ─── Tampilkan modal validasi gagal ──────────────────────────────────────
        function showValidationModal(message) {
            document.getElementById('validationModalMessage').innerHTML = message;
            document.getElementById('validationModal').style.display = 'flex';
        }

        // ─── Validasi form sebelum dikirim ke server ──────────────────────────────
        document.getElementById('profileForm').addEventListener('submit', function (e) {
            const nikInput  = document.querySelector('input[name="nik"]');
            const noKkInput = document.querySelector('input[name="no_kk"]');
            const nik       = nikInput  ? nikInput.value.trim()  : '';
            const noKk      = noKkInput ? noKkInput.value.trim() : '';

            // — Validasi NIK —
            if (nik === '') {
                showValidationModal('NIK wajib diisi.');
                e.preventDefault(); return;
            }
            if (!/^\d+$/.test(nik)) {
                showValidationModal('NIK hanya boleh berisi <strong>angka</strong>.');
                e.preventDefault(); return;
            }
            if (nik.length !== 16) {
                showValidationModal(
                    'NIK harus tepat <strong>16 digit</strong> angka.<br>' +
                    'Saat ini Anda memasukkan <strong>' + nik.length + ' digit</strong>.'
                );
                e.preventDefault(); return;
            }

            // — Validasi No. KK —
            if (noKk === '') {
                showValidationModal('No. Kartu Keluarga wajib diisi.');
                e.preventDefault(); return;
            }
            if (!/^\d+$/.test(noKk)) {
                showValidationModal('No. KK hanya boleh berisi <strong>angka</strong>.');
                e.preventDefault(); return;
            }
            if (noKk.length !== 16) {
                showValidationModal(
                    'No. KK harus tepat <strong>16 digit</strong> angka.<br>' +
                    'Saat ini Anda memasukkan <strong>' + noKk.length + ' digit</strong>.'
                );
                e.preventDefault(); return;
            }
        });

        // ─── Auto-dismiss popup server-side setelah 3 detik ──────────────────────
        setTimeout(function () {
            const alertBox = document.querySelector('.alert_sukses_menambah:not(#validationModal)');
            if (alertBox) {
                alertBox.style.display = 'none';
                const url = new URL(window.location.href);
                url.searchParams.delete('status');
                window.history.replaceState({}, document.title, url.pathname);
            }
        }, 3000);
    </script>
</body>

</html>