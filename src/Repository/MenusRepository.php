<?php

namespace Repository;

use Entity\Menus;


use PDO;

class MenusRepository {
    private PDO $conn;

    public function __construct(PDO $conn){
        $this->conn = $conn;
    }

    //=======================================================================
    // 1 - CREATE
    //=======================================================================
    public function create(Menus $menu): bool {
        $stmt = $this->conn->prepare("
            INSERT INTO menus
            (titre, description,image, theme, regime, nb_min_personne, prix_par_personne, conditions, stock_disponible, date_creation, date_modification)
            VALUES (:titre, :description,:image ,:theme, :regime, :nb_min_personne, :prix_par_personne, :conditions, :stock_disponible, :date_creation, :date_modification)
        ");

        $success = $stmt->execute([

            'titre' => $menu->getTitre(),
            'description' => $menu->getDescription(),
            'image' => $menu->getImage(),
            'theme' => $menu->getTheme(),
            'regime' => $menu->getRegime(),
            'nb_min_personne' => $menu->getNbMinPersonne(),
            'prix_par_personne' => $menu->getPrixParPersonne(),
            'conditions' => $menu->getConditions(),
            'stock_disponible' => $menu->getStockDisponible(),
            'date_creation' => $menu->getDateCreation(), // string ou DateTime formaté
            'date_modification' => $menu->getDateModification() ?? $menu->getDateCreation()
        ]);

        // Récupérer l'ID auto-incrémenté et le renseigner dans l'objet
        if ($success) {
            $menu->setIdMenu((int)$this->conn->lastInsertId());
        }

        return $success;
    }

    //=======================================================================
    // 2 - READ BY ID
    //=======================================================================
    public function readById(int $id): ?Menus
    {
        $stmt = $this->conn->prepare("SELECT * FROM menus WHERE id_menu = :id_menu");
        $stmt->execute(['id_menu' => $id]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        $menu = new Menus();
        $menu->setIdMenu((int)$data['id_menu']);
        $menu->setTitre($data['titre']);
        $menu->setDescription($data['description']);
        $menu->setImage($data['image']);
        $menu->setTheme($data['theme']);
        $menu->setRegime($data['regime']);
        $menu->setNbMinPersonne((int)$data['nb_min_personne']);
        $menu->setPrixParPersonne((float)$data['prix_par_personne']);
        $menu->setConditions($data['conditions']);
        $menu->setStockDisponible((int)$data['stock_disponible']);

        return $menu;
    }


    public function readByTitre(string $titre): array
    {
        $stmt = $this->conn->prepare("
        SELECT * FROM menus
        WHERE titre LIKE :titre
    ");

        $stmt->execute([
            'titre' => "%$titre%"
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    //=======================================================================
    // 3 - READ ALL
    //=======================================================================
    public function readAll(): array {
        $stmt = $this->conn->query("SELECT * FROM menus");
        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $menus = [];
        foreach ($dataList as $data){
            $menu = new Menus();
            $menu->setIdMenu((int)$data['id_menu']);
            $menu->setTitre($data['titre']);
            $menu->setDescription($data['description']);
            $menu->setImage($data['image'] ?? null); // si image est NULL

            $menu->setTheme($data['theme']);
            $menu->setRegime($data['regime']);
            $menu->setNbMinPersonne((int)$data['nb_min_personne']);
            $menu->setPrixParPersonne((float)$data['prix_par_personne']);
            $menu->setConditions($data['conditions']);
            $menu->setStockDisponible((int)$data['stock_disponible']);
            $menu->setDateCreation($data['date_creation']); // string ou DateTime
            $menu->setDateModification($data['date_modification']); // string ou DateTime

            $menus[] = $menu;
        }

        return $menus;
    }


    //=======================================================================
    // 4 - READ BY FILTRE
    //=======================================================================
    public function readByFiltre(array $filters): array
    {
        $sql = "SELECT * FROM menus WHERE 1=1";
        $params = [];

        if (!empty($filters['prix_max'])) {
            $sql .= " AND prix_par_personne <= :prix_max";
            $params['prix_max'] = $filters['prix_max'];
        }

        if (!empty($filters['theme'])) {
            $sql .= " AND theme LIKE :theme";
            $params['theme'] = '%' . $filters['theme'] . '%';
        }

        if (!empty($filters['regime'])) {
            $sql .= " AND regime LIKE :regime";
            $params['regime'] = '%' . $filters['regime'] . '%';
        }

        if (!empty($filters['nb_min_personne'])) {
            $sql .= " AND nb_min_personne <= :nb_min_personne";
            $params['nb_min_personne'] = $filters['nb_min_personne'];
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $menus = [];
        foreach ($rows as $data) {
            $menu = new \Entity\Menus();
            $menu->setIdMenu($data['id_menu']);
            $menu->setTitre($data['titre']);
            $menu->setDescription($data['description']);
            $menu->setImage($data['image'] ?? null);
            $menu->setTheme($data['theme']);
            $menu->setRegime($data['regime']);
            $menu->setNbMinPersonne($data['nb_min_personne']);
            $menu->setPrixParPersonne($data['prix_par_personne']);
            $menu->setConditions($data['conditions']);
            $menu->setStockDisponible($data['stock_disponible']);
            $menu->setDateCreation($data['date_creation']);
            $menu->setDateModification($data['date_modification']);

            $menus[] = $menu;
        }

        return $menus;
    }


    //=======================================================================
    // 5 - UPDATE
    //=======================================================================
    public function update(
        $id_menu,
        $titre,
        $description,
        $image,
        $theme,
        $regime,
        $nb_min_personne,
        $prix_par_personne,
        $conditions,
        $stock_disponible,
        $date_modification
    ) {
        $sql = "
        UPDATE menus SET
            titre = ?,
            description = ?,
            image = ?,
            theme = ?,
            regime = ?,
            nb_min_personne = ?,
            prix_par_personne = ?,
            conditions = ?,
            stock_disponible = ?,
            date_modification = ?
        WHERE id_menu = ?
    ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            $titre,
            $description,
            $image,
            $theme,
            $regime,
            $nb_min_personne,
            $prix_par_personne,
            $conditions,
            $stock_disponible,
            $date_modification,
            $id_menu
        ]);
    }


    //=======================================================================
    // 6 - DELETE
    //=======================================================================
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare(
            "DELETE FROM menus WHERE id_menu = :id_menu"
        );

        $stmt->execute(['id_menu' => $id]);

        return $stmt->rowCount() > 0;
    }


}
