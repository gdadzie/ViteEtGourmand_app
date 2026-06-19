<?php
// Si la variable $success est définie par le contrôleur, on affiche un message de succès
// Si la variable $error est définie, on affiche un message d'erreur
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte — Vite & Gourmand</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-header i {
            font-size: 2rem;       /* taille de l'icône */
            vertical-align: middle; /* aligne verticalement avec le texte */
            margin-right: 8px;     /* espace entre icône et texte */
            color: #aa6d27;        /* couleur personnalisée */
        }


        .dashboard-header h1 {
            font-size: 1.8rem;
            color: #aa6d27; /* couleur personnalisée */
            font-weight: 600;
        }

        .form-card {
            background-color: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .form-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.12);
        }

        .card-title {
            font-size: 1.4rem;
            font-weight: 600;
            color: #aa6d27; /* couleur personnalisée */
            margin-bottom: 20px;
        }

        .btn-custom {
            background-color: #aa6d27;
            border-color: #aa6d27;
            color: #fff;
        }

        .btn-custom:hover {
            background-color: #945c22;
            border-color: #945c22;
        }

        @media (max-width: 576px) {
            .form-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="dashboard-header">
        <h1><i class="bi bi-person-plus me-2"></i>Créer un compte</h1>
        <p>Inscrivez-vous pour accéder à nos services</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6">
            <div class="form-card">

                <!-- Alertes -->
                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($success) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= htmlspecialchars($error) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=inscription" method="POST">
                    <div class="mb-3">
                        <label for="prenom" class="form-label">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" required>
                    </div>

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" required>
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
                        <input type="tel" class="form-control" id="telephone" name="telephone">
                    </div>

                    <div class="mb-3">
                        <label for="numero_rue" class="form-label">N°rue</label>
                        <input type="text" class="form-control" id="adresse" name="adresse">
                    </div>

                    <div class="mb-3">
                        <label for="nom_rue" class="form-label">Rue</label>
                        <input type="text" class="form-control" id="adresse" name="adresse">
                    </div>

                    <div class="mb-3">
                        <label for="code_postale" class="form-label">Code Postale</label>
                        <input type="text" class="form-control" id="adresse" name="adresse">
                    </div>

                    <div class="mb-3">
                        <label for="id_ville" class="form-label">Ville</label>
                        <input type="text" class="form-control" id="adresse" name="adresse">
                    </div>

                    <button type="submit" class="btn btn-custom w-100">S’inscrire</button>

                    <p class="text-center mt-3 mb-0">
                        Déjà un compte ? <a href="index.php?page=connexion">Connectez-vous</a>
                    </p>
                </form>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
