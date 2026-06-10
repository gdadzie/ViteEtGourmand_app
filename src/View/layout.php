<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require ROOT . '/src/includes/head.php'; ?>
    <title><?= htmlspecialchars($title ?? 'Vite & Gourmand') ?></title>
</head>

<body>

<?php require ROOT . '/src/includes/navbar.php'; ?>

<div class="container pt-5 mt-5">

    <?php
    if (isset($view) && file_exists($view)) {
        require $view;
    } else {
        echo '<div class="alert alert-danger">Vue introuvable</div>';
    }

    ?>


</div>

</body>
</html>