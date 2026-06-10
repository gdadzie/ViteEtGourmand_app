<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="description" content="Vite & Gourmand — Traiteur à Bordeaux. Menus pour événements, commandes en ligne." />
    <title>Vite & Gourmand — Espace administrateur</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root{
            /* Charte (harmonisée avec les autres pages que tu utilises) */
            --bg:#f5f5f5;
            --card:#ffffff;
            --text:#212529;
            --muted:#6c757d;

            /* Couleur principale appli (comme tes autres vues) */
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
            margin:0;
            padding:0;
            overflow-x:hidden;
            color:var(--text);
        }

        /* Header */
        .dashboard-header{
            background:var(--card);
            border:1px solid var(--border);
            border-radius:var(--radius);
            box-shadow:var(--shadow);
            padding:22px 22px;
        }
        .dashboard-header h1{
            font-size:1.65rem;
            font-weight:700;
            margin:0;
            display:flex;
            align-items:center;
            gap:.6rem;
        }
        .dashboard-header h1 .brand-dot{
            width:10px;
            height:10px;
            border-radius:99px;
            background:var(--brand);
            display:inline-block;
        }
        .dashboard-header p{
            color:var(--muted);
            margin:6px 0 0 0;
        }
        .header-icon{
            color:var(--brand);
            font-size:1.35rem;
        }

        /* Lien carte */
        a.card-link{
            text-decoration:none;
            color:inherit;
            display:block;
        }
        a.card-link:focus-visible{
            outline:3px solid rgba(170,109,39,.35);
            outline-offset:4px;
            border-radius:var(--radius);
        }

        /* Carte */
        .card-admin{
            border-radius:var(--radius);
            background:var(--card);
            border:1px solid var(--border);
            box-shadow:var(--shadow);
            padding:22px;
            transition:transform .18s ease, box-shadow .18s ease, border-color .18s ease;
            text-align:left;
            height:100%;
            position:relative;
            overflow:hidden;
        }
        .card-admin:hover{
            transform:translateY(-4px);
            box-shadow:var(--shadow-hover);
            border-color:rgba(170,109,39,.25);
        }

        /* Badge icône harmonisé */
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
        .card-admin i{
            font-size:1.45rem;
            color:var(--brand);
            margin:0;
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

        /* Petit chevron “entrée” */
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

        /* Grid responsive (comme ta base, mais plus clean) */
        .admin-cards{
            display:grid;
            grid-template-columns:1fr;
            gap:16px;
        }
        @media (min-width:768px){
            .admin-cards{ grid-template-columns:repeat(2, 1fr); gap:20px; }
        }
        @media (min-width:1200px){
            .admin-cards{ grid-template-columns:repeat(3, 1fr); }
        }
    </style>
</head>

<body>

<?php include __DIR__ . '/../partials/menu.php'; ?>

<div class="container-fluid px-2 px-md-4 my-4 my-md-5">

    <!-- HEADER (même esprit que ton dashboard user : fond blanc + dot + icône couleur brand) -->
    <div class="dashboard-header mb-4">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1 class="text-start">
                <i class="bi bi-speedometer2 header-icon"></i>
                Mon espace administrateur
            </h1>
        </div>
        <p class="text-start">
            Bienvenue <?= htmlspecialchars($_SESSION['prenom'] ?? 'Administrateur') ?>
        </p>
    </div>

    <!-- CARTES (Données inchangées : mêmes liens / titres / textes) -->
    <div class="admin-cards">

        <a href="?page=creation_employe" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-person-plus"></i></div>
                    <div>
                        <h5 class="card-title">Créer un employé</h5>
                        <p class="card-text">Ajoutez un nouvel employé avec le rôle employé.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=liste_des_utilisateurs" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-people"></i></div>
                    <div>
                        <h5 class="card-title">Gestion des utilisateurs</h5>
                        <p class="card-text">Consultez et gérez les comptes utilisateurs.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=modification_horaire" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-clock-history"></i></div>
                    <div>
                        <h5 class="card-title">Modifier les horaires</h5>
                        <p class="card-text">Mettez à jour les horaires du service traiteur.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="index.php?page=modify_contacts" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-telephone"></i></div>
                    <div>
                        <h5 class="card-title">Modifier les contacts</h5>
                        <p class="card-text">Mettez à jour les informations de contact.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=gestion_des_menus" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-card-list"></i></div>
                    <div>
                        <h5 class="card-title">Gestion des menus</h5>
                        <p class="card-text">Consultez les menus proposés au public.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="?page=creer_un_menu" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-plus-circle"></i></div>
                    <div>
                        <h5 class="card-title">Ajouter un menu</h5>
                        <p class="card-text">Créez un nouveau menu traiteur.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="index.php?page=gestion_des_commandes" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-receipt"></i></div>
                    <div>
                        <h5 class="card-title">Commandes</h5>
                        <p class="card-text">Consultez les commandes clients.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

        <a href="index.php?page=sales_stats" class="card-link">
            <div class="card-admin">
                <div class="d-flex align-items-start gap-3">
                    <div class="icon-badge"><i class="bi bi-graph-up"></i></div>
                    <div>
                        <h5 class="card-title">Statistiques / CA</h5>
                        <p class="card-text">Analysez les performances et le chiffre d’affaires.</p>
                        <div class="card-cta">Accéder <i class="bi bi-arrow-right"></i></div>
                    </div>
                </div>
            </div>
        </a>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
