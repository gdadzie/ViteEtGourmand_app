<main class="contact-page py-4 py-md-5">
    <div class="container">
        <div class="row justify-content-center g-4 align-items-stretch">
            <div class="col-12 col-md-8 col-lg-6">
                <section class="card contact-card p-4 p-md-5" aria-labelledby="contact-title">
                    <div class="text-center mb-3">
                        <i class="bi bi-envelope-paper contact-icon" aria-hidden="true"></i>
                    </div>
                    <h1 id="contact-title" class="h3 text-center mb-4">Nous contacter</h1>

                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success text-center" role="status"><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger text-center" role="alert"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form action="index.php?page=contact" method="post">
                        <div class="mb-3">
                            <label for="email" class="form-label">Votre email</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="exemple@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="titre" class="form-label">Titre</label>
                            <input type="text" id="titre" class="form-control" name="titre" placeholder="Sujet de votre message" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message</label>
                            <textarea id="message" class="form-control" name="message" rows="5" placeholder="Écrivez votre message..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Envoyer le message</button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center mb-0"><a href="<?= htmlspecialchars($dashboardUrl ?? '?page=home') ?>" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a></p>
                </section>
            </div>

            <aside class="col-12 col-md-8 col-lg-4" aria-labelledby="hours-title">
                <section class="card contact-hours-card h-100 p-4 p-md-5">
                    <div class="text-center mb-3">
                        <i class="bi bi-clock-history contact-icon" aria-hidden="true"></i>
                    </div>
                    <h2 id="hours-title" class="h4 text-center mb-4">Nos horaires</h2>

                    <?php if (!empty($horaires)): ?>
                        <dl class="contact-hours-list mb-0">
                            <?php foreach ($horaires as $horaire): ?>
                                <div class="contact-hours-row">
                                    <dt><?= htmlspecialchars(ucfirst($horaire->getJour())) ?></dt>
                                    <dd>
                                        <?php if ($horaire->getEstFerme()): ?>
                                            <span class="badge text-bg-danger">Fermé</span>
                                        <?php else: ?>
                                            <?= htmlspecialchars(substr((string) $horaire->getHeureOuverture(), 0, 5)) ?>
                                            -
                                            <?= htmlspecialchars(substr((string) $horaire->getHeureFermeture(), 0, 5)) ?>
                                        <?php endif; ?>
                                    </dd>
                                </div>
                            <?php endforeach; ?>
                        </dl>
                    <?php else: ?>
                        <p class="text-center text-muted mb-0">Aucun horaire disponible.</p>
                    <?php endif; ?>
                </section>
            </aside>
        </div>
    </div>
</main>
