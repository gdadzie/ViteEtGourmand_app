<?php
declare(strict_types=1);

namespace Controller\Admin;

use PDO;
use Config\Database;
use Entity\Utilisateurs;
use Repository\MenusRepository;
use Repository\UtilisateursRepository;
use Repository\HorairesRepository;
use Repository\VillesRepository;
use Service\Authentification\AuthService;
use Service\MailService;

class AdminController
{
    private UtilisateursRepository $utilisateursRepo;
    private MenusRepository $menusRepo;
    private PDO $conn;
    private VillesRepository $villesRepo;

    public function __construct(UtilisateursRepository $utilisateursRepo, PDO $conn)
    {
        $this->utilisateursRepo = $utilisateursRepo;
        $this->menusRepo = new MenusRepository($conn);
        $this->conn = $conn;
        $this->villesRepo = new VillesRepository($conn);

    }

    // AFFICHER LE TABLEAU DE BORD ADMIN
    public function index(): void
    {

        AuthService::requireAdmin();

        // Empêcher la mise en cache
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        // Vérifier le rôle : seuls les admins (3) peuvent accéder
        if (($_SESSION['id_role'] ?? null) !== 3) {
            $_SESSION['error'] = 'Accès interdit !';
            header('Location: index.php?page=connexion');
            exit;
        }

        $admins = $this->utilisateursRepo->readByRole(3);
        require __DIR__ . '/../../View/Admin/espace_administrateur.php';
    }

    // CRÉATION D'UN EMPLOYE
    public function creationEmploye(): void
    {
        AuthService::requireAdmin();

        // Chargement des villes pour le formulaire
        $villes = $this->villesRepo->findAll();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Formulaires/formulaire_creation_employe.php';
            return;
        }

        $prenom      = trim($_POST['prenom'] ?? '');
        $nom         = trim($_POST['nom'] ?? '');
        $telephone   = trim($_POST['telephone'] ?? '');
        $numero_rue  = trim($_POST['numero_rue'] ?? '');
        $nom_rue     = trim($_POST['nom_rue'] ?? '');
        $code_postal = trim($_POST['code_postal'] ?? '');
        $idVille     = (int)($_POST['id_ville'] ?? 0);
        $email       = trim($_POST['email'] ?? '');
        $mdp         = $_POST['mdp'] ?? '';
        $role        = 2;

        // Vérification email existant
        if ($email !== '' && $this->utilisateursRepo->readByEmail($email)) {
            $error = "Cet email est déjà utilisé.";
            require __DIR__ . '/../../View/Formulaires/formulaire_creation_employe.php';
            return;
        }

        // Validation ville
        if ($idVille <= 0) {
            $error = "Veuillez sélectionner une ville.";
            require __DIR__ . '/../../View/Formulaires/formulaire_creation_employe.php';
            return;
        }

        $villeEntity = $this->villesRepo->findById($idVille);

        if (!$villeEntity) {
            $error = "La ville sélectionnée est introuvable.";
            require __DIR__ . '/../../View/Formulaires/formulaire_creation_employe.php';
            return;
        }

        // Création de l'employé
        $u = new Utilisateurs(
            $prenom,
            $nom,
            $email,
            password_hash($mdp, PASSWORD_BCRYPT),
            $telephone,
            $role,
            true,
            null,
            $numero_rue,
            $nom_rue,
            $code_postal,
            $idVille
        );

        if ($this->utilisateursRepo->create($u)) {

            try {
                $mailService = new MailService();

                $mailService->envoyerMailCreationCompte(
                    $email,
                    $prenom . ' ' . $nom
                );

                $success = "Le compte employé a été créé et l'email de notification a été envoyé.";
            } catch (\Exception $e) {
                $success = "Le compte employé a été créé, mais l'email n'a pas pu être envoyé.";
            }

            require __DIR__ . '/../../View/Admin/espace_administrateur.php';
            return;
        }

        $error = "Erreur lors de la création du compte.";
        require __DIR__ . '/../../View/Formulaires/formulaire_creation_employe.php';
    }

    // AFFICHER LA LISTE DES UTILISATEURS
    public function listeDesUtilisateurs(): void
    {
        $prenom   = trim($_GET['prenom'] ?? '');
        $nom      = trim($_GET['nom'] ?? '');
        $email    = trim($_GET['email'] ?? '');
        $estActif = (isset($_GET['est_actif']) && $_GET['est_actif'] !== '')
            ? (int) $_GET['est_actif']
            : null;

        // ✅ on passe les paramètres, et on n'écrase plus ensuite
        $utilisateurs = $this->utilisateursRepo->readByRoleEmploye(
            $prenom,
            $nom,
            $email,
            $estActif
        );

        //envoyer un mail a chaque utilisateur
        $mailService = new MailService();
        $mailService->envoyerMailCreationCompte(
            $email,
            $prenom . ' ' . $nom,
            'employe'
        );

        $mailService->envoyerMailCreationCompte(
            $email,
            $prenom . ' ' . $nom,
            'utilisateur'
        );

        require __DIR__ . '/../../View/Utilisateurs/liste_des_utilisateurs.php';
    }


    // AFFICHER LA LISTE DES EMPLOYÉS (AVEC FILTRES)
    public function listeDesEmployes(): void
    {
        AuthService::requireAdmin();

        $prenom   = trim($_GET['prenom'] ?? '');
        $nom      = trim($_GET['nom'] ?? '');
        $email    = trim($_GET['email'] ?? '');
        $estActif = isset($_GET['est_actif']) && $_GET['est_actif'] !== ''
            ? (int) $_GET['est_actif']
            : null;

        // IMPORTANT : ne pas écraser $utilisateurs après
        $utilisateurs = $this->utilisateursRepo->readByRoleEmploye($prenom, $nom, $email, $estActif);

        require __DIR__ . '/../../View/Utilisateurs/liste_des_employes.php';
    }

    public function modificationHoraires(): void
    {
        $pdo = Database::getConnection();
        if (!$pdo) {
            die('Erreur DB');
        }

        $repo = new HorairesRepository($pdo);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            foreach ($_POST['horaires'] as $id => $data) {
                $horaire = $repo->readById((int)$id);
                if (!$horaire) {
                    continue;
                }

                $estFerme = isset($data['est_ferme']);

                $horaire->setEstFerme($estFerme);
                $horaire->setHeureOuverture($estFerme ? null : ($data['ouverture'] ?? null));
                $horaire->setHeureFermeture($estFerme ? null : ($data['fermeture'] ?? null));

                $repo->update($horaire);
            }

            header('Location: index.php?page=home');
            exit;
        }

        $horaires = $repo->readAll();
        require __DIR__ . '/../../View/Formulaires/modification_horaires.php';
    }

    public function gestionDesMenus(): void
    {
        $menus = $this->menusRepo->readAll();
        require __DIR__ . '/../../View/Fonctionalites/gestion_menus.php';
    }
}
