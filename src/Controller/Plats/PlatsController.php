<?php

namespace Controller\Plats;

use Repository\PlatsRepository;
use Service\Plats\PlatsService;
use PDO;

class PlatsController
{
    private PlatsService $service;

    public function __construct(PDO $conn)
    {
        $repo = new PlatsRepository($conn);
        $this->service = new PlatsService($repo);
    }

    /**
     * Créer un plat
     */
    public function creerUnPlat(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Plats/creer_un_plat.php';
            return;
        }

        try {
            $this->service->createPlat(
                $_POST,
                $_FILES
            );

            $success = "Le plat a été créé avec succès !";

        } catch (\Exception $e) {

            $error = $e->getMessage();
        }

        require __DIR__ . '/../../View/Plats/creer_un_plat.php';
    }

    /**
     * Liste des plats
     */
    public function listeDesPlats(): void
    {
        $plats = $this->service->readPlats();

        require __DIR__ . '/../../View/Plats/liste_des_plats.php';
    }

    /**
     * Supprimer un plat
     */
    public function supprimerUnPlat(): void
    {
        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $error = "Plat invalide.";
            require __DIR__ . '/../../View/Plats/liste_des_plats.php';
            return;
        }

        try {
            if ($this->service->deletePlat($id)) {
                $success = "Le plat a été supprimé avec succès.";
            } else {
                $error = "Impossible de supprimer le plat.";
            }

        } catch (\Exception $e) {

            $error = $e->getMessage();
        }

        $plats = $this->service->readPlats();

        require __DIR__ . '/../../View/Plats/liste_des_plats.php';
    }
}