<?php

namespace Src\Console\Commands;

class MakeModelCommand
{
    public function handle(
        array $argv
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Model Name
        |--------------------------------------------------------------------------
        */

        $name =
            $argv[2]
            ?? null;

        if (!$name) {

            echo "Model name required.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Table Name
        |--------------------------------------------------------------------------
        */

        $table =
            strtolower($name) . 's';

        /*
        |--------------------------------------------------------------------------
        | Model Directory
        |--------------------------------------------------------------------------
        */

        $directory =
            __DIR__ .
            "/../../../modules/{$name}/Domain/Models";

        if (!is_dir($directory)) {

            mkdir(
                $directory,
                0777,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Model File
        |--------------------------------------------------------------------------
        */

        $file =
            "{$directory}/{$name}.php";

        if (file_exists($file)) {

            echo "Model already exists.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Model Template
        |--------------------------------------------------------------------------
        */

        $template = <<<PHP
<?php

namespace Modules\\{$name}\\Domain\\Models;

use Src\\Infrastructure\\Database\\Model;

class {$name}
extends Model
{
    protected static string \$table =
        '{$table}';
}

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

        echo "Model created: {$file}\n";
    }
}