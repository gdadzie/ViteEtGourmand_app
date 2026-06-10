<?php

namespace Service\Avis;

use Entity\Avis;

use Repository\AvisRepository;
class AvisService
{

    private AvisRepository $avisRepo;
    public function __construct(AvisRepository $avisRepo){
        $this->avisRepo = $avisRepo;
    }
    public function createAvis(array $data, int $idUtilisateur): bool
    {
        $avis = new Avis();

        $avis->setNote((int)($data['note'] ?? 0));
        $avis->setCommentaire(trim($data['commentaire'] ?? ''));

        // IMPORTANT : liaison
        $avis->setIdCommande((int)($data['id_commande'] ?? 0));
        $avis->setIdUtilisateur($idUtilisateur);

        return $this->avisRepo->create($avis);
    }
}