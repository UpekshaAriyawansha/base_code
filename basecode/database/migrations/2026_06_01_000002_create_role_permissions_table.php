<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec(

            "CREATE TABLE role_permissions (

                id INT AUTO_INCREMENT PRIMARY KEY,

                role_id INT NOT NULL,

                permission_id INT NOT NULL,

                created_at TIMESTAMP
                    DEFAULT CURRENT_TIMESTAMP,

                UNIQUE KEY unique_role_permission
                (
                    role_id,
                    permission_id
                )

            )"

        );
    }

    public function down(): void
    {
        Database::connection()
            ->exec(
                'DROP TABLE role_permissions'
            );
    }
};