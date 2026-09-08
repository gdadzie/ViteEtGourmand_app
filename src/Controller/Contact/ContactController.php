<?php

namespace Controller\Contact;

use Repository\ContactRepository;
use Repository\HorairesRepository;
use Service\Authentification\AuthService;
use Service\MailService;
use View\View;

class ContactController
{
    public function __construct(
        private ContactRepository $repository,
        private MailService $mailService,
        private HorairesRepository $horairesRepository
    ) {
    }

    public function index(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->store();
            return;
        }

        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        View::render('Contact/contact', [
            'currentPage' => 'contact',
            'pageTitle' => 'Vite & Gourmand - Contact',
            'metaDescription' => 'Contactez Vite & Gourmand pour toute demande de devis ou information.',
            'success' => $success,
            'error' => $error,
            'horaires' => $this->horairesRepository->readAll(),
            'cssFiles' => ['/assets/css/contact.css'],
        ]);
    }

    private function store(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $title = trim((string) ($_POST['titre'] ?? ''));
        $message = trim((string) ($_POST['message'] ?? ''));

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $title === '' || $message === '') {
            $_SESSION['error'] = 'Veuillez renseigner un e-mail valide, un objet et votre message.';
            header('Location: index.php?page=contact');
            exit;
        }

        if (mb_strlen($title) > 255 || mb_strlen($message) > 5000) {
            $_SESSION['error'] = 'Votre message est trop long.';
            header('Location: index.php?page=contact');
            exit;
        }

        $userId = isset($_SESSION['id_utilisateur']) ? (int) $_SESSION['id_utilisateur'] : null;
        if (!$this->repository->create($userId, $email, $title, $message)) {
            $_SESSION['error'] = 'Votre message n’a pas pu être enregistré. Réessayez plus tard.';
            header('Location: index.php?page=contact');
            exit;
        }

        $this->mailService->envoyerMailContact($email, $title, $message);
        $acknowledgementSent = $this->mailService->envoyerAccuseReceptionContact($email, $title);
        $_SESSION['success'] = $acknowledgementSent
            ? 'Votre message a bien été envoyé. Un accusé de réception vous a été adressé.'
            : 'Votre message a bien été enregistré. Nous vous répondrons rapidement.';

        header('Location: index.php?page=contact');
        exit;
    }

    public function inbox(): void
    {
        AuthService::requireAdminEmploye();

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $this->processInboxAction();
            return;
        }

        $success = $_SESSION['success'] ?? null;
        $error = $_SESSION['error'] ?? null;
        unset($_SESSION['success'], $_SESSION['error']);

        try {
            $messages = $this->repository->readInbox();
        } catch (\Throwable $exception) {
            error_log('Contact inbox error: ' . $exception->getMessage());
            $messages = [];
            $error = 'La messagerie est momentanément indisponible.';
        }

        View::render('Contact/inbox', [
            'currentPage' => 'messagerie_contact',
            'pageTitle' => 'Vite & Gourmand - Messagerie contact',
            'metaDescription' => 'Gestion des messages de contact.',
            'messages' => $messages,
            'success' => $success,
            'error' => $error,
            'cssFiles' => ['/assets/css/contact-inbox.css'],
        ]);
    }

    private function processInboxAction(): void
    {
        $id = (int) ($_POST['message_id'] ?? 0);
        $action = (string) ($_POST['action'] ?? '');
        if ($id <= 0 || !in_array($action, ['reply', 'mark-treated'], true)) {
            $_SESSION['error'] = 'Action de messagerie invalide.';
            $this->redirectInbox();
        }

        try {
            $message = $this->repository->readInboxMessage($id);
            if ($message === null) {
                $_SESSION['error'] = 'Ce message est introuvable.';
                $this->redirectInbox();
            }

            if ($action === 'reply') {
                $reply = trim((string) ($_POST['reply'] ?? ''));
                if ($reply === '' || mb_strlen($reply) > 5000) {
                    $_SESSION['error'] = 'Votre réponse doit contenir entre 1 et 5 000 caractères.';
                    $this->redirectInbox();
                }
                if (!$this->mailService->envoyerReponseContact($message['email'], $message['titre_message'], $reply)) {
                    $_SESSION['error'] = 'La réponse n’a pas pu être envoyée. Vérifiez la configuration e-mail.';
                    $this->redirectInbox();
                }
                $this->repository->markAsTreated($id, $reply);
                $_SESSION['success'] = 'La réponse a été envoyée et le message est marqué comme traité.';
            } else {
                $this->repository->markAsTreated($id);
                $_SESSION['success'] = 'Le message est marqué comme traité.';
            }
        } catch (\Throwable $exception) {
            error_log('Contact inbox action error: ' . $exception->getMessage());
            $_SESSION['error'] = 'Cette action n’a pas pu être effectuée. Réessayez.';
        }

        $this->redirectInbox();
    }

    private function redirectInbox(): never
    {
        header('Location: index.php?page=messagerie_contact');
        exit;
    }
}
