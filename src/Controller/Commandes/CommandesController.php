<?php

namespace Controller\Commandes;

use Entity\Avis;
use Entity\Commande;
use Entity\Villes;
use Repository\AvisRepository;
use Repository\CommandesRepository;
use Repository\CommandeStatutMongoRepository;
use Repository\MenusRepository;
use Repository\UtilisateursRepository;
Use Repository\VillesRepository;

class CommandesController
{
    private CommandesRepository $commandeRepo;
    private MenusRepository $menusRepo;
    private UtilisateursRepository $utilisateursRepo;
    private VillesRepository $villesRepo;
    private AvisRepository $avisRepo;
    private Avis $avis;

    // =========================================================
    // CONSTRUCTEUR
    // =========================================================
    public function __construct(
        CommandesRepository $commandeRepo,
        MenusRepository $menusRepo,
        UtilisateursRepository $utilisateursRepo,
        VillesRepository $villesRepo,
        AvisRepository $avisRepo,
        Avis $avis
    ) {
        $this->commandeRepo = $commandeRepo;
        $this->menusRepo = $menusRepo;
        $this->utilisateursRepo = $utilisateursRepo;
        $this->villesRepo = $villesRepo;
        $this->avisRepo = $avisRepo;
        $this->avis = $avis;

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // =========================================================
    // LISTE DES COMMANDES (ADMIN / EMPLOYÉ)
    // =========================================================
    public function listeDesCommandes(): void
    {
        //Vérification de la session
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        //Vérification des roles
        if (!isset($_SESSION['id_role']) || !in_array((int)$_SESSION['id_role'], [2, 3])) {
            $_SESSION['error'] = "Accès interdit";
            header('Location: index.php?page=home');
            exit;
        }

        // Récupération des donnés dans le repository
        $commandes = $this->commandeRepo->readAll();
        $this->menusRepo->readByTitre((int)$_GET['id']);





        // Affichage de la vue
        require __DIR__ . '/../../View/Commandes/gestion_des_commandes.php';
    }

    // =========================================================
    // COMMANDER UN MENU
    // =========================================================
    public function commanderMenu(): void
    {
        // =====================================================
        // SESSION SAFE
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

        //Vérification de la session
        $idUtilisateur = $_SESSION['id_utilisateur'] ?? null;

        if (!$idUtilisateur) {

            $_SESSION['error'] = "Connexion requise";

            header('Location: index.php?page=connexion');

            exit;
        }

        //Récupération des données dans le repository
        $client = $this->utilisateursRepo->readById((int)$idUtilisateur);

        // =====================================================
        // ÉTAPE 1 : AFFICHAGE FORMULAIRE
        // =====================================================
        if (isset($_POST['id'])) {

            $idMenu = (int)($_POST['id'] ?? 0);

            if ($idMenu <= 0) {

                $_SESSION['error'] = "Menu invalide";

                header('Location: index.php?page=liste_des_menus');

                exit;
            }

            $menu = $this->menusRepo->readById($idMenu);

            if (!$menu) {

                $_SESSION['error'] = "Menu introuvable";

                header('Location: index.php?page=liste_des_menus');

                exit;
            }

            // =================================================
            // DONNÉES CLIENT
            // =================================================
            $numeroRue = $client?->getNumeroRue() ?? '';
            $nomRue = $client?->getNomRue() ?? '';
            $codePostal = $client?->getCodePostal() ?? '';
            $ville = $client?->getIdVille()??'';
            $nom = $client?->getNom() ?? '';
            $prenom = $client?->getPrenom() ?? '';
            $telephone = $client?->getTelephone() ?? '';
            $email = $client?->getEmail() ?? '';

            // =================================================
            // DONNÉES MENU
            // =================================================
            $minimumPersonnes = (int)$menu->getNbMinPersonne();

            $prixParPersonne = (float)$menu->getPrixParPersonne();

            $aujourdhui = new \DateTime();

            $maxDate = (new \DateTime())->modify('+1 year');

            $villes = $this->villesRepo->findAll();



            require __DIR__ . '/../../View/Commandes/finaliser_commande.php';

            return;
        }

        // =====================================================
        // ÉTAPE 2 : ENREGISTRER COMMANDE
        // =====================================================
        if (isset($_POST['id_menu'])) {

            // =================================================
            // RÉCUPÉRATION DES DONNÉES
            // =================================================
            $idMenu = (int)($_POST['id_menu'] ?? 0);

            $nb = (int)($_POST['nombre_personnes'] ?? 0);

            $adresse = trim($_POST['adresse_livraison'] ?? '');

            $idVille = (int)($_POST['id_ville'] ?? 0);

            $date = trim($_POST['date_livraison'] ?? '');

            $heure = trim($_POST['heure_livraison'] ?? '');

            $modeReception = trim(
                $_POST['mode_reception'] ?? 'livraison'
            );

            $modePaiement = trim(
                $_POST['mode_paiement'] ?? 'paiement_livraison'
            );



            // =================================================
            // VALIDATION
            // =================================================
            if (
                !$idMenu ||
                !$nb ||
                empty($adresse) ||
                empty($date) ||
                empty($heure)
            ) {

                $_SESSION['error'] = "Tous les champs sont obligatoires";

                header('Location: index.php?page=liste_des_menus');

                exit;
            }

            // =================================================
            // MENU
            // =================================================
            $menu = $this->menusRepo->readById($idMenu);

            if (!$menu) {

                $_SESSION['error'] = "Menu introuvable";

                header('Location: index.php?page=liste_des_menus');

                exit;
            }

            // =================================================
            // VALIDATION NOMBRE PERSONNES
            // =================================================
            $minimumPersonnes = (int)$menu->getNbMinPersonne();

            $stockDisponible = (int)$menu->getStockDisponible();

            if (
                $nb < $minimumPersonnes ||
                $nb > $stockDisponible
            ) {

                $_SESSION['error'] = "Nombre de personnes invalide";

                header('Location: index.php?page=liste_des_menus');

                exit;
            }

            // =================================================
            // CALCUL DES PRIX
            // =================================================
            $detailsPrix = $this->calculerPrixTotal(
                (float)$menu->getPrixParPersonne(),
                $nb,
                $minimumPersonnes,
                $villeEntity
            );

            $prixMenus = $detailsPrix['prix_menus'];

            $reduction = $detailsPrix['reduction'];

            $fraisLivraison = $detailsPrix['livraison'];

            $prixTotal = $detailsPrix['total'];

            // =================================================
            // CRÉATION COMMANDE
            // =================================================
            $commande = new Commande();

            $commande->setIdUtilisateur((int)$idUtilisateur);

            $commande->setIdMenu($idMenu);

            $commande->setNombrePersonnes($nb);

            $commande->setPrixTotal($prixTotal);

            $commande->setAdresseLivraison($adresse);

            // IMPORTANT :
            // on sauvegarde l'ID SQL de la ville
            $commande->setIdVille($idVille);

            $commande->setDateLivraison($date);

            $commande->setHeureLivraison($heure);

            $commande->setStatut('reçue');

            $commande->setDateCreation(date('Y-m-d H:i:s'));

            $commande->setModeReception($modeReception);

            $commande->setModePaiement($modePaiement);

            $commande->setStatutPaiement('unpaid');

            // =================================================
            // SAUVEGARDE
            // =================================================
            if ($this->commandeRepo->create($commande)) {

                $_SESSION['success'] =
                    "Commande créée avec succès";

            } else {

                $_SESSION['error'] =
                    "Erreur lors de la création de la commande";
            }

            header('Location: index.php?page=espace_utilisateur');

            exit;
        }

        // =====================================================
        // FALLBACK
        // =====================================================
        $_SESSION['error'] = "Requête invalide";

        header('Location: index.php?page=liste_des_menus');

        exit;
    }

    // =========================================================
    // SUPPRESSION COMMANDE
    // =========================================================
    public function supprimerUneCommande(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        //Récupére l'id_commande via le formulaire POST
        $id = (int)($_POST['id_commande'] ?? 0);

        //Vérifie si l'id_commande est valide
        if (!$id) {
            $_SESSION['error'] = "ID invalide";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        //supprime la commande
        if ($this->commandeRepo->delete($id)) {
            $_SESSION['success'] = "Commande supprimée avec succès";
        } else {
            $_SESSION['error'] = "Commande introuvable";
        }

        // Récupère l'identifiant du rôle de l'utilisateur connecté depuis la session
        $idRole = (int)($_SESSION['id_role'] ?? 0);

        //Redirection en fonction du role de l'utilisateur
        if($idRole === 1) {
            header('Location: index.php?page=espace_utilisateur');
        }
        elseif ($idRole === 2) {
            header('Location: index.php?page=espace_employe');
        } elseif ($idRole === 3) {
            header('Location: index.php?page=admin');
        } else {
            header('Location: index.php?page=gestion_des_commandes');
        }

        exit;
    }


    // =========================================================
    // MODIFIER STATUT COMMANDE
    // =========================================================
    // =========================================================
    // MODIFICATION STATUT
    // =========================================================
    public function modifierStatutCommande(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        $id = (int)($_POST['id'] ?? 0);

        if (!$id) {
            $_SESSION['error'] = "ID invalide";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        $commande = $this->commandeRepo->readById($id);

        if (!$commande) {
            $_SESSION['error'] = "Commande introuvable";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        $ancien = $commande->getStatut();

        // ✅ STATUTS NORMALISÉS
        $map = [
            'recue' => 'acceptee',
            'acceptee' => 'payee',
            'payee' => 'en_preparation',
            'en_preparation' => 'livree',
            'livree' => 'attente_retour',
            'attente_retour' => 'terminee'
        ];

        if (!isset($map[$ancien])) {
            $_SESSION['error'] = "Statut non modifiable";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        $nouveau = $map[$ancien];

        $this->commandeRepo->updateStatut($id, $nouveau);

        $mongo = new CommandeStatutMongoRepository();

        $mongo->ajouterHistorique(
            $id,
            $ancien,
            $nouveau,
            $_SESSION['id_utilisateur'] ?? null,
            $_SESSION['id_role'] ?? null
        );

        $_SESSION['success'] = "Statut mis à jour";

        header('Location: index.php?page=gestion_des_commandes');
        exit;
    }

    // =========================================================
    // HISTORIQUE UTILISATEUR
    // =========================================================
    public function historiqueCommandeParUtilisateur(): void
    {
        if (!isset($_SESSION['id_utilisateur'])) {
            header('Location: index.php?page=connexion');
            exit;
        }

        $idUtilisateur = (int)$_SESSION['id_utilisateur'];

        $avisValide = $this->avis;
        $commandes = $this->commandeRepo->readAllCommandeByUtilisateur($idUtilisateur);


        require __DIR__ . '/../../View/Commandes/liste_des_commandes_par_utilisateur.php';
    }



    // =========================================================
    // VALIDATION PAIEMENT
    // =========================================================
    public function validerPaiement(): void
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        if ($method !== 'POST') {
            http_response_code(405);
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        if (!isset($_SESSION['id_utilisateur'])) {
            $_SESSION['error'] = "Connexion requise";
            header('Location: index.php?page=connexion');
            exit;
        }

        $idCommande = (int)($_POST['id_commande'] ?? 0);

        if (!$idCommande) {
            $_SESSION['error'] = "Commande invalide";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        $commande = $this->commandeRepo->readById($idCommande);

        if (!$commande) {
            $_SESSION['error'] = "Commande introuvable";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        if ($commande->getStatutPaiement() === 'paid') {
            $_SESSION['error'] = "Déjà payé";
            header('Location: index.php?page=gestion_des_commandes');
            exit;
        }

        if ($this->commandeRepo->validerPaiement($idCommande)) {
            $_SESSION['success'] = "Paiement validé";
        } else {
            $_SESSION['error'] = "Erreur paiement";
        }

        header('Location: index.php?page=gestion_des_commandes');
        exit;
    }
// =========================================================
// CALCUL DES FRAIS DE LIVRAISON
// =========================================================


    private function calculLivraison(array $ville): float
    {
        if ($ville['nom_ville'] === 'Bordeaux') {
            return 0;
        }

        $distance = (int)$ville['distance_km'];

        return round(5 + ($distance * 0.59), 2);
    }
// =========================================================
// CALCUL DE LA RÉDUCTION
// =========================================================
    private function calculerReduction(
        float $prixMenus,
        int $nombrePersonnes,
        int $minimumPersonnes
    ): float {

        if ($nombrePersonnes >= ($minimumPersonnes + 5)) {
            return round($prixMenus * 0.10, 2);
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

        $prixMenus = $prixParPersonne * $nombrePersonnes;

        $reduction = $this->calculerReduction(
            $prixMenus,
            $nombrePersonnes,
            $minimumPersonnes
        );

        $fraisLivraison = $this->calculLivraison($villeEntity);

        $total = $prixMenus - $reduction + $fraisLivraison;

        return [
            'prix_menus' => round($prixMenus, 2),
            'reduction' => round($reduction, 2),
            'livraison' => round($fraisLivraison, 2),
            'total' => round($total, 2),
        ];
    }

    public function detailCommande()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        $commande = $this->commandeRepo->readById($id);

        if (!$commande) {
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        if (!isset($_SESSION['id_utilisateur']) || $commande->getIdUtilisateur() != $_SESSION['id_utilisateur']) {
            header('Location: index.php?page=mes_commandes');
            exit;
        }

        require __DIR__ . '/../../View/Commandes/detail_commande.php';
    }
}