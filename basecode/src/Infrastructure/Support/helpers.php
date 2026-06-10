<?php

use Src\Support\Config;


// if (!function_exists('env')) {

//     function env(
//         string $key,
//         mixed $default = null
//     ): mixed {

//         return $_ENV[$key] ?? $default;
//     }
// }


if (!function_exists('config')) {

    function config(
        string $key,
        mixed $default = null
    ): mixed {

        return Config::get(
            $key,
            $default
        );
    }
}

if (!function_exists('base_path')) {

    function base_path(
        string $path = ''
    ): string {

        $basePath =
            dirname(__DIR__, 3);

        return $path
            ? $basePath .
                DIRECTORY_SEPARATOR .
                str_replace(
                    ['/', '\\'],
                    DIRECTORY_SEPARATOR,
                    $path
                )
            : $basePath;
    }
}