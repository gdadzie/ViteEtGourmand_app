<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="Vite & Gourmand — Traiteur à Bordeaux. Menus pour événements, commandes en ligne." />
    <title>Vite & Gourmand — Espace administrateur</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/css/dashboard/dashboard_admin.css" rel="stylesheet">
</head>

<body>



<div class="container-fluid px-2 px-md-4 my-4 my-md-5">

    <!-- HEADER (même esprit que ton dashboard user : fond blanc + dot + icône couleur brand) -->
    <div class="dashboard-header mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="text-start">
                <i class="bi bi-speedometer2 header-icon"></i>
                Mon espace administrateur
            </h1>
        </div>
        <p class="text-start">
            Bienvenue <?= htmlspecialchars($_SESSION['prenom'] ?? 'Administrateur') ?>
        </p>
    </div>

    <!-- CARTES (Données inchangées : mêmes liens / titres / textes) -->
    <div class="admin-cards">

        <a href="?page=creation_employe" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-person-plus"></i></div>
                    <div>
                        <h5 class="card-title">Créer un employé</h5>
                        <p class="card-text">Ajoutez un nouvel employé avec le rôle employé.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=liste_des_utilisateurs" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-people"></i></div>
                    <div>
                        <h5 class="card-title">Gestion des utilisateurs</h5>
                        <p class="card-text">Consultez et gérez les comptes utilisateurs.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=modification_horaire" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h5 class="card-title">Modifier les horaires</h5>
                        <p class="card-text">Mettez à jour les horaires du service traiteur.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="index.php?page=gestion_avis" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-telephone"></i></div>
                    <div>
                        <h5 class="card-title">Gestion des avis</h5>
                        <p class="card-text">Gérez tous les avis clients.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=gestion_des_menus" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-card-list"></i></div>
                    <div>
                        <h5 class="card-title">Gestion des menus</h5>
                        <p class="card-text">Consultez les menus proposés au public.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=creer_un_menu" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div>
                    <div>
                        <h5 class="card-title">Ajouter un menu</h5>
                        <p class="card-text">Créez un nouveau menu traiteur.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="index.php?page=gestion_des_commandes" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-receipt"></i></div>
                    <div>
                        <h5 class="card-title">Commandes</h5>
                        <p class="card-text">Consultez les commandes clients.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="index.php?page=sales_stats" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-graph-up"></i></div>
                    <div>
                        <h5 class="card-title">Statistiques / CA</h5>
                        <p class="card-text">Analysez les performances et le chiffre d’affaires.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
