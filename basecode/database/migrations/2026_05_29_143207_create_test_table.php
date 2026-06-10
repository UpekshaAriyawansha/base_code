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

                    "CREATE TABLE test (

                        id INT AUTO_INCREMENT PRIMARY KEY

                    )"

                );
    }

    public function down(): void
    {
        $db =
            Database::connection();

        $db->exec(

            "DROP TABLE test"

        );
    }
};
