<?php
// liste_des_menus.php
// Affichage des menus avec images et suppression possible via POST

// Démarrage session si pas déjà fait
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Alertes session
$success = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menus - Vite & Gourmand</title>
    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', sans-serif;
        }

        .card-menu {
            border-radius: 12px;
            background-color: #ffffff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            display: flex;
            flex-direction: column;
        }

        .card-menu:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.12);
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: 600;
        }

        .card-text {
            font-size: 0.95rem;
            color: #555;
        }

        .btn-orange {
            background-color: #aa6d27;
            color: white;
        }

        .btn-orange:hover {
            background-color: #944f1e;
            color: white;
        }

        .breadcrumb-custom a {
            color: #aa6d27;
            text-decoration: none;
        }

        .breadcrumb-custom a:hover {
            text-decoration: underline;
        }

        .alert {
            margin-top: 1rem;
        }

        .card-img-top {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>

<div class="container my-5">

    <div class="container my-5">

        <!-- FIL D'ARIANE -->
        <nav aria-label="breadcrumb" class="mb-3 p-4 mt-5">
            <ol class="breadcrumb breadcrumb-custom">
                <li class="breadcrumb-item">
                    <a href="?page=espace_employe">
                        <i class="bi bi-arrow-left"></i> Retour
                    </a>
                </li>
            </ol>
        </nav>

        <h1 class="mb-4 text-center">Gestion des menus</h1>

        <!-- MENU DASHBOARD CARDS -->
        <div class="row g-4 mb-4">

            <!-- AJOUTER MENU -->
            <div class="col-12 col-md-4">
                <a href="?page=creer_un_menu" class="text-decoration-none">
                    <div class="card card-menu text-center p-4 h-100">

                        <div class="mb-2">
                            <i class="bi bi-plus-circle-fill" style="font-size:2rem;color:#aa6d27;"></i>
                        </div>

                        <h5 class="card-title">Ajouter un menu</h5>
                        <p class="card-text">Créer un nouveau menu avec image et détails</p>

                    </div>
                </a>
            </div>

            <!-- CONSULTER MENUS -->
            <div class="col-12 col-md-4">
                <a href="?page=gestion_des_menus" class="text-decoration-none">
                    <div class="card card-menu text-center p-4 h-100">

                        <div class="mb-2">
                            <i class="bi bi-grid-3x3-gap-fill" style="font-size:2rem;color:#aa6d27;"></i>
                        </div>

                        <h5 class="card-title">Consulter les menus</h5>
                        <p class="card-text">Voir tous les menus disponibles</p>

                    </div>
                </a>
            </div>

            <!-- MODIFIER MENU -->
            <div class="col-12 col-md-4">
                <a href="?page=modifier_menu" class="text-decoration-none">
                    <div class="card card-menu text-center p-4 h-100">

                        <div class="mb-2">
                            <i class="bi bi-pencil-square" style="font-size:2rem;color:#aa6d27;"></i>
                        </div>

                        <h5 class="card-title">Modifier un menu</h5>
                        <p class="card-text">Éditer les menus existants</p>

                    </div>
                </a>
            </div>

        </div>




    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
