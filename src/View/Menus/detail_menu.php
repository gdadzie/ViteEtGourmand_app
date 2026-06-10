<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle) ?></title>

    <meta name="description" content="<?= htmlspecialchars($metaDescription) ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="index, follow">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="/assets/css/menu/detail_menus.css">
</head>

<body>

<main class="container my-4 my-md-5">

    <nav class="mb-4">
        <a href="index.php?page=liste_des_menus" class="back-link">
            <i class="bi bi-arrow-left"></i> Retour à la liste des menus
        </a>
    </nav>

    <article class="menu-card">

        <!-- IMAGE -->
        <section class="menu-media">

            <img src="<?= htmlspecialchars($imagePath) ?>"
                 alt="Photo du menu <?= htmlspecialchars($menu->getTitre()) ?>"
                 class="menu-img" loading="lazy">

            <div class="top-badges">
                <span class="badge-theme"><i class="bi bi-palette me-1"></i> <?= htmlspecialchars($menu->getTheme()) ?></span>
                <span class="badge-soft"><i class="bi bi-check2-circle me-1"></i> <?= htmlspecialchars($menu->getRegime()) ?></span>
            </div>

            <div class="price-pill">
                <?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?> € <span>/ personne</span>
            </div>

        </section>

        <!-- CONTENT -->
        <section class="content">

            <h1><?= htmlspecialchars($menu->getTitre()) ?></h1>

            <p class="menu-desc">
                <?= nl2br(htmlspecialchars($menu->getDescription())) ?>
            </p>

            <div class="divider"></div>



            <!-- INFOS + COMMANDE -->
            <section class="row g-3 mb-3">

                <!-- INFOS -->
                <div class="col-12 col-md-7">
                    <div class="info-card h-100">

                        <?php
                        $details = [
                                ["bi-people-fill", "Nombre minimum de personnes", $menu->getNbMinPersonne()],
                                ["bi-box-seam", "Stock disponible", $menu->getStockDisponible()],
                                ["bi-palette", "Thème", $menu->getTheme()],
                                ["bi-leaf", "Régime", $menu->getRegime()]
                        ];
                        ?>

                        <?php foreach ($details as [$icon, $label, $value]): ?>
                            <div class="info-item">
                                <span class="info-icon"><i class="bi <?= $icon ?>"></i></span>
                                <div>
                                    <div class="info-label"><?= $label ?></div>
                                    <div class="info-value"><?= htmlspecialchars($value) ?></div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                </div>

                <!-- PRIX + COMMANDE -->
                <div class="col-12 col-md-5">

                    <?php $prixTotalMin = $menu->getPrixParPersonne() * $menu->getNbMinPersonne(); ?>

                    <aside class="info-card h-100 d-flex flex-column justify-content-between">

                        <div>

                            <div class="info-label">Tarif</div>

                            <div class="price-value">
                                <i class="bi bi-currency-euro"></i>
                                <?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?>
                            </div>

                            <p class="text-muted mb-0">par personne</p>

                            <div class="mt-2 text-muted small">
                                <i class="bi bi-calculator"></i>
                                Total minimum (<?= (int)$menu->getNbMinPersonne() ?> pers) :
                                <strong><?= number_format($prixTotalMin, 2, ',', ' ') ?> €</strong>
                            </div>

                        </div>

                        <form method="POST" action="index.php?page=commander_menu">
                            <input type="hidden" name="id" value="<?= (int)$menu->getIdMenu() ?>">

                            <button class="btn btn-order btn-sm w-100 py-2">
                                <i class="bi bi-cart-plus me-1"></i>
                                Commander
                            </button>
                        </form>

                    </aside>

                </div>

            </section>

            <!-- CONDITIONS -->
            <?php if ($menu->getConditions()): ?>
                <section class="alert conditions mb-0">
                    <h2 class="fw-bold mb-1">
                        <i class="bi bi-info-circle me-1"></i> Conditions
                    </h2>
                    <p class="text-muted mb-0">
                        <?= nl2br(htmlspecialchars($menu->getConditions())) ?>
                    </p>
                </section>
            <?php endif; ?>

        </section>

    </article>

</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>