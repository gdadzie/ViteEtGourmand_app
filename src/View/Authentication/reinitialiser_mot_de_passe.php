<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe — Vite & Gourmand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/formulaires/formulaire.css">
</head>
<body>
<main class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <section class="card login-card p-4">
                <h1 class="text-center mb-4">Réinitialiser le mot de passe</h1>

                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success" role="status"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form action="index.php?page=reinitialiser_mot_de_passe" method="POST">
                    <?php if (!empty($token)) : ?>
                        <input type="hidden" name="token" value="<?= htmlspecialchars($token, ENT_QUOTES, 'UTF-8') ?>">
                        <div class="mb-3">
                            <label for="mdp" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" id="mdp" name="mdp" minlength="10" pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[^A-Za-z0-9]).{10,}" required>
                            <div class="form-text">10 caractères minimum, avec majuscule, minuscule, chiffre et caractère spécial.</div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Définir mon nouveau mot de passe</button>
                    <?php else : ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Adresse e-mail</label>
                            <input type="email" class="form-control" id="email" name="email" autocomplete="email" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer le lien de réinitialisation</button>
                    <?php endif; ?>
                </form>
                <p class="text-center mt-4 mb-0"><a href="index.php?page=connexion">Retour à la connexion</a></p>
            </section>
        </div>
    </div>
</main>
</body>
</html>
