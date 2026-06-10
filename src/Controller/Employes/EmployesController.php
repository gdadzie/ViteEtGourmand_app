<?php
declare(strict_types=1);

namespace Controller\Employes;

use Repository\HorairesRepository;
use Repository\UtilisateursRepository;
use Repository\MenusRepository;

class EmployesController
{
    private HorairesRepository $horairesRepo;
    private UtilisateursRepository $utilisateursRepo;
    private MenusRepository $menusRepo;

    public function __construct(HorairesRepository $horairesRepo, UtilisateursRepository $utilisateursRepo, MenusRepository $menusRepo)
    {
        $this->horairesRepo = $horairesRepo;
        $this->utilisateursRepo = $utilisateursRepo;
        $this->menusRepo = $menusRepo;

    }

    // Récupérer tous les employés
    public function index(): void
    {
        $employes = $this->utilisateursRepo->readByRoleEmploye(); // id_role = 2 pour employé
        require __DIR__ . '/../../View/Authentication/espace_employe.php';
    }

    // Récupérer tous les employés
    public function gestionDesMenus(): void
    {
        $employes = $this->utilisateursRepo->readByRole(2); // id_role = 2 pour employé
        require __DIR__ . '/../../View/Admin/gestion_des_menus.php';
    }

    // Afficher le formulaire de modification des horaires
    public function show(): void
    {
        $horaires = $this->horairesRepo->readAll();
        require __DIR__ . '/../../View/Formulaires/modification_horaires.php';
    }

    // Traiter le formulaire POST des horaires
    public function store(): void
    {
        $horairesPost = $_POST['horaires'] ?? [];

        foreach ($horairesPost as $id => $data) {
            $horaire = $this->horairesRepo->readById((int)$id);
            if (!$horaire) continue;

            $estFerme = !empty($data['est_ferme']);
            $heureOuverture = $estFerme ? null : ($data['ouverture'] ?? '09:00');
            $heureFermeture = $estFerme ? null : ($data['fermeture'] ?? '18:00');

            $horaire->setHeureOuverture($heureOuverture);
            $horaire->setHeureFermeture($heureFermeture);
            $horaire->setEstFerme($estFerme);

            $this->horairesRepo->update($horaire);
        }

        echo "<div class='alert alert-success text-center mt-3'>Contact mis à jour avec succès !</div>";

        $this->show(); // réaffiche le formulaire
    }

    public function gestionDesMenusEmployes(): void
    {
        $menus = $this->menusRepo->readAll();
        require __DIR__ . '/../../View/Employes/consulter_menus.php';
    }



}
