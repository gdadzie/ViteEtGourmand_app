<?php

namespace Service\Menus;

use Entity\Menus;
use Repository\MenusRepository;

class MenusService
{
    private MenusRepository $menusRepo;

    public function __construct(MenusRepository $menusRepo)
    {
        $this->menusRepo = $menusRepo;
    }

    //=======================================================================
    // 0 - INDEX: AFFICHER LES MENUS
    //=======================================================================

    public function index()
    {
        return $this->menusRepo->readAll();
    }

    //=======================================================================
    // 1 - CREATE: CRÉER UN NOUVEAU MENU
    //=======================================================================
    public function createMenu(array $data, array $files)
    {
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

        // Upload image (sécurisé)
        $imageNom = null;

        if (!empty($files['image_menu']['name'])) {

            $dossierUploads = ROOT . '/public/uploads/';

            if (!is_dir($dossierUploads)) {
                mkdir($dossierUploads, 0755, true);
            }

            $tmp = $files['image_menu']['tmp_name'] ?? '';
            $size = (int)($files['image_menu']['size'] ?? 0);

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $tmp ? finfo_file($finfo, $tmp) : '';
            finfo_close($finfo);

            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
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

        return $this->menusRepo->create($menu);
    }

    //=======================================================================
    // 2 - READ: AFFICHER LES MENUS
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
    // 3 - READ : AFFICHER LES DETAILS DU MENU
    //=======================================================================
    public function readDetailMenu(int $id)
    {
        if ($id <= 0) {
            return null;
        }

        return $this->menusRepo->readById($id);
    }

    //=======================================================================
    // 4 - UPDATE : MODIFIER LES INFORMATIONS DU MENU
    //=======================================================================
    public function updateMenu(int $id, array $data, array $files)
    {
        if ($id <= 0) {
            return false;
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

        // 🔥 gestion image (optionnelle)
        $currentMenu = $this->menusRepo->readById($id);

        $imageNom = $currentMenu->getImage(); // garde l'ancienne image par défaut

        if (!empty($files['image_menu']['name'])) {

            $dossierUploads = ROOT . '/public/uploads/';

            if (!is_dir($dossierUploads)) {
                mkdir($dossierUploads, 0755, true);
            }

            $tmp = $files['image_menu']['tmp_name'] ?? '';
            $size = (int)($files['image_menu']['size'] ?? 0);

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $tmp ? finfo_file($finfo, $tmp) : '';
            finfo_close($finfo);

            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
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

            // (optionnel) supprimer ancienne image
            if ($currentMenu->getImage()) {
                $oldPath = $dossierUploads . $currentMenu->getImage();
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
        }

        $menu->setImage($imageNom);
        $menu->setDateModification(date('Y-m-d H:i:s'));

        return $this->menusRepo->update($menu);


    }

    //=======================================================================
    // 5 - DELETE : SUPPRIMER UN MENU
    //=======================================================================
    public function deleteMenu(int $id)
    {
        if ($id <= 0) {
            return false;
        }

        return $this->menusRepo->delete($id);
    }
}