
<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);

$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');


?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des commandes</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --brand: #aa6d27;
            --brand-dark: #935f22;
            --bg: #f5f5f5;
            --muted: #6c757d;
            --border: rgba(0,0,0,.08);
            --shadow: 0 6px 18px rgba(0,0,0,.05);
            --radius: 18px;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: #212529;
            font-family: 'Segoe UI', sans-serif;
        }

        .container-custom { max-width: 1400px; }

        .topbar,
        .filter-card,
        .table-card {
            background: #fff;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .topbar {
            padding: 24px;
        }

        .brand-dot {
            width: 10px;
            height: 10px;
            margin-right: 8px;
            border-radius: 50%;
            background: var(--brand);
            display: inline-block;
        }

        .page-title {
            margin: 0;
            font-size: 1.7rem;
            font-weight: 700;
        }

        .muted { color: var(--muted); }

        /* Filtres */
        .filter-card {
            padding: 20px;
            margin-bottom: 24px;
        }

        .filter-title {
            margin-bottom: 16px;
            font-weight: 700;
        }

        .filter-title i { color: var(--brand); }

        .filter-label {
            display: block;
            margin-bottom: 6px;
            color: #495057;
            font-size: .82rem;
            font-weight: 600;
        }

        .filter-control {
            min-height: 42px;
            border: 1px solid #dee2e6;
            border-radius: 10px;
            font-size: .9rem;
        }

        .filter-control:focus {
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(170,109,39,.12);
        }

        .btn-reset {
            min-height: 42px;
            padding: 8px 16px;
            border: 1px solid var(--brand);
            border-radius: 10px;
            background: #fff;
            color: var(--brand);
            font-weight: 600;
        }

        .btn-reset:hover {
            background: #fdf2e7;
            color: var(--brand-dark);
        }

        .result-count {
            margin-top: 14px;
            color: var(--muted);
            font-size: .85rem;
        }

        .result-count strong { color: var(--brand); }

        /* Tableau */
        .table-card { overflow: hidden; }

        thead th {
            padding: 14px !important;
            border: 0 !important;
            background: var(--brand) !important;
            color: #fff !important;
            text-align: center;
            font-weight: 600;
            white-space: nowrap;
        }

        tbody td {
            padding: 14px !important;
            text-align: center;
            vertical-align: middle;
            font-size: .9rem;
        }

        tbody tr:hover { background: #fcfaf7; }

        /* Statuts */
        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: .8rem;
            font-weight: 600;
            white-space: nowrap;
        }

        .recue { background:#fff3cd; color:#856404; }
        .acceptee { background:#e7ddff; color:#5a33b1; }
        .payee { background:#e2e3e5; color:#333; }
        .en_preparation { background:#dbeafe; color:#0b5ed7; }
        .livree { background:#d1e7dd; color:#146c43; }
        .attente_retour { background:#f8d7da; color:#842029; }
        .terminee { background:#343a40; color:#fff; }

        /* Actions */
        .actions {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .actions .btn { border-radius: 8px; }

        .btn-detail {
            padding: 0;
            border: 0;
            background: none;
            color: var(--brand);
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: .2s;
        }

        .btn-detail:hover {
            color: var(--brand-dark);
            gap: 8px;
        }

        .btn-detail i { transition: transform .2s; }
        .btn-detail:hover i { transform: translateX(2px); }

        .small-text { font-size: .85rem; }
        .no-result { display: none; }

        @media (max-width: 767px) {
            .container-custom {
                padding-left: 12px;
                padding-right: 12px;
            }

            .topbar,
            .filter-card {
                padding: 16px;
            }

            .page-title { font-size: 1.4rem; }
        }
    </style>
</head>

<body>

<div class="container container-custom my-5">

    <!-- En-tête -->
    <div class="topbar mb-4">
        <div class="d-flex align-items-center mb-1">
            <span class="brand-dot"></span>
            <h1 class="page-title">Suivi des commandes</h1>
        </div>
        <div class="muted">Consultez et gérez les commandes associées à la boutique.</div>
    </div>

    <!-- Messages -->
    <?php if ($success): ?>
        <div class="alert alert-success"><?= $e($success) ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $e($error) ?></div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="filter-card">
        <div class="filter-title">
            <i class="bi bi-funnel me-1"></i> Filtrer les commandes
        </div>

        <div class="row g-3 align-items-end">

            <div class="col-12 col-md-6 col-lg-3">
                <label class="filter-label" for="filterReference">Référence de commande</label>
                <input type="text" id="filterReference" class="form-control filter-control"
                       placeholder="Ex. 125">
            </div>

            <div class="col-12 col-md-6 col-lg-3">
                <label class="filter-label" for="filterClient">Nom / identifiant client</label>
                <input type="text" id="filterClient" class="form-control filter-control"
                       placeholder="Rechercher un client">
            </div>

            <div class="col-12 col-md-6 col-lg-2">
                <label class="filter-label" for="filterDate">Date</label>
                <input type="date" id="filterDate" class="form-control filter-control">
            </div>

            <div class="col-12 col-md-6 col-lg-2">
                <label class="filter-label" for="filterStatus">Statut</label>
                <select id="filterStatus" class="form-select filter-control">
                    <option value="">Tous les statuts</option>
                    <option value="recue">Reçue</option>
                    <option value="acceptee">Acceptée</option>
                    <option value="payee">Payée</option>
                    <option value="en_preparation">En préparation</option>
                    <option value="livree">Livrée</option>
                    <option value="attente_retour">En attente de retour</option>
                    <option value="terminee">Terminée</option>
                </select>
            </div>

            <div class="col-12 col-lg-2">
                <button type="button" id="resetFilters" class="btn-reset w-100">
                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                </button>
            </div>

        </div>

        <div class="result-count">
            <strong id="resultCount">0</strong> commande(s) affichée(s)
        </div>
    </div>

    <!-- Tableau -->
    <div class="table-card">
        <div class="table-responsive">

            <table class="table align-middle mb-0">
                <thead>
                <tr>
                    <th>Commande</th>
                    <th>Menus</th>
                    <th>Client</th>
                    <th>Date création</th>
                    <th>Statut</th>
                    <th>Prix</th>
                    <th>Actions</th>
                </tr>
                </thead>

                <tbody id="ordersTableBody">

                <?php if (!empty($commandes)): ?>

                    <?php foreach ($commandes as $commande): ?>
                        <?php
                        $client = $clients[$commande->getIdUtilisateur()] ?? null;
                        ?>

                        <?php
                        $statutOriginal = trim($commande->getStatut());
                        $statut = strtolower($statutOriginal);

                        $statut = str_replace(
                                ['é','è','ê','ë','à','â','ä','ù','û','ü','ô','ö','î','ï','ç',' '],
                                ['e','e','e','e','a','a','a','u','u','u','o','o','i','i','c','_'],
                                $statut
                        );

                        $statut = trim($statut, '_');
                        ?>

                        <tr
                                class="order-row"
                                data-reference="<?= $e($commande->getIdCommande()) ?>"
                                data-client="<?= $e($commande->getIdUtilisateur()) ?>"
                                data-date="<?= $e($commande->getDateCreation()) ?>"
                                data-status="<?= $e($statut) ?>"
                        >

                            <td><strong>#<?= $e($commande->getIdCommande()) ?></strong></td>

                            <td><strong>#<?= $e($commande->getTitreMenu()) ?></strong></td>

                            <td class="small-text">
                                <?= $e($client
                                        ? $client->getPrenom() . ' ' . $client->getNom()
                                        : 'Client inconnu') ?>
                            </td>

                            <td class="small-text">
                                <?= $e(date('d/m/Y à H:i', strtotime($commande->getDateCreation()))) ?>
                            </td>

                            <td>
                                <span class="status <?= $statut ?>">
                                    <?= $e(ucfirst(str_replace('_', ' ', $statut))) ?>
                                </span>
                            </td>

                            <td>
                                <strong>
                                    <?= number_format($commande->getPrixTotal(), 2, ',', ' ') ?> €
                                </strong>
                            </td>

                            <td>
                                <div class="actions">

                                    <a
                                            href="index.php?page=detail_commande&id=<?= $commande->getIdCommande() ?>"
                                            class="btn-detail"
                                    >
                                        Voir le détail
                                        <i class="bi bi-arrow-right"></i>
                                    </a>

                                    <?php if ($statut === 'recue'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-success">Accepter</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'acceptee'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-primary">Préparer</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'payee'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-dark">Payée</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'en_preparation'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-warning">En préparation</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'livree'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-dark">Livrée</button>
                                        </form>
                                    <?php endif; ?>

                                    <?php if ($statut === 'attente_retour'): ?>
                                        <form method="POST" action="index.php?page=modifier_statut_commande">
                                            <input type="hidden" name="id" value="<?= $commande->getIdCommande() ?>">
                                            <button class="btn btn-sm btn-dark">Retour</button>
                                        </form>
                                    <?php endif; ?>

                                </div>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                <?php else: ?>

                    <tr>
                        <td colspan="7" class="text-center text-muted p-4">
                            Aucune commande trouvée
                        </td>
                    </tr>

                <?php endif; ?>

                <tr id="noFilterResult" class="no-result">
                    <td colspan="7" class="text-center text-muted p-4">
                        <i class="bi bi-search fs-4 d-block mb-2"></i>
                        Aucune commande ne correspond aux critères sélectionnés.
                    </td>
                </tr>

                </tbody>
            </table>

        </div>
    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {

        const reference = document.getElementById('filterReference');
        const client = document.getElementById('filterClient');
        const date = document.getElementById('filterDate');
        const status = document.getElementById('filterStatus');
        const reset = document.getElementById('resetFilters');
        const count = document.getElementById('resultCount');
        const noResult = document.getElementById('noFilterResult');
        const rows = document.querySelectorAll('.order-row');

        const normalize = value => value.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .trim();

        function filterOrders() {
            const ref = normalize(reference.value);
            const cli = normalize(client.value);
            const dat = date.value;
            const sta = normalize(status.value);
            let visible = 0;

            rows.forEach(row => {
                const match =
                    (!ref || normalize(row.dataset.reference).includes(ref)) &&
                    (!cli || normalize(row.dataset.client).includes(cli)) &&
                    (!dat || row.dataset.date.includes(dat)) &&
                    (!sta || normalize(row.dataset.status) === sta);

                row.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            count.textContent = visible;
            noResult.style.display = rows.length && !visible ? 'table-row' : 'none';
        }

        [reference, client].forEach(input =>
            input.addEventListener('input', filterOrders)
        );

        [date, status].forEach(input =>
            input.addEventListener('change', filterOrders)
        );

        reset.addEventListener('click', () => {
            reference.value = '';
            client.value = '';
            date.value = '';
            status.value = '';
            filterOrders();
        });

        filterOrders();
    });
</script>

</body>
</html>

