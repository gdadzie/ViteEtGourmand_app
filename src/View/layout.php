<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require ROOT . '/src/includes/head.php'; ?>

    <title><?= htmlspecialchars($pageTitle ?? 'Vite & Gourmand') ?></title>
</head>

<body>

<?php if ($showMenu ?? true): ?>
    <?php require ROOT . '/src/includes/navbar.php'; ?>
<?php endif; ?>

<div class="container pt-5 mt-5">

    <?= $content ?>

</div>

</body>
</html>