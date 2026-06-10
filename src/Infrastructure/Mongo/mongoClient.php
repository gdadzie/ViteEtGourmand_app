<?php

namespace Infrastructure\Mongo;

use MongoDB\Client;

class MongoClientFactory
{
    private static ?Client $client = null;

    public static function getClient(): Client
    {
        if (self::$client === null) {
            self::$client = new Client("mongodb://localhost:27017");
        }

        return self::$client;
    }
}
