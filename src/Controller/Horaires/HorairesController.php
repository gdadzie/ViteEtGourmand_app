<?php

namespace Controller\Horaires;

use Entity\Horaires;
use Repository\HorairesRepository;

class HorairesController
{
    private HorairesRepository $repo;

    public function __construct(HorairesRepository $repo) {
        $this->repo = $repo;
    }
    public function show(): void {
        $horaires = $this->repo->findAll();
        require __DIR__ . '/../../View/partials/footer.php';
    }

    public function store(): void {

        // Empêcher la mise en cache
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        // 1️⃣ Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        // 2️⃣ Vérifier le rôle : seuls employés (2) et admins (3) peuvent accéder
        if (!in_array($_SESSION['id_role'], [2, 3])) {
            $_SESSION['error'] = 'Accès interdit !';
            header('Location: index.php?page=connexion');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->show();
            return;
        }

        $horairesPost = $_POST['horaires'] ?? [];

        foreach ($horairesPost as $id => $data) {
            $horaire = $this->repo->findById((int)$id);
            if (!$horaire) continue;

            $estFerme = !empty($data['est_ferme']);
            $heureOuverture = $estFerme ? null : $data['ouverture'] ?? '09:00';
            $heureFermeture = $estFerme ? null : $data['fermeture'] ?? '18:00';

            $horaire->setHeureOuverture($heureOuverture);
            $horaire->setHeureFermeture($heureFermeture);
            $horaire->setEstFerme($estFerme);

            $this->repo->update($horaire);
        }

        echo "<div class='alert alert-success text-center mt-3'>Contact mis à jour avec succès !</div>";

        $this->show();

        header('Location: /Home/home?success=1');
        exit;

    }
}
