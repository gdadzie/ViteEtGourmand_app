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
    <title>Gestion des avis</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/fonctionalites/gestion_des_avis.css">
</head>

<body>

<!-- FIL D'ARIANE / BOUTON RETOUR -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb breadcrumb-custom">
        <li class="breadcrumb-item">
            <a href="<?= $retour ?>">
                <i class="bi bi-arrow-left"></i> Retour
            </a>
        </li>
    </ol>
</nav>
<div class="container container-custom my-5">

    <!-- TOPBAR -->
    <div class="topbar mb-4 d-flex justify-content-between align-items-center">

        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="brand-dot"></span>
                <h1 class="page-title mb-0">Gestion des avis</h1>
            </div>
            <div class="muted">
                Liste complète des avis clients.
            </div>
        </div>

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
                    <th>ID</th>
                    <th>Utilisateur</th>
                    <th>Commande</th>
                    <th>Note</th>
                    <th>Commentaire</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($avis)): ?>

                    <?php foreach ($avis as $a): ?>

                        <tr>

                            <!-- ID -->
                            <td><?= $e($a->getIdAvis()) ?></td>

                            <!-- USER -->
                            <td><?= $e($a->getIdUtilisateur()) ?></td>

                            <!-- COMMANDE -->
                            <td>#<?= $e($a->getIdCommande()) ?></td>

                            <!-- NOTE -->
                            <td class="note-stars">
                                <?= str_repeat("★", (int)$a->getNote()) ?>
                                <?= str_repeat("☆", 5 - (int)$a->getNote()) ?>
                            </td>

                            <!-- COMMENTAIRE -->
                            <td style="max-width:300px;">
                                <?= $e($a->getCommentaire()) ?>
                            </td>

                            <!-- STATUT -->
                            <td>
                                <?php if ((int)$a->getEstValide() === 1): ?>
                                    <span class="badge badge-valid">Validé</span>
                                <?php else: ?>
                                    <span class="badge badge-invalid">En attente</span>
                                <?php endif; ?>
                            </td>

                            <!-- ACTIONS -->
                            <td class="d-flex justify-content-center gap-2">

                                <!-- VALIDER -->
                                <?php if ((int)$a->getEstValide() === 0): ?>
                                    <form method="POST" action="index.php?page=valider_avis">
                                        <input type="hidden" name="id_avis" value="<?= $a->getIdAvis() ?>">
                                        <button class="btn btn-success btn-sm">
                                            <i class="bi bi-check"></i>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- SUPPRIMER -->
                                <form method="POST"
                                      action="index.php?page=supprimer_avis"
                                      onsubmit="return confirm('Supprimer cet avis ?');">

                                    <input type="hidden" name="id_avis" value="<?= $a->getIdAvis() ?>">

                                    <button class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7">
                            <div class="text-center p-4 text-muted">
                                Aucun avis trouvé
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