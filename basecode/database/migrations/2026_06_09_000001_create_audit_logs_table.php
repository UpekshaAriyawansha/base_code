<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec(
            "
            CREATE TABLE audit_logs (
                id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

                event_type VARCHAR(100) NOT NULL,
                module VARCHAR(100) NOT NULL,

                user_id BIGINT UNSIGNED NULL,

                entity_id BIGINT UNSIGNED NULL,
                entity_type VARCHAR(100) NULL,

                description TEXT NULL,

                metadata JSON NULL,

                ip_address VARCHAR(100) NULL,

                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                INDEX idx_event_type (event_type),
                INDEX idx_module (module),
                INDEX idx_user_id (user_id),
                INDEX idx_entity_id (entity_id),
                INDEX idx_created_at (created_at)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            "
        );
    }

    public function down(): void
    {
        Database::connection()->exec(
            "DROP TABLE IF EXISTS audit_logs"
        );
    }
};

// CREATE TABLE audit_logs (
//     id BIGINT PRIMARY KEY AUTO_INCREMENT,

//     event_type VARCHAR(100) NOT NULL,
//     module VARCHAR(100) NOT NULL,

//     user_id BIGINT NULL,

//     entity_id BIGINT NULL,
//     entity_type VARCHAR(100) NULL,

//     description TEXT,

//     metadata JSON NULL,

//     ip_address VARCHAR(100) NULL,

//     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
// );