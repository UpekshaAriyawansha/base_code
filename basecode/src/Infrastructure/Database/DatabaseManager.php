<?php

namespace Src\Infrastructure\Database;

use PDO;

class DatabaseManager
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo =
            Database::connection();
    }

    public function begin(): void
    {
        $this->pdo
            ->beginTransaction();
    }

    public function commit(): void
    {
        $this->pdo
            ->commit();
    }

    public function rollback(): void
    {
        if (
            $this->pdo->inTransaction()
        ) {

            $this->pdo
                ->rollBack();
        }
    }

    public function connection(): PDO
    {
        return $this->pdo;
    }
}