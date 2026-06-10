<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<meta name="description" content="Vite & Gourmand — Traiteur à Bordeaux. Menus pour événements, commandes en ligne." />
<title>Modifier les horaires</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous" defer></script>

<link rel="stylesheet" href="assets/css/home/home.css" />
<link rel="stylesheet" href="assets/css/media_queries_page_accueil.css?v=2">
<meta name="robots" content="index, follow"></head>
<body>

<!-- FIL D'ARIANE / BOUTON RETOUR -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb breadcrumb-custom">
        <li class="breadcrumb-item">
            <a href="?page=espace_admin"><i class="bi bi-arrow-left"></i> Retour</a>
        </li>
    </ol>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Horaires d'ouverture</h2>

    <table class="table table-bordered w-50">
        <tbody>
        

        <?php foreach ($horaires as $horaire): ?>
            <tr>
                <td class="fw-bold text-capitalize">
                    <?= htmlspecialchars($horaire['jour']) ?>
                </td>

                <td>
                    <?php if ($horaire['est_ferme']): ?>
                        <span class="text-danger fw-semibold">Fermé</span>
                    <?php else: ?>
                        <?= substr($horaire['heure_ouverture'], 0, 5) ?>
                        –
                        <?= substr($horaire['heure_fermeture'], 0, 5) ?>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>

        </tbody>
    </table>
</div>

</body>
</html>
