<?php

namespace Src\Console\Commands;

class MakeModuleCommand
{
    // public function handle(
    //     array $argv
    // ): void {

    //     $name =
    //         $argv[2]
    //         ?? null;

    //     if (!$name) {

    //         echo "Module name required." . PHP_EOL;

    //         return;
    //     }

    //     $basePath =
    //         base_path(
    //             "modules/{$name}"
    //         );

    //     $directories = [

    //         "{$basePath}/Application",
    //         "{$basePath}/Domain",
    //         "{$basePath}/Infrastructure",
    //         "{$basePath}/Presentation",

    //         "{$basePath}/Application/Services",
    //         "{$basePath}/Domain/Models",
    //         "{$basePath}/Infrastructure/Persistence",
    //         "{$basePath}/Presentation/Controllers",
    //         "{$basePath}/Presentation/Requests",
    //         "{$basePath}/Presentation/Resources",

    //     ];

    //     foreach ($directories as $directory) {

    //         if (!is_dir($directory)) {

    //             mkdir(
    //                 $directory,
    //                 0777,
    //                 true
    //             );
    //         }
    //     }

    //     echo "Module {$name} created successfully." . PHP_EOL;
    // }

    public function handle(array $argv): void
{
    $name = $argv[0] ?? null;

    if (!$name) {
        echo "Module name required." . PHP_EOL;
        return;
    }

    $basePath = base_path("modules/{$name}");

    $directories = [
        "{$basePath}/Application",
        "{$basePath}/Domain",
        "{$basePath}/Infrastructure",
        "{$basePath}/Presentation",

        "{$basePath}/Application/Services",
        "{$basePath}/Domain/Models",
        "{$basePath}/Infrastructure/Persistence",
        "{$basePath}/Presentation/Controllers",
        "{$basePath}/Presentation/Requests",
        "{$basePath}/Presentation/Resources",
    ];

    foreach ($directories as $directory) {
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }
    }

    echo "Module {$name} created successfully." . PHP_EOL;
}
}