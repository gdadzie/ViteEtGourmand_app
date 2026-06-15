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
            white-space: nowrap;
        }

        tbody td {
            padding: 14px !important;
            text-align: center;
            vertical-align: middle;
            border-color: #f1f1f1;
            font-size: 0.92rem;
        }

        tbody tr:hover { background-color: #fcfaf7; }

        .status {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 999px;
            display: inline-block;
            font-size: 0.8rem;
        }

        .reçue { background:#fff3cd; color:#856404; }
        .acceptée { background:#e7ddff; color:#5a33b1; }
        .en_preparation { background:#dbeafe; color:#0b5ed7; }
        .en_livraison { background:#d1fae5; color:#198754; }
        .livrée { background:#d1e7dd; color:#146c43; }
        .terminée { background:#343a40; color:white; }

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

        .btn-soft {
            border-radius:12px;
            padding:8px 14px;
            font-size:0.88rem;
        }

        .empty-state {
            padding:50px 20px;
            text-align:center;
        }

        .empty-state i {
            font-size:3rem;
            color:#aa6d27;
            opacity:0.7;
        }

        .empty-state h5 { margin-top:16px; font-weight:600; }

        .empty-state p { color:#6c757d; }

    </style>
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
                                <strong>#<?= $e($commande->getIdMenu()) ?></strong>
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
                                <?php if ($commande->getStatut() === 'terminée'): ?>
                                    <a class="btn btn-warning btn-sm rounded-pill"
                                       href="index.php?page=avis&id_commande=<?= $commande->getIdCommande() ?>">
                                        ⭐ Avis
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted small">Non disponible</span>
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