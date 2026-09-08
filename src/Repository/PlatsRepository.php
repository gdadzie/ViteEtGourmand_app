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
        // La composition d'un menu est normalement stockee dans menus_plats.
        // Certaines anciennes bases possedent encore id_menu et image_plat dans
        // la table plats : on les renseigne uniquement lorsqu'elles existent.
        $columns = $this->getColumns('plats');
        $fields = ['nom_plat', 'type_plat'];
        $values = [
            'nom_plat' => $plat->getNomPlat(),
            'type_plat' => $plat->getTypePlat(),
        ];

        if (isset($columns['id_menu'])) {
            $fields[] = 'id_menu';
            $values['id_menu'] = $plat->getIdMenu();
        }

        if (isset($columns['image_plat'])) {
            $fields[] = 'image_plat';
            $values['image_plat'] = $plat->getImagePlat();
        }

        $placeholders = implode(', ', array_map(static fn (string $field): string => ':' . $field, $fields));
        $stmt = $this->conn->prepare(sprintf(
            'INSERT INTO plats (%s) VALUES (%s)',
            implode(', ', $fields),
            $placeholders
        ));

        $success = $stmt->execute($values);

        if ($success) {
            $plat->setIdPlat((int)$this->conn->lastInsertId());
        }

        return $success;
    }

    /** @return array<string, true> */
    private function getColumns(string $table): array
    {
        $columns = [];
        $stmt = $this->conn->query("SHOW COLUMNS FROM `{$table}`");

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $column) {
            $columns[$column['Field']] = true;
        }

        return $columns;
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
