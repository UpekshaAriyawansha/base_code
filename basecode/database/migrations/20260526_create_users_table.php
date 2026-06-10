<?php

return new class {

    public function up(): void
    {
        $db = \Src\Infrastructure\Database\Database::connection();

        $db->exec("
            CREATE TABLE IF NOT EXISTS users (

                id BIGINT AUTO_INCREMENT PRIMARY KEY,

                role_id BIGINT NULL,

                first_name VARCHAR(100) NOT NULL,

                last_name VARCHAR(100) NOT NULL,

                email VARCHAR(255) NOT NULL UNIQUE,

                password VARCHAR(255) NOT NULL,

                status VARCHAR(50) DEFAULT 'active',

                last_login_at TIMESTAMP NULL,

                deleted_at TIMESTAMP NULL,

                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP

            )
        ");
    }
};





