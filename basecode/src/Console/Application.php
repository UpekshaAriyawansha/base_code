<?php

namespace Src\Console;

use Src\Console\Commands\MigrateCommand;
use Src\Console\Commands\MakeModelCommand;
use Src\Console\Commands\MakeControllerCommand;
use Src\Console\Commands\MakeMigrationCommand;
use Src\Console\Commands\RollbackCommand;
use Src\Console\Commands\MakeRequestCommand;
use Src\Console\Commands\MakeServiceCommand;
use Src\Console\Commands\SeedCommand;
use Src\Console\Commands\MakeModuleCommand;

class Application
{
    private array $commands = [];

    public function __construct()
    {
        $this->registerCommands();
    }

    /*
    |--------------------------------------------------------------------------
    | Register Commands
    |--------------------------------------------------------------------------
    */

    private function registerCommands(): void
    {
        $this->commands = [

            'migrate' =>
                new MigrateCommand(),

            'migrate:rollback' =>
                new RollbackCommand(),

            'make:model' =>
                new MakeModelCommand(),

            'make:controller' =>
                new MakeControllerCommand(),

            'make:migration' =>
                new MakeMigrationCommand(),

            'make:request' =>
                new MakeRequestCommand(),

            'make:service' =>
                new MakeServiceCommand(),

            'db:seed' =>
                new SeedCommand(),

            'make:module' =>
                new MakeModuleCommand(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Run Command
    |--------------------------------------------------------------------------
    */

    public function run(
        array $argv
    ): void {

        $commandName =
            $argv[1]
            ?? null;

        if (!$commandName) {

            echo "No command provided.\n";

            return;
        }

        if (
            !isset(
                $this->commands[$commandName]
            )
        ) {

            echo "Command not found.\n";

            return;
        }

        // $this->commands[$commandName]
        //     ->handle($argv);

                // ✅ FIX: pass ONLY arguments after command name : now all the Commands php please update
        $this->commands[$commandName]
            ->handle(array_slice($argv, 2));
    }
}