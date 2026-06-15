<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Employé</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            /* Charte harmonisée application */
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
            background-color:var(--bg);
            font-family:'Segoe UI', sans-serif;
            color:var(--text);
            overflow-x:hidden;
        }

        /* Header harmonisé */
        .dash-header{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            padding:22px 22px;
        }
        .dash-header h1{
            font-size:1.65rem;
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
        .header-icon{
            color:var(--brand);
            font-size:1.35rem;
        }
        .dash-header p{
            margin:6px 0 0 0;
            color:var(--muted);
        }

        /* Liens cartes */
        a.card-link{
            text-decoration:none;
            color:inherit;
            display:block;
            height:100%;
        }
        a.card-link:focus-visible{
            outline:3px solid rgba(170,109,39,.35);
            outline-offset:4px;
            border-radius:var(--radius);
        }

        /* Cartes harmonisées */
        .card-emp{
            border-radius:var(--radius);
            background:var(--card);
            border:1px solid var(--border);
            box-shadow:var(--shadow);
            padding:22px;
            transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            height:100%;
        }
        .card-emp:hover{
            transform:translateY(-4px);
            box-shadow:var(--shadow-hover);
            border-color:rgba(170,109,39,.25);
        }

        .icon-badge{
            width:46px;
            height:46px;
            border-radius:14px;
            display:flex;
            align-items:center;
            justify-content:center;
            background:var(--tint);
            flex:0 0 auto;
        }
        .icon-badge i{
            font-size:1.45rem;
            color:var(--brand);
        }

        .card-title{
            font-size:1.05rem;
            font-weight:700;
            margin:0;
        }
        .card-text{
            font-size:.95rem;
            color:var(--muted);
            margin:.25rem 0 0 0;
        }
        .card-cta{
            margin-top:14px;
            display:flex;
            align-items:center;
            gap:.5rem;
            color:var(--brand);
            font-weight:600;
            font-size:.95rem;
        }
        .card-cta i{
            font-size:1rem;
            color:var(--brand);
        }
    </style>
</head>


<body>
<?php include __DIR__ . '/../partials/menu.php'; ?>


<div class="container my-5">

    <!-- HEADER -->
    <div class="dash-header mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="text-start">
                <i class="bi bi-briefcase header-icon"></i>
                Espace Employé
            </h1>
        </div>
        <p class="text-start">
            Bienvenue, <?= htmlspecialchars($_SESSION['prenom'] ?? 'Employé') ?> !
        </p>
    </div>

    <!-- CARTES (données inchangées : mêmes liens / titres / textes) -->
    <div class="row g-4">

        <!-- Carte 1 : Gestion des avis -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="?page=creation_employe" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-chat-square-text"></i></div>
                        <div>
                            <h5 class="card-title">Gestion des avis</h5>
                            <p class="card-text">Validation ou refus des avis reçu des utilisateurs.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 2 : Modifier les horaires -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="?page=modification_horaire" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-clock-history"></i></div>
                        <div>
                            <h5 class="card-title">Modifier les horaires</h5>
                            <p class="card-text">Mettez à jour les horaires d’ouverture et fermeture du service traiteur.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 3 : Modifier contacts -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=gestion_avis" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-telephone"></i></div>
                        <div>
                            <h5 class="card-title">Gestion des avis</h5>
                            <p class="card-text">Validez les avis des clients</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 4 : Voir les menus -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=gestion_menus" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-card-list"></i></div>
                        <div>
                            <h5 class="card-title">Gestion des menus</h5>
                            <p class="card-text">Consultez et gérez les menus proposés par le service traiteur.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 5 : Voir les commandes -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=gestion_des_commandes" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-receipt"></i></div>
                        <div>
                            <h5 class="card-title">Voir les commandes</h5>
                            <p class="card-text">Accédez à l’historique des commandes et suivez les ventes.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <!-- Carte 6 : Statistiques / CA -->
        <div class="col-12 col-md-6 col-xl-4">
            <a href="index.php?page=sales_stats" class="card-link">
                <div class="card-emp">
                    <div class="d-flex align-items-start gap-3">
                        <div class="icon-badge"><i class="bi bi-graph-up"></i></div>
                        <div>
                            <h5 class="card-title">Statistiques / CA</h5>
                            <p class="card-text">Analysez le chiffre d’affaires par menu ou période.</p>
                            <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>
<a class="navbar-brand" href="?page=home" aria-label="Retour à l'accueil">
    <span class="brand-dot"></span>
    Vite &amp; Gourmand
</a>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
