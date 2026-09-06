    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact — Vite & Gourmand</title>

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

        .contact-card {
            border-radius: 14px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }

        .contact-icon {
            font-size: 2.5rem;
            color: var(--vg-orange);
        }

        h3 {
            font-weight: 600;
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
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-5">

            <div class="card contact-card p-4">

                <!-- Icon -->
                <div class="text-center mb-3">
                    <i class="bi bi-envelope-paper contact-icon"></i>
                </div>

                <h3 class="text-center mb-4">Nous contacter</h3>

                <!-- Message succès -->
                <?php if (!empty($success)) : ?>
                    <div class="alert alert-success text-center">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>

                <!-- Message erreur -->
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger text-center">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- FORMULAIRE -->
                <form action="index.php?page=contact" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Votre email</label>
                        <input type="email"
                               class="form-control"
                               name="email"
                               placeholder="exemple@email.com"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text"
                               class="form-control"
                               name="titre"
                               placeholder="Sujet de votre message"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Votre message</label>
                        <textarea class="form-control"
                                  name="message"
                                  rows="5"
                                  placeholder="Écrivez votre message..."
                                  required></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Envoyer le message
                    </button>

                </form>

                <hr class="my-4">

                <p class="text-center mb-0">
                    <a href="index.php?page=home" class="btn btn-outline-secondary btn-sm mt-2">
                        retour à l'accueil
                    </a>
                </p>

            </div>

        </div>
    </div>
</div>
