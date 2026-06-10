<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $db =
            Database::connection();

        $db->exec(

            "CREATE TABLE products (

                id BIGINT AUTO_INCREMENT PRIMARY KEY,

                name VARCHAR(255) NOT NULL,

                price DECIMAL(10,2) NOT NULL,

                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

            )"

        );
    }

    public function down(): void
    {
        $db =
            Database::connection();

        $db->exec(

            "DROP TABLE products"

        );
    }
};