<?php

namespace Controller\Utilisateurs;

use Entity\Utilisateurs;
use PDO;
use Repository\UtilisateursRepository;

class UtilisateursController
{
    private UtilisateursRepository $repo;

    // =========================================================
    // CONSTRUCTEUR
    // =========================================================
    public function __construct(PDO $conn)
    {
        $this->repo = new UtilisateursRepository($conn);
    }

    // =========================================================
    // READ - LISTE UTILISATEURS
    // =========================================================
    public function readAllUtilisateurs()
    {
        $utilisateurs = $this->repo->readAll();

        require __DIR__ . '/../../View/Utilisateurs/liste_des_utilisateurs.php';

        return $utilisateurs;
    }

    // =========================================================
    // READ - AFFICHER UN UTILISATEUR PAR SON ID
    // =========================================================
    public function readUtilisateurById(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] = "Connexion requise";
            header('Location: index.php?page=connexion');
            exit;
        }

        $id = (int)$_SESSION['id_utilisateur'];

        $utilisateur = $this->repo->readById($id);

        if (!$utilisateur) {
            $_SESSION['error'] = "Utilisateur introuvable";
            header('Location: index.php?page=home');
            exit;
        }

        require __DIR__ . '/../../View/Utilisateurs/mes_informations.php';
    }

    // =========================================================
    // UPDATE - METTRE A JOUR UN UTILISATEUR
    // =========================================================
    public function updateUtilisateur(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=espace_utilisateur');
            exit;
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] = "Connexion requise";
            header('Location: index.php?page=connexion');
            exit;
        }

        $id = (int)$_SESSION['id_utilisateur'];

        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $numero_rue = trim($_POST['numero_rue'] ?? '');
        $nom_rue = trim($_POST['nom_rue'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');
        $id_ville = trim($_POST['id_ville'] ?? '');

        if (empty($prenom) || empty($nom) || empty($email)) {
            $_SESSION['error'] = "Prénom, nom et email sont obligatoires";
            header('Location: index.php?page=profil');
            exit;
        }

        $ok = $this->repo->update(
            $id,
            $prenom,
            $nom,
            $email,
            $telephone,
            $numero_rue,
            $nom_rue,
            $code_postal,
            $id_ville
        );

        if ($ok) {
            $_SESSION['success'] = "Profil mis à jour avec succès";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour";
        }

        header('Location: index.php?page=profil');
        exit;
    }

    // =========================================================
    // UPDATE - ACTIVER / DÉSACTIVER UTILISATEUR
    // =========================================================
    public function activateUtilisateur()
    {
        if ((int) ($_SESSION['id_role'] ?? 0) !== 3) {
            http_response_code(403);
            exit('Accès interdit');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=liste_des_utilisateurs');
            exit;
        }

        if (empty($_POST['id']) || !isset($_POST['est_actif'])) {
            $_SESSION['error'] = "Données manquantes";
            header('Location: index.php?page=liste_des_utilisateurs');
            exit;
        }

        $id = (int) $_POST['id'];
        $estActif = (int) $_POST['est_actif'];

        $u = new Utilisateurs();
        $u->setIdUtilisateur($id);
        $u->setEstActif($estActif);

        if ($this->repo->update($u)) {
            $_SESSION['success'] = "Statut de l'utilisateur mis à jour";
        } else {
            $_SESSION['error'] = "Modification impossible";
        }

        header('Location: index.php?page=liste_des_utilisateurs');
        exit;
    }

    // =========================================================
    // DELETE - SUPPRIMER UN UTILISATEUR PAR SON ID
    // =========================================================
    public function deleteUtilisateurById()
    {
        if ((int) ($_SESSION['id_role'] ?? 0) !== 3) {
            http_response_code(403);
            exit('Accès interdit');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=liste_des_utilisateurs');
            exit;
        }

        if (empty($_POST['id'])) {
            $_SESSION['error'] = "ID de l'utilisateur manquant";
            header('Location: index.php?page=liste_des_utilisateurs');
            exit;
        }

        $id = (int) $_POST['id'];

        if ($this->repo->delete($id)) {
            $_SESSION['success'] = "Utilisateur supprimé avec succès";
        } else {
            $_SESSION['error'] = "Utilisateur introuvable";
        }

        header('Location: index.php?page=liste_des_utilisateurs');
        exit;
    }
}
