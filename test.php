<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb+srv://ghada:STUDIprojet@cluster0.c8df6.mongodb.net/moviz-mvc?retryWrites=true&w=majority");
$collections = $client->listDatabases();
foreach ($collections as $db) {
    echo $db->getName(), "\n";
}
