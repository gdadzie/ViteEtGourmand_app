<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Employé</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/dashboard_employe.css">

</head>


<body>
<?php include __DIR__ . '/../partials/menu.php'; ?>


<div class="container my-5">

    <!-- HEADER -->
    <div class="dash-header mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="text-start">
                <i class="bi bi-briefcase header-icon"></i>
                Espace Employé
            </h1>
        </div>
        <p class="text-start">
            Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] ?? 'Employé') ?> !
        </p>
    </div>

    <!-- CARTES (données inchangées : mêmes liens / titres / textes) -->
    <div class="row g-4">

        <!-- Carte 1 : Gestion des avis -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="?page=creation_employe" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-chat-square-text"></i></div>
                        <div>
                            <h5 class="card-title">Gestion des avis</h5>
                            <p class="card-text">Validation ou refus des avis reçu des utilisateurs.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 2 : Modifier les horaires -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="?page=modification_horaire" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <h5 class="card-title">Modifier les horaires</h5>
                            <p class="card-text">Mettez à jour les horaires d’ouverture et fermeture du service traiteur.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 3 : Modifier contacts -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=gestion_avis" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-telephone"></i></div>
                        <div>
                            <h5 class="card-title">Gestion des avis</h5>
                            <p class="card-text">Validez les avis des clients</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 4 : Voir les menus -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=gestion_menus" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-card-list"></i></div>
                        <div>
                            <h5 class="card-title">Gestion des menus</h5>
                            <p class="card-text">Consultez et gérez les menus proposés par le service traiteur.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 5 : Voir les commandes -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=gestion_des_commandes" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-receipt"></i></div>
                        <div>
                            <h5 class="card-title">Voir les commandes</h5>
                            <p class="card-text">Accédez à l’historique des commandes et suivez les ventes.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 6 : Statistiques / CA -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=sales_stats" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-graph-up"></i></div>
                        <div>
                            <h5 class="card-title">Statistiques / CA</h5>
                            <p class="card-text">Analysez le chiffre d’affaires par menu ou période.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
<a class="navbar-brand" href="?page=home" aria-label="Retour à l'accueil">
    <span class="brand-dot"></span>
    Vite &amp; Gourmand
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
