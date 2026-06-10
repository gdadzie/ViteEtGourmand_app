<?php
$isConnected = isset($_SESSION['id_utilisateur'], $_SESSION['id_role']);
$role   = $_SESSION['id_role'] ?? null;
$prenom = $_SESSION['prenom'] ?? '';

$currentPage = $currentPage ?? ''; // fourni par le layout/controller
?>

<nav class="navbar navbar-expand-lg">
    <div class="container">

        <a class="navbar-brand" href="?page=home" aria-label="Retour à l'accueil">
            <span class="brand-dot"></span>
            Vite &amp; Gourmand
        </a>

        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav"
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Ouvrir le menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'home' ? 'active' : '' ?>" href="?page=home">Accueil</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'liste_des_menus' ? 'active' : '' ?>" href="?page=liste_des_menus">Menus</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'contact' ? 'active' : '' ?>" href="?page=contact">Contact</a>
                </li>

                <?php if ($isConnected): ?>
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle d-inline-flex align-items-center gap-2"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span><?= htmlspecialchars($prenom) ?></span>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-vg">
                            <?php if ((int)$role === 1): ?>
                                <li>
                                    <a class="dropdown-item" href="?page=espace_utilisateur">
                                        <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
                                    </a>
                                </li>

                            <?php elseif ((int)$role === 2): ?>
                                <li>
                                    <a class="dropdown-item" href="?page=espace_employe">
                                        <i class="bi bi-briefcase me-2"></i>Tableau employé
                                    </a>
                                </li>

                            <?php elseif ((int)$role === 3): ?>
                                <li>
                                    <a class="dropdown-item" href="?page=espace_admin">
                                        <i class="bi bi-speedometer2 me-2"></i>Mon tableau de bord
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="?page=creation_employe">
                                        <i class="bi bi-person-plus me-2"></i>Créer un nouvel employé
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="?page=liste_des_utilisateurs">
                                        <i class="bi bi-people me-2"></i>Gestion des utilisateurs
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="?page=modification_horaire">
                                        <i class="bi bi-clock-history me-2"></i>Gestion des horaires
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li><hr class="dropdown-divider"></li>

                            <li>
                                <a class="dropdown-item text-danger" href="?page=deconnexion">
                                    <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item ms-lg-3">
                        <a class="btn-login" href="?page=connexion">
                            <i class="bi bi-person-circle me-1"></i>
                            Connexion
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
