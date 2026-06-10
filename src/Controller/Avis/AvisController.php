<?php

namespace Controller\Avis;

use Entity\Avis;
use Repository\AvisRepository;
use Repository\CommandesRepository;
use Service\Avis\AvisService;

class AvisController
{
    private AvisService $avisService;
    private AvisRepository $avisRepo;
    private CommandesRepository $commandeRepo;

    public function __construct(
        AvisRepository $avisRepo,
        CommandesRepository $commandeRepo,
        AvisService $avisService
    ) {
        $this->avisRepo = $avisRepo;
        $this->commandeRepo = $commandeRepo;
        $this->avisService = $avisService;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(){

        require __DIR__ . '/../../View/Avis/creer_avis.php';
    }

    // ===============================
    // FORMULAIRE AJOUT AVIS
    // ===============================
    public function createAvis(): void
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] = "Connexion requise";
            header('Location: index.php?page=connexion');
            exit;
        }

        $idCommande = (int)($_GET['id_commande'] ?? 0);

        if ($idCommande <= 0) {
            $_SESSION['error'] = "Commande introuvable";
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        $commande = $this->commandeRepo->readCommandeByIdUtilisateur(
            (int)$_SESSION['id_utilisateur'],
            $idCommande
        );

        if (!$commande) {
            $_SESSION['error'] = "Commande introuvable";
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        if ($commande->getStatut() !== 'terminée') {
            $_SESSION['error'] = "Vous ne pouvez pas encore laisser d'avis";
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        if ($this->avisRepo->existeDeja($idCommande)) {
            $_SESSION['error'] = "Vous avez déjà laissé un avis";
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        // ✅ AFFICHER LE FORMULAIRE DIRECTEMENT
        require __DIR__ . '/../../View/Avis/creer_avis.php';
    }


    // ===============================
    // STORE AVIS
    // ===============================

    public function storeAvis(): void
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $this->avisService->createAvis(
            $_POST,
            (int)$_SESSION['id_utilisateur']
        );

        header('Location: index.php?page=mes_commandes');
        exit;
    }

    // ===============================
    // ENREGISTRER AVIS
    // ===============================
    public function enregistrerAvis(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: index.php?page=mes_commandes');
            exit;
        }

        if (!isset($_SESSION['id_utilisateur'])) {

            header('Location: index.php?page=connexion');
            exit;
        }

        $idCommande = (int)($_POST['id_commande'] ?? 0);

        $note = (int)($_POST['note'] ?? 0);

        $commentaire = trim($_POST['commentaire'] ?? '');

        // Validation
        if (
            !$idCommande ||
            $note < 1 ||
            $note > 5 ||
            empty($commentaire)
        ) {

            $_SESSION['error'] =
                "Tous les champs sont obligatoires";

            header(
                "Location: index.php?page=ajouter_avis&id_commande=$idCommande"
            );

            exit;
        }

        // Vérifie commande utilisateur
        $commande = $this->commandeRepo->findAllByUtilisateur(
            (int)$_SESSION['id_utilisateur'],
            $idCommande
        );

        if (!$commande) {

            $_SESSION['error'] = "Commande invalide";

            header('Location: index.php?page=mes_commandes');
            exit;
        }

        // Vérification statut terminé
        if ($commande->getStatut() !== 'terminée') {

            $_SESSION['error'] =
                "Impossible de laisser un avis";

            header('Location: index.php?page=mes_commandes');
            exit;
        }

        // Vérification doublon
        if ($this->avisRepo->existeDeja($idCommande)) {

            $_SESSION['error'] =
                "Vous avez déjà laissé un avis";

            header('Location: index.php?page=mes_commandes');
            exit;
        }

        // Création avis
        $avis = new Avis();

        $avis->setIdCommande($idCommande);

        $avis->setIdUtilisateur(
            (int)$_SESSION['id_utilisateur']
        );

        $avis->setNote($note);

        $avis->setCommentaire($commentaire);

        // Sauvegarde
        if ($this->avisRepo->create($avis)) {

            $_SESSION['success'] =
                "Votre avis a été envoyé avec succès";

        } else {

            $_SESSION['error'] =
                "Erreur lors de l'envoi de l'avis";
        }

        header('Location: index.php?page=mes_commandes');
        exit;
    }

    // ===============================
    // LISTE AVIS ADMIN / EMPLOYÉ
    // ===============================
    public function gestionAvis(): void
    {
        if (!isset($_SESSION['id_utilisateur'])) {

            header('Location: index.php?page=connexion');
            exit;
        }

        // 2 = admin / 3 = employé
        if (
            !isset($_SESSION['id_role']) ||
            !in_array((int)$_SESSION['id_role'], [2, 3])
        ) {

            $_SESSION['error'] = "Accès interdit";

            header('Location: index.php?page=home');
            exit;
        }

        $avis = $this->avisRepo->findAll();

        require __DIR__ .
            '/../../View/Avis/gestion_avis.php';
    }

    // ===============================
    // VALIDER AVIS
    // ===============================
    public function validerAvis(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: index.php?page=gestion_avis');
            exit;
        }

        $idAvis = (int)($_POST['id_avis'] ?? 0);

        if (!$idAvis) {

            $_SESSION['error'] = "Avis invalide";

            header('Location: index.php?page=gestion_avis');
            exit;
        }

        if ($this->avisRepo->valider($idAvis)) {

            $_SESSION['success'] =
                "Avis validé avec succès";

        } else {

            $_SESSION['error'] =
                "Erreur lors de la validation";
        }

        header('Location: index.php?page=gestion_avis');
        exit;
    }

    // ===============================
    // SUPPRIMER AVIS
    // ===============================
    public function supprimerAvis(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {

            header('Location: index.php?page=gestion_avis');
            exit;
        }

        $idAvis = (int)($_POST['id_avis'] ?? 0);

        if (!$idAvis) {

            $_SESSION['error'] = "Avis invalide";

            header('Location: index.php?page=gestion_avis');
            exit;
        }

        if ($this->avisRepo->delete($idAvis)) {

            $_SESSION['success'] =
                "Avis supprimé avec succès";

        } else {

            $_SESSION['error'] =
                "Erreur lors de la suppression";
        }

        header('Location: index.php?page=gestion_avis');
        exit;
    }
}