<?php
declare(strict_types=1);

namespace Controller\Authentification;

use Service\Authentification\AuthService;
use Repository\VillesRepository;
use View\View;
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
            $this->render('Auth/connexion');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $mdp   = $_POST['mdp'] ?? '';

        $result = $this->authService->login($email, $mdp);

        if (!$result['success']) {
            $error = $result['message'];
            $this->render('Auth/connexion', ['error' => $error]);
            return;
        }

        header('Location: ' . $result['redirect']);
        exit;
    }

    public function inscription(): void
    {
        $villes = $this->villesRepo->findAll();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('Auth/inscription', ['villes' => $villes]);
            return;
        }

        // =========================
        // RGPD CHECK
        // =========================
        if (!isset($_POST['rgpd'])) {
            $error = "Vous devez accepter la politique de confidentialitÃ©.";
            $this->render('Auth/inscription', ['villes' => $villes, 'error' => $error]);
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
            $this->render('Auth/inscription', ['villes' => $villes, 'error' => $error]);
            return;
        }

        $success = $result['message'];
        $this->render('Auth/inscription', ['villes' => $villes, 'success' => $success]);
    }

    public function deconnexion(): void
    {
        $this->authService->logout();

        header('Location: index.php?page=connexion');
        exit;
    }

    public function success(): void
    {


        if ((int)($_SESSION['id_role'] ?? 0) !== 1) {
            $_SESSION['error'] = "AccÃ¨s interdit";
            header('Location: index.php?page=home');
            exit;
        }
        require __DIR__ . '/../../View/Dashboard/client.php';
    }

    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $token = trim($_GET['token'] ?? '');
            $this->render('Auth/reinitialisation', ['token' => $token]);
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $token = trim($_POST['token'] ?? '');
        $mdp = $_POST['mdp'] ?? '';

        $result = $token !== ''
            ? $this->authService->resetMotDePasse($token, $mdp)
            : $this->authService->requestPasswordReset($email);

        if (!$result['success']) {
            $error = $result['message'];
            $this->render('Auth/reinitialisation', ['token' => $token, 'error' => $error]);
            return;
        }

        // âœ… message succÃ¨s
        $success = $result['message'];

        $this->render('Auth/reinitialisation', ['token' => $token, 'success' => $success]);
    }

    private function render(string $view, array $data = []): void
    {
        View::render($view, $data + [
            'pageTitle' => 'Vite & Gourmand',
            'cssFiles' => ['/assets/css/formulaires/formulaire.css'],
        ]);
    }
}
