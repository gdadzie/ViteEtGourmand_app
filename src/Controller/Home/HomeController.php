<?php

namespace Controller\Home;

use Repository\AvisRepository;
use View\View;

class HomeController
{
    private AvisRepository $avisRepo;

    public function __construct(
        AvisRepository $avisRepo
    ) {
        $this->avisRepo = $avisRepo;
    }

    public function index(): void
    {
        // Si tu gardes $horaires créé dans index.php :
        // on le récupère via global (solution rapide et acceptable)
        global $horaires;

        // =========================
        // AVIS VALIDÉS
        // =========================
        $avisValides =
            $this->avisRepo->findByAvisValide();

        View::render('home/home', [
            'currentPage'     => 'home',
            'pageTitle'       => 'Vite & Gourmand — Accueil',
            'metaDescription' => 'Vite & Gourmand — Traiteur à Bordeaux. Menus pour événements, commandes en ligne.',
            'horaires'        => $horaires ?? [],

            // =========================
            // AVIS
            // =========================
            'avisValides'     => $avisValides,

            // CSS/JS spécifiques à cette page
            'cssFiles' => [
                '/assets/css/home/home.css',
                '/assets/css/media_queries_page_accueil.css?v=2',
            ],
            'jsFiles' => [
                '/assets/js/menu/menu.js',
            ],
        ]);
    }
}