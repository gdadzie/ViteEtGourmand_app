<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="<?= htmlspecialchars($metaDescription ?? '') ?>">

    <title><?= htmlspecialchars($pageTitle ?? 'Vite & Gourmand') ?></title>

    <!-- Bootstrap Icons -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- Global CSS -->
    <link rel="stylesheet" href="/assets/css/menu/menu.css">
    <link rel="stylesheet" href="/assets/css/footer/footer.css">

    <!-- Page CSS -->
    <?php foreach ($cssFiles ?? [] as $css): ?>
        <link rel="stylesheet" href="<?= htmlspecialchars($css) ?>">
    <?php endforeach; ?>
</head>

<body>

<?php if ($showMenu ?? true): ?>
    <?php require ROOT . '/src/View/partials/menu.php'; ?>
<?php endif; ?>

<?php require $viewFile; ?>

<?php require ROOT . '/src/View/partials/footer.php'; ?>

<!-- Page JS -->
<?php foreach ($jsFiles ?? [] as $js): ?>
    <script src="<?= htmlspecialchars($js) ?>" defer></script>
<?php endforeach; ?>

</body>
</html>