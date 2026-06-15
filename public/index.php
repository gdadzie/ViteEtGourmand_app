<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ===============================
// RACINE
// ===============================
define('ROOT', dirname(__DIR__));
define('VIEW_PATH', ROOT . '/src/View');

// ===============================
// SESSION
// ===============================
session_start();

// ===============================
// AUTOLOADER
// ===============================
require_once ROOT . '/vendor/autoload.php';

// ===============================
// USE - CONTROLLERS, REPOSITORIES, DATABASE
// ===============================
use Config\Database;

// Repositories
use Repository\AvisRepository;
use Repository\MenusRepository;
use Repository\PlatsRepository;
use Repository\UtilisateursRepository;
use Repository\HorairesRepository;
use Repository\CommandesRepository;
use Repository\VillesRepository;

// Controllers
use Controller\Admin\AdminController;
use Controller\Employes\EmployesController;
use Controller\Horaires\HorairesController;
use Controller\Home\HomeController;
use Controller\Utilisateurs\AuthController;
use Controller\Utilisateurs\UtilisateursController;
use Controller\Menus\MenusController;
use Controller\Plats\PlatsController;
use Controller\Commandes\CommandesController;
use Controller\Avis\AvisController;
use Controller\Contact\ContactController;

// Services
use Service\Avis\AvisService;
use Service\Menus\MenusService;

// ===============================
// CONNEXION DB
// ===============================
$env = $_ENV['APP_ENV'] ?? 'local';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../', ".env.$env");
$dotenv->load();

$conn = Database::getConnection();

if (!$conn) {
    die("Connexion base de données échouée : " . Database::getLastError());
}

// ===============================
// REPOSITORIES
// ===============================
$utilisateursRepo = new UtilisateursRepository($conn);
$horairesRepo     = new HorairesRepository($conn);
$platsRepo        = new PlatsRepository($conn);
$menusRepo        = new MenusRepository($conn);
$commandeRepo     = new CommandesRepository($conn);
$avisRepo         = new AvisRepository($conn);
$villesRepo       = new VillesRepository($conn);

// ===============================
// SERVICES
// ===============================
$menuService = new MenusService($menusRepo);
$avisService = new AvisService($avisRepo);

// ===============================
// CONTROLLERS
// ===============================
$homeController = new HomeController($avisRepo);
$ContactController = new ContactController();
$authController = new AuthController();

$utilisateursController = new UtilisateursController($conn);

$adminController = new AdminController(
    $utilisateursRepo,
    $conn
);

$employesController = new EmployesController(
    $horairesRepo,
    $utilisateursRepo,
    $menusRepo
);

$horairesController = new HorairesController($horairesRepo);

// 👉 IMPORTANT : controller doit recevoir SERVICE, pas repo
$menusController = new MenusController($menuService);

$platsController = new PlatsController($conn);

$commandesController = new CommandesController(
    $commandeRepo,
    $menusRepo,
    $utilisateursRepo,
    $villesRepo
);

$avisController = new AvisController(
    $avisRepo,
    $commandeRepo,
    $avisService
);

// ===============================
// DONNÉES GLOBALES
// ===============================
$horaires = $horairesRepo->readAll();

// ===============================
// ROUTING
// ===============================
$page = $_GET['page'] ?? 'home';

// ===============================
// PROTECTION MÉTHODE POST
// ===============================
function requirePostMethod()
{
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

    if ($method === 'OPTIONS') {
        http_response_code(204);
        exit;
    }

    if ($method !== 'POST') {
        http_response_code(405);
        $_SESSION['error'] = "Méthode non autorisée";
        header('Location: index.php?page=liste_des_menus');
        exit;
    }
}

