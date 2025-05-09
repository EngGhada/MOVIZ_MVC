<?php

namespace App\Repository;

use App\Entity\Entity;
use App\Db\Mysql;
use App\Tools\StringTools;
use MongoDB\Client;
use App\Db\Mongo;


class Repository
{
    protected \PDO $pdo;
    protected ?Client $mongo = null;
    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $this->pdo = $mysql->getPDO();

          // Optional Mongo init if available
          if (class_exists(Mongo::class)) {
            $this->mongo = Mongo::getClient();
        }

    }

    public function getMongo(): ?Client
    {
        return $this->mongo;
    }

}