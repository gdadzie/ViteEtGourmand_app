<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — Vite & Gourmand</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
    <header class="hero">
    <div class="hero-content">
        <div class="mb-2 d-flex align-items-center justify-content-center">
            <span class="brand-dot"></span>
            <span class="fw-semibold" style="color:rgba(255,255,255,.9)">Traiteur • Bordeaux</span>
        </div>

        <h1 class="titre-h1">Vite &amp; Gourmand</h1>
        <p>Des menus savoureux pour tous vos événements</p>

        <div class="hero-badges">
            <span class="hero-chip"><i class="bi bi-award"></i> +25 ans d’expérience</span>
            <span class="hero-chip"><i class="bi bi-truck"></i> Commandes en ligne</span>
            <span class="hero-chip"><i class="bi bi-heart"></i> Fait avec passion</span>
        </div>
    </div>
</header>

<main class="container-fluid">

    <!-- QUI SOMMES-NOUS -->
    <section class="row g-4 align-items-center justify-content-center px-2 px-md-4 py-4">

        <div class="col-12 col-md-6">
            <img class="frame-img" src="assets/images/images/menu_noel_1.jpg" alt="Menu Noël">
        </div>

        <div class="col-12 col-md-5">
            <div class="section-card">
                <h2 class="h-title mb-3 titre-h2">Qui sommes-nous ?</h2>

                <p class="text-muted-2 paragraphe-main mb-2">
                    Depuis plus de 25 ans, Vite & Gourmand accompagne vos moments importants avec une cuisine savoureuse, généreuse et de qualité.
                    Basée à Bordeaux, notre entreprise familiale met tout son savoir-faire au service de vos événements privés ou professionnels.
                </p>

                <p class="text-muted-2 paragraphe-main mb-2">
                    Nous créons des menus sur mesure pour Noël, Pâques, anniversaires, mariages ou toute autre célébration.
                    Chaque plat est préparé avec passion, pour garantir fraîcheur, goût et présentation irréprochable.
                </p>

                <p class="text-muted-2 paragraphe-main mb-3">
                    Grâce à notre application web, vous pouvez consulter facilement tous nos menus, filtrer vos choix selon vos envies,
                    et passer vos commandes directement en ligne. Toutes les informations sur les plats, les régimes alimentaires et les conditions
                    de commande sont disponibles pour préparer vos événements en toute sérénité.
                </p>

                <button class="btn btn-outline-brand btn-en-savoir-plus">
                    En savoir plus <i class="bi bi-chevron-right ms-1"></i>
                </button>
            </div>
        </div>

    </section>

    <hr class="section-divider">

    <!-- ÉQUIPE -->
    <section class="section-slab bg-tint">
        <div class="container px-2 px-md-4 text-center">

            <h3 class="h-title mb-4 titre-h3">Notre Équipe Gourmand</h3>

            <div class="row justify-content-center g-4">

                <div class="col-12 col-md-4">
                    <div class="team-card">
                        <img src="assets/images/avatars/jose-pdg-vite-et-gourmand.jpg"
                             class="team-photo bubble-photo"
                             alt="José Almeida">

                        <h4 class="mt-3 mb-2">José Almeida</h4>

                        <span class="role-badge">
                            <i class="bi bi-briefcase"></i>
                            Co-fondateur & Directeur Général
                        </span>
                    </div>
                </div>

                <div class="col-12 col-md-4">
                    <div class="team-card">
                        <img src="assets/images/avatars/julie-pdg-vite-et-gourmand.jpg"
                             class="team-photo bubble-photo"
                             alt="Julie Fernandes">

                        <h4 class="mt-3 mb-2">Julie Fernandes</h4>

                        <span class="role-badge">
                            <i class="bi bi-egg-fried"></i>
                            Co-fondatrice & Directrice Culinaire
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <hr class="section-divider">

    <!-- AVIS -->
    <section class="section-slab">
        <div class="container px-2 px-md-4">

            <h2 class="text-center h-title mb-4">
                Avis de nos clients
            </h2>

            <div class="row g-4">

                <?php if (!empty($avisValides)): ?>

                    <?php foreach ($avisValides as $avis): ?>

                        <?php
                        $nomClient = method_exists($avis, 'getNomUtilisateur')
                                ? $avis->getNomUtilisateur()
                                : 'Client';

                        $initiale = strtoupper(substr($nomClient, 0, 1));
                        ?>

                        <div class="col-12 col-md-3">

                            <div class="review-card h-100">

                                <div class="d-flex align-items-start">

                                    <!-- AVATAR LETTRE -->
                                    <div class="avatar-letter me-3">
                                        <?= htmlspecialchars($initiale) ?>
                                    </div>

                                    <div class="flex-grow-1">

                                        <h6 class="fw-semibold mb-1">
                                            <?= htmlspecialchars($nomClient) ?>
                                        </h6>

                                        <!-- ÉTOILES -->
                                        <div class="stars mb-2">

                                            <?php for ($i = 1; $i <= 5; $i++): ?>

                                                <?php if ($i <= (int)$avis->getNote()): ?>

                                                    <i class="bi bi-star-fill"></i>

                                                <?php else: ?>

                                                    <i class="bi bi-star"></i>

                                                <?php endif; ?>

                                            <?php endfor; ?>

                                        </div>

                                        <!-- COMMENTAIRE -->
                                        <p class="text-muted small mb-0">
                                            <span class="quote">“</span>

                                            <?= nl2br(htmlspecialchars($avis->getCommentaire())) ?>
                                        </p>

                                    </div>

                                </div>

                            </div>

                        </div>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="col-12 text-center text-muted">
                        Aucun avis pour le moment
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </section>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<style>

    .avatar-letter{
        width:55px;
        height:55px;
        min-width:55px;
        border-radius:50%;
        background:#aa6d27;
        color:white;
        display:flex;
        align-items:center;
        justify-content:center;
        font-size:1.3rem;
        font-weight:700;
        text-transform:uppercase;
        box-shadow:0 2px 8px rgba(0,0,0,0.15);
    }

    .review-card{
        background:white;
        border-radius:18px;
        padding:20px;
        height:100%;
        box-shadow:0 4px 15px rgba(0,0,0,0.06);
        transition:0.2s ease;
    }

    .review-card:hover{
        transform:translateY(-4px);
    }

    .stars{
        color:#f4b400;
    }

</style>

</html>