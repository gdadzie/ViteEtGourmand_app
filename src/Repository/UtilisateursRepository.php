<?php

namespace Repository;

use Entity\Utilisateurs;
use PDO;

class UtilisateursRepository
{
    private PDO $conn;

    // =========================================================
    // CONSTRUCTEUR
    // =========================================================
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    // =========================================================
    // CREATE - AJOUTER UN UTILISATEUR
    // =========================================================
    public function create(Utilisateurs $u): bool
    {
        $stmt = $this->conn->prepare("
            INSERT INTO utilisateurs 
            (prenom, nom, email, mot_de_passe, telephone, numero_rue, nom_rue, code_postal, id_ville, id_role, est_actif)
            VALUES 
            (:prenom, :nom, :email, :mdp, :tel, :numero_rue, :nom_rue, :code_postal, :id_ville, :role, :actif)
        ");

        return $stmt->execute([
            'prenom'  => $u->getPrenom(),
            'nom'     => $u->getNom(),
            'email'   => $u->getEmail(),
            'mdp'     => $u->getMotDePasse(),
            'tel'     => $u->getTelephone(),
            'numero_rue' => $u->getNumeroRue(),
            'nom_rue' => $u->getNomRue(),
            'code_postal' => $u->getCodePostal(),
            'id_ville' => $u->getIdVille(),
            'role'    => $u->getIdRole(),
            'actif'   => $u->getEstActif()
        ]);
    }

    // =========================================================
    // READ - AFFICHER TOUS LES UTILISATEURS
    // =========================================================
    public function readAll(): array
    {
        $stmt = $this->conn->query("

        SELECT u.*, v.nom_ville
        FROM utilisateurs u
        LEFT JOIN villes v ON u.id_ville = v.id_ville
        
        ");

        $dataList = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $users = [];

        foreach ($dataList as $data) {
            $users[] = $this->hydrate($data);
        }

        return $users;
    }

    // =========================================================
    // READ - AFFICHER UN UTILISATEUR
    // =========================================================
    public function readById(int $id): ?Utilisateurs
    {
        $stmt = $this->conn->prepare("
            SELECT * 
            FROM utilisateurs 
            WHERE id_utilisateur = :id
            LIMIT 1
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

    // =========================================================
    // READ - VERIFIER SI L'EMAIL EXISTE DEJA
    // =========================================================
    public function readByEmail(string $email): ?Utilisateurs
    {
        $stmt = $this->conn->prepare("
            SELECT *
            FROM utilisateurs u
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? $this->hydrate($data) : null;
    }

    // =========================================================
    // READ - AFFICHER UTILISATEURS PAR ROLE
    // =========================================================
    public function readByRole(int $role): array
    {
        $sql = "SELECT * FROM utilisateurs WHERE id_role = :role";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':role', $role, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =========================================================
    // READ - AFFICHER UTILISATEURS PAR ROLE EMPLOYE
    // =========================================================
    public function readByRoleEmploye(
        string $prenom = '',
        string $nom = '',
        string $email = '',
        ?int $estActif = null
    ): array {
        $sql = "SELECT * FROM utilisateurs WHERE id_role = :role";
        $params = [':role' => 2];

        if ($prenom !== '') {
            $sql .= " AND prenom LIKE :prenom";
            $params[':prenom'] = '%' . $prenom . '%';
        }

        if ($nom !== '') {
            $sql .= " AND nom LIKE :nom";
            $params[':nom'] = '%' . $nom . '%';
        }

        if ($email !== '') {
            $sql .= " AND email LIKE :email";
            $params[':email'] = '%' . $email . '%';
        }

        if ($estActif !== null) {
            $sql .= " AND est_actif = :est_actif";
            $params[':est_actif'] = $estActif;
        }

        $sql .= " ORDER BY nom ASC, prenom ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $utilisateurs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = $this->hydrate($row);
        }

        return $utilisateurs;
    }

    // =========================================================
    // READ - AFFICHER LES UTILISATEURS ACTIFS
    // =========================================================
    public function readByEstActif(
        string $prenom = '',
        string $nom = '',
        string $email = '',
        ?int $estActif = null
    ): array {
        $sql = "SELECT * FROM utilisateurs WHERE 1=1";
        $params = [];

        if ($prenom !== '') {
            $sql .= " AND prenom LIKE :prenom";
            $params[':prenom'] = '%' . $prenom . '%';
        }

        if ($nom !== '') {
            $sql .= " AND nom LIKE :nom";
            $params[':nom'] = '%' . $nom . '%';
        }

        if ($email !== '') {
            $sql .= " AND email LIKE :email";
            $params[':email'] = '%' . $email . '%';
        }

        if ($estActif !== null) {
            $sql .= " AND est_actif = :est_actif";
            $params[':est_actif'] = $estActif;
        }

        $sql .= " ORDER BY nom ASC, prenom ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);

        $utilisateurs = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $utilisateurs[] = $this->hydrate($row);
        }

        return $utilisateurs;
    }

    // =========================================================
    // UPDATE - ACTIVER / DÉSACTIVER
    // =========================================================
    public function update(Utilisateurs $u): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE utilisateurs 
            SET est_actif = :est_actif 
            WHERE id_utilisateur = :id_utilisateur
        ");

        return $stmt->execute([
            'est_actif' => (int)$u->getEstActif(),
            'id_utilisateur' => $u->getIdUtilisateur()
        ]);
    }

    // =========================================================
    // UPDATE - MISE À JOUR PROFIL UTILISATEUR
    // =========================================================
    public function updateUtilisateur(
        int $id,
        string $prenom,
        string $nom,
        string $email,
        string $telephone,
        string $numeroRue,
        string $nomRue,
        int $codePostal,
        int $idVille

    ): bool {

        $sql = "
            UPDATE utilisateurs
            SET 
                prenom = :prenom,
                nom = :nom,
                email = :email,
                telephone = :telephone,
                numero_rue = :numero_rue,
                nom_rue = :nom_rue,
                code_postal = :code_postal,
                id_ville = :id_ville
            
            WHERE id_utilisateur = :id
        ";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            'prenom' => $prenom,
            'nom' => $nom,
            'email' => $email,
            'telephone' => $telephone,
            'numero_rue' => $numeroRue,
            'nom_rue' => $nomRue,
            'code_postal' => $codePostal,
            'id_ville' => $idVille,
            'id' => $id
        ]);
    }

    Public function updatePassword(string $email, string $mdp): bool
    {
        $stmt = $this->conn->prepare("
            UPDATE utilisateurs 
            SET mot_de_passe = :mdp 
            WHERE email = :email
        ");

        return $stmt->execute([
            'mdp' => $mdp,
            'email' => $email
        ]);

    }

    // =========================================================
    // DELETE - SUPPRIMER UN UTILISATEUR
    // =========================================================
    public function delete(int $id): bool
    {
        $stmt = $this->conn->prepare("
            DELETE FROM utilisateurs 
            WHERE id_utilisateur = :id_utilisateur
        ");

        return $stmt->execute([
            'id_utilisateur' => $id
        ]);
    }

    // =========================================================
    // HYDRATATION (TRANSFORMATION SQL → OBJET)
    // =========================================================
    private function hydrate(array $data): Utilisateurs
    {
        $u = new Utilisateurs();

        $u->setIdUtilisateur((int)$data['id_utilisateur']);
        $u->setPrenom($data['prenom']);
        $u->setNom($data['nom']);
        $u->setEmail($data['email']);
        $u->setMotDePasse($data['mot_de_passe']);
        $u->setTelephone($data['telephone']);
        $u->setNumeroRue($data['numero_rue']);
        $u->setNomRue($data['nom_rue']);
        $u->setCodePostal((int)$data['code_postal']);
        $u->setIdVille((int)$data['id_ville']);
        $u->setIdRole((int)$data['id_role']);
        $u->setEstActif((bool)$data['est_actif']);
        $u->setDateCreation($data['date_creation']);

        return $u;
    }
}