<?php
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
$oldPlat = $oldPlat ?? [];
?>

<main class="plat-form-page py-4 py-md-5">
    <div class="container">
        <nav aria-label="Fil d'Ariane" class="mb-4">
            <a class="back-link" href="?page=liste_des_plats"><i class="bi bi-arrow-left" aria-hidden="true"></i> Retour aux plats</a>
        </nav>
        <div class="row justify-content-center"><div class="col-12 col-lg-9 col-xl-8">
            <section class="plat-form-card" aria-labelledby="create-plat-title">
                <header class="plat-form-header">
                    <span class="plat-form-icon"><i class="bi bi-egg-fried" aria-hidden="true"></i></span>
                    <div><h1 id="create-plat-title">Ajouter un plat</h1><p>Créez un plat du catalogue et associez-le immédiatement à un menu.</p></div>
                </header>
                <?php if ($success): ?><div class="alert alert-success" role="status"><?= htmlspecialchars($success) ?></div><?php endif; ?>
                <?php if ($error): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>
                <form method="post" action="?page=creer_un_plat" enctype="multipart/form-data" class="row g-4">
                    <div class="col-12 col-md-7">
                        <label for="nom_plat" class="form-label">Nom du plat <span aria-hidden="true">*</span></label>
                        <input type="text" id="nom_plat" name="nom_plat" class="form-control" maxlength="255" required value="<?= htmlspecialchars((string) ($oldPlat['nom_plat'] ?? '')) ?>" placeholder="Ex. Filet de saumon aux herbes">
                        <div class="form-text">Un intitulé court et clair, visible dans la composition des menus.</div>
                    </div>
                    <div class="col-12 col-md-5">
                        <label for="type_plat" class="form-label">Catégorie <span aria-hidden="true">*</span></label>
                        <select id="type_plat" name="type_plat" class="form-select" required>
                            <option value="">Sélectionnez une catégorie</option>
                            <?php foreach (['entree' => 'Entrée', 'plat' => 'Plat principal', 'dessert' => 'Dessert'] as $value => $label): ?>
                                <option value="<?= $value ?>" <?= ($oldPlat['type_plat'] ?? '') === $value ? 'selected' : '' ?>><?= $label ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label for="id_menu" class="form-label">Menu associé <span aria-hidden="true">*</span></label>
                        <select id="id_menu" name="id_menu" class="form-select" required>
                            <option value="">Sélectionnez le menu qui inclut ce plat</option>
                            <?php foreach ($menus as $menu): ?>
                                <option value="<?= (int) $menu->getIdMenu() ?>" <?= (int) ($oldPlat['id_menu'] ?? 0) === $menu->getIdMenu() ? 'selected' : '' ?>><?= htmlspecialchars((string) $menu->getTitre()) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Le plat sera aussi disponible lors de la création d'un nouveau menu.</div>
                    </div>
                    <div class="col-12">
                        <label for="image_plat" class="form-label">Photo du plat</label>
                        <div class="image-upload-box">
                            <input type="file" id="image_plat" name="image_plat" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp" data-image-input>
                            <p class="mb-0">JPG, PNG, GIF ou WEBP — 5 Mo maximum.</p>
                            <img src="" alt="Aperçu de la photo sélectionnée" class="image-preview d-none" data-image-preview>
                        </div>
                    </div>
                    <div class="col-12 d-flex flex-column flex-sm-row justify-content-between gap-3 pt-2">
                        <a href="?page=liste_des_plats" class="btn btn-outline-secondary px-4"><i class="bi bi-arrow-left me-1"></i> Annuler</a>
                        <button type="submit" class="btn btn-primary px-4"><i class="bi bi-check-circle me-1"></i> Enregistrer le plat</button>
                    </div>
                </form>
            </section>
        </div></div>
    </div>
</main>
