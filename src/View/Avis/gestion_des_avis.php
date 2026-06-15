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

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .container-custom { max-width: 1400px; }

        .topbar {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        .brand-dot {
            width: 10px; height: 10px;
            border-radius: 999px;
            background: #aa6d27;
            display: inline-block;
            margin-right: 8px;
        }

        .page-title { font-size: 1.7rem; font-weight: 700; }
        .muted { color: #6c757d; }

        .table-card {
            background: white;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        thead th {
            background-color: #aa6d27 !important;
            color: white !important;
            font-weight: 600;
            border: none !important;
            padding: 16px 14px !important;
            text-align: center;
        }

        tbody td {
            padding: 14px !important;
            text-align: center;
            vertical-align: middle;
            font-size: 0.9rem;
        }

        tbody tr:hover {
            background-color: #fcfaf7;
        }

        .badge-valid {
            background: #d1e7dd;
            color: #146c43;
            font-weight: 600;
        }

        .badge-invalid {
            background: #f8d7da;
            color: #842029;
            font-weight: 600;
        }

        .note-stars {
            color: #f5a623;
            font-weight: bold;
        }

        .btn-accent {
            background:#aa6d27;
            border-color:#aa6d27;
            color:white;
        }

        .btn-accent:hover {
            background:#935f22;
            border-color:#935f22;
            color:white;
        }
    </style>
</head>

<body>

<!-- FIL D'ARIANE / BOUTON RETOUR -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb breadcrumb-custom">
        <li class="breadcrumb-item">
            <a href="?page=espace_admin"><i class="bi bi-arrow-left"></i> Retour</a>
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