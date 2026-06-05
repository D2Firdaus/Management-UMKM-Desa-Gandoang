<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/Encryption.php';

/**
 * ProfileModel
 *
 * Mengelola interaksi database untuk tabel 'user' dan 'profile'.
 *
 * Kolom yang dienkripsi secara transparan (AES-256-CBC via Encryption::class):
 *   - profile.nik
 *   - profile.no_hp
 *   - profile.no_kk
 *
 * Controller dan View tidak perlu tahu tentang enkripsi — semua
 * ditangani di dalam model ini.
 */
class ProfileModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->checkAndFixSchema();
    }

    /**
     * Menjamin skema database sudah siap untuk menyimpan data terenkripsi:
     *   1. Kolom nik, no_hp, no_kk diperluas menjadi TEXT.
     *   2. UNIQUE KEY yang mereferensikan ketiga kolom tersebut dihapus
     *      (ciphertext tidak bisa digunakan sebagai unique key yang bermakna).
     *   3. Kolom foto_ktp ditambahkan jika belum ada.
     */
    private function checkAndFixSchema(): void
    {
        try {
            // --- Perluas kolom nik menjadi TEXT jika masih VARCHAR ---
            $stmtNik = $this->conn->query("SHOW COLUMNS FROM `profile` LIKE 'nik'");
            $colNik  = $stmtNik->fetch(PDO::FETCH_ASSOC);
            if ($colNik && stripos($colNik['Type'], 'varchar') !== false) {
                // Hapus UNIQUE KEY dulu (jika ada) sebelum ALTER
                try {
                    $this->conn->exec("ALTER TABLE `profile` DROP INDEX `nik`");
                } catch (PDOException) {
                    // Key mungkin sudah tidak ada — abaikan
                }
                $this->conn->exec(
                    "ALTER TABLE `profile`
                     MODIFY COLUMN `nik`   TEXT NOT NULL,
                     MODIFY COLUMN `no_hp` TEXT NOT NULL,
                     MODIFY COLUMN `no_kk` TEXT NOT NULL"
                );
            }

            // --- Tambahkan kolom foto_ktp jika belum ada ---
            $stmtKtp = $this->conn->query("SHOW COLUMNS FROM `profile` LIKE 'foto_ktp'");
            if (!$stmtKtp->fetch()) {
                $this->conn->exec(
                    "ALTER TABLE `profile` ADD COLUMN `foto_ktp` VARCHAR(255) NULL AFTER `no_kk`"
                );
            }

            // --- Tambahkan kolom foto_kk jika belum ada ---
            $stmtKk = $this->conn->query("SHOW COLUMNS FROM `profile` LIKE 'foto_kk'");
            if (!$stmtKk->fetch()) {
                $this->conn->exec(
                    "ALTER TABLE `profile` ADD COLUMN `foto_kk` VARCHAR(255) NULL AFTER `foto_ktp`"
                );
            }
        } catch (PDOException $e) {
            // Abaikan jika tabel belum ada
        }
    }


    /**
     * Ambil data user beserta profilnya berdasarkan id_user.
     * Data sensitif (NIK, No. HP, No. KK) akan otomatis didekripsi.
     *
     * @return array|false Data profil yang sudah didekripsi, atau false jika tidak ditemukan
     */
    public function getProfileByUserId(int $id_user): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT 
                u.id_user, u.nama, u.email, u.status,
                p.id_profile, p.nik, p.no_hp, p.no_kk, p.foto_ktp, p.foto_kk
             FROM user u
             LEFT JOIN profile p ON u.id_user = p.id_user
             WHERE u.id_user = :id_user"
        );
        $stmt->execute([':id_user' => $id_user]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return false;
        }

        // Dekripsi data sensitif sebelum dikembalikan ke View
        $row['nik']   = Encryption::decrypt((string)($row['nik']   ?? ''));
        $row['no_hp'] = Encryption::decrypt((string)($row['no_hp'] ?? ''));
        $row['no_kk'] = Encryption::decrypt((string)($row['no_kk'] ?? ''));

        return $row;
    }

    /**
     * Perbarui atau buat profil baru.
     * Data sensitif (NIK, No. HP, No. KK) akan otomatis dienkripsi sebelum disimpan.
     */
    public function saveProfile(
        int $id_user,
        string $nama,
        string $status,
        string $nik,
        string $no_hp,
        string $no_kk,
        ?string $foto_ktp,
        ?string $foto_kk
    ): bool {
        try {
            $this->conn->beginTransaction();

            // 1. Update data pada tabel user (Nama & Status)
            $stmtUser = $this->conn->prepare(
                "UPDATE user SET nama = :nama, status = :status WHERE id_user = :id_user"
            );
            $stmtUser->execute([
                ':nama'    => $nama,
                ':status'  => $status,
                ':id_user' => $id_user,
            ]);

            // 2. Enkripsi data PII sebelum disimpan ke database
            $encNik   = Encryption::encrypt($nik);
            $encNoHp  = Encryption::encrypt($no_hp);
            $encNoKk  = Encryption::encrypt($no_kk);

            // 3. Cek apakah record profile sudah ada
            $stmtCheck = $this->conn->prepare(
                "SELECT id_profile FROM profile WHERE id_user = :id_user"
            );
            $stmtCheck->execute([':id_user' => $id_user]);
            $profileExists = $stmtCheck->fetch();

            if ($profileExists) {
                // Perbarui profil yang sudah ada
                $sqlProfile = "UPDATE profile SET nik = :nik, no_hp = :no_hp, no_kk = :no_kk";
                $params = [
                    ':nik'     => $encNik,
                    ':no_hp'   => $encNoHp,
                    ':no_kk'   => $encNoKk,
                    ':id_user' => $id_user,
                ];

                // Hanya update file jika file baru diunggah
                if ($foto_ktp !== null) {
                    $sqlProfile .= ", foto_ktp = :foto_ktp";
                    $params[':foto_ktp'] = $foto_ktp;
                }
                if ($foto_kk !== null) {
                    $sqlProfile .= ", foto_kk = :foto_kk";
                    $params[':foto_kk'] = $foto_kk;
                }

                $sqlProfile .= " WHERE id_user = :id_user";
                $stmtProfile = $this->conn->prepare($sqlProfile);
                $stmtProfile->execute($params);
            } else {
                // Buat profil baru
                $stmtProfile = $this->conn->prepare(
                    "INSERT INTO profile (id_user, nik, no_hp, no_kk, foto_ktp, foto_kk) 
                     VALUES (:id_user, :nik, :no_hp, :no_kk, :foto_ktp, :foto_kk)"
                );
                $stmtProfile->execute([
                    ':id_user'  => $id_user,
                    ':nik'      => $encNik,
                    ':no_hp'    => $encNoHp,
                    ':no_kk'    => $encNoKk,
                    ':foto_ktp' => $foto_ktp ?? '',
                    ':foto_kk'  => $foto_kk  ?? '',
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    /**
     * Hapus file tertentu (foto_ktp atau foto_kk) dari database dan disk.
     */
    public function deleteProfileFile(int $id_user, string $fileType): bool
    {
        if (!in_array($fileType, ['foto_ktp', 'foto_kk'], true)) {
            return false;
        }

        try {
            // Ambil nama file lama
            $stmt = $this->conn->prepare(
                "SELECT $fileType FROM profile WHERE id_user = :id_user"
            );
            $stmt->execute([':id_user' => $id_user]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row && !empty($row[$fileType])) {
                // Cek di private storage dulu, fallback ke asset/images (legacy)
                $privatePath = __DIR__ . '/../storage/private/images' . $row[$fileType];
                $legacyPath  = __DIR__ . '/../asset/images/'    . $row[$fileType];
                $filePath    = file_exists($privatePath) ? $privatePath : $legacyPath;

                if (file_exists($filePath)) {
                    @unlink($filePath);
                }

                // Kosongkan nilai di database
                $stmtUpdate = $this->conn->prepare(
                    "UPDATE profile SET $fileType = '' WHERE id_user = :id_user"
                );
                $stmtUpdate->execute([':id_user' => $id_user]);
                return true;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
