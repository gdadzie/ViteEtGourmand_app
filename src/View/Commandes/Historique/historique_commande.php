<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique commande</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f5f5f5; }
        h2 { font-size: 1.5rem; }
        th, td { vertical-align: middle; }
    </style>
</head>
<body>
<div class="container my-5">

    <h2 class="mb-4 p-5">Historique commande #<?= htmlspecialchars($idCommande ?? '-') ?></h2>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <table class="table table-striped text-center">
        <thead class="table-dark">
        <tr>
            <th>Date modification</th>
            <th>Ancien statut</th>
            <th>Nouveau statut</th>
            <th>Modifié par (ID utilisateur)</th>
            <th>Rôle</th>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($historique)): ?>
            <?php foreach ($historique as $h): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars(
                                isset($h['date_modification'])
                                        ? (is_object($h['date_modification']) ? $h['date_modification']->toDateTime()->format('Y-m-d H:i:s') : $h['date_modification'])
                                        : '-'
                        ) ?>
                    </td>
                    <td><?= htmlspecialchars($h['ancien_statut'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($h['nouveau_statut'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($h['modifie_par'] ?? 'Inconnu') ?></td>
                    <td><?= htmlspecialchars($h['role'] ?? '-') ?></td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="5">Aucune modification enregistrée</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <a href="index.php?page=gestion_des_commandes" class="btn btn-secondary mt-3">Retour aux commandes</a>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
