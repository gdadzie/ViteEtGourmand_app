<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta charset="UTF-8">
    <title>Espace utilisateur</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f5f5f5; }
        .container { max-width: 1200px; }
        .card-tile { border: 0; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.06); transition: transform .12s ease, box-shadow .12s ease; }
        .card-tile:hover { transform: translateY(-2px); box-shadow: 0 10px 26px rgba(0,0,0,0.10); }
        .icon-badge { width: 46px; height: 46px; border-radius: 14px; display:flex; align-items:center; justify-content:center; background:#fdf2e7; }
        .page-title { font-size: 1.6rem; }
        .muted { color:#6c757d; }
        .stat-chip { border-radius: 999px; padding: .25rem .6rem; font-size: .8rem; background:#fff; border:1px solid rgba(0,0,0,.08); }
        .quick-link { text-decoration:none; color:inherit; }
        .quick-link:focus { outline: none; }
        .topbar { background: #fff; border-radius: 16px; box-shadow: 0 6px 18px rgba(0,0,0,0.04); }
        .brand-dot { width:10px; height:10px; border-radius:99px; background:#aa6d27; display:inline-block; margin-right:8px; }
        .accent { color:#aa6d27; }
        .btn-accent { background:#aa6d27; border-color:#aa6d27; }
        .btn-accent:hover { background:#935f22; border-color:#935f22; }
    </style>
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

    <!-- Topbar -->
    <div class="topbar p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="brand-dot"></span>
                <h1 class="page-title mb-0">Mon espace utilisateur</h1>
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

        <!-- Voir les menus -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="index.php?page=liste_des_menus">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-bag-check fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Consulter les menus</h5>
                                <div class="muted small">Voir toutes les menus disponibles + détails</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Accédez à l’intégralité complet de nos menus et au détail.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent text-white w-100">
                            Ouvrir <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>


        <!-- Mes commandes -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="index.php?page=mes_commandes">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-bag-check fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Mes commandes</h5>
                                <div class="muted small">Voir toutes vos commandes + détails</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Accédez à l’historique complet de vos commandes, au détail, et aux actions disponibles.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-accent text-white w-100">
                            Ouvrir <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Modifier mon profil -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="index.php?page=profil">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-person-gear fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Mes informations</h5>
                                <div class="muted small">Modifier vos infos personnelles</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Mettez à jour votre prénom, nom, email, téléphone, adresse et autres informations.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-outline-secondary w-100">
                            Modifier <i class="bi bi-pencil-square ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>


        <!-- Suivi des commandes (info) -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="index.php?page=mes_commandes&statut=acceptee">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="icon-badge">
                                <i class="bi bi-truck fs-4 accent"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0">Suivi de commande</h5>
                                <div class="muted small">Disponible après acceptation</div>
                            </div>
                        </div>
                        <p class="card-text muted mb-0">
                            Une fois acceptée, votre commande affiche toutes les étapes avec la date et l’heure.
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4">
                        <span class="btn btn-outline-secondary w-100">
                            Voir les commandes acceptées <i class="bi bi-arrow-right ms-1"></i>
                        </span>
                    </div>
                </div>
            </a>
        </div>

        <!-- Règles (info) -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-tile h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="icon-badge">
                            <i class="bi bi-info-circle fs-4 accent"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0">Rappels</h5>
                            <div class="muted small">Annulation / modification</div>
                        </div>
                    </div>

                    <ul class="mb-0 muted">
                        <li>Annulation possible tant que la commande n’est pas <strong>acceptée</strong>.</li>
                        <li>Modification possible tant que la commande n’est pas <strong>acceptée</strong>.</li>
                        <li>Le <strong>menu</strong> n’est pas modifiable, le reste oui.</li>
                        <li>À la fin (“terminée”), vous recevez un mail pour laisser un avis.</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Déconnexion -->
        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="index.php?page=deconnexion">
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
