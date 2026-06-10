<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Alertes session
$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commandes - Vite & Gourmand</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        th, td {
            text-align: center;
            vertical-align: middle;
            font-size: 0.85rem;
        }

        th {
            background-color: #aa6d27;
            color: white;
        }

        .status-reçue { color: orange; font-weight: bold; }
        .status-acceptée { color: purple; font-weight: bold; }
        .status-en_preparation { color: teal; font-weight: bold; }
        .status-en_livraison { color: #0d6efd; font-weight: bold; }
        .status-livrée { color: green; font-weight: bold; }
        .status-terminée { color: black; font-weight: bold; }

        .badge-small {
            font-size: 0.75rem;
        }
    </style>
</head>

<body>

<div class="container my-4">

    <h3 class="text-center mb-3 p-3">Liste des commandes</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <div class="table-responsive">

        <table class="table table-striped align-middle text-center">

            <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Menu</th>
                <th>Personnes</th>
                <th>Total</th>
                <th>Réception</th>
                <th>Paiement</th>
                <th>Statut paiement</th>
                <th>Action paiement</th>
                <th>Date livraison</th>
                <th>Heure</th>
                <th>Statut commande</th>
                <th>Date</th>
                <th>Action</th>
                <th>Historique</th>
            </tr>
            </thead>

            <tbody>

            <?php if (!empty($commandes)): ?>

                <?php foreach ($commandes as $commande): ?>

                    <?php
                    $idCommande = $commande->getIdCommande();
                    $statut = $commande->getStatut();
                    $statutPaiement = $commande->getStatutPaiement() ?? 'unpaid';
                    ?>

                    <tr>

                        <td><?= $idCommande ?></td>

                        <td><?= htmlspecialchars($commande->getIdUtilisateur()) ?></td>

                        <td><?= htmlspecialchars($commande->getIdMenu()) ?></td>

                        <td><?= $commande->getNombrePersonnes() ?></td>

                        <td>
                            <strong>
                                <?= number_format($commande->getPrixTotal(), 2, ',', ' ') ?> €
                            </strong>
                        </td>

                        <td>
                            <span class="badge bg-secondary badge-small">
                                <?= htmlspecialchars($commande->getModeReception() ?? 'livraison') ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge bg-dark badge-small">
                                <?= htmlspecialchars($commande->getModePaiement() ?? 'paiement_livraison') ?>
                            </span>
                        </td>

                        <td>
                            <?php if ($statutPaiement === 'paid'): ?>
                                <span class="badge bg-success">Payé</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Non payé</span>
                            <?php endif; ?>
                        </td>

                        <!-- PAIEMENT ACTION -->
                        <td>
                            <?php if ($statutPaiement !== 'paid'): ?>
                                <form method="POST"
                                      action="index.php?page=valider_paiement_commande"
                                      onsubmit="return confirm('Confirmer le paiement ?');">

                                    <input type="hidden" name="id_commande" value="<?= $idCommande ?>">

                                    <button type="submit" class="btn btn-sm btn-warning">
                                        Valider paiement
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-success">OK</span>
                            <?php endif; ?>
                        </td>

                        <td><?= htmlspecialchars($commande->getDateLivraison()) ?></td>

                        <td><?= htmlspecialchars($commande->getHeureLivraison()) ?></td>

                        <td class="status-<?= str_replace(' ', '_', $statut) ?>">
                            <?= htmlspecialchars($statut) ?>
                        </td>

                        <td><?= htmlspecialchars($commande->getDateCreation()) ?></td>

                        <td>
                            <?php
                            $actions = [
                                    'reçue' => 'Accepter',
                                    'acceptée' => 'Préparer',
                                    'en_preparation' => 'Livraison',
                                    'en_livraison' => 'Livrée',
                                    'livrée' => 'Attente retour',
                                    'attente_retour' => 'Terminer'
                            ];
                            ?>

                            <?php if (isset($actions[$statut])): ?>

                                <form method="POST"
                                      action="index.php?page=modifier_statut_commande"
                                      onsubmit="return confirm('Changer le statut ?');">

                                    <input type="hidden" name="id" value="<?= $idCommande ?>">

                                    <button type="submit" class="btn btn-sm btn-success">
                                        <?= $actions[$statut] ?>
                                    </button>

                                </form>

                            <?php else: ?>
                                <span class="text-muted">Terminée</span>
                            <?php endif; ?>
                        </td>

                        <td>
                            <a href="index.php?page=historique_commande&id=<?= $idCommande ?>"
                               class="btn btn-sm btn-info">
                                <i class="bi bi-clock-history"></i>
                            </a>
                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php else: ?>

                <tr>
                    <td colspan="15">Aucune commande trouvée</td>
                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>