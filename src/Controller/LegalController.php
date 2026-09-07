<?php

namespace Controller;

use View\View;

class LegalController
{
    public function mentionsLegales(): void { $this->render('home/mentions_legales', 'Mentions légales'); }
    public function confidentialite(): void { $this->render('home/confidentialite', 'Politique de confidentialité'); }
    public function conditionsGenerales(): void { $this->render('home/conditions_generales', 'Conditions générales de vente'); }

    private function render(string $view, string $title): void
    {
        View::render($view, ['pageTitle' => $title . ' | Vite & Gourmand', 'metaDescription' => $title . ' de Vite & Gourmand.']);
    }
}
