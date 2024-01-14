<?php

namespace App\Infrastructure\Database;

use PDO;

class ConnectionCreator
{
    public function create()
    {
        $path = __DIR__.'../../../../database.sqlite';
        $connection = new PDO('sqlite:'.$path);
        $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

        return $connection;
    }
}
