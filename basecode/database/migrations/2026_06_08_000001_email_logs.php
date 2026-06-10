<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec("
            CREATE TABLE email_logs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                recipient VARCHAR(255) NOT NULL,
                subject VARCHAR(255) NOT NULL,
                body TEXT NULL,
                status VARCHAR(50) NOT NULL,
                error TEXT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");
    }

    public function down(): void
    {
        Database::connection()->exec("
            DROP TABLE IF EXISTS email_logs
        ");
    }
};

// CREATE TABLE email_logs (
//     id INT AUTO_INCREMENT PRIMARY KEY,
//     recipient VARCHAR(255),
//     subject VARCHAR(255),
//     status VARCHAR(50),
//     error TEXT NULL,
//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );