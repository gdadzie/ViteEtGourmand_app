<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Vite & Gourmand</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', sans-serif;
        }

        :root {
            --vg-orange: #f28c28;
        }

        .login-card {
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .login-card h3 {
            font-weight: 600;
        }

        .login-icon {
            font-size: 2.5rem;
            color: var(--vg-orange);
        }

        .form-control {
            border-radius: 8px;
        }

        .form-control:focus {
            border-color: var(--vg-orange);
            box-shadow: 0 0 0 0.2rem rgba(242, 140, 40, 0.25);
        }

        .btn-primary {
            background-color: var(--vg-orange);
            border-color: var(--vg-orange);
            border-radius: 8px;
        }

        .btn-primary:hover {
            background-color: #e07f1f;
            border-color: #e07f1f;
        }

        .btn-outline-secondary {
            border-radius: 8px;
        }
    </style>
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card login-card p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-person-circle login-icon"></i>
                </div>

                <h3 class="text-center mb-4">Connexion</h3>

                <!-- Message d’erreur -->
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form action="index.php?page=connexion" method="POST">

                    <div class="mb-3">
                        <label for="email" class="form-label">Adresse email</label>
                        <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                placeholder="exemple@email.com"
                                required
                        >
                    </div>

                    <div class="mb-3">
                        <label for="mdp" class="form-label">Mot de passe</label>
                        <input
                                type="password"
                                class="form-control"
                                id="mdp"
                                name="mdp"
                                placeholder="••••••••"
                                required
                        >
                    </div>

                    <button type="submit" class="btn btn-primary w-100 mt-2">
                        Se connecter
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center mb-0">
                    Pas encore de compte ?
                    <br>
                    <a href="index.php?page=inscription" class="btn btn-outline-secondary btn-sm mt-2">
                        Créer un compte
                    </a>
                    <br>
                    <a href="index.php?page=home" class="btn btn-outline-secondary btn-sm mt-2">
                        retour à l'accueil
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
