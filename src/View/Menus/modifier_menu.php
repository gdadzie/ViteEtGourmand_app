<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';

unset($_SESSION['success'], $_SESSION['error']);

/*
 * Cette vue attend qu'un objet $menu soit envoyé par le contrôleur.
 * Exemple :
 * $menu = $menuRepository->findById($id);
 */
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Modification d'un menu">

    <title>Modifier un menu</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/menu/modifier_menu.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>



</head>

<body class="pt-5">

<nav aria-label="breadcrumb" class="mb-3 p-4 mt-5">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="?page=liste_des_menus">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>
        </li>
    </ol>
</nav>

<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <?php if($success): ?>

                <div class="alert alert-success alert-dismissible fade show">

                    <?= htmlspecialchars($success) ?>

                    <button
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>

            <?php if($error): ?>

                <div class="alert alert-danger alert-dismissible fade show">

                    <?= htmlspecialchars($error) ?>

                    <button
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php endif; ?>


            <div class="card shadow">

                <div class="card-body p-4">

                    <h2 class="text-center mb-4">

                        <i class="bi bi-pencil-square"></i>

                        Modifier le menu

                    </h2>

                    <form
                        method="post"
                        action="?page=valider_modification_menu"
                        enctype="multipart/form-data">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $menu->getIdMenu() ?>">

                        <!-- IMAGE -->

                        <div class="mb-4 text-center">

                            <?php if($menu->getImage()): ?>

                                <img
                                    src="uploads/<?= htmlspecialchars($menu->getImage()) ?>"
                                    class="image-menu mb-3"
                                    alt="Image du menu">

                            <?php endif; ?>

                            <label class="form-label">

                                Nouvelle image

                            </label>

                            <input
                                type="file"
                                name="image_menu"
                                class="form-control"
                                accept="image/*">

                            <div class="form-text">

                                Laissez vide pour conserver l'image actuelle.

                            </div>

                        </div>


                        <!-- TITRE -->

                        <div class="mb-3">

                            <label class="form-label">

                                Titre

                            </label>

                            <input
                                type="text"
                                name="titre"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($menu->getTitre()) ?>">

                        </div>


                        <!-- DESCRIPTION -->

                        <div class="mb-3">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea
                                name="description"
                                rows="4"
                                class="form-control"
                                required><?= htmlspecialchars($menu->getDescription()) ?></textarea>

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Thème

                                </label>

                                <input
                                    type="text"
                                    name="theme"
                                    class="form-control"
                                    value="<?= htmlspecialchars($menu->getTheme()) ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Régime

                                </label>

                                <input
                                    type="text"
                                    name="regime"
                                    class="form-control"
                                    value="<?= htmlspecialchars($menu->getRegime()) ?>">

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Nombre minimum de personnes

                                </label>

                                <input
                                    type="number"
                                    name="nb_min_personne"
                                    class="form-control"
                                    min="1"
                                    value="<?= $menu->getNbMinPersonne() ?>">

                            </div>

                            <div class="col-md-6 mb-3">

                                <label class="form-label">

                                    Prix par personne

                                </label>

                                <div class="input-group">

                                    <span class="input-group-text">

                                        €

                                    </span>

                                    <input
                                        type="number"
                                        step="0.01"
                                        name="prix_par_personne"
                                        class="form-control"
                                        value="<?= $menu->getPrixParPersonne() ?>">

                                </div>

                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="form-label">

                                Conditions

                            </label>

                            <textarea
                                name="conditions"
                                rows="3"
                                class="form-control"><?= htmlspecialchars($menu->getConditions()) ?></textarea>

                        </div>


                        <div class="mb-4">

                            <label class="form-label">

                                Stock disponible

                            </label>

                            <input
                                type="number"
                                name="stock_disponible"
                                class="form-control"
                                value="<?= $menu->getStockDisponible() ?>">

                        </div>


                        <div class="d-flex justify-content-between">

                            <a
                                href="?page=liste_des_menus"
                                class="btn btn-outline-secondary">

                                <i class="bi bi-arrow-left"></i>

                                Retour

                            </a>



                                <button
                                        class="btn btn-warning px-4"
                                        type="submit">

                                    <i class="bi bi-check-circle"></i>

                                    Enregistrer les modifications

                                </button>



                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>