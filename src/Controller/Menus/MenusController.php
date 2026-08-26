<?php

namespace Controller\Menus;

use Service\Menus\MenusService;
use View\View;

class MenusController
{
    private MenusService $menuService;

    public function __construct(MenusService $menuService)
    {
        $this->menuService = $menuService;
    }

    // ======================================================
    // CREATE MENU
    // ======================================================
    public function createMenu(): void
    {
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Pragma: no-cache');
        header('Expires: 0');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::render('Menus/creer_un_menu');
            return;
        }

        try {
            echo '<pre>';
            echo '<pre>';

            echo "PHP version : " . PHP_VERSION . "\n";
            echo "upload_max_filesize : " . ini_get('upload_max_filesize') . "\n";
            echo "post_max_size : " . ini_get('post_max_size') . "\n";
            echo "file_uploads : " . ini_get('file_uploads') . "\n";

            print_r($_FILES);

            echo '</pre>';
            exit;
            print_r($_FILES);
            echo '</pre>';
            exit;

            $this->menuService->createMenu($_POST, $_FILES);

            $_SESSION['success'] = "Menu créé avec succès !";
            header('Location: index.php?page=liste_des_menus');
            exit;

        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: index.php?page=creer_un_menu');
            exit;
        }
    }

    // ======================================================
    // LIST MENU
    // ======================================================
    public function listeDesMenus(): void
    {
        $filters = [
            'prix_max'        => $_GET['prix_max'] ?? null,
            'theme'           => $_GET['theme'] ?? null,
            'regime'          => $_GET['regime'] ?? null,
            'nb_min_personne' => $_GET['nb_min_personne'] ?? null,
        ];

        $menus = $this->menuService->readMenus($filters);

        global $horaires;

        View::render('Menus/liste_des_menus', [
            'currentPage' => 'liste_des_menus',
            'pageTitle'   => 'Nos menus — Vite & Gourmand',
            'horaires'    => $horaires ?? [],
            'menus'       => $menus,
            'filters'     => $filters,
            'cssFiles'    => ['/assets/css/menus/liste.css'],
            'jsFiles'     => ['/assets/js/menus/filters.js'],
        ]);

        //Affiche le nom des menus
    }

    // ======================================================
    // DETAIL MENU
    // ======================================================
    public function readDetailMenu(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        if ($id <= 0) {
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $menu = $this->menuService->readDetailMenu($id);

        if (!$menu) {
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $imagePath = '/uploads/default.png';

        if (!empty($menu->getImage())) {
            $file = ROOT . '/public/uploads/' . $menu->getImage();

            if (file_exists($file)) {
                $imagePath = '/uploads/' . $menu->getImage();
            }
        }

        $metaTitle = $menu->getTitre() . ' | Vite & Gourmand';

        $metaDescription = trim(
            substr(strip_tags((string)$menu->getDescription()), 0, 150)
        );

        if ($metaDescription === '') {
            $metaDescription = "Découvrez ce menu chez Vite & Gourmand.";
        }

        global $horaires;

        View::render('Menus/detail_menu', [
            'currentPage'      => 'detail_menu',
            'pageTitle'        => $metaTitle,
            'metaDescription'  => $metaDescription,
            'metaImage'        => $imagePath,
            'imagePath'        => $imagePath,
            'menu'             => $menu,
            'horaires'         => $horaires ?? [],
            'cssFiles'         => ['/assets/css/menus/detail.css'],
            'jsFiles'          => [],
        ]);
    }

    // ======================================================
    // DELETE MENU
    // ======================================================
    public function deleteMenu(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = "ID invalide";
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $this->menuService->deleteMenu($id);

        $_SESSION['success'] = "Menu supprimé avec succès";
        header('Location: index.php?page=liste_des_menus');
        exit;
    }

    public function updateMenu(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = "ID invalide.";
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $this->menuService->updateMenu($id, $_POST, $_FILES);

        $_SESSION['success'] = "Le menu a été modifié avec succès.";

        header('Location: index.php?page=liste_des_menus');
        exit;
    }

    public function editMenu(): void
    {
        $id = (int) ($_POST['id'] ?? $_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['error'] = "Menu introuvable.";
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        $menu = $this->menuService->readDetailMenu($id);

        if (!$menu) {
            $_SESSION['error'] = "Menu introuvable.";
            header('Location: index.php?page=liste_des_menus');
            exit;
        }

        View::render('Menus/modifier_menu', [
            'menu' => $menu
        ]);
    }


}