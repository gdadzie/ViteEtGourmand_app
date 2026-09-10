<?php
$e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$menuStats = $menuStats ?? [];
$menusStats = $menusStats ?? [];
$filtersStats = $filtersStats ?? ['id_menu' => 0, 'date_debut' => '', 'date_fin' => ''];
$mongoDisponible = $mongoDisponible ?? false;
$stats = $stats ?? [];
$menuStatsJson = htmlspecialchars(
    json_encode($menuStats, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_HEX_TAG) ?: '[]',
    ENT_QUOTES,
    'UTF-8'
);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Statistiques | Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/dashboard/layout.css">
</head>
<body>
<main class="container my-5">
    <div class="topbar p-4 mb-4 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
        <div>
            <h1 class="page-title mb-1"><i class="bi bi-bar-chart-line me-2 accent"></i>Statistiques</h1>
            <p class="muted mb-0">Suivi des commandes et du chiffre d’affaires par menu.</p>
        </div>
        <a class="btn btn-outline-secondary" href="<?= \View\View::dashboardUrl() ?>"><i class="bi bi-arrow-left me-1"></i>Retour au tableau de bord</a>
    </div>

    <section class="card card-tile" aria-labelledby="stats-menus-title">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-4">
                <div>
                    <h2 id="stats-menus-title" class="h4 mb-1">Commandes par menu</h2>
                    <p class="muted mb-0">Données synchronisées depuis MongoDB.</p>
                </div>
                <span class="badge <?= $mongoDisponible ? 'text-bg-success' : 'text-bg-warning' ?> align-self-md-start">
                    <?= $mongoDisponible ? 'MongoDB synchronisé' : 'MongoDB indisponible' ?>
                </span>
            </div>

            <form class="row g-3 align-items-end mb-4" method="get" action="index.php" aria-label="Filtrer les statistiques">
                <input type="hidden" name="page" value="statistiques">
                <div class="col-12 col-md-4">
                    <label for="stat-menu" class="form-label">Menu</label>
                    <select id="stat-menu" name="stat_menu" class="form-select">
                        <option value="">Tous les menus</option>
                        <?php foreach ($menusStats as $menu): ?>
                            <option value="<?= (int) $menu->getIdMenu() ?>" <?= (int) $filtersStats['id_menu'] === $menu->getIdMenu() ? 'selected' : '' ?>><?= $e($menu->getTitre()) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="date-debut" class="form-label">Du</label>
                    <input id="date-debut" type="date" name="date_debut" class="form-control" value="<?= $e($filtersStats['date_debut']) ?>">
                </div>
                <div class="col-6 col-md-3">
                    <label for="date-fin" class="form-label">Au</label>
                    <input id="date-fin" type="date" name="date_fin" class="form-control" value="<?= $e($filtersStats['date_fin']) ?>">
                </div>
                <div class="col-12 col-md-2 d-flex gap-2">
                    <button class="btn btn-accent flex-fill" type="submit">Filtrer</button>
                    <a class="btn btn-outline-secondary" href="?page=statistiques">Réinitialiser</a>
                </div>
            </form>

            <div class="row g-3 mb-4">
                <div class="col-12 col-md-4"><div class="border rounded-3 p-3 h-100"><span class="muted d-block small">Commandes</span><strong class="fs-3"><?= (int) ($stats['total'] ?? 0) ?></strong></div></div>
                <div class="col-12 col-md-4"><div class="border rounded-3 p-3 h-100"><span class="muted d-block small">Commandes acceptées</span><strong class="fs-3"><?= (int) ($stats['acceptees'] ?? 0) ?></strong></div></div>
                <div class="col-12 col-md-4"><div class="border rounded-3 p-3 h-100"><span class="muted d-block small">Commandes terminées</span><strong class="fs-3"><?= (int) ($stats['terminees'] ?? 0) ?></strong></div></div>
            </div>

            <?php if ($mongoDisponible && !empty($menuStats)): ?>
                <div class="row g-4 align-items-center">
                    <div class="col-12 col-lg-7"><canvas id="menu-stats-chart" data-menu-stats="<?= $menuStatsJson ?>" aria-label="Graphique des commandes par menu" role="img"></canvas></div>
                    <div class="col-12 col-lg-5">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead><tr><th>Menu</th><th>Commandes</th><th>CA</th></tr></thead>
                                <tbody>
                                <?php foreach ($menuStats as $menuStat): ?>
                                    <tr>
                                        <td><?= $e($menuStat['menu_titre'] ?? 'Menu') ?></td>
                                        <td><?= (int) ($menuStat['nombre_commandes'] ?? 0) ?></td>
                                        <td><?= number_format((float) ($menuStat['chiffre_affaires'] ?? 0), 2, ',', ' ') ?> €</td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <p class="alert alert-info mb-0">Aucune donnée statistique disponible pour le moment.</p>
            <?php endif; ?>
        </div>
    </section>
</main>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script src="assets/js/dashboard/admin-stats.js" defer></script>
</body>
</html>
