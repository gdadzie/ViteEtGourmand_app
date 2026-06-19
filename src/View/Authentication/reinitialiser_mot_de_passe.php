<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — Vite & Gourmand</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/formulaires/formulaire.css">

</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">

            <div class="card login-card p-4">
                <div class="text-center mb-3">
                    <i class="bi bi-person-circle login-icon"></i>
                </div>

                <h1 class="text-center mb-4">Réinitialiser le mot de passe</h1>
                <?php if (isset($success)) : ?>
                    <p style="color:green;">
                        <?= htmlspecialchars($success) ?>
                    </p>
                <?php endif; ?>

                <?php if (isset($error)) : ?>
                    <p style="color:red;">
                        <?= htmlspecialchars($error) ?>
                    </p>
                <?php endif; ?>

                <!-- Message d’erreur -->
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>



                <form action="index.php?page=reinitialiser_mot_de_passe" method="POST">

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
                        réinitialiser mon mot de passe
                    </button>
                </form>

                <hr class="my-4">

                <p class="text-center mb-0">
                    <br>
                    <a href="index.php?page=connexion" class="btn btn-outline-secondary btn-sm mt-2">
                        retour à la connexion
                    </a>

                </p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

