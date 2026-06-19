<?php
declare(strict_types=1);

namespace Controller\Authentification;

use Service\Authentification\AuthService;

class AuthController
{
    private AuthService $authService;

    public function __construct()
    {
        $this->authService = new AuthService();
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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Authentication/formulaire_inscription.php';
            return;
        }

        $result = $this->authService->register($_POST);

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