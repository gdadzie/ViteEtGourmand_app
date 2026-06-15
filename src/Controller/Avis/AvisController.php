<?php

namespace Controller\Avis;

use Repository\AvisRepository;
use Repository\CommandesRepository;
use Service\Avis\AvisService;

class AvisController
{
    public function __construct(
        private AvisRepository $avisRepo,
        private CommandesRepository $commandeRepo,
        private AvisService $avisService
    ) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /* =========================================================
       OUTILS INTERNES (UTILITAIRES)
    ========================================================= */

    private function redirect(string $page): void
    {
        header("Location: index.php?page=$page");
        exit;
    }

    private function requireAuth(): void
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] = "Connexion requise";
            $this->redirect('connexion');
        }
    }

    /* =========================================================
       AFFICHAGE FORMULAIRE AVIS
    ========================================================= */

    public function create(): void
    {
        $this->requireAuth();

        $idCommande = (int)($_GET['id_commande'] ?? 0);

        if ($idCommande <= 0) {
            $_SESSION['error'] = "Commande invalide";
            $this->redirect('mes_commandes');
        }

        $commande = $this->commandeRepo->readById($idCommande);


        if (
            !$commande ||
            $commande->getIdUtilisateur() != $_SESSION['id_utilisateur'] ||
            $commande->getStatut() !== 'terminée' ||

            $this->avisRepo->existeDeja($idCommande)


        ) {
            $_SESSION['error'] = "Impossible de laisser un avis";
            $this->redirect('mes_commandes');
        }



        require __DIR__ . '/../../View/Avis/creer_avis.php';
    }

    /* =========================================================
       ENREGISTREMENT AVIS
    ========================================================= */

    public function store(): void
    {
        $this->requireAuth();

        try {
            $this->avisService->createAvis(
                $_POST,
                (int)$_SESSION['id_utilisateur']
            );

            $_SESSION['success'] = "Avis envoyé avec succès";

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        $this->redirect('mes_commandes');
    }

    /* =========================================================
       ADMIN - LISTE AVIS
    ========================================================= */

    public function indexAdmin(): void
    {
        $this->requireAuth();

        if (!in_array((int)($_SESSION['id_role'] ?? 0), [2, 3])) {
            $_SESSION['error'] = "Accès interdit";
            $this->redirect('home');
        }

        $avis = $this->avisRepo->readAll();

        require __DIR__ . '/../../View/Avis/gestion_des_avis.php';
    }

    /* =========================================================
       ADMIN - VALIDER AVIS
    ========================================================= */

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('gestion_avis');
        }

        $idAvis = (int)($_POST['id_avis'] ?? 0);

        if (!$idAvis) {
            $_SESSION['error'] = "Avis invalide";
            $this->redirect('gestion_avis');
        }

        $this->avisRepo->valider($idAvis)
            ? $_SESSION['success'] = "Avis validé avec succès"
            : $_SESSION['error'] = "Erreur lors de la validation";

        $this->redirect('gestion_avis');
    }

    /* =========================================================
       ADMIN - SUPPRIMER AVIS
    ========================================================= */

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('gestion_avis');
        }

        $idAvis = (int)($_POST['id_avis'] ?? 0);

        if (!$idAvis) {
            $_SESSION['error'] = "Avis invalide";
            $this->redirect('gestion_avis');
        }

        $this->avisRepo->delete($idAvis)
            ? $_SESSION['success'] = "Avis supprimé avec succès"
            : $_SESSION['error'] = "Erreur lors de la suppression";

        $this->redirect('gestion_avis');
    }


}