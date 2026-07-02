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
    // 0 - INDEX
    //=======================================================================
    public function index()
    {
        return $this->menusRepo->readAll();
    }

    //=======================================================================
    // 1 - CREATE
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

        return $this->menusRepo->create($menu);
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

        $imageNom = $currentMenu->getImage();

        // =========================
        // CHECK UPLOAD PROPRE
        // =========================
        if (
            isset($files['image_menu']) &&
            $files['image_menu']['error'] === UPLOAD_ERR_OK &&
            !empty($files['image_menu']['tmp_name'])
        ) {

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

            // delete old image
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