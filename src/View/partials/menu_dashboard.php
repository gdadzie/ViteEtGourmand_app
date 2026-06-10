<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isConnected = isset($_SESSION['id_utilisateur'], $_SESSION['id_role']);
$role   = $_SESSION['id_role'] ?? null;
$prenom = $_SESSION['prenom'] ?? '';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="?page=home">Vite & Gourmand</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="?page=home">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="?page=liste_des_menus">Menus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="?page=contact">Contact</a>
                </li>

                <!-- ================= UTILISATEUR CONNECTÉ ================= -->
                <?php if ($isConnected): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <?= htmlspecialchars($prenom) ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <?php if ($role == 1): ?>
                                <li>
                                    <a class="dropdown-item" href="?page=espace_utilisateur">
                                        Mon tableau de bord
                                    </a>
                                </li>
                            <?php elseif ($role == 2): ?>
                                <li>
                                    <a class="dropdown-item" href="?page=espace_employe">
                                        Tableau employé
                                    </a>
                                </li>
                            <?php elseif ($role == 3): ?>
                                <li>
                                    <a class="dropdown-item" href="?page=espace_admin">
                                        Mon tableau de bord
                                    </a>
                                    <a class="dropdown-item" href="?page=creation_employe">
                                        Créer un nouveau employé
                                    </a>
                                    <a class="dropdown-item" href="?page=liste_des_utilisateurs">
                                        Gestion des utilisateurs
                                    </a>
                                    <a class="dropdown-item" href="?page=modification_horaire">
                                        Gestion des horaires
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item text-danger" href="?page=deconnexion">
                                    Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- ================= UTILISATEUR NON CONNECTÉ ================= -->
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?page=connexion">
                            <i class="bi bi-person-circle"></i>
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
