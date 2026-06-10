<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Exemple : données utilisateur déjà chargées par le contrôleur
$u = $u ?? null;

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes informations</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .card-box {
            border: 0;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.06);
        }

        .accent {
            color: #aa6d27;
        }

        .btn-accent {
            background: #aa6d27;
            border-color: #aa6d27;
            color: white;
        }

        .btn-accent:hover {
            background: #935f22;
            border-color: #935f22;
            color: white;
        }

        .topbar {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.04);
        }

        .icon-badge {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:#fdf2e7;
        }
    </style>
</head>

<body>

<div class="container my-5">

    <!-- TOP BAR -->
    <div class="topbar p-4 mb-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="icon-badge">
                <i class="bi bi-person-gear fs-4 accent"></i>
            </div>
            <div>
                <h3 class="mb-0">Mes informations</h3>
                <small class="text-muted">Modifier vos données personnelles</small>
            </div>
        </div>

        <!-- RETOUR -->
        <a href="index.php?page=espace_utilisateur" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- ALERTS -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success">
            <?= $e($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?= $e($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- FORMULAIRE -->
    <div class="card card-box p-4">

        <form method="POST" action="index.php?page=update_profil">

            <div class="row g-3">

                <!-- PRÉNOM -->
                <div class="col-md-6">
                    <label class="form-label">Prénom</label>
                    <input type="text"
                           name="prenom"
                           class="form-control"
                           value="<?= $e($u?->getPrenom() ?? '') ?>"
                           required>
                </div>

                <!-- NOM -->
                <div class="col-md-6">
                    <label class="form-label">Nom</label>
                    <input type="text"
                           name="nom"
                           class="form-control"
                           value="<?= $e($u?->getNom() ?? '') ?>"
                           required>
                </div>

                <!-- EMAIL -->
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           value="<?= $e($utilisateur?->getEmail() ?? '') ?>"
                           required>
                </div>

                <!-- TÉLÉPHONE -->
                <div class="col-md-6">
                    <label class="form-label">Téléphone</label>
                    <input type="text"
                           name="telephone"
                           class="form-control"
                           value="<?= $e($utilisateur?->getTelephone() ?? '') ?>">
                </div>

                <!-- NUMÉRO DE RUE -->
                <div class="col-md-2">
                    <label class="form-label">N°</label>
                    <input type="text"
                           name="numero_rue"
                           class="form-control"
                           value="<?= $e($utilisateur?->getNumeroRue() ?? '') ?>">
                </div>

                <!-- NOM DE RUE -->
                <div class="col-md-6">
                    <label class="form-label">Rue</label>
                    <input type="text"
                           name="nom_rue"
                           class="form-control"
                           value="<?= $e($utilisateur?->getNomRue() ?? '') ?>">
                </div>

                <!-- CODE POSTAL -->
                <div class="col-md-4">
                    <label class="form-label">Code postal</label>
                    <input type="text"
                           name="code_postal"
                           class="form-control"
                           value="<?= $e($utilisateur?->getCodePostal() ?? '') ?>">
                </div>

                <!-- VILLE -->
                <div class="col-md-6">
                    <label class="form-label">Ville</label>
                    <select name="id_ville" class="form-select">

                        <?php foreach (($villes ?? []) as $ville): ?>

                            <option
                                    value="<?= $ville['id_ville'] ?>"
                                    <?= ($u?->getIdVille() == $ville['id_ville']) ? 'selected' : '' ?>>
                                <?= $e($ville['nom_ville']) ?>
                            </option>

                        <?php endforeach; ?>

                    </select>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between">

                <a href="index.php?page=espace_utilisateur"
                   class="btn btn-outline-secondary">
                    Annuler
                </a>

                <button type="submit" class="btn btn-accent">
                    <i class="bi bi-check2-circle"></i> Mettre à jour
                </button>

            </div>

        </form>

    </div>

</div>

</body>
</html>