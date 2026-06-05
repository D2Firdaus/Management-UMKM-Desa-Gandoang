<?php
declare(strict_types=1);

class JourneyModel
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function getPaginatedByUser(int $id_user, string $search, int $limit, int $offset): array
    {
        $sql = "SELECT j.id_journey, j.id_umkm, j.foto, j.deskripsi, j.tanggal, u.nama_umkm 
                FROM journey j
                JOIN umkm u ON j.id_umkm = u.id_umkm
                WHERE u.id_user = :id_user
                  AND (
                      j.deskripsi LIKE :search OR
                      u.nama_umkm LIKE :search OR
                      j.tanggal LIKE :search
                  )
                ORDER BY j.tanggal DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindValue(':search', "%$search%", PDO::PARAM_STR);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countByUser(int $id_user, string $search): int
    {
        $sql = "SELECT COUNT(*) 
                FROM journey j
                JOIN umkm u ON j.id_umkm = u.id_umkm
                WHERE u.id_user = :id_user
                  AND (
                      j.deskripsi LIKE :search OR
                      u.nama_umkm LIKE :search OR
                      j.tanggal LIKE :search
                  )";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_user' => $id_user,
            ':search'  => "%$search%",
        ]);

        return (int) $stmt->fetchColumn();
    }

    public function getById(int $id_journey, int $id_user): array|false
    {
        $sql = "SELECT j.*, u.nama_umkm 
                FROM journey j
                JOIN umkm u ON j.id_umkm = u.id_umkm
                WHERE j.id_journey = :id_journey AND u.id_user = :id_user";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':id_journey' => $id_journey,
            ':id_user'    => $id_user
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO journey (id_umkm, foto, deskripsi, tanggal) 
                VALUES (:id_umkm, :foto, :deskripsi, :tanggal)";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_umkm'   => $data['id_umkm'],
            ':foto'      => $data['foto'],
            ':deskripsi' => $data['deskripsi'],
            ':tanggal'   => $data['tanggal']
        ]);
    }

    public function update(int $id_journey, array $data): bool
    {
        $sql = "UPDATE journey 
                SET id_umkm = :id_umkm, 
                    foto = :foto, 
                    deskripsi = :deskripsi, 
                    tanggal = :tanggal 
                WHERE id_journey = :id_journey";

        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':id_umkm'   => $data['id_umkm'],
            ':foto'      => $data['foto'],
            ':deskripsi' => $data['deskripsi'],
            ':tanggal'   => $data['tanggal'],
            ':id_journey'=> $id_journey
        ]);
    }

    public function delete(int $id_journey): bool
    {
        $sql = "DELETE FROM journey WHERE id_journey = :id_journey";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id_journey' => $id_journey]);
    }
}
