<?php

namespace Src\Console\Commands;

class MakeServiceCommand
{
    public function handle(
        array $argv
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Service Name
        |--------------------------------------------------------------------------
        */

        $name =
            $argv[2]
            ?? null;

        if (!$name) {

            echo "Service name required.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Module Name
        |--------------------------------------------------------------------------
        */

        $module =
            str_replace(
                'Service',
                '',
                $name
            );

        /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        */

        $directory =
            __DIR__ .
            "/../../../modules/{$module}/Application/Services";

        if (!is_dir($directory)) {

            mkdir(
                $directory,
                0777,
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | File
        |--------------------------------------------------------------------------
        */

        $file =
            "{$directory}/{$name}.php";

        if (file_exists($file)) {

            echo "Service already exists.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        */

        $template = <<<PHP
<?php

namespace Modules\\{$module}\\Application\\Services;

class {$name}
{
    //
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

        echo "Service created: {$file}\n";
    }
}