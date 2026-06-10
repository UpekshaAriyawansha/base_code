<?php

namespace Src\Support;

class Config
{
    private static array $items = [];

    public static function load(): void
    {
        $files = glob(
            __DIR__ . '/../../config/*.php'
        );

        foreach ($files as $file) {

            $key =
                basename(
                    $file,
                    '.php'
                );

            self::$items[$key] =
                require $file;
        }
    }

    public static function get(
        string $key,
        mixed $default = null
    ): mixed {

        $segments =
            explode('.', $key);

        $config =
            self::$items;

        foreach ($segments as $segment) {

            if (
                !isset(
                    $config[$segment]
                )
            ) {

                return $default;
            }

            $config =
                $config[$segment];
        }

        return $config;
    }
}