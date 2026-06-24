<?php
declare(strict_types=1);

namespace Service\Authentification;

use Config\Database;
use Entity\Utilisateurs;
use Repository\UtilisateursRepository;
use Repository\VillesRepository;

class AuthService
{
    private UtilisateursRepository $userRepo;
    private $villesRepo;

    public function __construct()
    {
        $pdo = Database::getConnection();

        if (!$pdo) {
            die('Erreur DB : ' . Database::getLastError());
        }

        $this->userRepo = new UtilisateursRepository($pdo);
        $this->villesRepo = new VillesRepository($pdo);
    }

    /**
     * =========================
     * CONNEXION
     * =========================
     */
    public function login(string $email, string $mdp): array
    {
        $user = $this->userRepo->readByEmail($email);

        if (!$user || !password_verify($mdp, $user->getMotDePasse())) {
            return [
                'success' => false,
                'message' => 'Email ou mot de passe incorrect.'
            ];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['id_utilisateur'] = $user->getIdUtilisateur();
        $_SESSION['id_role'] = $user->getIdRole();
        $_SESSION['email'] = $user->getEmail();
        $_SESSION['nom'] = $user->getNom();
        $_SESSION['prenom'] = $user->getPrenom();

        $redirect = match ($user->getIdRole()) {
            1 => 'index.php?page=espace_utilisateur',
            2 => 'index.php?page=espace_employe',
            3 => 'index.php?page=espace_admin',
            default => 'index.php?page=connexion'
        };

        return [
            'success' => true,
            'redirect' => $redirect
        ];
    }

    /**
     * =========================
     * INSCRIPTION (RGPD AJOUTÉ)
     * =========================
     */
    public function register(array $data, bool $rgpd): array
    {
        // =========================
        // RGPD CHECK
        // =========================
        if (!$rgpd) {
            return [
                'success' => false,
                'message' => 'Vous devez accepter la politique de confidentialité.'
            ];
        }

        $prenom = trim($data['prenom'] ?? '');
        $nom = trim($data['nom'] ?? '');
        $telephone = trim($data['telephone'] ?? '');
        $numeroRue = trim($data['numero_rue'] ?? '');
        $nomRue = trim($data['nom_rue'] ?? '');
        $codePostal = trim($data['code_postal'] ?? '');
        $email = trim($data['email'] ?? '');
        $mdp = $data['mdp'] ?? '';

        // =========================
        // EMAIL CHECK
        // =========================
        if ($this->userRepo->readByEmail($email)) {
            return [
                'success' => false,
                'message' => 'Cet email est déjà utilisé.'
            ];
        }

        // =========================
        // VILLE CHECK
        // =========================
        $idVille = (int)($data['id_ville'] ?? 0);

        if ($idVille <= 0) {
            return [
                'success' => false,
                'message' => 'Veuillez sélectionner une ville.'
            ];
        }

        // =========================
        // CREATE USER
        // =========================
        $user = new Utilisateurs();

        $user->setPrenom($prenom);
        $user->setNom($nom);
        $user->setEmail($email);
        $user->setMotDePasse(password_hash($mdp, PASSWORD_BCRYPT));
        $user->setTelephone($telephone);
        $user->setNumeroRue($numeroRue);
        $user->setNomRue($nomRue);
        $user->setCodePostal($codePostal);
        $user->setIdVille($idVille);
        $user->setIdRole(1);
        $user->setEstActif(true);

        // =========================
        // INSERT DB
        // =========================
        if (!$this->userRepo->create($user)) {
            return [
                'success' => false,
                'message' => 'Une erreur est survenue lors de la création du compte.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.'
        ];
    }
    /**
     * =========================
     * DECONNEXION
     * =========================
     */
    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * =========================
     * RESET MOT DE PASSE
     * =========================
     */
    public function resetMotDePasse(string $email, string $nouveauMotDePasse): array
    {
        $user = $this->userRepo->readByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Aucun compte trouvé avec cet email.'
            ];
        }

        $hash = password_hash($nouveauMotDePasse, PASSWORD_BCRYPT);

        $success = $this->userRepo->updatePassword(
            $user->getEmail(),
            $hash
        );

        if (!$success) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du mot de passe.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Mot de passe modifié avec succès.'
        ];
    }

    /**
     * =========================
     * ROLES CHECK
     * =========================
     */
    public static function requireAdmin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['id_utilisateur']) ||
            $_SESSION['id_role'] !== 3
        ) {
            header('Location: index.php?page=connexion');
            exit;
        }
    }

    public static function requireEmploye(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['id_utilisateur']) ||
            $_SESSION['id_role'] !== 2
        ) {
            header('Location: index.php?page=connexion');
            exit;
        }
    }

    public static function requireUtilisateur(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['id_utilisateur']) ||
            $_SESSION['id_role'] !== 1
        ) {
            header('Location: index.php?page=connexion');
            exit;
        }
    }

    public static function requireAdminEmploye(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['id_utilisateur']) ||
            !in_array($_SESSION['id_role'], [2, 3])
        ) {
            header('Location: index.php?page=connexion');
            exit;
        }
    }
}