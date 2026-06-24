<?php
declare(strict_types=1);

namespace Controller\Authentification;

use Service\Authentification\AuthService;
use Repository\VillesRepository;
use PDO;

class AuthController
{
    private AuthService $authService;
    private VillesRepository $villesRepo;
    private PDO $conn;

    public function __construct(AuthService $authService, PDO $conn)
    {
        $this->authService = new AuthService();
        $this->villesRepo = new VillesRepository($conn);


    }

    public function connexion(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Authentication/formulaire_de_connexion.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mdp'] ?? '';

        $result = $this->authService->login($email, $mdp);

        if (!$result['success']) {
            $error = $result['message'];
            require __DIR__ . '/../../View/Authentication/formulaire_de_connexion.php';
            return;
        }

        header('Location: ' . $result['redirect']);
        exit;
    }

    public function inscription(): void
    {
        $villes = $this->villesRepo->findAll();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }

        // =========================
        // RGPD CHECK
        // =========================
        if (!isset($_POST['rgpd'])) {
            $error = "Vous devez accepter la politique de confidentialité.";
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }

        // =========================
        // INSCRIPTION
        // =========================
        $result = $this->authService->register(
            $_POST,
            isset($_POST['rgpd'])
        );

        if (!$result['success']) {
            $error = $result['message'];
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }

        $success = $result['message'];
        require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
    }

    public function deconnexion(): void
    {
        $this->authService->logout();

        header('Location: index.php?page=connexion');
        exit;
    }

    public function success(): void
    {
        require __DIR__ . '/../../View/Authentication/espace_utilisateur.php';
    }

    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Authentication/reinitialiser_mot_de_passe.php';
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $mdp = $_POST['mdp'] ?? '';

        $result = $this->authService->resetMotDePasse($email, $mdp);

        if (!$result['success']) {
            $error = $result['message'];
            require __DIR__ . '/../../View/Authentication/reinitialiser_mot_de_passe.php';
            return;
        }

        // ✅ message succès
        $success = $result['message'];

        require __DIR__ . '/../../View/Authentication/reinitialiser_mot_de_passe.php';
    }
}