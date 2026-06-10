-- CREATE TABLE permissions (

--     id BIGINT AUTO_INCREMENT PRIMARY KEY,

--     name VARCHAR(100) NOT NULL,

--     slug VARCHAR(100) NOT NULL UNIQUE,

--     description TEXT NULL,

--     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP

-- );

<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec(

            "CREATE TABLE permissions (

                id INT AUTO_INCREMENT PRIMARY KEY,

                name VARCHAR(100) NOT NULL,

                slug VARCHAR(100) NOT NULL UNIQUE,

                created_at TIMESTAMP
                DEFAULT CURRENT_TIMESTAMP

            )"

        );
    }

    public function down(): void
    {
        Database::connection()->exec(
            "DROP TABLE permissions"
        );
    }
};