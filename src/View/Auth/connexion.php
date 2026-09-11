<main class="auth-page">
    <div class="auth-page__overlay"></div>
    <div class="container position-relative py-5"><div class="row justify-content-center"><div class="col-12 col-md-8 col-lg-5">
            <section class="auth-card">
                <p class="auth-card__eyebrow">Vite &amp; Gourmand</p>
                <h1 class="auth-card__title">Connexion</h1>
                <p class="auth-card__intro">Accédez à votre espace personnel.</p>
                <?php if (!empty($error)): ?>

                    <div class="alert alert-danger" role="alert">
                    <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form  action="index.php?page=connexion" method="post">
                    <div class="mb-3">

                        <input id="email" type="email" class="form-control" name="email"  placeholder="e-mail" autocomplete="email" required>
                    </div>
                    <div class="mb-3">
                        <input id="mdp" type="password" class="form-control" name="mdp" placeholder="mot de passe" autocomplete="current-password" required>
                    </div>
                    <button class="btn btn-primary w-100" type="submit">Se connecter</button>
                </form>
                <div class="d-flex justify-content-between mt-4 small">
                    <a href="?page=reinitialiser_mot_de_passe">Mot de passe oublié ?</a>
                    <a href="?page=inscription">Créer un compte</a>
                </div>
            </section>
        </div>
    </div>
</main>
