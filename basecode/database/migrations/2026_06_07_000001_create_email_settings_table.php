<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec("
            CREATE TABLE email_settings (

                id INT AUTO_INCREMENT PRIMARY KEY,

                smtp_host VARCHAR(255) NOT NULL,

                smtp_port INT NOT NULL,

                encryption VARCHAR(20)
                    DEFAULT 'tls',

                username VARCHAR(255)
                    NOT NULL,

                password TEXT NULL,

                sender_email VARCHAR(255)
                    NOT NULL,

                sender_name VARCHAR(255)
                    NOT NULL,

                created_at TIMESTAMP
                    DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP NULL
                    DEFAULT NULL
                    ON UPDATE CURRENT_TIMESTAMP

            )
        ");
    }

    public function down(): void
    {
        Database::connection()
            ->exec(
                "DROP TABLE email_settings"
            );
    }
};