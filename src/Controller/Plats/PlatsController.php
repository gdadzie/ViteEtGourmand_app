<?php

namespace Controller\Plats;

use PDO;
use Repository\MenusRepository;
use Repository\PlatsRepository;
use Service\Plats\PlatsService;
use View\View;

class PlatsController
{
    private PlatsService $service;
    private MenusRepository $menusRepository;

    public function __construct(PDO $conn)
    {
        $this->service = new PlatsService(new PlatsRepository($conn));
        $this->menusRepository = new MenusRepository($conn);
    }

    public function creerUnPlat(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->renderCreateForm();
            return;
        }

        try {
            $this->service->createPlat($_POST, $_FILES);
            $_SESSION['success'] = 'Le plat a ete cree avec succes.';
            header('Location: index.php?page=liste_des_plats');
            exit;
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            $_SESSION['old_plat'] = [
                'nom_plat' => trim((string) ($_POST['nom_plat'] ?? '')),
                'type_plat' => trim((string) ($_POST['type_plat'] ?? '')),
                'id_menu' => (int) ($_POST['id_menu'] ?? 0),
            ];
            header('Location: index.php?page=creer_un_plat');
            exit;
        }
    }

    public function listeDesPlats(): void
    {
        View::render('Plats/liste_des_plats', [
            'plats' => $this->service->readPlats(),
            'pageTitle' => 'Nos plats | Vite & Gourmand',
            'cssFiles' => ['/assets/css/liste_des_plats.css'],
        ]);
    }

    public function supprimerUnPlat(): void
    {
        $id = (int) ($_GET['id'] ?? 0);

        try {
            if ($id <= 0 || !$this->service->deletePlat($id)) {
                $_SESSION['error'] = 'Impossible de supprimer ce plat.';
            } else {
                $_SESSION['success'] = 'Le plat a ete supprime avec succes.';
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: index.php?page=liste_des_plats');
        exit;
    }

    private function renderCreateForm(): void
    {
        $oldPlat = $_SESSION['old_plat'] ?? [];
        unset($_SESSION['old_plat']);

        View::render('Plats/creer_un_plat', [
            'menus' => $this->menusRepository->readAll(),
            'oldPlat' => $oldPlat,
            'pageTitle' => 'Creer un plat | Vite & Gourmand',
            'metaDescription' => 'Ajoutez un plat au catalogue Vite & Gourmand.',
            'cssFiles' => ['/assets/css/plats/creer-un-plat.css'],
            'jsFiles' => ['/assets/js/plats/creer-un-plat.js'],
        ]);
    }
}
