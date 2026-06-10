<?php
declare(strict_types=1);

namespace Controller\Utilisateurs;

use Config\Database;
use Entity\Utilisateurs;
use Repository\UtilisateursRepository;


class AuthController
{
    private UtilisateursRepository $userRepo;

    public function __construct()
    {
        $pdo = Database::getConnection();

        if (!$pdo) {
            die('Erreur DB : ' . Database::getLastError());
        }

        $this->userRepo = new UtilisateursRepository($pdo);
    }

    public function connexion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Authentication/formulaire_de_connexion.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mdp'] ?? '';

        $user = $this->userRepo->readByEmail($email);

        if (!$user || !password_verify($mdp, $user->getMotDePasse())) {
            $error = "Email ou mot de passe incorrect.";
            require __DIR__ . '/../../View/Authentication/formulaire_de_connexion.php';
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['id_utilisateur'] = $user->getIdUtilisateur();
        $_SESSION['id_role']        = $user->getIdRole();
        $_SESSION['email']          = $user->getEmail();
        $_SESSION['nom']            = $user->getNom();
        $_SESSION['prenom']         = $user->getPrenom();

        switch ($_SESSION['id_role']) {
            case 1:
                header('Location: index.php?page=espace_utilisateur');
                break;
            case 2:
                header('Location: index.php?page=espace_employe');
                break;
            case 3:
                header('Location: index.php?page=espace_admin');
                break;
            default:
                session_destroy();
                header('Location: index.php?page=connexion');
                break;
        }

        exit;
    }


    public function inscription(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }

        $prenom = trim($_POST['prenom'] ?? '');
        $nom = trim($_POST['nom'] ?? '');
        $telephone = trim($_POST['telephone'] ?? '');
        $adresse = trim($_POST['adresse'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $mdp = $_POST['mdp'] ?? '';

        // Vérifie si l'email existe déjà
        if ($this->userRepo->findByEmail($email)) {
            $error = "Cet email est déjà utilisé.";
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }

        $u = new Utilisateurs();
        $u->setPrenom($prenom);
        $u->setNom($nom);
        $u->setEmail($email);
        $u->setMotDePasse(password_hash($mdp, PASSWORD_BCRYPT));
        $u->setTelephone($telephone);
        $u->setAdresse($adresse);
        $u->setIdRole(1);
        $u->setEstActif(true);

        if ($this->userRepo->create($u)) {
            // Compte créé avec succès
            $success = "Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.";
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        } else {
            // Erreur lors de la création
            $error = "Une erreur est survenue, impossible de créer le compte.";
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }
    }





    public function deconnexion(): void
    {
        // 1️⃣ Démarrer la session si nécessaire
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // 2️⃣ Vider toutes les variables de session
        $_SESSION = [];

        // 3️⃣ Détruire la session côté serveur
        session_destroy();

        // 4️⃣ Supprimer le cookie de session (sécurité)
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // 5️⃣ Redirection vers la page de connexion
        header('Location: index.php?page=connexion');
        exit;
    }


    public function success(): void
    {
        require __DIR__ . '/../../View/Authentication/espace_utilisateur.php';
    }
}
