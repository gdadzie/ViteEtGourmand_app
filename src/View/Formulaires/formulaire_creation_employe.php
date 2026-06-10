<?php
// Assurer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un employé - Vite & Gourmand</title>

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Fonts (même combo que tes autres pages design) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">

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
            --shadow:0 10px 30px rgba(0,0,0,.10);
            --shadow-soft:0 6px 18px rgba(0,0,0,.06);
            --shadow-hover:0 14px 34px rgba(0,0,0,.11);
            --radius:18px;
        }

        body{
            background:var(--bg);
            font-family:'Inter',sans-serif;
            color:var(--text);
            overflow-x:hidden;
        }

        h1,h2,h3,h4,h5{
            font-family:'Poppins',sans-serif;
            font-weight:800;
            letter-spacing:-0.3px;
        }

        /* “plancher” / slab comme sur tes sections */
        .section-slab{
            position:relative;
            padding: 44px 0;
        }
        .section-slab.bg-tint{
            background: linear-gradient(180deg, rgba(170,109,39,.08), rgba(170,109,39,.04));
        }

        .slab-inner{
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 22px;
            box-shadow: var(--shadow-soft);
            padding: 22px;
        }

        /* Header type dashboard */
        .dashboard-header h1{
            font-family:'Outfit',sans-serif;
            font-weight:800;
            letter-spacing:-.8px;
            font-size: clamp(1.8rem, 2.2vw, 2.2rem);
            margin:0;
        }
        .dashboard-header p{ color:var(--muted); margin:6px 0 0 0; }

        .brand-dot{
            width:10px;height:10px;border-radius:99px;
            background:var(--brand);
            display:inline-block;
            box-shadow:0 0 0 4px rgba(170,109,39,.18);
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

        /* Card principale (comme tes cards admin/user) */
        .card-admin{
            border-radius: 18px;
            background: #fff;
            border: 1px solid rgba(0,0,0,.07);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        /* Bandeau haut de carte (accent) */
        .card-top{
            padding: 18px 18px 14px 18px;
            background:
                    radial-gradient(800px 260px at 20% 20%, rgba(170,109,39,.18), transparent 60%),
                    linear-gradient(180deg, rgba(253,242,231,.9), rgba(255,255,255,1));
            border-bottom: 1px solid rgba(0,0,0,.06);
        }

        .chip{
            display:inline-flex;
            align-items:center;
            gap:.45rem;
            background: rgba(170,109,39,.10);
            border:1px solid rgba(170,109,39,.18);
            color: var(--brand-dark);
            border-radius: 999px;
            padding: .35rem .7rem;
            font-size: .9rem;
            font-weight: 700;
        }

        /* Form */
        .card-body{
            padding: 18px;
        }

        .form-label{
            font-weight: 700;
            color: #3a3a3a;
        }

        .form-control, .form-select, textarea{
            border-radius: 14px;
            border: 1px solid rgba(0,0,0,.10);
            padding: .7rem .85rem;
        }

        .form-control:focus, .form-select:focus, textarea:focus{
            border-color: rgba(170,109,39,.45);
            box-shadow: 0 0 0 .2rem rgba(170,109,39,.18);
        }

        /* Input group lock */
        .input-group-text{
            border-radius: 14px 0 0 14px;
            border: 1px solid rgba(0,0,0,.10);
            background:#fff;
        }

        /* Switch */
        .form-check-input{
            width: 2.9rem;
            height: 1.45rem;
            cursor:pointer;
        }
        .form-check-input:checked{
            background-color: var(--brand);
            border-color: var(--brand);
        }

        /* Boutons (même style que tes btn-brand) */
        .btn-brand{
            background: var(--brand);
            border-color: var(--brand);
            color:#fff;
            border-radius: 999px;
            font-weight: 900;
            padding: 12px 18px;
            box-shadow: 0 12px 26px rgba(170,109,39,.22);
            transition: transform .16s ease, background .16s ease, border-color .16s ease;
        }
        .btn-brand:hover{
            background: var(--brand-dark);
            border-color: var(--brand-dark);
            color:#fff;
            transform: translateY(-1px);
        }

        .btn-ghost{
            border-radius: 999px;
            border: 1px solid rgba(0,0,0,.12);
            background: rgba(255,255,255,.8);
            font-weight: 800;
            padding: 12px 16px;
            color: var(--text);
        }
        .btn-ghost:hover{
            background: rgba(255,255,255,.95);
            border-color: rgba(170,109,39,.25);
        }

        /* Alerts comme ailleurs */
        .alert{
            border-radius: 16px;
            border: 1px solid rgba(0,0,0,.08);
            box-shadow: 0 8px 18px rgba(0,0,0,.06);
        }

        /* Container largeur cohérente */
        .wrap{ max-width: 880px; }

        @media (max-width: 576px){
            .card-body{ padding: 14px; }
            .card-top{ padding: 14px; }
        }
    </style>
</head>

<body>

<div class="container wrap my-4 my-md-5">

    <!-- RETOUR -->
    <a href="?page=espace_admin" class="back-link mb-3">
        <i class="bi bi-arrow-left"></i> Retour
    </a>

    <!-- HEADER dashboard -->
    <div class="dashboard-header mb-3">
        <div class="d-flex align-items-center gap-2">
            <span class="brand-dot"></span>
            <h1>Créer un employé</h1>
        </div>
        <p>Ajoutez un nouveau compte et définissez ses informations.</p>
    </div>

</div>

<!-- “Plancher” teinté (comme tes sections équipe/avis) -->
<section class="section-slab bg-tint">
    <div class="container wrap">
        <div class="slab-inner">

            <div class="card-admin">

                <!-- bandeau haut -->
                <div class="card-top d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="chip">
                        <i class="bi bi-person-plus"></i>
                        Nouveau compte
                    </span>

                    <span class="chip">
                        <i class="bi bi-shield-check"></i>
                        Création sécurisée
                    </span>
                </div>

                <div class="card-body">

                    <!-- Messages d'alerte -->
                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert" aria-live="polite">
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php elseif (!empty($success)) : ?>
                        <div class="alert alert-success" role="alert" aria-live="polite">
                            <?= htmlspecialchars($success) ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="?page=creation_employe">

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="prenom" class="form-label">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" required>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="nom" class="form-label">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" required>
                            </div>

                            <div class="col-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <div class="col-12">
                                <label for="mdp" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="mdp" name="mdp" required style="border-radius:0 14px 14px 0;">
                                </div>
                                <div class="form-text text-muted">
                                    Utilisez un mot de passe fort (au moins 8 caractères).
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="telephone" class="form-label">Téléphone</label>
                                <input type="text" class="form-control" id="telephone" name="telephone">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="id_role" class="form-label">Rôle</label>
                                <select id="id_role" name="id_role" class="form-select" required>
                                    <option value="">-- Choisir --</option>
                                    <option value="2">Employé</option>
                                    <option value="1">Utilisateur</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label for="adresse" class="form-label">Adresse</label>
                                <textarea class="form-control" id="adresse" name="adresse" rows="3"></textarea>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mt-4">

                            <div class="form-check form-switch m-0">
                                <input class="form-check-input" type="checkbox" name="est_actif" value="1" id="est_actif">
                                <label class="form-check-label fw-semibold" for="est_actif">Actif</label>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="?page=espace_admin" class="btn btn-ghost">
                                    Annuler
                                </a>

                                <button type="submit" class="btn btn-brand">
                                    <i class="bi bi-person-plus me-1"></i>
                                    Créer l'employé
                                </button>
                            </div>

                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
