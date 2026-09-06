<?php
ini_set('display_errors', getenv('APP_ENV') === 'local' ? '1' : '0');
ini_set('display_startup_errors', getenv('APP_ENV') === 'local' ? '1' : '0');
error_reporting(E_ALL);

// ===============================
// RACINE
// ===============================
define('ROOT', dirname(__DIR__));
define('VIEW_PATH', ROOT . '/src/View');

// ===============================
// SESSION
// ===============================
require_once ROOT . '/src/Core/Security.php';
\Core\Security::startSession();
ob_start([\Core\Security::class, 'injectCsrfFields']);

// ===============================
// AUTOLOADER
// ===============================
require_once ROOT . '/vendor/autoload.php';

// ===============================
// USE - CONTROLLERS, REPOSITORIES, DATABASE
// ===============================
use Config\Database;
use Controller\Admin\AdminController;
use Controller\Authentification\AuthController;
use Controller\Avis\AvisController;
use Controller\Commandes\CommandesController;
use Controller\Contact\ContactController;
use Controller\Employes\EmployesController;
use Controller\Home\HomeController;
use Controller\Horaires\HorairesController;
use Controller\Menus\MenusController;
use Controller\Plats\PlatsController;
use Controller\Utilisateurs\UtilisateursController;
use Entity\Avis;
use Entity\Commande;
use Entity\Menus;
use Entity\Plats;
use Entity\Utilisateurs;
use Entity\Villes;
use Repository\AvisRepository;
use Repository\CommandesRepository;
use Repository\HorairesRepository;
use Repository\MenusRepository;
use Repository\PlatsRepository;
use Repository\UtilisateursRepository;
use Repository\VillesRepository;
use Service\Authentification\AuthService;
use Service\Avis\AvisService;
use Service\MailService;
use Service\Menus\MenusService;


// ===============================
// CONNEXION DB
// ===============================
$env = getenv('APP_ENV') ?: 'local';
$envFile = ROOT . "/.env.$env";
if (is_file($envFile)) {
    Dotenv\Dotenv::createImmutable(ROOT, ".env.$env")->safeLoad();
}

$conn = Database::getConnection();

if (!$conn) {
    die("Connexion base de donnÃ©es Ã©chouÃ©e : " . Database::getLastError());
}



$avis = new Avis();
$menus = new Menus();
$plats = new Plats();
$utilisateurs = new Utilisateurs();

$commande = new Commande();
$villes = new Villes();
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
$menuService = new MenusService($menusRepo, $platsRepo);
$avisService = new AvisService($avisRepo);
$authService = new AuthService();
$mailService = new MailService();

// ===============================
// CONTROLLERS
// ===============================
$homeController = new HomeController($avisRepo);
$ContactController = new ContactController();
$authController = new AuthController(
    $authService,
    $conn
);

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


$menusController = new MenusController($menuService);

$platsController = new PlatsController($conn);

$commandesController = new CommandesController(
    $commandeRepo,
    $menusRepo,
    $utilisateursRepo,
    $villesRepo,
    $avisRepo,
    $avis,
    $mailService
);

$avisController = new AvisController(
    $avisRepo,
    $commandeRepo,
    $avisService
);

// ===============================
// DONNÃ‰ES GLOBALES
// ===============================
$horaires = $horairesRepo->readAll();

// ===============================
// ROUTING
// ===============================
$page = $_GET['page'] ?? 'home';

if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
    \Core\Security::verifyPost();
}

// ===============================
// PROTECTION MÃ‰THODE POST
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
        $_SESSION['error'] = "MÃ©thode non autorisÃ©e";
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

    case 'reinitialiser_mot_de_passe':
        $authController->resetPassword();
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

    case 'detail_avis':
        $avisController->showAvisByIdCommande();
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
        $avisController->delete();
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

    case 'modifier_menu':
        $menusController->editMenu();
        break;

    case 'valider_modification_menu':
        requirePostMethod();
        $menusController->updateMenu();
        break;

    case 'gestion_des_commandes':
        $commandesController->listeDesCommandes();
        break;

    // ===============================
    // EMPLOYÃ‰
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
        // âŒ requirePostMethod supprimÃ© (controller gÃ¨re POST)
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
        echo "<h1>Page non trouvÃ©e</h1>";
        break;
}
