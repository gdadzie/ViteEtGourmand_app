<?php
$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>

<main class="plat-form-page py-4 py-md-5">
    <div class="container">
        <nav aria-label="Fil d'Ariane" class="mb-4">
            <a class="back-link" href="<?= \View\View::dashboardUrl() ?>"><i class="bi bi-arrow-left" aria-hidden="true"></i> Retour au tableau de bord</a>
        </nav>
        <div class="row justify-content-center"><div class="col-12 col-lg-9 col-xl-8">
            <section class="plat-form-card" aria-labelledby="edit-plat-title">
                <header class="plat-form-header">
                    <span class="plat-form-icon"><i class="bi bi-pencil-square" aria-hidden="true"></i></span>
                    <div><h1 id="edit-plat-title">Modifier un plat</h1><p>Modifiez les informations et la photo du plat sélectionné.</p></div>
                </header>
                <?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <form method="post" action="?page=valider_modification_plat" enctype="multipart/form-data" class="row g-4">
                    <input type="hidden" name="id" value="<?= (int) $plat->getIdPlat() ?>">
                    <div class="col-12 col-md-7">
                        <label for="nom_plat" class="form-label">Nom du plat <span aria-hidden="true">*</span></label>
                        <input type="text" id="nom_plat" name="nom_plat" class="form-control" maxlength="255" required value="<?= htmlspecialchars((string) $plat->getNomPlat()) ?>">
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="type_plat" class="form-label">Catégorie <span aria-hidden="true">*</span></label>
                        <select id="type_plat" name="type_plat" class="form-select" required>
                            <?php foreach (['entree' => 'Entrée', 'plat' => 'Plat principal', 'dessert' => 'Dessert'] as $value => $label): ?>
                                <option value="<?= $value ?>" <?= $plat->getTypePlat() === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="image_plat" class="form-label">Nouvelle photo</label>
                        <?php if ($plat->getImagePlat()): ?>
                            <img class="img-fluid rounded mb-3 d-block" style="max-height: 220px" src="/index.php?page=media_image&amp;type=plat&amp;id=<?= (int) $plat->getIdPlat() ?>&amp;name=<?= rawurlencode($plat->getImagePlat()) ?>" alt="Photo actuelle de <?= htmlspecialchars((string) $plat->getNomPlat()) ?>">
                        <?php endif; ?>
                        <input type="file" id="image_plat" name="image_plat" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" data-image-input>
                        <div class="form-text">Laissez vide pour conserver la photo actuelle. JPG, PNG, GIF ou WEBP - 5 Mo maximum.</div>
                        <img src="" alt="Aperçu de la nouvelle photo sélectionnée" class="image-preview d-none mt-3" data-image-preview>
                    </div>
                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between gap-3 pt-2">
                        <a href="<?= \View\View::dashboardUrl() ?>" class="btn btn-outline-secondary px-4">Retour au tableau de bord</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i> Enregistrer les modifications</button>
                    </div>
                </form>
            </section>
        </div></div>
    </div>
</main>
