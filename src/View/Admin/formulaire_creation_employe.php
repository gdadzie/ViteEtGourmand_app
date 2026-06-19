<?php
// Assurer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un employé - Vite & Gourmand</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            max-width: 600px;

        }

        .form-container {
            background-color: #fff;
            padding: 25px 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
        }

        label {
            font-weight: 500;
        }

        .btn-primary {
            background-color: #aa6d27;
            border-color: #aa6d27;
        }

        .btn-primary:hover {
            background-color: #944f1e;
            border-color: #944f1e;
        }

        .alert {
            border-radius: 8px;
            font-size: 0.9rem;
        }

        select.form-select {
            margin-bottom: 15px;
        }

        @media (max-width: 576px) {
            .container {
                width: 95%;
                padding: 0 5px;
            }
        }
    </style>
</head>
<body>

<!-- FIL D'ARIANE / BOUTON RETOUR -->
<nav aria-label="breadcrumb" class="mb-3 p-4 mt-5">
    <ol class="breadcrumb breadcrumb-custom">
        <li class="breadcrumb-item">
            <a href="?page=espace_admin"><i class="bi bi-arrow-left"></i> Retour</a>
        </li>
    </ol>
</nav>

<div class="container p-5">
    <div class="form-container">
        <h2>Créer un employé</h2>

        <!-- Messages d'alerte -->
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php elseif (!empty($success)) : ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>

        <form method="post" action="?page=creation_employe">
            <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                    <label for="prenom" class="form-label">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" required>
                </div>
                <div class="col-md-6">
                    <label for="nom" class="form-label">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>

            <div class="mb-3">
                <label for="mdp" class="form-label">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" required>
            </div>

            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="text" class="form-control" id="telephone" name="telephone">
            </div>

            <div class="mb-3">
                <label for="id_ville" class="form-label">Ville</label>

                <select name="id_ville" id="id_ville" class="form-select" required>
                    <option value="">-- Sélectionner une ville --</option>

                    <?php
                    $selectedVille = $_POST['id_ville'] ?? '';
                    ?>

                    <?php foreach ($villes as $ville): ?>
                        <option
                                value="<?= $ville['id_ville'] ?>"
                                <?= $selectedVille == $ville['id_ville'] ? 'selected' : '' ?>
                        >
                            <?= htmlspecialchars($ville['nom_ville']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="id_role" class="form-label">Rôle</label>
                <select id="id_role" name="id_role" class="form-select" required>
                    <option value="">-- Choisir --</option>
                    <option value="2">Employé</option>
                    <option value="1">Utilisateur</option>
                </select>
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="est_actif" value="1" id="est_actif">
                <label class="form-check-label" for="est_actif">Actif</label>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Créer l'employé</button>
            </div>
        </form>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
