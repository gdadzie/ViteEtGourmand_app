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
            INSERT INTO plats (nom_plat, type_plat, id_menu, image_plat)
            VALUES (:nom_plat, :type_plat, :id_menu, :image_plat)
        ");

        $success = $stmt->execute([
            'nom_plat' => $plat->getNomPlat(),
            'type_plat' => $plat->getTypePlat(),
            'id_menu' => $plat->getIdMenu(),
            'image_plat' => $plat->getImagePlat(),
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

    public function findByIds(array $ids): array
    {
        $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
        if ($ids === []) return [];

        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $stmt = $this->conn->prepare(
            "SELECT * FROM plats WHERE id_plat IN ({$placeholders}) ORDER BY type_plat, nom_plat"
        );
        $stmt->execute($ids);

        return $stmt->fetchAll(PDO::FETCH_CLASS, Plats::class);
    }

    public function attachToMenu(int $idMenu, int $idPlat): bool
    {
        $stmt = $this->conn->prepare(
            'INSERT INTO menus_plats (id_menu, id_plat)
             SELECT :id_menu, :id_plat
             WHERE NOT EXISTS (
                SELECT 1 FROM menus_plats WHERE id_menu = :id_menu_check AND id_plat = :id_plat_check
             )'
        );

        return $stmt->execute([
            'id_menu' => $idMenu,
            'id_plat' => $idPlat,
            'id_menu_check' => $idMenu,
            'id_plat_check' => $idPlat,
        ]);
    }

    public function findById(int $id): ?Plats
    {
        $stmt = $this->conn->prepare('SELECT * FROM plats WHERE id_plat = :id_plat');
        $stmt->execute(['id_plat' => $id]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, Plats::class);

        return $stmt->fetch() ?: null;
    }

    public function delete(int $id_plat): bool
    {
        $unlink = $this->conn->prepare('DELETE FROM menus_plats WHERE id_plat = :id_plat');
        $unlink->execute(['id_plat' => $id_plat]);

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
