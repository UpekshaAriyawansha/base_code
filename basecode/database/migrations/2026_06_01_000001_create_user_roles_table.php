<?php

use Src\Infrastructure\Database\Database;
use Src\Infrastructure\Database\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Database::connection()->exec(

            "CREATE TABLE user_roles (

                id INT AUTO_INCREMENT PRIMARY KEY,

                user_id INT NOT NULL,

                role_id INT NOT NULL,

                created_at TIMESTAMP
                    DEFAULT CURRENT_TIMESTAMP,

                UNIQUE KEY unique_user_role
                (
                    user_id,
                    role_id
                )

            )"

        );
    }

    public function down(): void
    {
        Database::connection()
            ->exec(
                'DROP TABLE user_roles'
            );
    }
};