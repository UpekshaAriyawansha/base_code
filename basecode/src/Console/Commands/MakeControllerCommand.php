<?php

namespace Src\Console\Commands;

class MakeControllerCommand
{
    public function handle(
        array $argv
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Controller Name
        |--------------------------------------------------------------------------
        */

        $name =
            $argv[2]
            ?? null;

        if (!$name) {

            echo "Controller name required.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Module Name
        |--------------------------------------------------------------------------
        */

        $module =
            str_replace(
                'Controller',
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
            "/../../../modules/{$module}/Presentation/Controllers";

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

            echo "Controller already exists.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        */

        $template = <<<PHP
<?php

namespace Modules\\{$module}\\Presentation\\Controllers;

use Src\\Presentation\\Controllers\\Controller;

class {$name}
extends Controller
{
    public function index(): void
    {
        \$this->success(

            'List fetched successfully',

            []

        );
    }

    public function show(
        int \$id
    ): void {

        \$this->success(

            'Single item fetched',

            [
                'id' => \$id
            ]

        );
    }

    public function create(): void
    {
        \$this->success(

            'Item created successfully'

        );
    }

    public function update(
        int \$id
    ): void {

        \$this->success(

            'Item updated successfully',

            [
                'id' => \$id
            ]

        );
    }

    public function delete(
        int \$id
    ): void {

        \$this->success(

            'Item deleted successfully',

            [
                'id' => \$id
            ]

        );
    }
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

        echo "Controller created: {$file}\n";
    }
}