<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';

unset($_SESSION['success'], $_SESSION['error']);
?>

<link rel="stylesheet" href="/assets/css/liste_des_menus.css">




<main class="  container my-4 my-md-5" role="main">

    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="m-0">Nos menus</h1>
        </div>
        <p class="page-sub">Découvrez nos menus traiteur, pensés pour tous vos événements.</p>

        <!-- FILTRES -->


            <form id="filterForm" class="filters-card">

                <div class="row g-3">

                    <!-- Prix max -->
                    <div class="col-12 col-md-6 col-lg-3">
                        <label for="prixMax" class="form-label">
                            Prix maximum
                        </label>

                        <input type="number"
                               class="form-control"
                               id="prixMax"
                               name="prixMax"
                               min="0"
                               placeholder="Ex : 50">
                    </div>

                    <!-- Fourchette prix -->
                    <div class="col-6 col-lg-2">
                        <label for="prixMin" class="form-label">
                            Prix min
                        </label>

                        <input type="number"
                               class="form-control"
                               id="prixMin"
                               name="prixMin"
                               min="0"
                               placeholder="20">
                    </div>

                    <div class="col-6 col-lg-2">
                        <label for="prixRangeMax" class="form-label">
                            Prix max
                        </label>

                        <input type="number"
                               class="form-control"
                               id="prixRangeMax"
                               name="prixRangeMax"
                               min="0"
                               placeholder="100">
                    </div>

                    <!-- Thème -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="theme" class="form-label">
                            Thème
                        </label>

                        <select id="theme"
                                name="theme"
                                class="form-select">

                            <option value="">Tous</option>
                            <option value="Noel">Noël</option>
                            <option value="Paques">Pâques</option>
                            <option value="Classique">Classique</option>
                            <option value="Evenement">Événement</option>

                        </select>
                    </div>

                    <!-- Régime -->
                    <div class="col-12 col-md-6 col-lg-2">
                        <label for="regime" class="form-label">
                            Régime
                        </label>

                        <select id="regime"
                                name="regime"
                                class="form-select">

                            <option value="">Tous</option>
                            <option value="Classique">Classique</option>
                            <option value="Vegetarien">Végétarien</option>
                            <option value="Vegan">Vegan</option>

                        </select>
                    </div>

                    <!-- Nombre minimum -->
                    <div class="col-12 col-md-6 col-lg-1">
                        <label for="personnes" class="form-label">
                            Pers.
                        </label>

                        <input type="number"
                               class="form-control"
                               id="personnes"
                               name="personnes"
                               min="1"
                               placeholder="2">
                    </div>

                </div>

            </form>


    </div>



    <!-- ALERTS -->
    <?php if ($success): ?>
        <div class="alert alert-success" role="alert" aria-live="polite">
            <?= htmlspecialchars($success) ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger" role="alert" aria-live="polite">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <!-- MENUS -->
    <div class="row g-4 menu-grid">

        <?php foreach ($menus as $menu): ?>
            <div class="col-sm-6 col-md-4 col-lg-3">

                <article class="card-menu">

                    <!-- IMAGE -->
                    <div class="menu-media">

                        <?php if ($menu->getImage() && file_exists('uploads/' . $menu->getImage())): ?>
                            <img src="uploads/<?= htmlspecialchars($menu->getImage()) ?>"
                                 alt="Image du menu <?= htmlspecialchars($menu->getTitre()) ?>">
                        <?php else: ?>
                            <img src="uploads/default.png"
                                 alt="Image par défaut du menu <?= htmlspecialchars($menu->getTitre()) ?>">
                        <?php endif; ?>

                        <div class="price-badge">
                            <?= number_format($menu->getPrixParPersonne(), 2, ',', ' ') ?> €
                            <span>/ pers</span>
                        </div>

                    </div>

                    <!-- CONTENU -->
                    <div class="card-body">

                        <h2 class="menu-title">
                            <?= htmlspecialchars($menu->getTitre()) ?>
                        </h2>

                        <p class="menu-description">
                            <?= htmlspecialchars($menu->getDescription()) ?>
                        </p>

                        <!-- FOOTER CARD -->
                        <div class="menu-footer">

                            <span class="pill">
                                <i class="bi bi-people"></i>
                                Par personne
                            </span>

                            <a href="index.php?page=detail_menu&id=<?= $menu->getIdMenu() ?>"
                               class="btn-eye"
                               title="Voir le menu <?= htmlspecialchars($menu->getTitre()) ?>"
                               aria-label="Voir le menu <?= htmlspecialchars($menu->getTitre()) ?>">

                                <i class="bi bi-eye"></i>
                            </a>

                        </div>

                    </div>

                </article>

            </div>
        <?php endforeach; ?>

    </div>

</main>

<script src="/assets/js/liste-des-menus.js"></script>