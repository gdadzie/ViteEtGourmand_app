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
    <div class="page-header mb-4">

        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="m-0">Nos plats</h1>
        </div>

        <p class="page-sub">
            Découvrez notre sélection de plats.
        </p>

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
                                    src="/uploads/<?= rawurlencode($plat->getImagePlat()) ?>"
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


                            <a
                                    href="index.php?page=detail_plat&id=<?= $plat->getIdPlat() ?>"
                                    class="btn-eye"
                                    title="Voir le plat <?= htmlspecialchars($plat->getNomPlat()) ?>"
                                    aria-label="Voir le plat <?= htmlspecialchars($plat->getNomPlat()) ?>"
                            >
                                <i class="bi bi-eye"></i>
                            </a>

                        </div>

                    </div>

                </article>

            </div>

        <?php endforeach; ?>

    </div>

</main>
