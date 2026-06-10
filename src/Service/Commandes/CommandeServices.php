<?php

namespace Service\Commandes;

use repository\CommandesRepository;

class CommandeServices
{
    private CommandesRepository $commandeRepo;
    public function __construct(CommandesRepository $commandeRepo){

        $this->commandeRepo = $commandeRepo;
    }

    public function index(){

        return $this->commandeRepo->readAll();
    }


}