<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta charset="UTF-8">
    <title>Espace administrateur</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/dashboard/layout.css">
</head>

<body>
<?php
// Helpers sûrs (si tu n’as pas de variables, ça marche quand même)
$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
$prenom = $prenom ?? ($_SESSION['prenom'] ?? ''); // optionnel
$nom    = $nom    ?? ($_SESSION['nom'] ?? '');    // optionnel

// Stats optionnelles si ton contrôleur les passe (sinon, affichage "—")
$stats = $stats ?? [
        'total' => null,
        'en_attente' => null,
        'acceptees' => null,
        'terminees' => null,
        'a_noter' => null,
];
?>

<div class="container my-5">

    <!-- En-tête du tableau de bord -->
    <div class="topbar p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="brand-dot"></span>
                <h1 class="page-title mb-0">Mon espace admin</h1>
            </div>
            <div class="muted">
                Bonjour <?= $e(trim($prenom . ' ' . $nom)) ?: '👋' ?> — gérez vos commandes, votre profil et vos avis.
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <span class="stat-chip">
                <i class="bi bi-receipt me-1"></i>
                Total : <strong><?= $stats['total'] ?? '—' ?></strong>
            </span>
            <span class="stat-chip">
                <i class="bi bi-hourglass-split me-1"></i>
                En attente : <strong><?= $stats['en_attente'] ?? '—' ?></strong>
            </span>
            <span class="stat-chip">
                <i class="bi bi-check2-circle me-1"></i>
                Acceptées : <strong><?= $stats['acceptees'] ?? '—' ?></strong>
            </span>
            <span class="stat-chip">
                <i class="bi bi-flag me-1"></i>
                Terminées : <strong><?= $stats['terminees'] ?? '—' ?></strong>
            </span>
        </div>
    </div>

    <!-- Alerts (si tu utilises $_SESSION['success']/['error']) -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div><?= $e($_SESSION['success']) ?></div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <div><?= $e($_SESSION['error']) ?></div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Cartes principales -->
    <div class="row g-4">

        <!-- Créer un nouvel employé -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=creation_employe">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-person-plus fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Créer un compte employé</h5>
                                <div class="muted small">Accèder au formulaire de création d'un nouvel employé.</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Ajoutez un nouvel employé et créer ses identifiants de connexions.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent  w-100">
                            Ouvrir <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Gestion des employé -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=liste_des_utilisateurs">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-people fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Gestion des employés</h5>
                                <div class="muted small">Accèder à la liste de tous vos employés.</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                           Recherchez un employé et activez ou désactivez son compte.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent  w-100">
                            Accèder <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Gestion des menus -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=gestion_des_menus">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-menu-button-wide fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Gestion des menus</h5>
                                <div class="muted small">Accèder a la liste intégrale des menus de Vite & Gourmand.</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                           Créer un nouveau menu, consulter et modifier les menus existants.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent w-100">
                            Accèder <i class="bi bi-pencil-square ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Gestion des commandes  -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=gestion_des_commandes">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-clock fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Gestion des commandes</h5>
                                <div class="muted small">Accèder à liste des commandes des clients.</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Accepte et termine les commandes des clients.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent w-100">
                            Accèder <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        <!-- Gestion des horaires  -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=modification_horaire">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-clock fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Modifier les horaires</h5>
                                <div class="muted small">Accèder aux horaires d'ouverture et fermerture.</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Mettez à jour les horaires du service traiteur.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent w-100">
                            Voir les commandes acceptées <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>
        <!-- Statistiques/CA -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=gestion_des_menus">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-bar-chart-line fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Gestion des statistiques et du chiffre d'affaire</h5>
                                <div class="muted small">Accèder au statistique de ventes des menus .</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Consulter les statistiques et le chiffre d'affaire de votre entreprise.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent w-100">
                            Accèder <i class="bi bi-pencil-square ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>



        <!-- Site web -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=home">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-globe2 fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Site web</h5>
                                <div class="muted small">Accèder au site web .</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Naviguer sur le site web de Vite & Gourmand.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent w-100">
                            Accèder <i class="bi bi-pencil-square ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Déconnexion -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=deconnexion">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-box-arrow-right fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Déconnexion</h5>
                                <div class="muted small">Quitter votre espace</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Vous pourrez vous reconnecter à tout moment pour gérer vos commandes et votre profil.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-outline-danger w-100">
                            Se déconnecter
                        </span>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
