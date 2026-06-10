
<footer class="footer-vg pt-5 pb-3 mt-5">
    <div class="container-fluid px-3 px-md-4">

        <div class="footer-topbar mb-4">
            <span class="footer-dot"></span>
            <span class="footer-topbar-text">Vite &amp; Gourmand — Traiteur • Bordeaux</span>
        </div>

        <div class="row g-4">

            <div class="col-12 col-lg-4">
                <h5 class="footer-title mb-3 text-center text-md-start">
                    <i class="bi bi-clock me-2"></i>Horaires
                </h5>

                <?php if (!empty($horaires)): ?>
                    <div class="footer-card">
                        <table class="table footer-table mb-0">
                            <tbody>
                            <?php foreach ($horaires as $horaire): ?>
                                <tr>
                                    <td class="fw-semibold text-capitalize">
                                        <?= htmlspecialchars($horaire->getJour()) ?>
                                    </td>
                                    <td class="text-md-end">
                                        <?php if ($horaire->getEstFerme()): ?>
                                            <span class="badge badge-closed">Fermé</span>
                                        <?php else: ?>
                                            <span class="time-pill">
                        <?= $horaire->getHeureOuverture() ? substr($horaire->getHeureOuverture(), 0, 5) : '—' ?>
                        <span class="sep">–</span>
                        <?= $horaire->getHeureFermeture() ? substr($horaire->getHeureFermeture(), 0, 5) : '—' ?>
                      </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="footer-card">
                        <p class="text-center mb-0">Aucun horaire disponible.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="col-12 col-lg-4">
                <h5 class="footer-title mb-3 text-center text-md-start">
                    <i class="bi bi-telephone me-2"></i>Contact
                </h5>

                <div class="footer-card">
                    <ul class="footer-list mb-0">
                        <li class="d-flex gap-2 align-items-start">
                            <span class="footer-icon"><i class="bi bi-geo-alt"></i></span>
                            <span>12 rue des Gourmets, 33000 Bordeaux</span>
                        </li>
                        <li class="d-flex gap-2 align-items-start">
                            <span class="footer-icon"><i class="bi bi-envelope"></i></span>
                            <span>contact@viteetgourmand.fr</span>
                        </li>
                        <li class="d-flex gap-2 align-items-start">
                            <span class="footer-icon"><i class="bi bi-telephone"></i></span>
                            <span>+33 5 12 34 56 78</span>
                        </li>
                    </ul>

                    <div class="mt-3 d-flex justify-content-start justify-content-md-start gap-2 flex-wrap">
                        <a href="?page=contact" class="btn btn-footer-outline">Nous contacter</a>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <h5 class="footer-title mb-3 text-center text-md-start">
                    <i class="bi bi-share me-2"></i>Suivez-nous
                </h5>

                <div class="footer-card">
                    <p class="text-center text-md-start footer-muted mb-3">
                        Actus, nouveautés, menus de saison et coulisses de l’atelier.
                    </p>

                    <div class="d-flex justify-content-center justify-content-md-start gap-2">
                        <a href="https://facebook.com/viteetgourmand" class="social-btn" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                        <a href="https://instagram.com/viteetgourmand" class="social-btn" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="https://twitter.com/viteetgourmand" class="social-btn" aria-label="Twitter"><i class="bi bi-twitter"></i></a>
                    </div>

                    <div class="mt-4 footer-newsletter">
                        <div class="footer-muted mb-2 fw-semibold">Newsletter</div>
                        <form class="d-flex gap-2" method="post" action="#">
                            <input type="email" class="form-control footer-input" placeholder="Votre email" aria-label="Votre email">
                            <button type="submit" class="btn btn-footer">OK</button>
                        </form>
                        <div class="footer-muted small mt-2">Pas de spam. Désinscription à tout moment.</div>
                    </div>
                </div>
            </div>

        </div>

        <hr class="footer-divider my-4">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <div class="small footer-muted text-center text-md-start">
                &copy; 2025 Vite &amp; Gourmand — Tous droits réservés
            </div>

            <div class="small text-center text-md-end">
                <a class="footer-link" href="?page=mentions_legales">Mentions légales</a>
                <span class="footer-sep">•</span>
                <a class="footer-link" href="?page=confidentialite">Confidentialité</a>
            </div>
        </div>

    </div>
</footer>