// ===============================
// SWITCH ROUTES
// ===============================
switch ($page) {

    // ===============================
    // SUPPORT / UTILISATEUR
    // ===============================
    case 'home':
        $homeController->index();
        break;

    case 'contact':
        $ContactController->index();
        break;

    case 'inscription':
        $authController->inscription();
        break;

    case 'connexion':
        $authController->connexion();
        break;

    case 'espace_utilisateur':
        $authController->success();
        break;

    case 'deconnexion':
        $authController->deconnexion();
        break;

    case 'mes_commandes':
        $commandesController->historiqueCommandeParUtilisateur();
        break;

    case 'detail_commande':
        $commandesController->detailCommande();
        break;

    case 'profil':
        $utilisateursController->readUtilisateurById();
        break;

    case 'modifier_profil':
        $utilisateursController->updateUtilisateur();
        break;

    // ===============================
    // AVIS
    // ===============================

    case 'avis':
        $avisController->create();
        break;

    case 'ajouter_avis':
        $avisController->store();
        break;

    case'mon_historique_avis':
        $avisController->showAvisByUtilisateur();
        break;

    case 'enregistrer_avis':
        requirePostMethod();
        $avisController->storeAvis();
        break;

    case 'gestion_avis':
        $avisController->indexAdmin();
        break;

    case 'valider_avis':
        requirePostMethod();
        $avisController->update();
        break;

    case 'supprimer_avis':
        requirePostMethod();
        $avisController->supprimerAvis();
        break;

    // ===============================
    // ADMIN
    // ===============================
    case 'espace_admin':
        $adminController->index();
        break;

    case 'creation_employe':
        $adminController->creationEmploye();
        break;

    case 'supprimer_utilisateur':
        requirePostMethod();
        $utilisateursController->deleteUtilisateurById();
        break;

    case 'activer_utilisateur':
        requirePostMethod();
        $utilisateursController->activateUtilisateur();
        break;

    case 'modification_horaire':
        $adminController->modificationHoraires();
        break;

    case 'liste_des_utilisateurs':
        $adminController->listeDesUtilisateurs();
        break;

    case 'liste_des_employes':
        $adminController->listeDesEmployes();
        break;

    case 'filtre_des_employes':
        $adminController->listeDesEmployes();
        break;

    case 'filtre_des_utilisateurs':
        $adminController->listeDesUtilisateurs();
        break;

    case 'gestion_menus':
        $adminController->gestionDesMenus();
        break;

    case 'gestion_des_menus':
        $employesController->gestionDesMenusEmployes();
        break;

    case 'gestion_des_commandes':
        $commandesController->listeDesCommandes();
        break;

    // ===============================
    // EMPLOYÉ
    // ===============================
    case 'espace_employe':
        $employesController->index();
        break;

    case 'modifier_horaires':
        requirePostMethod();
        $horairesController->store();
        break;

    case 'horaires':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $horairesController->store();
        } else {
            $horairesController->show();
        }
        break;

    // ===============================
    // MENUS
    // ===============================
    case 'liste_des_menus':
        $menusController->listeDesMenus();
        break;

    case 'detail_menu':
        $menusController->readDetailMenu();
        break;

    case 'creer_un_menu':
        // ❌ requirePostMethod supprimé (controller gère POST)
        $menusController->createMenu();
        break;

    case 'supprimer_menu':
        $menusController->deleteMenu();
        break;

    // ===============================
    // PLATS
    // ===============================
    case 'liste_des_plats':
        $platsController->listeDesPlats();
        break;

    case 'creer_un_plat':
        requirePostMethod();
        $platsController->creerUnPlat();
        break;

    // ===============================
    // COMMANDES
    // ===============================
    case 'commander_menu':
        requirePostMethod();
        $commandesController->commanderMenu();
        break;

    case 'supprimer_commande':
        requirePostMethod();
        $commandesController->supprimerUneCommande();
        break;

    case 'modifier_statut_commande':
        requirePostMethod();
        $commandesController->modifierStatutCommande();
        break;

    case 'valider_paiement_commande':
        requirePostMethod();
        $commandesController->validerPaiement();
        break;

    // ===============================
    // 404
    // ===============================
    default:
        http_response_code(404);
        echo "<h1>Page non trouvée</h1>";
        break;
}