<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta charset="UTF-8">
    <title>Liste des employés</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .container { max-width: 1200px; }

        h2 {
            font-size: 1.5rem;
            text-align: center;
        }

        .table-container {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        table {
            width: 100%;
        }

        th {
            background-color: #aa6d27;
            color: white;
            vertical-align: middle;
            text-align: center;
        }

        td {
            vertical-align: middle;
            text-align: center;
            padding: 6px 4px;
            font-size: 0.9rem;
        }

        .btn-action {
            margin: 0 3px;
        }

        tbody tr:hover {
            background-color: #fdf2e7;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 576px) {
            td, th {
                font-size: 0.85rem;
                padding: 4px 3px;
            }
            .btn-action {
                margin: 0 1px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
<?php
// Helpers
$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');

// Valeurs de filtre venant du contrôleur (ou fallback GET)
$prenom   = $prenom   ?? ($_GET['prenom'] ?? '');
$nom      = $nom      ?? ($_GET['nom'] ?? '');
$email    = $email    ?? ($_GET['email'] ?? '');
$estActif = $estActif ?? (isset($_GET['est_actif']) && $_GET['est_actif'] !== '' ? (int)$_GET['est_actif'] : null);

// URL reset (sans paramètres)
$resetUrl = 'index.php?page=liste_des_employes';
?>

<div class="container my-5 p-5">
    <h2 class="mb-4">Liste des employés</h2>

    <!-- FILTRES -->
    <form method="get" action="index.php" class="mb-3 row g-2">
        <input type="hidden" name="page" value="liste_des_employes">

        <div class="col-sm-3">
            <input
                    type="text"
                    name="prenom"
                    placeholder="Prénom"
                    class="form-control"
                    value="<?= $e($prenom) ?>"
            >
        </div>

        <div class="col-sm-3">
            <input
                    type="text"
                    name="nom"
                    placeholder="Nom"
                    class="form-control"
                    value="<?= $e($nom) ?>"
            >
        </div>

        <div class="col-sm-3">
            <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    class="form-control"
                    value="<?= $e($email) ?>"
            >
        </div>

        <div class="col-sm-3">
            <select name="est_actif" class="form-select">
                <option value="" <?= ($estActif === null ? 'selected' : '') ?>>Tous les statuts</option>
                <option value="1" <?= ($estActif === 1 ? 'selected' : '') ?>>Actifs</option>
                <option value="0" <?= ($estActif === 0 ? 'selected' : '') ?>>Inactifs</option>
            </select>
        </div>

        <div class="col-12 d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-funnel"></i> Filtrer
            </button>
            <a href="<?= $e($resetUrl) ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
            </a>
        </div>
    </form>

    <div class="d-flex justify-content-end mb-2">
        <span class="text-muted"><?= isset($utilisateurs) ? count($utilisateurs) : 0 ?> employé(s) trouvé(s)</span>
    </div>

    <!-- TABLE -->
    <div class="table-container table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Rôle</th>
                <th>Actif</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php if (empty($utilisateurs)): ?>
                <tr>
                    <td colspan="10" class="text-center">Aucun employé trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= (int)$u->getIdUtilisateur() ?></td>
                        <td><?= $e($u->getPrenom()) ?></td>
                        <td><?= $e($u->getNom()) ?></td>
                        <td><?= $e($u->getEmail()) ?></td>
                        <td><?= $e($u->getTelephone()) ?></td>
                        <td><?= $e($u->getAdresse()) ?></td>

                        <td>
                            <?php
                            // Correction selon ton code : admin=3, employé=2, client=1
                            switch ((int)$u->getIdRole()) {
                                case 3: echo 'Admin'; break;
                                case 2: echo 'Employé'; break;
                                case 1: echo 'Client'; break;
                                default: echo 'Inconnu';
                            }
                            ?>
                        </td>

                        <td>
                            <form action="index.php?page=activer_utilisateur" method="post" class="m-0">
                                <input type="hidden" name="id" value="<?= (int)$u->getIdUtilisateur() ?>">
                                <input type="hidden" name="est_actif" value="<?= $u->getEstActif() ? 0 : 1 ?>">

                                <div class="form-check form-switch d-flex justify-content-center m-0">
                                    <input class="form-check-input"
                                           type="checkbox"
                                            <?= $u->getEstActif() ? 'checked' : '' ?>
                                           onclick="this.form.submit();">
                                </div>
                            </form>
                        </td>

                        <td><?= $e($u->getDateCreation()) ?></td>

                        <td>
                            <form action="index.php?page=supprimer_utilisateur" method="post"
                                  class="m-0"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                <input type="hidden" name="id" value="<?= (int)$u->getIdUtilisateur() ?>">
                                <button type="submit" class="btn p-0 border-0 bg-transparent">
                                    <i class="bi bi-x-lg text-danger action-icon"></i>
                                </button>
                            </form>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
