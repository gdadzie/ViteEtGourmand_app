<?php
declare(strict_types=1);

namespace Config;

use PDO;
use PDOException;
use RuntimeException;

final class Database
{
    public static function getConnection(): PDO
    {
        $url = getenv('JAWSDB_URL');
        if ($url) {
            $parts = parse_url($url);
            $host = $parts['host'] ?? '';
            $database = ltrim($parts['path'] ?? '', '/');
            $user = $parts['user'] ?? '';
            $password = $parts['pass'] ?? '';
            $port = $parts['port'] ?? 3306;
        } else {
            $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
            $database = $_ENV['DB_NAME'] ?? '';
            $user = $_ENV['DB_USER'] ?? '';
            $password = $_ENV['DB_PASS'] ?? '';
            $port = (int) ($_ENV['DB_PORT'] ?? 3306);
        }

        if ($database === '') throw new RuntimeException('La configuration de la base de données est incomplète.');

        try {
            return new PDO("mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4", $user, $password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $exception) {
            error_log('Database connection failed: ' . $exception->getMessage());
            throw new RuntimeException('Connexion à la base de données indisponible.');
        }
    }
}
