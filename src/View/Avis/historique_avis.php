<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes avis</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f5f5f5;
        }

        .container-custom {
            max-width: 1200px;
        }

        .topbar {
            background: #fff;
            border-radius: 18px;
            padding: 24px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: #aa6d27;
            display: inline-block;
            margin-right: 8px;
        }

        .page-title {
            font-size: 1.7rem;
            font-weight: 700;
        }

        .muted {
            color: #6c757d;
        }

        .review-card {
            background: #fff;
            border-radius: 18px;
            padding: 20px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
            transition: .2s ease;
            height: 100%;
        }

        .review-card:hover {
            transform: translateY(-3px);
        }

        .stars {
            color: #f5a623;
            font-size: 1rem;
        }

        .badge-status {
            font-size: 0.75rem;
            padding: 6px 10px;
            border-radius: 999px;
        }

        .valid {
            background: #d1e7dd;
            color: #146c43;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .menu-id {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>
</head>

<body>

<div class="container container-custom my-5">

    <!-- TOPBAR -->
    <div class="topbar mb-4 d-flex justify-content-between align-items-center">

        <div>
            <div class="d-flex align-items-center mb-1">
                <span class="brand-dot"></span>
                <h1 class="page-title mb-0">Mes avis</h1>
            </div>
            <div class="muted">
                Retrouvez tous vos avis laissés sur vos commandes.
            </div>
        </div>

        <a href="index.php?page=espace_utilisateur"
           class="btn btn-outline-dark rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Retour
        </a>

    </div>

    <!-- ALERTS -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $e($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $e($error) ?></div>
    <?php endif; ?>

    <!-- GRID -->
    <div class="row g-4">

        <?php if (!empty($avis)): ?>

            <?php foreach ($avis as $a): ?>

                <div class="col-12 col-md-6 col-lg-4">

                    <div class="review-card">

                        <!-- HEADER -->
                        <div class="d-flex justify-content-between align-items-start mb-2">

                            <div class="menu-id">
                                Commande #<?= $e($a->getIdCommande()) ?>
                            </div>

                            <?php if ((int)$a->getEstValide() === 1): ?>
                                <span class="badge-status valid">Validé</span>
                            <?php else: ?>
                                <span class="badge-status pending">En attente</span>
                            <?php endif; ?>

                        </div>

                        <!-- STARS -->
                        <div class="stars mb-2">
                            <?= str_repeat("★", (int)$a->getNote()) ?>
                            <?= str_repeat("☆", 5 - (int)$a->getNote()) ?>
                        </div>

                        <!-- COMMENTAIRE -->
                        <p class="mb-3 text-muted">
                            <?= $e($a->getCommentaire()) ?>
                        </p>

                        <!-- FOOTER -->
                        <div class="d-flex justify-content-between align-items-center">

                            <small class="text-muted">
                                <i class="bi bi-person-circle me-1"></i>
                                Vous
                            </small>

                        </div>

                    </div>

                </div>

            <?php endforeach; ?>

        <?php else: ?>

            <div class="col-12">
                <div class="text-center p-5 bg-white rounded-4 shadow-sm">
                    <i class="bi bi-chat-square-text" style="font-size:2.5rem;color:#aa6d27;"></i>
                    <h5 class="mt-3">Aucun avis</h5>
                    <p class="text-muted">Vous n’avez encore laissé aucun avis.</p>
                </div>
            </div>

        <?php endif; ?>

    </div>

</div>

</body>
</html>