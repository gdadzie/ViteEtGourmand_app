<?php

namespace Controller\Plats;

use App\Entity\Plats;
use Repository\PlatsRepository;
use PDO;


class PlatsController
{
    private PlatsRepository $repo;
    public function __construct( PDO $conn) {
        $this->repo = new platsRepository($conn);
    }

    public function creerUnPlat(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require __DIR__ . '/../../View/Plats/creer_un_plat.php';
            return;
        }

        $plat = new Plats();
        $plat->setNomPlat(trim($_POST['nom_plat'] ?? ''));
        $plat->setTypePlat(trim($_POST['type_plat'] ?? ''));

        if ($this->repo->createPlat($plat)) {
            // Compte créé avec succès
            $success = "Votre menu a été créé avec succès ! Vous pouvez maintenant vous connecter.";
            require __DIR__ . '/../../View/Menus/creer_un_menu.php';
            return;
        } else {
            // Erreur lors de la création
            $error = "Une erreur est survenue, impossible de créer le menu.";
            require __DIR__ . '/../../View/Menus/creer_un_menu.php';
            return;
        }


    }

    public function listeDesPlats(): void
    {
        $plats = $this->repo->findAll();
        require __DIR__ . '/../../View/Plats/liste_des_plats.php';
    }

}