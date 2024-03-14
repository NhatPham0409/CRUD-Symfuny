<?php

namespace App\Model;

use App\Service\GetEnvValue;
use PDO;

class ConnectToDatabase
{
    function open_database_connection()
    {
        $getEnv = new GetEnvValue();
        $connection = new PDO($_ENV['DB_URL'],$_ENV['DB_USER'],$_ENV['DB_PASS']);

        return $connection;
    }

    function close_database_connection(&$connection)
    {
        $connection = null;
    }
}