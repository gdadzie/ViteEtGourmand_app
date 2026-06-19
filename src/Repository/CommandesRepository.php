<?php

namespace Repository;


use Entity\Commande;
use PDO;
use Entity\Menus;

class CommandesRepository
{
    // =========================================================
    // 1. CONNEXION BASE DE DONNÉES
    // =========================================================

    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;


    }

    // =========================================================
    // 2. HYDRATATION ENTITY COMMANDE
    // =========================================================
    // Transforme un tableau SQL en objet Commande
    // =========================================================

    private function hydrate(array $data): Commande
    {
        $commande = new Commande();

        $commande->setIdCommande((int)$data['id_commande']);
        $commande->setIdUtilisateur((int)$data['id_utilisateur']);

        $commande->setIdVille((int)$data['id_ville']);
        $commande->setNombrePersonnes((int)$data['nombre_personnes']);
        $commande->setPrixTotal((float)$data['prix_total']);
        if (isset($data['titre_menu'])) {
            $commande->setTitreMenu($data['titre_menu']);
        }
        $commande->setAdresseLivraison($data['adresse_livraison']);
        $commande->setDateLivraison($data['date_livraison']);
        $commande->setHeureLivraison($data['heure_livraison']);
        $commande->setStatut($data['statut']);
        $commande->setDateCreation($data['date_creation']);

        $commande->setModeReception(
            $data['mode_reception'] ?? 'livraison'
        );

        $commande->setModePaiement(
            $data['mode_paiement'] ?? 'paiement_livraison'
        );

        // IMPORTANT :
        // Cohérent avec la vue et le système de paiement
        $commande->setStatutPaiement(
            $data['statut_paiement'] ?? 'unpaid'
        );

        return $commande;
    }

    // =========================================================
    // 3. CREATE
    // =========================================================
    // Création d'une nouvelle commande
    // =========================================================

    public function create(Commande $commande): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO commandes
            (
                id_utilisateur,
                id_menu,
                nombre_personnes,
                prix_total,
                adresse_livraison,
                id_ville,
                date_livraison,
                heure_livraison,
                statut,
                date_creation,
                mode_reception,
                mode_paiement,
                statut_paiement
            )
            VALUES
            (
                :id_utilisateur,
                :id_menu,
                :nombre_personnes,
                :prix_total,
                :adresse_livraison,
                :id_ville,
                :date_livraison,
                :heure_livraison,
                :statut,
                :date_creation,
                :mode_reception,
                :mode_paiement,
                :statut_paiement
            )
        ");

        $success = $stmt->execute([
            'id_utilisateur'    => $commande->getIdUtilisateur(),
            'id_menu'           => $commande->getIdMenu(),
            'nombre_personnes'  => $commande->getNombrePersonnes(),
            'prix_total'        => $commande->getPrixTotal(),
            'adresse_livraison' => $commande->getAdresseLivraison(),
            'id_ville'          => $commande->getIdVille(),
            'date_livraison'    => $commande->getDateLivraison(),
            'heure_livraison'   => $commande->getHeureLivraison(),
            'statut'            => $commande->getStatut(),
            'date_creation'     => $commande->getDateCreation(),
            'mode_reception'    => $commande->getModeReception(),
            'mode_paiement'     => $commande->getModePaiement(),
            'statut_paiement'   => $commande->getStatutPaiement(),
        ]);

        // Récupération de l'ID auto-incrémenté
        if ($success) {
            $commande->setIdCommande(
                (int)$this->conn->lastInsertId()
            );
        }

        return $success;
    }

    // =========================================================
    // 4. READ - FIND ALL
    // =========================================================
    // Récupère toutes les commandes
    // =========================================================

    public function readAll(): array
    {
        $stmt = $this->conn->query("
            SELECT *
            FROM commandes
            ORDER BY date_creation DESC
        ");

        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $commandes = [];

        foreach ($dataList as $data) {
            $commandes[] = $this->hydrate($data);
        }

        return $commandes;
    }

    // =========================================================
    // 5. READ - FIND BY ID
    // =========================================================
    // Récupère une commande par son ID
    // =========================================================

    public function readById(int $id): ?Commande
    {
        $sql = "
        SELECT *
        FROM commandes
        WHERE id_commande = :id_commande
    ";

        $requete = $this->conn->prepare($sql);

        $requete->execute([
            'id_commande' => $id
        ]);

        $data = $requete->fetch(PDO::FETCH_ASSOC);

        return $data
            ? $this->hydrate($data)
            : null;
    }

    // =========================================================
    // 6. READ - COMMANDES UTILISATEUR
    // =========================================================
    // Récupère toutes les commandes d'un utilisateur
    // =========================================================

    public function readAllCommandeByUtilisateur(int $idUtilisateur): array
    {
        $sql = "
            SELECT
                c.*,
                m.titre AS titre_menu
            FROM commandes c
            INNER JOIN menus m
                ON c.id_menu = m.id_menu
            WHERE c.id_utilisateur = :id_utilisateur
            ORDER BY c.date_creation DESC
        ";

        $requete = $this->conn->prepare($sql);

        $requete->execute([
            'id_utilisateur' => $idUtilisateur
        ]);

        $dataList = $requete->fetchAll(PDO::FETCH_ASSOC);

        $commandes = [];

        foreach ($dataList as $data) {
            $commandes[] = $this->hydrate($data);
        }

        return $commandes;
    }

    // =========================================================
    // 7. READ - COMMANDE UTILISATEUR PAR ID
    // =========================================================
    // Vérifie qu'une commande appartient bien à l'utilisateur
    // =========================================================

    public function readCommandeByIdUtilisateur(int $idUtilisateur, int $idCommande): ?Commande {

        $stmt = $this->conn->prepare("
            SELECT *
            FROM commandes
            WHERE id_utilisateur = :id_utilisateur
            AND id_commande = :id_commande
        ");

        $stmt->execute([
            'id_utilisateur' => $idUtilisateur,
            'id_commande'    => $idCommande
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data
            ? $this->hydrate($data)
            : null;
    }

    // =========================================================
    // 8. UPDATE - STATUT COMMANDE
    // =========================================================
    // Met à jour le statut d'une commande
    // =========================================================

    public function updateStatut(
        int $id,
        string $statut
    ): bool {

        $stmt = $this->conn->prepare("
            UPDATE commandes
            SET statut = :statut
            WHERE id_commande = :id_commande
        ");

        return $stmt->execute([
            'statut'      => $statut,
            'id_commande' => $id
        ]);
    }

    // =========================================================
    // 9. UPDATE - VALIDATION PAIEMENT
    // =========================================================
    // Valide le paiement d'une commande
    // =========================================================

    public function validerPaiement(int $id): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE commandes
            SET statut_paiement = 'payé'
            WHERE id_commande = :id
        ");

        return $stmt->execute([
            'id' => $id
        ]);
    }

    // =========================================================
    // 10. DELETE
    // =========================================================
    // Supprime une commande
    // =========================================================

    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("
            DELETE FROM commandes
            WHERE id_commande = :id_commande
        ");

        return $stmt->execute([
            'id_commande' => $id
        ]);
    }

    public function showCommandeByNomMenu(): array
    {
        $sql =

            "SELECT c.*,m.titre AS titre_menu
                FROM commandes c
                    JOIN menus m ON c.id_menu = m.id_menu
                
            ";
        $requete = $this->conn->prepare($sql);
        $requete->execute();
        $dataList = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $dataList;
    }
}
