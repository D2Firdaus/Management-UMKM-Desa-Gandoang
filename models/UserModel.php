<?php
declare(strict_types=1);

class UserModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Cari user berdasarkan email.
     * Digunakan untuk login dan forgot password.
     */
    public function findByEmail(string $email): array|false
    {
        $stmt = $this->conn->prepare(
            "SELECT id_user, nama, email, password, status FROM user WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cek apakah email sudah terdaftar.
     * Digunakan saat registrasi.
     */
    public function emailExists(string $email): bool
    {
        $stmt = $this->conn->prepare(
            "SELECT id_user FROM user WHERE email = :email"
        );
        $stmt->execute([':email' => $email]);
        return (bool) $stmt->fetch();
    }

    /**
     * Buat user baru.
     * Dipanggil setelah verifikasi OTP dan pembuatan password berhasil.
     */
    public function create(string $nama, string $email, string $hashedPassword): void
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO user (nama, email, password, status) VALUES (:nama, :email, :password, 'aktif')"
        );
        $stmt->execute([
            ':nama'     => $nama,
            ':email'    => $email,
            ':password' => $hashedPassword,
        ]);
    }

    /**
     * Update password user.
     * Dipanggil setelah reset password berhasil.
     */
    public function updatePassword(string $email, string $hashedPassword): void
    {
        $stmt = $this->conn->prepare(
            "UPDATE user SET password = :password WHERE email = :email"
        );
        $stmt->execute([
            ':password' => $hashedPassword,
            ':email'    => $email,
        ]);
    }
}
