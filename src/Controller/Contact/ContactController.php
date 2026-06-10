<?php

namespace Controller\Contact;

use View\View;

class ContactController
{
    public function index(): void
    {
        // messages flash (si tu les utilises via session)
        $success = $_SESSION['success'] ?? null;
        $error   = $_SESSION['error'] ?? null;

        unset($_SESSION['success'], $_SESSION['error']);

        View::render('Contact/contact', [
            'currentPage'     => 'contact',
            'pageTitle'       => 'Vite & Gourmand — Contact',
            'metaDescription' => 'Contactez Vite & Gourmand pour toute demande de devis ou information.',

            // messages pour la vue
            'success' => $success,
            'error'   => $error,

            // CSS spécifiques page contact
            'cssFiles' => [
                '/assets/css/contact/contact.css',
            ],

            // JS éventuel (validation, etc.)
            'jsFiles' => [
                '/assets/js/contact/contact.js',
            ],
        ]);
    }
}