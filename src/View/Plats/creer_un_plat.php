<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description"
          content="Vite & Gourmand — Créer un plat.">

    <title>Vite &amp; Gourmand — Créer un plat</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"
          rel="stylesheet">

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
            defer></script>

    <!-- CSS personnel -->
    <link rel="stylesheet" href="assets/css/home/home.css">
    <link rel="stylesheet"
          href="assets/css/media_queries_page_accueil.css?v=2">
</head>

<body class="pt-5 bg-light">

<!-- FIL D'ARIANE / BOUTON RETOUR -->
<nav aria-label="breadcrumb" class="mb-3 p-4 mt-5">

    <ol class="breadcrumb breadcrumb-custom">

        <li class="breadcrumb-item">

            <a href="?page=espace_employe">
                <i class="bi bi-arrow-left"></i>
                Retour
            </a>

        </li>

    </ol>

</nav>


<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card shadow-sm border-0">

                <div class="card-body p-4">

                    <!-- TITRE -->
                    <h1 class="h3 mb-4 text-center">

                        <i class="bi bi-egg-fried me-2"></i>

                        Créer un plat

                    </h1>


                    <!-- FORMULAIRE -->
                    <form
                        method="post"
                        action="?page=creer_un_plat"
                        class="needs-validation"
                        novalidate
                        enctype="multipart/form-data"
                    >

                        <!-- NOM DU PLAT -->
                        <div class="mb-3">

                            <label for="nom_plat" class="form-label">
                                Nom du plat
                            </label>

                            <input
                                type="text"
                                id="nom_plat"
                                name="nom_plat"
                                class="form-control"
                                placeholder="Ex : Poulet braisé"
                                required
                            >

                            <div class="invalid-feedback">
                                Veuillez saisir le nom du plat.
                            </div>

                        </div>


                        <!-- TYPE DU PLAT -->
                        <div class="mb-3">

                            <label for="type_plat" class="form-label">
                                Type de plat
                            </label>

                            <select
                                id="type_plat"
                                name="type_plat"
                                class="form-select"
                                required
                            >

                                <option value="" selected disabled>
                                    Sélectionnez un type
                                </option>

                                <option value="entree">
                                    Entrée
                                </option>

                                <option value="plat">
                                    Plat
                                </option>

                                <option value="dessert">
                                    Dessert
                                </option>

                            </select>

                            <div class="invalid-feedback">
                                Veuillez sélectionner un type de plat.
                            </div>

                        </div>


                        <!-- MENU -->
                        <div class="mb-3">

                            <label for="id_menu" class="form-label">
                                Menu associé
                            </label>

                            <select
                                id="id_menu"
                                name="id_menu"
                                class="form-select"
                                required
                            >

                                <option value="" selected disabled>
                                    Sélectionnez un menu
                                </option>

                                <?php foreach ($menus as $menu): ?>

                                    <option value="<?= $menu->getIdMenu() ?>">

                                        <?= htmlspecialchars($menu->getTitre()) ?>

                                    </option>

                                <?php endforeach; ?>

                            </select>

                            <div class="invalid-feedback">
                                Veuillez sélectionner un menu.
                            </div>

                        </div>


                        <!-- IMAGE -->
                        <div class="mb-4">

                            <label for="image_plat" class="form-label">
                                Ajouter une image
                            </label>

                            <input
                                type="file"
                                id="image_plat"
                                name="image_plat"
                                class="form-control"
                                accept="image/jpeg,image/png,image/gif,image/webp"
                            >

                            <div class="form-text">
                                Formats acceptés : JPG, PNG, GIF, WEBP. Max 5 Mo.
                            </div>

                        </div>


                        <!-- BOUTONS -->
                        <div class="d-flex justify-content-between align-items-center">

                            <a
                                href="?page=liste_des_plats"
                                class="btn btn-outline-secondary"
                            >

                                <i class="bi bi-arrow-left"></i>

                                Retour

                            </a>


                            <button
                                type="submit"
                                class="btn btn-primary px-4"
                            >

                                <i class="bi bi-check-circle me-1"></i>

                                Créer le plat

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

