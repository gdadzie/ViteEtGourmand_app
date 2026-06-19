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
    <title>Gestion des commandes</title>

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
            width: 10px;
            height: 10px;
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
            padding: 14px !important;
            text-align: center;
            white-space: nowrap;
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

        .status {
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 999px;
            display: inline-block;
            font-size: 0.8rem;
        }

        .recue { background:#fff3cd; color:#856404; }
        .acceptee { background:#e7ddff; color:#5a33b1; }
        .payee { background:#e2e3e5; color:#333; }
        .en_preparation { background:#dbeafe; color:#0b5ed7; }
        .livree { background:#d1e7dd; color:#146c43; }
        .attente_retour { background:#f8d7da; color:#842029; }
        .terminee { background:#343a40; color:white; }

        .actions {
            display: flex;
            gap: 6px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .small-text {
            font-size: 0.85rem;
        }
    </style>
</head>

<body>

<div class="container container-custom my-5">

    <div class="topbar mb-4 d-flex justify-content-between align-items-center">
        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="brand-dot"></span>
                <h1 class="page-title mb-0">Gestion des commandes</h1>
            </div>
            <div class="muted">Suivi et gestion des commandes clients</div>
        </div>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $e($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $e($error) ?></div>
    <?php endif; ?>

    <div class="table-card">
        <div class="table-responsive">

            <table class="table align-middle">

                <thead>
                <tr>
                    <th>Commande</th>
                    <th>Menus</th>
                    <th>Client</th>
                    <th>Date création</th>
                    <th>Statut</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody>

                <?php if (!empty($commandes)): ?>

                    <?php foreach ($commandes as $commande): ?>

                        <?php
                        // NORMALISATION ULTRA IMPORTANTE
                        $statut = strtolower(trim($commande->getStatut()));

                        $statut = str_replace(
                                ['é','è','ê','à',' '],
                                ['e','e','e','a','_'],
                                $statut
                        );
                        ?>

                        <tr>

                            <td><strong>#<?= $e($commande->getIdCommande()) ?></strong></td>
                            <td><strong>#<?= $e($commande->getTitreMenu()) ?></strong></td>

                            <td class="small-text">
                                <?= $e($commande->getIdUtilisateur()) ?>
                            </td>

                            <td class="small-text">
                                <?= $e($commande->getDateCreation()) ?>
                            </td>

                            <td>
                                <span class="status <?= $statut ?>">
                                    <?= $e(ucfirst(str_replace('_',' ',$statut))) ?>
                                </span>
                            </td>

                            <td>
                                <strong>
                                    <?= number_format($commande->getPrixTotal(), 2, ',', ' ') ?> €
                                </strong>
                            </td>

                            <td>
                                <div class="actions">

                                    <a href="index.php?page=detail_commande&id=<?= $commande->getIdCommande() ?>"
                                       class="btn btn-sm btn-outline-dark">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <?php if ($statut === 'recue'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-success">Accepter</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'acceptee'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-primary">Préparer</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'payee'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-dark">Payée</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'en_preparation'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-warning">En préparation</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'livree'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-dark">Livrée</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'attente_retour'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-dark">Retour</button>
                                        </form>
                                    <?php endif; ?>



                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="6" class="text-center text-muted p-4">
                            Aucune commande trouvée
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