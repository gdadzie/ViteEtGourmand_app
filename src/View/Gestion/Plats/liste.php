<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';

unset($_SESSION['success'], $_SESSION['error']);
?>

<main class="container my-4 my-md-5" role="main">

    <!-- HEADER -->
    <div class="page-header mb-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">

        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="m-0">Nos plats</h1>
        </div>

        <p class="page-sub mb-0">Gérez les plats disponibles pour la composition des menus.</p>

        <a class="btn btn-primary" href="?page=creer_un_plat"><i class="bi bi-plus-circle me-1"></i> Ajouter un plat</a>
    </div>


    <!-- ALERTS -->
    <?php if ($success): ?>

        <div class="alert alert-success" role="alert">
            <?= htmlspecialchars($success) ?>
        </div>

    <?php endif; ?>


    <?php if ($error): ?>

        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>


    <!-- PLATS -->
    <div class="row g-4 menu-grid">

        <?php foreach ($plats as $plat): ?>

            <div class="col-sm-6 col-md-4 col-lg-3">

                <article class="card-menu">

                    <!-- IMAGE -->
                    <div class="menu-media">

                        <?php if ($plat->getImagePlat()): ?>

                            <img
                                    src="/index.php?page=media_image&amp;type=plat&amp;id=<?= (int) $plat->getIdPlat() ?>&amp;name=<?= rawurlencode($plat->getImagePlat()) ?>"
                                    alt="Image du plat <?= htmlspecialchars($plat->getNomPlat()) ?>"
                            >

                        <?php else: ?>

                            <div class="image-placeholder">
                                <i class="bi bi-image"></i>
                            </div>

                        <?php endif; ?>


                        <!-- TYPE -->
                        <div class="price-badge">

                            <?php
                            $type = $plat->getTypePlat();

                            echo match ($type) {
                                'entree'  => 'Entrée',
                                'plat'    => 'Plat',
                                'dessert' => 'Dessert',
                                default   => ucfirst($type)
                            };
                            ?>

                        </div>

                    </div>


                    <!-- CONTENU -->
                    <div class="card-body">

                        <h2 class="menu-title">
                            <?= htmlspecialchars($plat->getNomPlat()) ?>
                        </h2>


                        <!-- FOOTER -->
                        <div class="menu-footer">

                            <span class="pill">

                                <?php
                                echo match ($plat->getTypePlat()) {
                                    'entree'  => 'Entrée',
                                    'plat'    => 'Plat',
                                    'dessert' => 'Dessert',
                                    default   => 'Plat'
                                };
                                ?>

                            </span>


                            <div class="d-flex gap-2">
                                <a href="?page=modifier_un_plat&id=<?= (int) $plat->getIdPlat() ?>" class="btn btn-sm btn-outline-primary" aria-label="Modifier <?= htmlspecialchars($plat->getNomPlat()) ?>">
                                    <i class="bi bi-pencil-square" aria-hidden="true"></i> Modifier
                                </a>
                                <form method="post" action="?page=supprimer_plat" onsubmit="return confirm('Supprimer ce plat ?');">
                                    <input type="hidden" name="id" value="<?= (int) $plat->getIdPlat() ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger" aria-label="Supprimer <?= htmlspecialchars($plat->getNomPlat()) ?>">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>

                </article>

            </div>

        <?php endforeach; ?>

    </div>

</main>
