<main class="contact-inbox py-4 py-md-5">
    <div class="container">
        <div class="inbox-header mb-4 p-4 p-md-5">
            <div>
                <p class="text-uppercase small fw-bold mb-2">Espace équipe</p>
                <h1 class="h2 mb-2"><i class="bi bi-envelope-open me-2"></i>Messages clients</h1>
                <p class="mb-0 text-muted">Consultez les demandes, répondez aux clients et suivez leur traitement.</p>
            </div>
            <a href="<?= htmlspecialchars($dashboardUrl ?? '?page=home') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Tableau de bord
            </a>
        </div>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success" role="status"><?= htmlspecialchars($success) ?></div>
        <?php endif; ?>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($messages)): ?>
            <section class="empty-inbox text-center p-5">
                <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                <h2 class="h4">Aucun message à traiter</h2>
                <p class="text-muted mb-0">Les nouvelles demandes envoyées depuis le formulaire de contact apparaîtront ici.</p>
            </section>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($messages as $message): ?>
                    <?php $isTreated = (bool) $message['est_traite']; ?>
                    <div class="col-12">
                        <article class="message-card <?= $isTreated ? 'is-treated' : '' ?> p-4">
                            <div class="d-flex flex-column flex-md-row justify-content-between gap-3 mb-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 flex-wrap mb-2">
                                        <h2 class="h5 mb-0"><?= htmlspecialchars($message['titre_message']) ?></h2>
                                        <span class="badge <?= $isTreated ? 'text-bg-success' : 'text-bg-warning' ?>">
                                            <?= $isTreated ? 'Traité' : 'À traiter' ?>
                                        </span>
                                    </div>
                                    <a href="mailto:<?= rawurlencode($message['email']) ?>" class="message-email">
                                        <i class="bi bi-person-circle me-1"></i><?= htmlspecialchars($message['email']) ?>
                                    </a>
                                </div>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= htmlspecialchars((string) $message['date_envoi']) ?></small>
                            </div>

                            <div class="message-body mb-4"><?= nl2br(htmlspecialchars($message['contenu_message'])) ?></div>

                            <?php if (!empty($message['reponse'])): ?>
                                <div class="sent-reply mb-4">
                                    <strong><i class="bi bi-reply-fill me-1"></i>Réponse envoyée</strong>
                                    <p class="mb-0 mt-2"><?= nl2br(htmlspecialchars($message['reponse'])) ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if (!$isTreated): ?>
                                <form action="index.php?page=messagerie_contact" method="post" class="reply-form">
                                    <input type="hidden" name="message_id" value="<?= (int) $message['id'] ?>">
                                    <label for="reply-<?= (int) $message['id'] ?>" class="form-label fw-semibold">Répondre au client</label>
                                    <textarea id="reply-<?= (int) $message['id'] ?>" name="reply" class="form-control" rows="4" maxlength="5000" placeholder="Rédigez votre réponse…"></textarea>
                                    <div class="d-flex flex-column flex-sm-row gap-2 mt-3">
                                        <button type="submit" name="action" value="reply" class="btn btn-primary">
                                            <i class="bi bi-send me-1"></i>Envoyer la réponse
                                        </button>
                                        <button type="submit" name="action" value="mark-treated" class="btn btn-outline-secondary">
                                            <i class="bi bi-check2-circle me-1"></i>Marquer comme traité
                                        </button>
                                    </div>
                                </form>
                            <?php elseif (!empty($message['date_traitement'])): ?>
                                <small class="text-success"><i class="bi bi-check2-circle me-1"></i>Traité le <?= htmlspecialchars((string) $message['date_traitement']) ?></small>
                            <?php endif; ?>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
