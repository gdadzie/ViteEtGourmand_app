<?php

namespace Controller\Commandes;

use Repository\CommandeStatutMongoRepository;
class StatistiquesController
{
    private CommandeStatutMongoRepository $mongoRepo;

    public function __construct()
    {
        $this->mongoRepo = new CommandeStatutMongoRepository();
    }

    public function statsMenus(): void
    {
        // Récupérer le nombre de commandes et chiffre d’affaires par menu
        $stats = $this->mongoRepo->getStatsMenus();

        require __DIR__ . '/../../View/Commandes/stats_menus.php';
    }
}
