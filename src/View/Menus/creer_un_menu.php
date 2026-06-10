<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="Vite & Gourmand — Traiteur à Bordeaux. Créez vos menus facilement." />
    <title>Vite &amp; Gourmand — Créer un menu</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- JS Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" defer></script>

    <!-- CSS perso -->
    <link rel="stylesheet" href="assets/css/home/home.css" />
    <link rel="stylesheet" href="assets/css/media_queries_page_accueil.css?v=2">
</head>

<body class="pt-5 bg-light">

<!-- FIL D'ARIANE / BOUTON RETOUR -->
<nav aria-label="breadcrumb" class="mb-3 p-4 mt-5">
    <ol class="breadcrumb breadcrumb-custom">
        <li class="breadcrumb-item">
            <a href="?page=espace_employe"><i class="bi bi-arrow-left"></i> Retour</a>
        </li>
    </ol>
</nav>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h1 class="h3 mb-4 text-center">
                        <i class="bi bi-card-list me-2"></i>
                        Créer un menu
                    </h1>

                    <form method="post" action="?page=creer_un_menu" class="needs-validation" novalidate enctype="multipart/form-data">

                        <!-- Titre -->
                        <div class="mb-3">
                            <label class="form-label">Titre du menu</label>
                            <input type="text" name="titre" class="form-control" placeholder="Ex : Menu Gourmand" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Décrivez le menu..." required></textarea>
                        </div>

                        <div class="row">
                            <!-- Thème -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Thème</label>
                                <input type="text" name="theme" class="form-control" placeholder="Ex : Italien, Brunch, Mariage">
                            </div>

                            <!-- Régime -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Régime</label>
                                <input type="text" name="regime" class="form-control" placeholder="Ex : Végétarien, Halal, Sans gluten">
                            </div>
                        </div>

                        <div class="row">
                            <!-- Nb min personnes -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nombre minimum de personnes</label>
                                <input type="number" name="nb_min_personne" class="form-control" min="1" required>
                            </div>

                            <!-- Prix -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prix par personne (€)</label>
                                <div class="input-group">
                                    <span class="input-group-text">€</span>
                                    <input type="number" step="0.01" name="prix_par_personne" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <!-- Conditions -->
                        <div class="mb-3">
                            <label class="form-label">Conditions</label>
                            <textarea name="conditions" class="form-control" rows="2" placeholder="Conditions particulières, délais, etc."></textarea>
                        </div>

                        <!-- Stock -->
                        <div class="mb-4">
                            <label class="form-label">Stock disponible</label>
                            <input type="number" name="stock_disponible" class="form-control" min="0" required>
                        </div>

                        <!-- Image du menu -->
                        <div class="mb-4">
                            <label class="form-label">Ajouter une image</label>
                            <input type="file" name="image_menu" class="form-control" accept="image/*">
                            <div class="form-text">Formats acceptés : jpg, png, gif. Max 5 Mo.</div>
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="?page=liste_des_menus" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>

                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i>
                                Créer le menu
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
