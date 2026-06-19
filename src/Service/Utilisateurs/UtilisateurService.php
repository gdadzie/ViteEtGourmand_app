<?php

namespace Service\Utilisateurs;

use Repository\UtilisateursRepository;

class UtilisateurService
{
    private UtilisateursRepository $utilisateurRepository;

    public function __construct(UtilisateursRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    //Modifier le profil utilisateur
    public function updateUtilisateurById(int $id): void{

        if
        ($_SESSION['id'] == $_POST[$id]);
    }



}