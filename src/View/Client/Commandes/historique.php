<?php $e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historique de commande | Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/layout.css">
</head>
<body>
<main class="container my-4 my-md-5" style="max-width: 900px">
    <div class="topbar p-4 mb-4 d-flex flex-column flex-md-row justify-content-between gap-3"><div><h1 class="page-title mb-1">Historique de la commande #<?= $idCommande ?></h1><p class="muted mb-0">Chaque étape importante de votre commande est enregistrée.</p></div><a class="btn btn-outline-secondary align-self-md-center" href="<?= \View\View::dashboardUrl() ?>"><i class="bi bi-arrow-left me-1"></i>Retour au tableau de bord</a></div>
    <section class="card card-tile"><div class="card-body p-4 p-md-5">
        <?php if ($historique): ?><div class="vstack gap-3"><?php foreach ($historique as $etape): ?><article class="border-start border-4 ps-3" style="border-color:#aa6d27 !important"><div class="d-flex flex-column flex-md-row justify-content-between gap-1"><strong><?= $e($etape['action']) ?></strong><small class="text-muted"><?= $e($etape['date_modification']) ?></small></div><div class="text-muted small">Statut : <?= $e(str_replace('_', ' ', $etape['nouveau_statut'])) ?></div></article><?php endforeach; ?></div><?php else: ?><div class="text-center py-4"><i class="bi bi-clock-history fs-1 text-muted"></i><p class="mt-3 mb-0">Aucun historique disponible pour le moment.</p></div><?php endif; ?>
    </div></section>
</main>
</body>
</html>
