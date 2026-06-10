<?php

namespace Config;

use PDO;
use PDOException;

class Database
{
    public static function getConnection(): PDO
    {
        // =========================
        // HEROKU / JAWSDB
        // =========================
        $url = getenv('JAWSDB_URL');

        if ($url) {

            $dbparts = parse_url($url);

            $host = $dbparts['host'];
            $db   = ltrim($dbparts['path'], '/');
            $user = $dbparts['user'];
            $pass = $dbparts['pass'];
            $port = $dbparts['port'] ?? 3306;

        } else {

            // =========================
            // LOCAL WAMP
            // =========================
            $host = 'localhost';
            $db   = 'vitegourmand_app';
            $user = 'root';
            $pass = '';
            $port = 3306;
        }

        try {

            return new PDO(
                "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4",
                $user,
                $pass,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );

        } catch (PDOException $e) {

            die("❌ ERREUR CONNEXION DB : " . $e->getMessage());
        }
    }
}