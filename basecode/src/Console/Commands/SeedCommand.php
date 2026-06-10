<?php

namespace Src\Console\Commands;

class SeedCommand
{
    public function handle(array $args = []): void
    {
        $seederName = $args[0] ?? 'DatabaseSeeder';

        $map = [
            'DatabaseSeeder'   => 'DatabaseSeeder',
            'PermissionSeeder' => 'PermissionSeeder',
            'SettingSeeder'    => 'SettingSeeder',
        ];

        $class = $map[$seederName] ?? $seederName;

        $file = base_path("database/seeders/{$class}.php");

        if (!file_exists($file)) {
            echo "Seeder file not found: {$file}" . PHP_EOL;
            return;
        }

        // ✅ IMPORTANT: include file FIRST
        require_once $file;

        if (!class_exists($class)) {
            echo "Seeder class not found inside file: {$class}" . PHP_EOL;
            return;
        }

        $seeder = new $class();
        $seeder->run();
    }
}