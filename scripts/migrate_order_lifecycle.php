<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) . '/Config/Database.php';

use Config\Database;

$root = dirname(__DIR__);
$environment = getenv('APP_ENV') ?: 'local';
$envFile = $root . '/.env.' . $environment;
if (is_file($envFile)) {
    Dotenv\Dotenv::createImmutable($root, '.env.' . $environment)->safeLoad();
}

$migration = file_get_contents($root . '/database/003_order_lifecycle.sql');
if ($migration === false) {
    throw new RuntimeException('Script SQL introuvable.');
}

$migration = preg_replace('/^--.*$/m', '', $migration) ?? '';
$connection = Database::getConnection();

foreach (array_filter(array_map('trim', explode(';', $migration))) as $statement) {
    $connection->exec($statement);
}

echo "Migration du cycle de vie des commandes appliquée.\n";
