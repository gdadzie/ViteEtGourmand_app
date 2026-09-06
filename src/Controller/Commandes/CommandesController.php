<?php

namespace Controller\Commandes;

use Entity\Avis;
use Entity\Commande;
use Repository\AvisRepository;
use Repository\CommandesRepository;
use Repository\CommandeStatutMongoRepository;
use Repository\MenusRepository;
use Repository\UtilisateursRepository;
use Repository\VillesRepository;
use Service\MailService;

class CommandesController
{
    private CommandesRepository $commandeRepo;
    private MenusRepository $menusRepo;
    private UtilisateursRepository $utilisateursRepo;
    private VillesRepository $villesRepo;
    private AvisRepository $avisRepo;
    private Avis $avis;
    private MailService $mailService;

    // =========================================================
    // CONSTRUCTEUR
    // =========================================================
    public function __construct(
        CommandesRepository $commandeRepo,
        MenusRepository $menusRepo,
        UtilisateursRepository $utilisateursRepo,
        VillesRepository $villesRepo,
        AvisRepository $avisRepo,
        Avis $avis,
        MailService $mailService
    ) {
        $this->commandeRepo = $commandeRepo;
        $this->menusRepo = $menusRepo;
        $this->utilisateursRepo = $utilisateursRepo;
        $this->villesRepo = $villesRepo;
        $this->avisRepo = $avisRepo;
        $this->avis = $avis;
        $this->mailService = $mailService;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =========================================================
    // LISTE DES COMMANDES (ADMIN / EMPLOYÉ)
    // =========================================================
    public function listeDesCommandes(): void
    {
        // Vérification de la session
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        // Vérification des rôles
        if (
            !isset($_SESSION['id_role']) ||
            !in_array((int)$_SESSION['id_role'], [2, 3])
        ) {
            $_SESSION['error'] = "Accès interdit";

            header('Location: index.php?page=home');
            exit;
        }

        // Récupération des commandes
        $commandes = $this->commandeRepo->readAll();

        $clients = [];

        foreach ($commandes as $commande) {
            $idUtilisateur = $commande->getIdUtilisateur();

            if (
                $idUtilisateur &&
                !isset($clients[$idUtilisateur])
            ) {
                $clients[$idUtilisateur] =
                    $this->utilisateursRepo->readById($idUtilisateur);
            }
        }

        $this->menusRepo->readByTitre($_POST['titre'] ?? '');

        // Affichage de la vue
        require __DIR__ . '/../../View/Commandes/gestion_des_commandes.php';
    }

    // =========================================================
    // COMMANDER UN MENU
    // =========================================================
    public function commanderMenu(): void
    {
        // =====================================================
        // SESSION
        // =====================================================
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // =====================================================
        // MÉTHODE HTTP
        // =====================================================
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method !== 'POST') {
            http_response_code(405);

            $_SESSION['error'] = "Méthode non autorisée";

            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        // =====================================================
        // VÉRIFICATION DE LA SESSION
        // =====================================================
        $idUtilisateur = $_SESSION['id_utilisateur'] ?? null;

        if (!$idUtilisateur) {
            $_SESSION['error'] = "Connexion requise";

            header('Location: index.php?page=connexion');
            exit;
        }

        // =====================================================
        // RÉCUPÉRATION DU CLIENT
        // =====================================================
        $client = $this->utilisateursRepo->readById(
            (int)$idUtilisateur
        );

        if (!$client) {
            $_SESSION['error'] = "Utilisateur introuvable";

            header('Location: index.php?page=connexion');
            exit;
        }

        // =====================================================
        // ÉTAPE 1 : AFFICHAGE DU FORMULAIRE
        // =====================================================
        if (isset($_POST['id'])) {

            $idMenu = (int)($_POST['id'] ?? 0);

            if ($idMenu <= 0) {
                $_SESSION['error'] = "Menu invalide";

                header('Location: index.php?page=liste_des_menus');
                exit;
            }

            // =================================================
            // RÉCUPÉRATION DU MENU
            // =================================================
            $menu = $this->menusRepo->readById($idMenu);

            if (!$menu) {
                $_SESSION['error'] = "Menu introuvable";

                header('Location: index.php?page=liste_des_menus');
                exit;
            }

            // =================================================
            // DONNÉES CLIENT
            // =================================================
            $numeroRue = $client->getNumeroRue() ?? '';
            $nomRue = $client->getNomRue() ?? '';
            $codePostal = $client->getCodePostal() ?? '';
            $ville = $client->getIdVille() ?? '';

            $nom = $client->getNom() ?? '';
            $prenom = $client->getPrenom() ?? '';
            $telephone = $client->getTelephone() ?? '';
            $email = $client->getEmail() ?? '';

            // =================================================
            // DONNÉES MENU
            // =================================================
            $minimumPersonnes =
                (int)$menu->getNbMinPersonne();

            $prixParPersonne =
                (float)$menu->getPrixParPersonne();

            // =================================================
            // DATES
            // =================================================
            $aujourdhui = new \DateTime();

            $maxDate =
                (new \DateTime())->modify('+1 year');

            // =================================================
            // VILLES
            // =================================================
            $villes = $this->villesRepo->findAll();

            // =================================================
            // AFFICHAGE FORMULAIRE
            // =================================================
            require __DIR__ .
                '/../../View/Commandes/finaliser_commande.php';

            return;
        }

        // =====================================================
        // ÉTAPE 2 : ENREGISTRER LA COMMANDE
        // =====================================================
        if (isset($_POST['id_menu'])) {

            // =================================================
            // RÉCUPÉRATION DES DONNÉES
            // =================================================
            $idMenu =
                (int)($_POST['id_menu'] ?? 0);

            $nb =
                (int)($_POST['nombre_personnes'] ?? 0);

            $adresse =
                trim($_POST['adresse_livraison'] ?? '');

            $idVille =
                (int)($_POST['id_ville'] ?? 0);

            $date =
                trim($_POST['date_livraison'] ?? '');

            $heure =
                trim($_POST['heure_livraison'] ?? '');

            $modeReception =
                trim(
                    $_POST['mode_reception']
                    ?? 'livraison'
                );

            $modePaiement =
                trim(
                    $_POST['mode_paiement']
                    ?? 'paiement_livraison'
                );

            // =================================================
            // VALIDATION DES CHAMPS
            // =================================================
            if (
                !$idMenu ||
                !$nb ||
                empty($adresse) ||
                !$idVille ||
                empty($date) ||
                empty($heure)
            ) {
                $_SESSION['error'] =
                    "Tous les champs sont obligatoires";

                header(
                    'Location: index.php?page=liste_des_menus'
                );

                exit;
            }

            // =================================================
            // RÉCUPÉRATION DU MENU
            // =================================================
            $menu =
                $this->menusRepo->readById($idMenu);

            if (!$menu) {
                $_SESSION['error'] =
                    "Menu introuvable";

                header(
                    'Location: index.php?page=liste_des_menus'
                );

                exit;
            }

            // =================================================
            // RÉCUPÉRATION DES VILLES
            // IMPORTANT :
            // on recharge les villes lors de la soumission
            // =================================================
            $villes =
                $this->villesRepo->findAll();

            // =================================================
            // VÉRIFICATION DE LA VILLE
            // =================================================
            if (!isset($villes[$idVille])) {
                $_SESSION['error'] =
                    "Ville invalide";

                header(
                    'Location: index.php?page=liste_des_menus'
                );

                exit;
            }

            // =================================================
            // VALIDATION NOMBRE DE PERSONNES
            // =================================================
            $minimumPersonnes =
                (int)$menu->getNbMinPersonne();

            $stockDisponible =
                (int)$menu->getStockDisponible();

            if (
                $nb < $minimumPersonnes ||
                $nb > $stockDisponible
            ) {
                $_SESSION['error'] =
                    "Le nombre de personnes doit être compris entre "
                    . $minimumPersonnes
                    . " et "
                    . $stockDisponible
                    . ".";

                header(
                    'Location: index.php?page=liste_des_menus'
                );

                exit;
            }

            // =================================================
            // CALCUL DES PRIX
            // =================================================
            $detailsPrix =
                $this->calculerPrixTotal(
                    (float)$menu->getPrixParPersonne(),
                    $nb,
                    $minimumPersonnes,
                    $villes[$idVille]
                );

            $prixMenus =
                $detailsPrix['prix_menus'];

            $reduction =
                $detailsPrix['reduction'];

            $fraisLivraison =
                $detailsPrix['livraison'];

            $prixTotal =
                $detailsPrix['total'];

            // =================================================
            // CRÉATION DE LA COMMANDE
            // =================================================
            $commande = new Commande();

            $commande->setIdUtilisateur(
                (int)$idUtilisateur
            );

            $commande->setIdMenu($idMenu);

            $commande->setNombrePersonnes($nb);

            $commande->setPrixTotal($prixTotal);

            $commande->setAdresseLivraison($adresse);

            // ID SQL de la ville
            $commande->setIdVille($idVille);

            $commande->setDateLivraison($date);

            $commande->setHeureLivraison($heure);

            $commande->setStatut('reçue');

            $commande->setDateCreation(
                date('Y-m-d H:i:s')
            );

            $commande->setModeReception(
                $modeReception
            );

            $commande->setModePaiement(
                $modePaiement
            );

            $commande->setStatutPaiement(
                'unpaid'
            );

            // =================================================
            // SAUVEGARDE DE LA COMMANDE
            // =================================================
            if ($this->commandeRepo->create($commande)) {

                // =================================================
                // ENVOI DU MAIL DE CONFIRMATION
                // =================================================
                $nomComplet = trim(
                    $client->getPrenom()
                    . ' '
                    . $client->getNom()
                );

                $mailEnvoye =
                    $this->mailService
                        ->envoyerMailConfirmationCommande(
                            $client->getEmail(),
                            $nomComplet,
                            $menu->getTitre(),
                            $nb,
                            $date,
                            $heure,
                            $adresse,
                            $prixMenus,
                            $reduction,
                            $fraisLivraison,
                            $prixTotal
                        );

                // =================================================
                // MESSAGE DE SUCCÈS
                // =================================================
                if ($mailEnvoye) {

                    $_SESSION['success'] =
                        "Commande créée avec succès. "
                        . "Un email de confirmation vous a été envoyé.";

                } else {

                    // La commande est bien créée même si
                    // le mail n'a pas pu être envoyé.
                    $_SESSION['success'] =
                        "Commande créée avec succès.";

                    $_SESSION['error'] =
                        "L'email de confirmation n'a pas pu être envoyé.";
                }

            } else {

                $_SESSION['error'] =
                    "Erreur lors de la création de la commande";
            }

            // =================================================
// PAGE DE CONFIRMATION
// =================================================
            $_SESSION['email_confirmation'] = $client->getEmail();

            require __DIR__ . '/../../View/Commandes/confirmation_commande.php';

            exit;
        }

        // =====================================================
        // FALLBACK
        // =====================================================
        $_SESSION['error'] =
            "Requête invalide";

        header(
            'Location: index.php?page=liste_des_menus'
        );

        exit;
    }

    // =========================================================
    // SUPPRESSION COMMANDE
    // =========================================================
    public function supprimerUneCommande(): void
    {
        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            !== 'POST'
        ) {
            header(
                'Location: index.php?page=gestion_des_commandes'
            );
            exit;
        }

        // Récupération de l'ID
        $id =
            (int)($_POST['id_commande'] ?? 0);

        // Vérification
        if (!$id) {
            $_SESSION['error'] =
                "ID invalide";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        // Suppression
        if ($this->commandeRepo->delete($id)) {

            $_SESSION['success'] =
                "Commande supprimée avec succès";

        } else {

            $_SESSION['error'] =
                "Commande introuvable";
        }

        // =====================================================
        // REDIRECTION SELON LE RÔLE
        // =====================================================
        $idRole =
            (int)($_SESSION['id_role'] ?? 0);

        if ($idRole === 1) {

            header(
                'Location: index.php?page=espace_utilisateur'
            );

        } elseif ($idRole === 2) {

            header(
                'Location: index.php?page=espace_employe'
            );

        } elseif ($idRole === 3) {

            header(
                'Location: index.php?page=admin'
            );

        } else {

            header(
                'Location: index.php?page=gestion_des_commandes'
            );
        }

        exit;
    }

    // =========================================================
    // MODIFIER STATUT COMMANDE
    // =========================================================
    public function modifierStatutCommande(): void
    {
        if (
            ($_SERVER['REQUEST_METHOD'] ?? 'GET')
            !== 'POST'
        ) {
            header(
                'Location: index.php?page=gestion_des_commandes'
            );
            exit;
        }

        $id =
            (int)($_POST['id'] ?? 0);

        if (!$id) {
            $_SESSION['error'] =
                "ID invalide";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        $commande =
            $this->commandeRepo->readById($id);

        if (!$commande) {
            $_SESSION['error'] =
                "Commande introuvable";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        $ancien =
            $commande->getStatut();

        // =====================================================
        // STATUTS NORMALISÉS
        // =====================================================
        $map = [
            'recue' => 'acceptee',
            'reçue' => 'acceptee',
            'acceptee' => 'payee',
            'payee' => 'en_preparation',
            'en_preparation' => 'livree',
            'livree' => 'attente_retour',
            'attente_retour' => 'terminee'
        ];

        if (!isset($map[$ancien])) {
            $_SESSION['error'] =
                "Statut non modifiable";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        $nouveau =
            $map[$ancien];

        $this->commandeRepo->updateStatut(
            $id,
            $nouveau
        );

        // =====================================================
        // HISTORIQUE MONGODB
        // =====================================================
        $mongo =
            new CommandeStatutMongoRepository();

        $mongo->ajouterHistorique(
            $id,
            $ancien,
            $nouveau,
            $_SESSION['id_utilisateur'] ?? null,
            $_SESSION['id_role'] ?? null
        );

        $_SESSION['success'] =
            "Statut mis à jour";

        header(
            'Location: index.php?page=gestion_des_commandes'
        );

        exit;
    }

    // =========================================================
    // HISTORIQUE UTILISATEUR
    // =========================================================
    public function historiqueCommandeParUtilisateur(): void
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header(
                'Location: index.php?page=connexion'
            );
            exit;
        }

        $idUtilisateur =
            (int)$_SESSION['id_utilisateur'];

        $avisValide =
            $this->avis;

        $commandes =
            $this->commandeRepo
                ->readAllCommandeByUtilisateur(
                    $idUtilisateur
                );

        require __DIR__ .
            '/../../View/Commandes/liste_des_commandes_par_utilisateur.php';
    }

    // =========================================================
    // VALIDATION PAIEMENT
    // =========================================================
    public function validerPaiement(): void
    {
        $method =
            $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method !== 'POST') {
            http_response_code(405);

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] =
                "Connexion requise";

            header(
                'Location: index.php?page=connexion'
            );

            exit;
        }

        $idCommande =
            (int)($_POST['id_commande'] ?? 0);

        if (!$idCommande) {
            $_SESSION['error'] =
                "Commande invalide";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        $commande =
            $this->commandeRepo->readById(
                $idCommande
            );

        if (!$commande) {
            $_SESSION['error'] =
                "Commande introuvable";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        if (
            $commande->getStatutPaiement()
            === 'paid'
        ) {
            $_SESSION['error'] =
                "Déjà payé";

            header(
                'Location: index.php?page=gestion_des_commandes'
            );

            exit;
        }

        if (
            $this->commandeRepo
                ->validerPaiement($idCommande)
        ) {

            $_SESSION['success'] =
                "Paiement validé";

        } else {

            $_SESSION['error'] =
                "Erreur paiement";
        }

        header(
            'Location: index.php?page=gestion_des_commandes'
        );

        exit;
    }

    // =========================================================
    // CALCUL DES FRAIS DE LIVRAISON
    // =========================================================
    private function calculLivraison(array $ville): float
    {
        if (
            isset($ville['nom_ville']) &&
            $ville['nom_ville'] === 'Bordeaux'
        ) {
            return 0.0;
        }

        $distance =
            (int)($ville['distance_km'] ?? 0);

        return round(
            5 + ($distance * 0.59),
            2
        );
    }

    // =========================================================
    // CALCUL DE LA RÉDUCTION
    // =========================================================
    private function calculerReduction(
        float $prixMenus,
        int $nombrePersonnes,
        int $minimumPersonnes
    ): float {

        if (
            $nombrePersonnes >=
            ($minimumPersonnes + 5)
        ) {
            return round(
                $prixMenus * 0.10,
                2
            );
        }

        return 0.0;
    }

    // =========================================================
    // CALCUL DU PRIX TOTAL
    // =========================================================
    private function calculerPrixTotal(
        float $prixParPersonne,
        int $nombrePersonnes,
        int $minimumPersonnes,
        array $villeEntity
    ): array {

        // Prix des menus
        $prixMenus =
            $prixParPersonne *
            $nombrePersonnes;

        // Réduction
        $reduction =
            $this->calculerReduction(
                $prixMenus,
                $nombrePersonnes,
                $minimumPersonnes
            );

        // Livraison
        $fraisLivraison =
            $this->calculLivraison(
                $villeEntity
            );

        // Total
        $total =
            $prixMenus
            - $reduction
            + $fraisLivraison;

        return [
            'prix_menus' =>
                round($prixMenus, 2),

            'reduction' =>
                round($reduction, 2),

            'livraison' =>
                round($fraisLivraison, 2),

            'total' =>
                round($total, 2),
        ];
    }

    // =========================================================
    // DÉTAIL COMMANDE
    // =========================================================
    public function detailCommande(): void
    {
        $id =
            $_GET['id'] ?? null;

        if (!$id) {
            header(
                'Location: index.php?page=mes_commandes'
            );
            exit;
        }

        $commande =
            $this->commandeRepo->readById($id);

        if (!$commande) {
            header(
                'Location: index.php?page=mes_commandes'
            );
            exit;
        }

        // Vérification propriétaire
        if (
            !isset($_SESSION['id_utilisateur']) ||
            $commande->getIdUtilisateur()
            != $_SESSION['id_utilisateur']
        ) {
            header(
                'Location: index.php?page=mes_commandes'
            );
            exit;
        }

        require __DIR__ .
            '/../../View/Commandes/detail_commande.php';
    }
}