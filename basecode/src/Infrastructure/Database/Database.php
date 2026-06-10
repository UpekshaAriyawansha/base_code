<?php

namespace Src\Infrastructure\Database;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function connection(): PDO
    {
        if (self::$connection === null) {

            try {

                $host =
                    config(
                        'database.host'
                    );

                $port =
                    config(
                        'database.port'
                    );

                $database =
                    config(
                        'database.database'
                    );

                $username =
                    config(
                        'database.username'
                    );

                $password =
                    config(
                        'database.password'
                    );

                self::$connection = new PDO(

                    sprintf(

                        'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',

                        $host,

                        $port,

                        $database

                    ),

                    $username,

                    $password

                );

                self::$connection->setAttribute(

                    PDO::ATTR_ERRMODE,

                    PDO::ERRMODE_EXCEPTION

                );

                self::$connection->setAttribute(

                    PDO::ATTR_DEFAULT_FETCH_MODE,

                    PDO::FETCH_ASSOC

                );

            } catch (PDOException $e) {

                throw new PDOException(
                    $e->getMessage()
                );
            }
        }

        return self::$connection;
    }
}