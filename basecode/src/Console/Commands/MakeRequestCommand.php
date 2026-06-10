<?php

namespace Src\Console\Commands;

class MakeRequestCommand
{
    public function handle(
        array $argv
    ): void {

        /*
        |--------------------------------------------------------------------------
        | Request Name
        |--------------------------------------------------------------------------
        */

        $name =
            $argv[2]
            ?? null;

        if (!$name) {

            echo "Request name required.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Module Name
        |--------------------------------------------------------------------------
        */

        preg_match(

            '/(Store|Update)(.*)Request/',

            $name,

            $matches

        );

        $module =
            $matches[2]
            ?? 'App';

        /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        */

        $directory =
            __DIR__ .
            "/../../../modules/{$module}/Presentation/Requests";

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

            echo "Request already exists.\n";

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        */

        $template = <<<PHP
<?php

namespace Modules\\{$module}\\Presentation\\Requests;

use Src\\Presentation\\Requests\\FormRequest;

class {$name}
extends FormRequest
{
    public function rules(): array
    {
        return [

            //
            
        ];
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

        echo "Request created: {$file}\n";
    }
}