<!DOCTYPE html>
<html lang="fr">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta charset="UTF-8">
    <title>Liste des utilisateurs</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        :root{
            --bg:#f5f5f5;
            --card:#ffffff;
            --text:#212529;
            --muted:#6c757d;

            --brand:#aa6d27;
            --brand-dark:#935f22;
            --tint:#fdf2e7;

            --border:rgba(0,0,0,.08);
            --shadow:0 6px 18px rgba(0,0,0,0.06);
            --shadow-hover:0 12px 28px rgba(0,0,0,0.10);
            --radius:16px;
        }

        body{
            font-family:'Segoe UI', sans-serif;
            background:var(--bg);
            color:var(--text);
            overflow-x:hidden;
        }

        .container{ max-width:1200px; }

        /* Header harmonisé */
        .page-header{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            padding:18px 18px;
        }
        .page-title{
            font-size:1.55rem;
            font-weight:700;
            margin:0;
            display:flex;
            align-items:center;
            gap:.6rem;
        }
        .brand-dot{
            width:10px; height:10px;
            border-radius:99px;
            background:var(--brand);
            display:inline-block;
        }
        .title-icon{
            color:var(--brand);
            font-size:1.25rem;
        }
        .page-subtitle{ margin:.35rem 0 0 0; color:var(--muted); }

        /* Filters card */
        .filters-card{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            padding:16px;
        }

        .btn-brand{
            background:var(--brand);
            border-color:var(--brand);
        }
        .btn-brand:hover{
            background:var(--brand-dark);
            border-color:var(--brand-dark);
        }

        /* Count chip */
        .count-chip{
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            border-radius:999px;
            padding:.35rem .7rem;
            background:#fff;
            border:1px solid var(--border);
            color:var(--muted);
            font-size:.9rem;
        }

        /* Table container harmonisé */
        .table-container{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            padding:0;
            box-shadow:var(--shadow);
            overflow:hidden;
        }

        table{ width:100%; margin:0; }

        thead th{
            background:var(--tint) !important;
            color:var(--text) !important;
            border-bottom:1px solid var(--border) !important;
            vertical-align:middle;
            text-align:center;
            font-weight:700;
            padding:14px 10px !important;
            white-space:nowrap;
        }

        tbody td{
            vertical-align:middle;
            text-align:center;
            padding:10px 8px !important;
            font-size:0.92rem;
        }

        tbody tr:hover{ background:#fff7ef; }

        .table-responsive{ overflow-x:auto; }

        /* Switch centré + jolie teinte */
        .form-switch .form-check-input{
            cursor:pointer;
        }
        .form-check-input:checked{
            background-color:var(--brand);
            border-color:var(--brand);
        }

        /* Retour “pill” comme les autres pages */
        .back-link{
            display:inline-flex;
            align-items:center;
            gap:.5rem;
            text-decoration:none;
            color: var(--muted);
            font-weight:700;
            padding:.55rem .9rem;
            border-radius:999px;
            border:1px solid rgba(0,0,0,.08);
            background: rgba(255,255,255,.8);
            box-shadow: 0 10px 22px rgba(0,0,0,.05);
            transition:.2s;
        }
        .back-link:hover{
            transform: translateY(-1px);
            border-color: rgba(170,109,39,.25);
            color:var(--text);
            background: rgba(255,255,255,.95);
        }

        /* Action icon */
        .action-icon{ font-size:1.1rem; }

        @media (max-width:576px){
            tbody td, thead th{
                font-size:0.85rem;
                padding:8px 6px !important;
            }
        }
    </style>
</head>

<body>

<?php
$e = fn($v) => htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');

$prenom   = $prenom   ?? ($_GET['prenom'] ?? '');
$nom      = $nom      ?? ($_GET['nom'] ?? '');
$email    = $email    ?? ($_GET['email'] ?? '');
$estActif = $estActif ?? (isset($_GET['est_actif']) && $_GET['est_actif'] !== '' ? (int)$_GET['est_actif'] : null);

$resetUrl = 'index.php?page=liste_des_utilisateurs';
?>

<div class="container my-4 my-md-5">

    <!-- RETOUR -->
    <a href="?page=espace_admin" class="back-link mb-3">
        <i class="bi bi-arrow-left"></i> Retour
    </a>
    <!-- Header -->
    <div class="page-header mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h2 class="page-title">
                <i class="bi bi-people title-icon"></i>
                Liste des utilisateurs
            </h2>
        </div>
        <p class="page-subtitle">Consultez et gérez les comptes (activation, suppression) et filtrez rapidement.</p>
    </div>

    <!-- Filters -->
    <div class="filters-card mb-3">
        <form method="get" action="index.php" class="row g-2 align-items-end">
            <input type="hidden" name="page" value="liste_des_utilisateurs">

            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Prénom</label>
                <input type="text" name="prenom" placeholder="Prénom" class="form-control" value="<?= $e($prenom) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Nom</label>
                <input type="text" name="nom" placeholder="Nom" class="form-control" value="<?= $e($nom) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Email</label>
                <input type="email" name="email" placeholder="Email" class="form-control" value="<?= $e($email) ?>">
            </div>

            <div class="col-12 col-md-3">
                <label class="form-label mb-1">Statut</label>
                <select name="est_actif" class="form-select">
                    <option value="" <?= ($estActif === null ? 'selected' : '') ?>>Tous les statuts</option>
                    <option value="1" <?= ($estActif === 1 ? 'selected' : '') ?>>Actifs</option>
                    <option value="0" <?= ($estActif === 0 ? 'selected' : '') ?>>Inactifs</option>
                </select>
            </div>

            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                <button type="submit" class="btn btn-brand text-white">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
                <a href="<?= $e($resetUrl) ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-counterclockwise"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Count -->
    <div class="d-flex justify-content-end mb-3">
        <span class="count-chip">
            <i class="bi bi-list-check"></i>
            <?= isset($utilisateurs) ? count($utilisateurs) : 0 ?> utilisateur(s) trouvé(s)
        </span>
    </div>

    <!-- Table -->
    <div class="table-container table-responsive">
        <table class="table table-hover align-middle">
            <thead>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Rôle</th>
                <th>Actif</th>
                <th>Date de création</th>
                <th>Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php if (empty($utilisateurs)): ?>
                <tr>
                    <td colspan="10" class="text-center py-4 text-muted">Aucun utilisateur trouvé</td>
                </tr>
            <?php else: ?>
                <?php foreach ($utilisateurs as $u): ?>
                    <tr>
                        <td><?= (int)$u->getIdUtilisateur() ?></td>
                        <td><?= $e($u->getPrenom()) ?></td>
                        <td><?= $e($u->getNom()) ?></td>
                        <td><?= $e($u->getEmail()) ?></td>
                        <td><?= $e($u->getTelephone()) ?></td>
                        <td><?= $e($u->getNumeroRue(). ' '.$u->getNomRue()) ?></td>

                        <td>
                            <?php
                            switch ((int)$u->getIdRole()) {
                                case 3: echo 'Admin'; break;
                                case 2: echo 'Employé'; break;
                                case 1: echo 'Client'; break;
                                default: echo 'Inconnu';
                            }
                            ?>
                        </td>

                        <td>
                            <form action="index.php?page=activer_utilisateur" method="post" class="m-0">
                                <input type="hidden" name="id" value="<?= (int)$u->getIdUtilisateur() ?>">
                                <input type="hidden" name="est_actif" value="<?= $u->getEstActif() ? 0 : 1 ?>">

                                <div class="form-check form-switch d-flex justify-content-center m-0">
                                    <input class="form-check-input"
                                           type="checkbox"
                                            <?= $u->getEstActif() ? 'checked' : '' ?>
                                           onclick="this.form.submit();">
                                </div>
                            </form>
                        </td>

                        <td><?= $e($u->getDateCreation()) ?></td>

                        <td>
                            <form action="index.php?page=supprimer_utilisateur" method="post"
                                  class="m-0"
                                  onsubmit="return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');">
                                <input type="hidden" name="id" value="<?= (int)$u->getIdUtilisateur() ?>">
                                <button type="submit" class="btn p-0 border-0 bg-transparent" title="Supprimer">
                                    <i class="bi bi-x-lg text-danger action-icon"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
