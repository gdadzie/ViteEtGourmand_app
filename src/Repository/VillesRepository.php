<?php

namespace Repository;

use PDO;

class VillesRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM villes ORDER BY nom_ville ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById(int $idVille): ?array
    {
        $sql = "
            SELECT *
            FROM villes
            WHERE id_ville = :id_ville
        ";

        $stmt = $this->conn->prepare($sql);

        $stmt->execute([
            ':id_ville' => $idVille
        ]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}