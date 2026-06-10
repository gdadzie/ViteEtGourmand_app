<?php

namespace Repository;

use Entity\Menus;
use PDO;
use Entity\Plats;

class PlatsRepository
{
    private PDO $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    public function createPlat(Plats $plat): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO plats (nom_plat, type_plat)
            VALUES (:nom_plat, :type_plat)
        ");

        $success = $stmt->execute([
            'nom_plat' => $plat->getNomPlat(),
            'type_plat' => $plat->getTypePlat(),
        ]);

        if ($success) {
            $plat->setIdPlat((int)$this->conn->lastInsertId());
        }

        return $success;
    }

    public function findAll(): array
    {
        $stmt = $this->conn->query("SELECT * FROM plats");
        return $stmt->fetchAll(PDO::FETCH_CLASS, Plats::class);
    }


}