<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$prenom = trim((string) ($_SESSION['prenom'] ?? ''));
$nom = trim((string) ($_SESSION['nom'] ?? ''));
$cartes = [
    ['messagerie_contact', 'bi-envelope-open', 'Messages clients', 'Consultez et traitez les demandes du formulaire de contact.', 'Répondez aux clients et suivez les messages traités.'],
    ['gestion_avis', 'bi-star', 'Gestion des avis', 'Validez ou modérez les avis laissés par les clients.', 'Gardez uniquement les avis conformes et utiles sur le site.'],
    ['gestion_menus', 'bi-journal-text', 'Gestion des menus', 'Créez, consultez et modifiez les menus proposés.', 'Organisez les offres traiteur et leur composition.'],
    ['gestion_des_commandes', 'bi-bag-check', 'Gestion des commandes', 'Consultez les commandes et mettez à jour leur statut.', 'Accompagnez chaque commande jusqu’à sa finalisation.'],
    ['modification_horaire', 'bi-clock-history', 'Modifier les horaires', 'Mettez à jour les horaires d’ouverture du traiteur.', 'Informez les clients des disponibilités du service.'],
    ['creer_un_plat', 'bi-plus-circle', 'Ajouter un plat', 'Créez un nouveau plat avec ses informations et son image.', 'Ajoutez des plats disponibles pour composer les menus.'],
    ['liste_des_plats', 'bi-egg-fried', 'Gestion des plats', 'Consultez, modifiez ou supprimez les plats existants.', 'Maintenez le catalogue des plats à jour.'],
    ['home', 'bi-globe2', 'Site web', 'Consultez le site tel qu’il est visible par les clients.', 'Vérifiez les menus et la présentation publique.'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Espace employé | Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/dashboard/layout.css">
</head>
<body>
<main class="container my-5">
    <div class="topbar p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <div class="d-flex align-items-center mb-1"><span class="brand-dot"></span><h1 class="page-title mb-0">Mon espace employé</h1></div>
            <p class="muted mb-0">Bonjour <?= $e(trim($prenom . ' ' . $nom)) ?: '👋' ?> — gérez les opérations quotidiennes du traiteur.</p>
        </div>
    </div>

    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success d-flex align-items-center" role="alert"><i class="bi bi-check-circle-fill me-2"></i><div><?= $e($_SESSION['success']) ?></div></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger d-flex align-items-center" role="alert"><i class="bi bi-exclamation-triangle-fill me-2"></i><div><?= $e($_SESSION['error']) ?></div></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($cartes as [$page, $icon, $title, $subtitle, $description]): ?>
            <div class="col-12 col-md-6 col-lg-4">
                <a class="quick-link" href="?page=<?= $e($page) ?>">
                    <div class="card card-tile h-100">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                <div class="icon-badge"><i class="bi <?= $e($icon) ?> fs-4 accent"></i></div>
                                <div><h2 class="h5 card-title mb-0"><?= $e($title) ?></h2><div class="muted small"><?= $e($subtitle) ?></div></div>
                            </div>
                            <p class="card-text muted mb-0"><?= $e($description) ?></p>
                        </div>
                        <div class="card-footer bg-transparent border-0 px-4 pb-4"><span class="btn btn-accent w-100">Ouvrir <i class="bi bi-arrow-right ms-1"></i></span></div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>

        <div class="col-12 col-md-6 col-lg-4">
            <a class="quick-link" href="?page=deconnexion">
                <div class="card card-tile h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3 mb-3"><div class="icon-badge"><i class="bi bi-box-arrow-right fs-4 accent"></i></div><div><h2 class="h5 card-title mb-0">Déconnexion</h2><div class="muted small">Quittez votre espace sécurisé.</div></div></div>
                        <p class="card-text muted mb-0">Vous pourrez vous reconnecter à tout moment.</p>
                    </div>
                    <div class="card-footer bg-transparent border-0 px-4 pb-4"><span class="btn btn-outline-danger w-100">Se déconnecter</span></div>
                </div>
            </a>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
