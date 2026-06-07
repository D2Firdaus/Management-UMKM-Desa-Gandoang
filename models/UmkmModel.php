<?php

declare(strict_types=1);

class UmkmModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getAllByUser(int $id_user, int $limit, int $offset, string $search = ''): array
    {
        $sql = "SELECT
                    u.id_umkm,
                    u.nama_umkm,
                    u.jenis_usaha,
                    u.alamat,
                    u.latitude,
                    u.longitude,
                    u.status,
                    usr.nama AS nama_pengaju,
                    v.nama   AS nama_validator
                FROM umkm u
                LEFT JOIN user  AS usr ON u.id_user      = usr.id_user
                LEFT JOIN user  AS v   ON u.id_validator = v.id_user
                WHERE u.id_user = :id_user
                  AND (
                        u.nama_umkm    LIKE :search
                     OR u.jenis_usaha  LIKE :search
                     OR u.alamat       LIKE :search
                     OR u.status       LIKE :search
                  )
                ORDER BY u.id_umkm DESC
                LIMIT $limit OFFSET $offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':search'  => "%$search%",
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ─── Hitung total UMKM milik user (untuk pagination) ─────────────────────
    public function countByUser(int $id_user, string $search = ''): int
    {
        $sql = "SELECT COUNT(*) AS total
                FROM umkm u
                WHERE u.id_user = :id_user
                  AND (
                        u.nama_umkm    LIKE :search
                     OR u.jenis_usaha  LIKE :search
                     OR u.alamat       LIKE :search
                     OR u.status       LIKE :search
                  )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':search'  => "%$search%",
        ]);

        return (int) $stmt->fetchColumn();
    }

    // ─── Ambil satu UMKM berdasar id_umkm dan id_user ────────────────────────
    public function getByIdAndUser(string $id_umkm, int $id_user): array|false
    {
        $sql = "SELECT * FROM umkm
                WHERE id_umkm = :id_umkm
                  AND id_user = :id_user
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_umkm' => $id_umkm,
            ':id_user' => $id_user,
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ─── Tambah UMKM baru ─────────────────────────────────────────────────────
    public function insert(int $id_user, string $nama_umkm, string $jenis_usaha, string $alamat, ?string $latitude = null, ?string $longitude = null): bool
    {
        // Default status = pending
        $sql = "INSERT INTO umkm (nama_umkm, jenis_usaha, id_user, id_validator, alamat, latitude, longitude, status)
                VALUES (:nama_umkm, :jenis_usaha, :id_user, :id_validator, :alamat, :latitude, :longitude, 'pending')";

        // Cari id admin pertama sebagai default validator
        $admin_stmt = $this->conn->query("SELECT id_user FROM user WHERE role = 'admin' LIMIT 1");
        $admin      = $admin_stmt->fetch(PDO::FETCH_ASSOC);
        $id_validator = $admin ? (int) $admin['id_user'] : $id_user;

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nama_umkm'    => $nama_umkm,
            ':jenis_usaha'  => $jenis_usaha,
            ':id_user'      => $id_user,
            ':id_validator' => $id_validator,
            ':alamat'       => $alamat,
            ':latitude'     => $latitude,
            ':longitude'    => $longitude,
        ]);
    }

    // ─── Update UMKM ─────────────────────────────────────────────────────────
    public function update(string $id_umkm, int $id_user, string $nama_umkm, string $jenis_usaha, string $alamat, ?string $latitude = null, ?string $longitude = null): bool
    {
        $sql = "UPDATE umkm
                SET nama_umkm    = :nama_umkm,
                    jenis_usaha  = :jenis_usaha,
                    alamat       = :alamat,
                    latitude     = :latitude,
                    longitude    = :longitude
                WHERE id_umkm = :id_umkm
                  AND id_user = :id_user";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':nama_umkm'   => $nama_umkm,
            ':jenis_usaha' => $jenis_usaha,
            ':alamat'      => $alamat,
            ':latitude'    => $latitude,
            ':longitude'   => $longitude,
            ':id_umkm'     => $id_umkm,
            ':id_user'     => $id_user,
        ]);
    }

    // ─── Soft-delete: set status = nonaktif ──────────────────────────────────
    public function softDelete(string $id_umkm, int $id_user): bool
    {
        $sql = "UPDATE umkm
                SET status = 'nonaktif'
                WHERE id_umkm = :id_umkm
                  AND id_user = :id_user";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_umkm' => $id_umkm,
            ':id_user' => $id_user,
        ]);
    }

    // ─── Ambil data UMKM untuk dropdown ──────────────────────────────────────
    public function getAllDropdownByUser(int $id_user): array
    {
        $sql = "SELECT id_umkm, nama_umkm 
                FROM umkm 
                WHERE id_user = :id_user AND status = 'aktif'
                ORDER BY nama_umkm ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id_user' => $id_user]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
