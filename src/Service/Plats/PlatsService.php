<?php

namespace Service\Plats;

use Entity\Plats;
use Repository\PlatsRepository;

class PlatsService
{
    private PlatsRepository $platsRepo;

    public function __construct(PlatsRepository $platsRepo)
    {
        $this->platsRepo = $platsRepo;
    }

    //=======================================================================
    // 0 - INDEX
    //=======================================================================
    public function index(): array
    {
        return $this->platsRepo->findAll();
    }

    //=======================================================================
    // 1 - CREATE
    //=======================================================================
    public function createPlat(array $data, array $files): bool
    {
        $plat = new Plats();

        // Nom
        $plat->setNomPlat(
            trim($data['nom_plat'] ?? '')
        );

        // Type
        $plat->setTypePlat(
            trim($data['type_plat'] ?? '')
        );

        // Menu associé
        $idMenu = (int)($data['id_menu'] ?? 0);

        if ($idMenu <= 0) {
            throw new \Exception("Veuillez sélectionner un menu.");
        }

        $plat->setIdMenu($idMenu);

        //===================================================================
        // IMAGE
        //===================================================================

        $imageNom = null;

        if (
            isset($files['image_plat']) &&
            $files['image_plat']['error'] === UPLOAD_ERR_OK &&
            !empty($files['image_plat']['tmp_name'])
        ) {

            $dossierUploads = ROOT . '/public/uploads/';

            if (!is_dir($dossierUploads)) {
                mkdir($dossierUploads, 0755, true);
            }

            $tmp = $files['image_plat']['tmp_name'];
            $size = (int)$files['image_plat']['size'];

            // Vérification du type MIME réel
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmp);
            finfo_close($finfo);

            $allowedMimes = [
                'image/jpeg' => 'jpg',
                'image/png'  => 'png',
                'image/gif'  => 'gif',
                'image/webp' => 'webp',
            ];

            // Vérification format
            if (!isset($allowedMimes[$mime])) {
                throw new \Exception(
                    "Format d'image invalide. Formats acceptés : JPG, PNG, GIF et WEBP."
                );
            }

            // Vérification taille
            if ($size > 5 * 1024 * 1024) {
                throw new \Exception(
                    "L'image dépasse la taille maximale de 5 Mo."
                );
            }

            // Extension
            $extension = $allowedMimes[$mime];

            // Nom unique
            $imageNom = uniqid('plat_', true) . '.' . $extension;

            // Chemin final
            $cheminFinal = $dossierUploads . $imageNom;

            // Déplacement de l'image
            if (!move_uploaded_file($tmp, $cheminFinal)) {
                throw new \Exception(
                    "Impossible d'enregistrer l'image du plat."
                );
            }
        }

        // Enregistrement du nom de l'image
        $plat->setImagePlat($imageNom ?? '');

        //===================================================================
        // ENREGISTREMENT EN BASE
        //===================================================================

        return $this->platsRepo->createPlat($plat);
    }

    //=======================================================================
    // 2 - READ LIST
    //=======================================================================
    public function readPlats(): array
    {
        return $this->platsRepo->findAll();
    }

    //=======================================================================
    // 3 - DETAIL
    //=======================================================================
    public function readDetailPlat(int $id): ?Plats
    {
        if ($id <= 0) {
            return null;
        }

        return $this->platsRepo->findById($id);
    }

    //=======================================================================
    // 4 - DELETE
    //=======================================================================
    public function deletePlat(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }

        $plat = $this->platsRepo->findById($id);

        if (!$plat) {
            throw new \Exception("Plat introuvable.");
        }

        // Suppression de l'image
        if (!empty($plat->getImagePlat())) {

            $cheminImage = ROOT . '/public/uploads/' . $plat->getImagePlat();

            if (file_exists($cheminImage)) {
                unlink($cheminImage);
            }
        }

        return $this->platsRepo->delete($id);
    }
}
