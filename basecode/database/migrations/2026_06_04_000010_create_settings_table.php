<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec(

            "CREATE TABLE settings (

                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                setting_key VARCHAR(255) UNIQUE NOT NULL,

                setting_value LONGTEXT NULL,

                setting_type ENUM(
                    'text',
                    'image',
                    'json'
                ) DEFAULT 'text',

                is_sensitive TINYINT(1) DEFAULT 0,

                is_editable TINYINT(1) DEFAULT 1,

                updated_by INT UNSIGNED NULL,

                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP NULL
                    DEFAULT NULL
                    ON UPDATE CURRENT_TIMESTAMP,

                INDEX idx_setting_key (setting_key)

            )"

        );
    }

    public function down(): void
    {
        Database::connection()
            ->exec("DROP TABLE settings");
    }
};