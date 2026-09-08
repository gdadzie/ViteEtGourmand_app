<main class="contact-inbox client-inbox py-4 py-md-5">
    <div class="container">
        <div class="inbox-header mb-4 p-4 p-md-5">
            <div>
                <p class="text-uppercase small fw-bold mb-2">Espace client</p>
                <h1 class="h2 mb-2"><i class="bi bi-chat-dots me-2"></i>Ma messagerie</h1>
                <p class="mb-0 text-muted">Retrouvez vos messages et les réponses de l’équipe Vite &amp; Gourmand.</p>
            </div>
            <a href="<?= \View\View::dashboardUrl() ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i>Tableau de bord
            </a>
        </div>

        <?php if (!empty($success)): ?><div class="alert alert-success" role="status"><?= htmlspecialchars($success) ?></div><?php endif; ?>
        <?php if (!empty($error)): ?><div class="alert alert-danger" role="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>

        <?php if (empty($messages)): ?>
            <section class="empty-inbox text-center p-5">
                <i class="bi bi-chat-square-text fs-1 d-block mb-3"></i>
                <h2 class="h4">Aucune conversation</h2>
                <p class="text-muted mb-3">Envoyez votre première demande depuis notre formulaire de contact.</p>
                <a href="?page=contact" class="btn btn-primary">Nous contacter</a>
            </section>
        <?php else: ?>
            <div class="row g-4">
                <?php foreach ($messages as $message): ?>
                    <?php $hasTeamExchange = !empty(array_filter($message['exchanges'], static fn (array $exchange): bool => $exchange['auteur'] === 'equipe')); ?>
                    <section class="col-12">
                        <article class="message-card p-4">
                            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mb-4">
                                <div>
                                    <h2 class="h5 mb-1"><?= htmlspecialchars($message['titre_message']) ?></h2>
                                    <small class="text-muted">Conversation commencée le <?= htmlspecialchars((string) $message['date_envoi']) ?></small>
                                </div>
                                <span class="badge align-self-sm-start <?= !empty($message['est_traite']) ? 'text-bg-success' : 'text-bg-warning' ?>">
                                    <?= !empty($message['est_traite']) ? 'Traité' : 'En attente' ?>
                                </span>
                            </div>

                            <div class="chat-thread">
                                <div class="chat-bubble chat-client">
                                    <small>Vous</small>
                                    <p><?= nl2br(htmlspecialchars($message['contenu_message'])) ?></p>
                                </div>

                                <?php if (!empty($message['reponse']) && !$hasTeamExchange): ?>
                                    <div class="chat-bubble chat-team">
                                        <small>Vite &amp; Gourmand</small>
                                        <p><?= nl2br(htmlspecialchars($message['reponse'])) ?></p>
                                    </div>
                                <?php endif; ?>

                                <?php foreach ($message['exchanges'] as $exchange): ?>
                                    <div class="chat-bubble <?= $exchange['auteur'] === 'equipe' ? 'chat-team' : 'chat-client' ?>">
                                        <small><?= $exchange['auteur'] === 'equipe' ? 'Vite & Gourmand' : 'Vous' ?> · <?= htmlspecialchars((string) $exchange['date_envoi']) ?></small>
                                        <p><?= nl2br(htmlspecialchars($exchange['contenu'])) ?></p>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <form action="index.php?page=ma_messagerie" method="post" class="reply-form mt-4">
                                <input type="hidden" name="message_id" value="<?= (int) $message['id'] ?>">
                                <label for="client-reply-<?= (int) $message['id'] ?>" class="form-label fw-semibold">Répondre dans cette conversation</label>
                                <textarea id="client-reply-<?= (int) $message['id'] ?>" name="reply" class="form-control" rows="3" maxlength="5000" required placeholder="Écrivez votre réponse…"></textarea>
                                <button type="submit" class="btn btn-primary mt-3"><i class="bi bi-send me-1"></i>Envoyer</button>
                            </form>
                        </article>
                    </section>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>
