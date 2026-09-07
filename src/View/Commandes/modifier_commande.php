<?php $e = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier ma commande | Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/dashboard/layout.css">
</head>
<body>
<main class="container my-4 my-md-5" style="max-width: 900px">
    <div class="topbar p-4 mb-4 d-flex flex-column flex-md-row justify-content-between gap-3">
        <div><h1 class="page-title mb-1">Modifier la commande #<?= $commande->getIdCommande() ?></h1><p class="muted mb-0">Vous pouvez modifier ces informations tant que la commande n’est pas acceptée.</p></div>
        <a class="btn btn-outline-secondary align-self-md-center" href="?page=detail_commande&id=<?= $commande->getIdCommande() ?>"><i class="bi bi-arrow-left me-1"></i>Retour</a>
    </div>

    <form class="card card-tile" method="post" action="index.php?page=enregistrer_modification_commande">
        <div class="card-body p-4 p-md-5">
            <input type="hidden" name="id_commande" value="<?= $commande->getIdCommande() ?>">
            <div class="alert alert-light border"><strong><?= $e($menu->getTitre()) ?></strong><br><span class="text-muted">Prix recalculé automatiquement selon le nombre de personnes et la livraison.</span></div>
            <div class="row g-3">
                <div class="col-12 col-md-6"><label class="form-label" for="nombre-personnes">Nombre de personnes</label><input id="nombre-personnes" class="form-control" type="number" name="nombre_personnes" min="<?= (int) $menu->getNbMinPersonne() ?>" max="<?= (int) $menu->getStockDisponible() ?>" value="<?= $commande->getNombrePersonnes() ?>" required><small class="text-muted">Minimum : <?= (int) $menu->getNbMinPersonne() ?> personnes.</small></div>
                <div class="col-12 col-md-6"><label class="form-label" for="ville">Ville</label><select id="ville" class="form-select" name="id_ville" required><?php foreach ($villes as $ville): ?><option value="<?= (int) $ville['id_ville'] ?>" <?= (int) $ville['id_ville'] === $commande->getIdVille() ? 'selected' : '' ?>><?= $e($ville['nom_ville']) ?></option><?php endforeach; ?></select></div>
                <div class="col-12"><label class="form-label" for="adresse">Adresse de livraison</label><input id="adresse" class="form-control" type="text" name="adresse_livraison" value="<?= $e($commande->getAdresseLivraison()) ?>" required></div>
                <div class="col-12 col-md-6"><label class="form-label" for="date">Date de livraison</label><input id="date" class="form-control" type="date" name="date_livraison" value="<?= $e($commande->getDateLivraison()) ?>" min="<?= date('Y-m-d') ?>" required></div>
                <div class="col-12 col-md-6"><label class="form-label" for="heure">Heure de livraison</label><input id="heure" class="form-control" type="time" name="heure_livraison" value="<?= $e($commande->getHeureLivraison()) ?>" required></div>
                <div class="col-12 col-md-6"><label class="form-label" for="reception">Réception</label><select id="reception" class="form-select" name="mode_reception"><option value="livraison" <?= $commande->getModeReception() === 'livraison' ? 'selected' : '' ?>>Livraison</option><option value="sur_place" <?= $commande->getModeReception() === 'sur_place' ? 'selected' : '' ?>>Retrait sur place</option></select></div>
                <div class="col-12 col-md-6"><label class="form-label" for="paiement">Paiement</label><select id="paiement" class="form-select" name="mode_paiement"><option value="paiement_livraison" <?= $commande->getModePaiement() === 'paiement_livraison' ? 'selected' : '' ?>>Paiement à la livraison</option><option value="paiement_sur_place" <?= $commande->getModePaiement() === 'paiement_sur_place' ? 'selected' : '' ?>>Paiement sur place</option></select></div>
            </div>
        </div>
        <div class="card-footer bg-transparent border-0 px-4 pb-4 d-flex gap-2 justify-content-end"><a class="btn btn-outline-secondary" href="?page=mes_commandes">Annuler</a><button class="btn btn-accent" type="submit"><i class="bi bi-check2 me-1"></i>Enregistrer les modifications</button></div>
    </form>
</main>
</body>
</html>
