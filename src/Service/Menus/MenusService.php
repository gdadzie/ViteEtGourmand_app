<?php

namespace Service\Menus;

use Entity\Menus;
use Entity\Plats;
use Repository\MenusRepository;
use Repository\PlatsRepository;

class MenusService
{
    private MenusRepository $menusRepo;
    private PlatsRepository $platsRepo;

    public function __construct(MenusRepository $menusRepo, PlatsRepository $platsRepo)
    {
        $this->menusRepo = $menusRepo;
        $this->platsRepo = $platsRepo;
    }

    //=======================================================================
    // 0 - INDEX
    //=======================================================================
    public function index()
    {
        return $this->menusRepo->readAll();
    }

    public function getPlatsDisponibles(): array
    {
        return $this->platsRepo->findAll();
    }

    //=======================================================================
    // 1 - CREATE
    //=======================================================================
    public function createMenu(array $data, array $files)
    {
        $compositionNouveaux = [
            'entree'  => trim((string)($data['composition']['entree'] ?? '')),
            'plat'    => trim((string)($data['composition']['plat'] ?? '')),
            'dessert' => trim((string)($data['composition']['dessert'] ?? '')),
        ];

        $platsExistants = $this->platsRepo->findByIds($data['plats_existants'] ?? []);
        $typesSelectionnes = [];
        foreach ($platsExistants as $platExistant) {
            $typesSelectionnes[$platExistant->getTypePlat()] = true;
        }
        foreach ($compositionNouveaux as $type => $nom) {
            if ($nom !== '') $typesSelectionnes[$type] = true;
        }

        if (!isset($typesSelectionnes['entree'], $typesSelectionnes['plat'], $typesSelectionnes['dessert'])) {
            throw new \Exception('La composition doit contenir une entree, un plat et un dessert.');
        }

        $menu = new Menus();
        $menu->setTitre(trim($data['titre'] ?? ''));
        $menu->setDescription(trim($data['description'] ?? ''));
        $menu->setTheme(trim($data['theme'] ?? ''));
        $menu->setRegime(trim($data['regime'] ?? ''));
        $menu->setNbMinPersonne((int)($data['nb_min_personne'] ?? 0));
        $menu->setPrixParPersonne((float)($data['prix_par_personne'] ?? 0));
        $menu->setConditions(trim($data['conditions'] ?? ''));
        $menu->setStockDisponible((int)($data['stock_disponible'] ?? 0));
        $menu->setDateCreation(date('Y-m-d H:i:s'));

        $imageNom = null;

        if (
            isset($files['image_menu']) &&
            $files['image_menu']['error'] === UPLOAD_ERR_OK &&
            !empty($files['image_menu']['tmp_name'])
        ) {

            $dossierUploads = ROOT . '/public/uploads/';

            if (!is_dir($dossierUploads)) {
                mkdir($dossierUploads, 0755, true);
            }

            $tmp = $files['image_menu']['tmp_name'];
            $size = (int)$files['image_menu']['size'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);

            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];

            if (!isset($allowedMimes[$mime]) || $size > 5 * 1024 * 1024) {
                throw new \Exception("Format ou taille d'image invalide");
            }

            $ext = $allowedMimes[$mime];
            $imageNom = uniqid('menu_', true) . '.' . $ext;

            $cheminFinal = $dossierUploads . $imageNom;

            if (!move_uploaded_file($tmp, $cheminFinal)) {
                throw new \Exception("Erreur upload image");
            }
        }

        $menu->setImage($imageNom);

        if (!$this->menusRepo->create($menu)) {
            return false;
        }

        foreach ($platsExistants as $platExistant) {
            if (!$this->platsRepo->attachToMenu($menu->getIdMenu(), (int) $platExistant->getIdPlat())) {
                throw new \Exception('Impossible d\'ajouter un plat existant au menu.');
            }
        }

