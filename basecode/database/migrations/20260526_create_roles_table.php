<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $db = Database::connection();

        $db->exec(
            "CREATE TABLE roles (

                id INT AUTO_INCREMENT PRIMARY KEY,

                name VARCHAR(100) NOT NULL,

                slug VARCHAR(100) NOT NULL UNIQUE,

                description VARCHAR(100) NOT NULL,

                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

            )"
        );
    }

    public function down(): void
    {
        Database::connection()
            ->exec("DROP TABLE roles");
    }
};