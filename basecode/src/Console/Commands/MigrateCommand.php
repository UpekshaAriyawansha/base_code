<?php

namespace Src\Console\Commands;

use Src\Infrastructure\Database\Database;

class MigrateCommand
{
    public function handle(): void
    {
        $migrationPath =
            __DIR__ .
            '/../../../database/migrations';

        $files =
            glob($migrationPath . '/*.php');

        $db =
            Database::connection();

        /*
        |--------------------------------------------------------------------------
        | Ensure migrations table exists (MATCH YOUR STRUCTURE)
        |--------------------------------------------------------------------------
        */

        $db->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INT AUTO_INCREMENT PRIMARY KEY,
                version VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        /*
        |--------------------------------------------------------------------------
        | Run Migrations
        |--------------------------------------------------------------------------
        */

        foreach ($files as $file) {

            $migrationVersion =
                basename($file);

            $migrationName =
                pathinfo($file, PATHINFO_FILENAME);

            /*
            |--------------------------------------------------------------------------
            | Already Migrated?
            |--------------------------------------------------------------------------
            */

            $stmt =
                $db->prepare(
                    "SELECT COUNT(*)
                     FROM migrations
                     WHERE version = ?"
                );

            $stmt->execute([
                $migrationVersion
            ]);

            $exists =
                $stmt->fetchColumn();

            if ($exists) {

                echo "SKIPPED: {$migrationVersion}\n";

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Run Migration
            |--------------------------------------------------------------------------
            */

            $migration =
                require $file;

            if (
                is_object($migration) &&
                method_exists($migration, 'up')
            ) {
                $migration->up();
            } else {
                echo "ERROR: Invalid migration file {$migrationVersion}\n";
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Save Migration Record
            |--------------------------------------------------------------------------
            */

            $stmt =
                $db->prepare(
                    "INSERT INTO migrations (version, name)
                     VALUES (?, ?)"
                );

            $stmt->execute([
                $migrationVersion,
                $migrationName
            ]);

            echo "MIGRATED: {$migrationVersion}\n";
        }

        echo "\nAll migrations completed.\n";
    }
}
// namespace Src\Console\Commands;

// use Src\Infrastructure\Database\Database;

// class MigrateCommand
// {
//     public function handle(): void
//     {
//         $migrationPath =
//             __DIR__ .
//             '/../../../database/migrations';

//         $files =
//             glob($migrationPath . '/*.php');

//         $db =
//             Database::connection();

//         foreach ($files as $file) {

//             $migrationName =
//                 basename($file);

//             /*
//             |--------------------------------------------------------------------------
//             | Already Migrated?
//             |--------------------------------------------------------------------------
//             */

//             $stmt =
//                 $db->prepare(

//                     "SELECT COUNT(*)
//                      FROM migrations
//                      WHERE migration = ?"

//                 );

//             $stmt->execute([
//                 $migrationName
//             ]);

//             $exists =
//                 $stmt->fetchColumn();

//             if ($exists) {

//                 echo "SKIPPED: {$migrationName}\n";

//                 continue;
//             }

//             /*
//             |--------------------------------------------------------------------------
//             | Run Migration
//             |--------------------------------------------------------------------------
//             */

//             $migration =
//                 require $file;

//             $migration->up();

//             /*
//             |--------------------------------------------------------------------------
//             | Save Migration
//             |--------------------------------------------------------------------------
//             */

//             $stmt =
//                 $db->prepare(

//                     "INSERT INTO migrations
//                      (migration)
//                      VALUES (?)"

//                 );

//             $stmt->execute([
//                 $migrationName
//             ]);

//             echo "MIGRATED: {$migrationName}\n";
//         }
//     }
// }