        foreach ($compositionNouveaux as $type => $nom) {
            if ($nom === '') continue;

            $plat = new Plats();
            $plat->setNomPlat($nom);
            $plat->setTypePlat($type);
            $plat->setIdMenu($menu->getIdMenu());

            if (!$this->platsRepo->createPlat($plat)) {
                throw new \Exception('Impossible d\'enregistrer la composition du menu.');
            }

            if (!$this->platsRepo->attachToMenu($menu->getIdMenu(), (int) $plat->getIdPlat())) {
                throw new \Exception('Impossible d\'ajouter le nouveau plat au menu.');
            }
        }

        return true;
    }

    //=======================================================================
    // 2 - READ LIST
    //=======================================================================
    public function readMenus(array $filters = [])
    {
        $hasFilters =
            !empty($filters['prix_max']) ||
            !empty($filters['theme']) ||
            !empty($filters['regime']) ||
            !empty($filters['nb_min_personne']);

        return $hasFilters
            ? $this->menusRepo->readByFiltre($filters)
            : $this->menusRepo->readAll();
    }

    //=======================================================================
    // 3 - DETAIL
    //=======================================================================
    public function readDetailMenu(int $id)
    {
        if ($id <= 0) {
            return null;
        }

        return $this->menusRepo->readById($id);
    }

    //=======================================================================
    // 4 - UPDATE (CORRIGÉ)
    //=======================================================================
    public function updateMenu(int $id, array $data, array $files)
    {
        if ($id <= 0) {
            return false;
        }

        $currentMenu = $this->menusRepo->readById($id);

        if (!$currentMenu) {
            throw new \Exception("Menu introuvable");
        }

        $menu = new Menus();

        $menu->setIdMenu($id);
        $menu->setTitre(trim($data['titre'] ?? ''));
        $menu->setDescription(trim($data['description'] ?? ''));
        $menu->setTheme(trim($data['theme'] ?? ''));
        $menu->setRegime(trim($data['regime'] ?? ''));
        $menu->setNbMinPersonne((int)($data['nb_min_personne'] ?? 0));
        $menu->setPrixParPersonne((float)($data['prix_par_personne'] ?? 0));
        $menu->setConditions(trim($data['conditions'] ?? ''));
        $menu->setStockDisponible((int)($data['stock_disponible'] ?? 0));

        $dossierUploads = ROOT . '/public/uploads/';

        if (!is_dir($dossierUploads)) {
            mkdir($dossierUploads, 0755, true);
        }

        // On garde l'image actuelle par défaut
        $imageNom = $currentMenu->getImage() ?? '';

        // Une nouvelle image a été envoyée
        if (
            isset($files['image_menu']) &&
            $files['image_menu']['error'] === UPLOAD_ERR_OK &&
            !empty($files['image_menu']['tmp_name'])
        ) {

            $tmp = $files['image_menu']['tmp_name'];
            $size = (int)$files['image_menu']['size'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);

            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];

            if (!isset($allowedMimes[$mime])) {
                throw new \Exception("Format d'image invalide.");
            }

            if ($size > 5 * 1024 * 1024) {
                throw new \Exception("L'image dépasse 5 Mo.");
            }

            $extension = $allowedMimes[$mime];
            $imageNom = uniqid('menu_', true) . '.' . $extension;

            $cheminFinal = $dossierUploads . $imageNom;

            if (!move_uploaded_file($tmp, $cheminFinal)) {
                throw new \Exception("Impossible d'enregistrer l'image.");
            }

            // Suppression de l'ancienne image
            if (!empty($currentMenu->getImage())) {
                $ancienneImage = $dossierUploads . $currentMenu->getImage();

                if (file_exists($ancienneImage)) {
                    unlink($ancienneImage);
                }
            }
        }

        $menu->setImage($imageNom);
        $menu->setDateModification(date('Y-m-d H:i:s'));

        return $this->menusRepo->update($menu);
    }

    //=======================================================================
    // 5 - DELETE
    //=======================================================================
    public function deleteMenu(int $id)
    {
        if ($id <= 0) {
            return false;
        }

        return $this->menusRepo->delete($id);
    }
}
