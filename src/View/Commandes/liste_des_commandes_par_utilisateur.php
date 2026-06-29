<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';

unset($_SESSION['success'], $_SESSION['error']);

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes commandes - Vite & Gourmand</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/menu/mes_commandes_utilisateur.css">

</head>

<body>

<div class="container container-custom my-5">

    <!-- TOPBAR -->
    <div class="topbar mb-4 d-flex justify-content-between align-items-center">

        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="brand-dot"></span>
                <h1 class="page-title mb-0">Mes commandes</h1>
            </div>
            <div class="muted">
                Consultez l’historique et le suivi de vos commandes.
            </div>
        </div>

        <a href="index.php?page=espace_utilisateur"
           class="btn btn-accent btn-soft">
            <i class="bi bi-arrow-left me-2"></i> Retour
        </a>

    </div>

    <!-- ALERTS -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $e($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $e($error) ?></div>
    <?php endif; ?>

    <!-- TABLE -->
    <div class="table-card">
        <div class="table-responsive">

            <table class="table align-middle">


                <thead>
                <tr>
                    <th>Menu</th>
                    <th>Prix</th>
                    <th>Date de commande</th>
                    <th>Statut</th>
                    <th>Avis</th>
                    <th>Actions</th>
                </tr>
                </thead>


                <tbody>

                <?php if (!empty($commandes)): ?>

                    <?php foreach ($commandes as $commande): ?>

                        <tr>

                            <!-- MENU -->
                            <td>
                                <strong class="titre_menu"> <?= $e($commande->getTitreMenu()) ?></strong>
                            </td>

                            <!-- PRIX -->
                            <td>
                                <strong>
                                    <?= number_format($commande->getPrixTotal(), 2, ',', ' ') ?> €
                                </strong>
                            </td>

                            <!-- DATE DE COMMANDE -->
                            <td>
                                <strong>
                                    <?= $e($commande->getDateCreation('%Y-%m-%d')) ?>
                                </strong>
                            </td>

                            <!-- STATUT -->
                            <td>
        <span class="status <?= $commande->getStatut() ?>">
            <?= $e(ucfirst(str_replace('_', ' ', $commande->getStatut()))) ?>
        </span>
                            </td>

                            <!-- AVIS -->
                            <td>
                                <?php if ($commande->getStatut() === 'terminee'): ?>
                                    <a class="btn btn-secondary btn-sm rounded-pill"
                                       href="index.php?page=avis&id_commande=<?= $commande->getIdCommande() ?>">
                                        ⭐ Avis
                                    </a>
                                <?php else: ($avisValide->getEstValide() === '1')?>
                                <a class="btn btn-warning btn-sm rounded-pill"
                                   href="index.php?page=detail_avis&id_commande=<?= $commande->getIdCommande() ?>">
                                    ⭐ Avis
                                </a>

                                <?php endif; ?>
                            </td>

                            <!-- ACTIONS -->
                            <td>
                                <div class="d-flex justify-content-center gap-2 flex-wrap">

                                    <!-- DÉTAIL -->
                                    <a href="index.php?page=detail_commande&id=<?= $commande->getIdCommande() ?>"
                                       class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- SUPPRIMER -->
                                    <?php if ($commande->getStatut() === 'reçue'): ?>
                                        <form method="POST"
                                              action="index.php?page=supprimer_commande&id_commande=<?= $commande->getIdCommande() ?>"
                                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette commande ?');">

                                            <input type="hidden"
                                                   name="id_commande"
                                                   value="<?= $commande->getIdCommande() ?>">

                                            <button type="submit"
                                                    class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>
                                    <?php endif; ?>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="8">
                            <div class="empty-state">
                                <i class="bi bi-bag-x"></i>
                                <h5>Aucune commande</h5>
                                <p>Vous n’avez encore rien commandé.</p>
                                <a href="index.php?page=liste_des_menus"
                                   class="btn btn-accent">
                                    Voir les menus
                                </a>
                            </div>
                        </td>
                    </tr>

                <?php endif; ?>

                </tbody>

            </table>

        </div>
    </div>

</div>

</body>
</html>