<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="Vite & Gourmand — Traiteur à Bordeaux. Menus pour événements, commandes en ligne." />
    <title>Vite &amp; Gourmand — Accueil</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous" defer></script>

    <link rel="stylesheet" href="assets/css/home/home.css" />
    <link rel="stylesheet" href="assets/css/media_queries_page_accueil.css?v=2">
    <meta name="robots" content="index, follow">
</head>

<div class="container my-5">

    <!-- Titre -->
    <div class="text-center mb-5">
        <h1 class="h3 fw-bold">
            <i class="bi bi-journal-text me-2"></i>Nos Plats
        </h1>
        <p class="text-muted">
            Découvrez notre sélection de plats
        </p>
    </div>

    <!-- Liste des plats -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

        <?php foreach ($plats as $plat): ?>
            <div class="col">
                <div class="card h-100 border-0 shadow-sm">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title fw-semibold mb-2">
                            <?= htmlspecialchars($plat->getNomPlat()) ?>
                        </h5>

                        <!-- Type de plat -->
                        <span class="badge
                            <?php
                        echo match ($plat->getTypePlat()) {
                            'entree'  => 'bg-success',
                            'plat'    => 'bg-primary',
                            'dessert' => 'bg-warning text-dark',
                            default   => 'bg-secondary'
                        };
                        ?>
                        ">
                            <?= ucfirst(htmlspecialchars($plat->getTypePlat())) ?>
                        </span>

                        <div class="mt-auto pt-3 text-end">
                            <i class="bi bi-arrow-right-circle text-muted"></i>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

</div>

</body>
</html>
