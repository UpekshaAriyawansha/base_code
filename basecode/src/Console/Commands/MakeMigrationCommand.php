<?php

namespace Src\Console\Commands;

class MakeMigrationCommand
{
    public function handle(
        array $argv
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Migration Name
        |--------------------------------------------------------------------------
        */

        $name =
            $argv[2]
            ?? null;

        if (!$name) {

            echo "Migration name required.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Timestamp
        |--------------------------------------------------------------------------
        */

        $timestamp =
            date('Y_m_d_His');

        /*
        |--------------------------------------------------------------------------
        | File Name
        |--------------------------------------------------------------------------
        */

        $fileName =
            "{$timestamp}_{$name}.php";

        /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        */

        $directory =
            __DIR__ .
            '/../../../database/migrations';

        if (!is_dir($directory)) {

            mkdir(
                $directory,
                0777,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | File Path
        |--------------------------------------------------------------------------
        */

        $file =
            "{$directory}/{$fileName}";

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        */

        $template = <<<PHP
<?php

use Src\\Infrastructure\\Database\\Database;
use Src\\Infrastructure\\Database\\Migration;

return new class extends Migration
{
    public function up(): void
    {
        \$db =
            Database::connection();

        \$db->exec(

            ""

        );
    }

    public function down(): void
    {
        \$db =
            Database::connection();

        \$db->exec(

            ""

        );
    }
};

PHP;

        /*
        |--------------------------------------------------------------------------
        | Create File
        |--------------------------------------------------------------------------
        */

        file_put_contents(
            $file,
            $template
        );

        echo "Migration created: {$fileName}\n";
    }
}