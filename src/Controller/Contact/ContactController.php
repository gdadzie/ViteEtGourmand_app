<?php

namespace Controller\Contact;

use Repository\ContactRepository;
use Repository\HorairesRepository;
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

        $mailSent = $this->mailService->envoyerMailContact($email, $title, $message);
        $_SESSION['success'] = $mailSent
            ? 'Votre message a été envoyé. Nous vous répondrons rapidement.'
            : 'Votre message a bien été enregistré. Nous vous répondrons rapidement.';

        header('Location: index.php?page=contact');
        exit;
    }
}
