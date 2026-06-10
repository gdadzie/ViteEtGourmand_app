<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Détail commande</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .container-custom {
            max-width: 1100px;
        }

        .topbar {
            background: white;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }

        .brand {
            color: #aa6d27;
            font-weight: 700;
        }

        .card-box {
            border: none;
            border-radius: 16px;
            box-shadow: 0 6px 18px rgba(0,0,0,0.05);
        }

        label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #555;
        }

        .form-control[readonly] {
            background: #f8f9fa;
            border: 1px solid #eee;
        }

        .badge-status {
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 0.8rem;
        }

        .accent {
            color: #aa6d27;
            font-weight: 600;
        }

    </style>

</head>

<body>

<div class="container container-custom my-5">

    <!-- TOP -->
    <div class="topbar d-flex justify-content-between align-items-center">

        <div>
            <h4 class="mb-0 brand">
                Commande #<?= $e($commande->getIdCommande()) ?>
            </h4>
            <small class="text-muted">
                Créée le <?= $e($commande->getDateCreation()) ?>
            </small>
        </div>

        <a href="index.php?page=mes_commandes"
           class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Retour
        </a>

    </div>

    <!-- STATUT -->
    <div class="mb-3">

        <span class="badge bg-dark badge-status">
            <?= $e(ucfirst($commande->getStatut())) ?>
        </span>

        <span class="badge <?= $commande->getStatutPaiement()==='payé'?'bg-success':'bg-warning text-dark' ?> badge-status">

            <?= $e(ucfirst($commande->getStatutPaiement())) ?>

        </span>

    </div>

    <!-- FORM -->
    <form>

        <div class="row g-3">

            <!-- LIVRAISON -->
            <div class="col-md-6">

                <div class="card card-box p-3">

                    <h6 class="accent mb-3">Livraison</h6>

                    <div class="mb-2">
                        <label>Adresse</label>
                        <input class="form-control" readonly
                               value="<?= $e($commande->getAdresseLivraison()) ?>">
                    </div>

                    <div class="mb-2">
                        <label>Date livraison</label>
                        <input class="form-control" readonly
                               value="<?= $e($commande->getDateLivraison()) ?>">
                    </div>

                    <div class="mb-2">
                        <label>Heure</label>
                        <input class="form-control" readonly
                               value="<?= $e($commande->getHeureLivraison()) ?>">
                    </div>

                </div>

            </div>

            <!-- PAIEMENT -->
            <div class="col-md-6">

                <div class="card card-box p-3">

                    <h6 class="accent mb-3">Paiement</h6>

                    <div class="mb-2">
                        <label>Mode paiement</label>
                        <input class="form-control" readonly
                               value="<?= $e($commande->getModePaiement()) ?>">
                    </div>

                    <div class="mb-2">
                        <label>Statut paiement</label>
                        <input class="form-control" readonly
                               value="<?= $e($commande->getStatutPaiement()) ?>">
                    </div>

                    <div class="mb-2">
                        <label>Prix total</label>
                        <input class="form-control" readonly
                               value="<?= number_format($commande->getPrixTotal(),2,',',' ') ?> €">
                    </div>

                </div>

            </div>

            <!-- DETAILS -->
            <div class="col-md-6">

                <div class="card card-box p-3">

                    <h6 class="accent mb-3">Commande</h6>

                    <div class="mb-2">
                        <label>Menu</label>
                        <input class="form-control" readonly
                               value="#<?= $e($commande->getIdMenu()) ?>">
                    </div>

                    <div class="mb-2">
                        <label>Nombre de personnes</label>
                        <input class="form-control" readonly
                               value="<?= $commande->getNombrePersonnes() ?>">
                    </div>

                    <div class="mb-2">
                        <label>Mode réception</label>
                        <input class="form-control" readonly
                               value="<?= $e($commande->getModeReception()) ?>">
                    </div>

                </div>

            </div>

            <!-- ACTIONS -->
            <div class="col-md-6">

                <div class="card card-box p-3">

                    <h6 class="accent mb-3">Actions</h6>

                    <div class="d-flex gap-2 flex-wrap">

                        <?php if ($commande->getStatut()==='reçue'): ?>

                            <form method="POST"
                                  action="index.php?page=annuler_commande"
                                  onsubmit="return confirm('Annuler cette commande ?');">

                                <input type="hidden"
                                       name="id_commande"
                                       value="<?= $commande->getIdCommande() ?>">

                                <button class="btn btn-danger btn-sm">
                                    Annuler
                                </button>

                            </form>

                        <?php endif; ?>

                        <?php if ($commande->getStatut()==='terminée'): ?>

                            <a href="index.php?page=ajouter_avis&id_commande=<?= $commande->getIdCommande() ?>"
                               class="btn btn-warning btn-sm">
                                ⭐ Avis
                            </a>

                        <?php endif; ?>

                        <a href="index.php?page=historique_commande&id=<?= $commande->getIdCommande() ?>"
                           class="btn btn-outline-dark btn-sm">
                            Historique
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </form>

</div>

</body>
</html>