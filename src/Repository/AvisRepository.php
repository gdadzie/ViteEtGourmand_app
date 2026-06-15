<?php

namespace Repository;

use Entity\Avis;
use PDO;

class AvisRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // ===============================
    // HYDRATE
    // ===============================
    private function hydrate(array $data): Avis
    {
        $avis = new Avis();

        $avis->setIdAvis((int)$data['id_avis']);

        $avis->setIdUtilisateur((int)$data['id_utilisateur']);

        $avis->setIdCommande((int)$data['id_commande']);

        $avis->setNote((int)$data['note']);

        $avis->setCommentaire($data['commentaire']);

        $avis->setEstValide((bool)$data['est_valide']);
        $avis->setDateCreation((int)$data['date_creation']);

        return $avis;
    }

    // ===============================
    // CRÉER AVIS
    // ===============================
    public function create(Avis $avis): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO avis
            (
                id_commande,
                id_utilisateur,
                note,
                commentaire,
                est_valide,
                date_creation
            )
            VALUES
            (
                :id_commande,
                :id_utilisateur,
                :note,
                :commentaire,
                0,
                now()
            )
        ");

        return $stmt->execute([

            'id_commande' => $avis->getIdCommande(),

            'id_utilisateur' => $avis->getIdUtilisateur(),

            'note' => $avis->getNote(),

            'commentaire' => $avis->getCommentaire()
        ]);
    }



    // ===============================
    // VALIDER AVIS
    // ===============================
    public function validate(int $id): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE avis
            SET est_valide = 1
            WHERE id_avis = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    // ===============================
    // SUPPRIMER AVIS
    // ===============================
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("
            DELETE FROM avis
            WHERE id_avis = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return $stmt->rowCount() > 0;
    }

    // ===============================
    // TOUS LES AVIS
    // ===============================
    public function readAll(): array
    {
        $stmt = $this->conn->query("
            SELECT *
            FROM avis
            ORDER BY id_avis DESC
        ");

        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $avisList = [];

        foreach ($dataList as $data) {

            $avisList[] = $this->hydrate($data);
        }

        return $avisList;
    }

    // ===============================
    // AVIS VALIDÉS
    // ===============================
    public function findValides(): array
    {
        $stmt = $this->conn->query("
            SELECT *
            FROM avis
            WHERE est_valide = 1
            ORDER BY id_avis DESC
        ");

        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $avisList = [];

        foreach ($dataList as $data) {

            $avisList[] = $this->hydrate($data);
        }

        return $avisList;
    }

    // ===============================
    // TROUVER PAR ID
    // ===============================
    public function readById(int $id): ?Avis
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM avis
            WHERE id_avis = :id
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$data) {
            return null;
        }

        return $this->hydrate($data);
    }

    public function readByUtilisateur(int $idUtilisateur): array
    {
        $stmt = $this->conn->prepare("
        SELECT *
        FROM avis
        WHERE id_utilisateur = :id
        ORDER BY id_avis DESC
    ");

        $stmt->execute([
            'id' => $idUtilisateur
        ]);

        $datas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $avis = [];

        foreach ($datas as $data) {
            $avis[] = $this->hydrate($data);
        }

        return $avis;
    }

    public function findAvisByCommande(int $idCommande): ?Avis
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM avis
            WHERE id_commande = :id_commande
        ");
        $stmt->execute([
            'id_commande' => $idCommande
        ]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }
        return $this->hydrate($data);
    }

    // ===============================
    // VÉRIFIER SI AVIS EXISTE DÉJÀ
    // ===============================
    public function existeDeja(int $idCommande): bool
    {
        $stmt = $this->conn->prepare("
            SELECT COUNT(*) as total
            FROM avis
            WHERE id_commande = :id_commande
        ");

        $stmt->execute([
            'id_commande' => $idCommande
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return (int)$result['total'] > 0;
    }
}