<?php

namespace App\Db;

use MongoDB\Client;

class Mongo
{
    private static $client;

    public static function getClient(): Client
    {
        if (!self::$client) {
            self::$client = new Client("mongodb+srv://ghada:STUDIprojet@cluster0.c8df6.mongodb.net/?retryWrites=true&w=majority");
        }
        return self::$client;
    }
}
