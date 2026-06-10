<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les horaires — Espace Employé</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Fonts PRO -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&family=Poppins:wght@600;700;800&display=swap">

    <style>

        :root{
            --brand:#aa6d27;
            --brand-dark:#8c5a20;
            --bg:#f5f5f5;
            --card:#ffffff;
            --border:rgba(0,0,0,.07);
            --shadow:0 10px 30px rgba(0,0,0,.08);
            --radius:18px;
        }

        body{
            background:var(--bg);
            font-family:'Inter', sans-serif;
        }

        h1,h2,h3{
            font-family:'Poppins', sans-serif;
            font-weight:800;
            letter-spacing:-0.3px;
        }

        /* Breadcrumb moderne */
        .back-link{
            display:inline-flex;
            align-items:center;
            gap:.5rem;
            text-decoration:none;
            color:#666;
            font-weight:600;
            padding:.55rem .9rem;
            border-radius:999px;
            border:1px solid var(--border);
            background:white;
            box-shadow:0 6px 16px rgba(0,0,0,.05);
            transition:.2s;
        }

        .back-link:hover{
            transform:translateY(-2px);
            color:#000;
        }

        /* Header */
        .dashboard-header{
            text-align:center;
            margin-bottom:40px;
        }

        .dashboard-header i{
            font-size:2.3rem;
            color:var(--brand);
        }

        .dashboard-header p{
            color:#6c757d;
        }

        /* Card */
        .horaires-card{
            background:var(--card);
            border-radius:var(--radius);
            padding:30px;
            box-shadow:var(--shadow);
            border:1px solid var(--border);
        }

        /* Tableau PRO */
        .table{
            border-radius:14px;
            overflow:hidden;
        }

        .table thead th{
            background:var(--brand);
            color:white;
            text-align:center;
            border:none;
        }

        .table tbody tr{
            transition:.15s;
        }

        .table tbody tr:hover{
            background:#fdf2e7;
        }

        .table td{
            vertical-align:middle;
        }

        input[type="time"]{
            border-radius:10px;
            border:1px solid var(--border);
            padding:6px;
        }

        input[type="checkbox"]{
            transform:scale(1.2);
            cursor:pointer;
        }

        /* Bouton premium */
        .btn-save{
            background:var(--brand);
            border:none;
            color:white;
            padding:12px 34px;
            border-radius:999px;
            font-weight:700;
            box-shadow:0 10px 25px rgba(170,109,39,.25);
            transition:.2s;
        }

        .btn-save:hover{
            background:var(--brand-dark);
            transform:translateY(-2px);
        }

        /* Responsive */
        @media(max-width:768px){
            .horaires-card{
                padding:18px;
            }
        }

    </style>
</head>

<body>

<div class="container my-5">

    <!-- RETOUR -->
    <a href="?page=espace_admin" class="back-link mb-4">
        <i class="bi bi-arrow-left"></i>
        Retour
    </a>

    <!-- HEADER -->
    <div class="dashboard-header">
        <i class="bi bi-clock-history"></i>
        <h1 class="mt-2">Modifier les horaires</h1>
        <p>Gérez les heures d’ouverture et de fermeture du service traiteur</p>
    </div>

    <!-- CARD -->
    <div class="horaires-card mx-auto">

        <form method="post" action="">

            <div class="table-responsive">
                <table class="table align-middle">

                    <thead>
                    <tr>
                        <th>Jour</th>
                        <th>Ouverture</th>
                        <th>Fermeture</th>
                        <th>Fermé</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php foreach ($horaires as $horaire): ?>
                        <tr>

                            <td class="fw-bold text-center">
                                <?= htmlspecialchars($horaire->getJour()) ?>
                            </td>

                            <td class="text-center">
                                <input type="time"
                                       name="horaires[<?= $horaire->getIdHoraire() ?>][ouverture]"
                                       value="<?= $horaire->getHeureOuverture() ?? '09:00' ?>"
                                        <?= $horaire->getEstFerme() ? 'disabled' : '' ?>
                                       class="form-control text-center">
                            </td>

                            <td class="text-center">
                                <input type="time"
                                       name="horaires[<?= $horaire->getIdHoraire() ?>][fermeture]"
                                       value="<?= $horaire->getHeureFermeture() ?? '18:00' ?>"
                                        <?= $horaire->getEstFerme() ? 'disabled' : '' ?>
                                       class="form-control text-center">
                            </td>

                            <td class="text-center">
                                <input type="checkbox"
                                       name="horaires[<?= $horaire->getIdHoraire() ?>][est_ferme]"
                                       value="1"
                                        <?= $horaire->getEstFerme() ? 'checked' : '' ?>
                                       onchange="toggleDisabled(this, <?= $horaire->getIdHoraire() ?>)">
                            </td>

                        </tr>
                    <?php endforeach; ?>
                    </tbody>

                </table>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn-save">
                    Enregistrer
                </button>
            </div>

        </form>
    </div>
</div>

<script>
    function toggleDisabled(checkbox, id) {
        const ouverture = document.querySelector(`input[name='horaires[${id}][ouverture]']`);
        const fermeture = document.querySelector(`input[name='horaires[${id}][fermeture]']`);

        ouverture.disabled = checkbox.checked;
        fermeture.disabled = checkbox.checked;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
