<?php
declare(strict_types=1);

namespace Service\Authentification;

use Config\Database;
use Entity\Utilisateurs;
use Repository\UtilisateursRepository;
use Repository\VillesRepository;
use Repository\PasswordResetRepository;
use Service\MailService;

class AuthService
{
    private UtilisateursRepository $userRepo;
    private $villesRepo;
    private PasswordResetRepository $passwordResetRepo;

    public function __construct()
    {
        $pdo = Database::getConnection();

        if (!$pdo) {
            die('Erreur DB : ' . Database::getLastError());
        }

        $this->userRepo = new UtilisateursRepository($pdo);
        $this->villesRepo = new VillesRepository($pdo);
        $this->passwordResetRepo = new PasswordResetRepository($pdo);
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

        if (!$user->getEstActif()) {
            return ['success' => false, 'message' => 'Ce compte est désactivé. Contactez l’entreprise.'];
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
     * INSCRIPTION (RGPD AJOUTÃ‰)
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
                'message' => 'Vous devez accepter la politique de confidentialitÃ©.'
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

        if (!self::isStrongPassword($mdp)) {
            return ['success' => false, 'message' => 'Le mot de passe doit contenir au moins 10 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.'];
        }

        // =========================
        // EMAIL CHECK
        // =========================
        if ($this->userRepo->readByEmail($email)) {
            return [
                'success' => false,
                'message' => 'Cet email est dÃ©jÃ  utilisÃ©.'
            ];
        }

        // =========================
        // VILLE CHECK
        // =========================
        $idVille = (int)($data['id_ville'] ?? 0);

        if ($idVille <= 0) {
            return [
                'success' => false,
                'message' => 'Veuillez sÃ©lectionner une ville.'
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
                'message' => 'Une erreur est survenue lors de la crÃ©ation du compte.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Votre compte a Ã©tÃ© crÃ©Ã© avec succÃ¨s ! Vous pouvez maintenant vous connecter.'
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
    public function requestPasswordReset(string $email): array
    {
        $user = $this->userRepo->readByEmail($email);
        if ($user && $user->getEstActif()) {
            $token = bin2hex(random_bytes(32));
            $this->passwordResetRepo->create($user->getIdUtilisateur(), hash('sha256', $token));
            (new MailService())->envoyerMailReinitialisation($user->getEmail(), $user->getPrenom() . ' ' . $user->getNom(), $token);
        }
        // Prevent account enumeration: always return this same response.
        return ['success' => true, 'message' => 'Si cette adresse correspond à un compte, un lien de réinitialisation vient d’être envoyé.'];
    }

    public function resetMotDePasse(string $token, string $nouveauMotDePasse): array
    {
        if (!self::isStrongPassword($nouveauMotDePasse)) {
            return ['success' => false, 'message' => 'Le mot de passe ne respecte pas les exigences de sécurité.'];
        }

        $userId = $this->passwordResetRepo->consume(hash('sha256', $token));
        if (!$userId) return ['success' => false, 'message' => 'Ce lien est invalide ou expiré.'];

        $user = $this->userRepo->readById($userId);
        if (!$user) return ['success' => false, 'message' => 'Compte introuvable.'];

        $hash = password_hash($nouveauMotDePasse, PASSWORD_DEFAULT);

        $success = $this->userRepo->updatePassword(
            $user->getEmail(),
            $hash
        );

        if (!$success) {
            return [
                'success' => false,
                'message' => 'Erreur lors de la mise Ã  jour du mot de passe.'
            ];
        }

        return [
            'success' => true,
            'message' => 'Mot de passe modifié avec succès.'
        ];
    }

    private static function isStrongPassword(string $password): bool
    {
        return (bool) preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{10,}$/', $password);
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
