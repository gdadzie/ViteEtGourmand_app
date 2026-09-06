<?php

namespace Repository;

use PDO;
use Entity\Plats;

class PlatsRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function createPlat(Plats $plat): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO plats (nom_plat, type_plat, id_menu)
            VALUES (:nom_plat, :type_plat, :id_menu)
        ");

        $success = $stmt->execute([
            'nom_plat' => $plat->getNomPlat(),
            'type_plat' => $plat->getTypePlat(),
            'id_menu' => $plat->getIdMenu(),
        ]);

        if ($success) {
            $plat->setIdPlat((int)$this->conn->lastInsertId());
        }

        return $success;
    }

    public function findAll(): array
    {
        $stmt = $this->conn->query("
            SELECT *
            FROM plats
            ORDER BY type_plat, nom_plat
        ");

        return $stmt->fetchAll(PDO::FETCH_CLASS, Plats::class);
    }

    public function delete(int $id_plat): bool
    {
        $stmt = $this->conn->prepare("
        DELETE FROM plats
        WHERE id_plat = :id_plat
    ");

        $stmt->execute([
            'id_plat' => $id_plat
        ]);

        return $stmt->rowCount() > 0;
    }
}