<?php

namespace Src\Console\Commands;

use Src\Infrastructure\Database\Database;

class RollbackCommand
{
    public function handle(
        array $argv
    ): void {

        $db =
            Database::connection();

        /*
        |--------------------------------------------------------------------------
        | Latest Migration
        |--------------------------------------------------------------------------
        */

        $stmt =
            $db->query(

                "SELECT *
                 FROM migrations
                 ORDER BY id DESC
                 LIMIT 1"

            );

        $migration =
            $stmt->fetch();

        if (!$migration) {

            echo "No migrations found.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Migration File
        |--------------------------------------------------------------------------
        */

        $file =
            __DIR__ .
            '/../../../database/migrations/' .
            $migration['migration'];

        if (!file_exists($file)) {

            echo "Migration file missing.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Run down()
        |--------------------------------------------------------------------------
        */

        $instance =
            require $file;

        $instance->down();

        /*
        |--------------------------------------------------------------------------
        | Delete Migration Record
        |--------------------------------------------------------------------------
        */

        $stmt =
            $db->prepare(

                "DELETE FROM migrations
                 WHERE id = ?"

            );

        $stmt->execute([
            $migration['id']
        ]);

        echo "ROLLED BACK: {$migration['migration']}\n";
    }
}