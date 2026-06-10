<?php

namespace Src\Providers;

use Src\Core\Providers\ServiceProvider;
use Src\Infrastructure\Events\EventDispatcher;
use Src\Infrastructure\Database\DatabaseManager;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(

            EventDispatcher::class,

            fn () => new EventDispatcher()

        );

        $this->app->singleton(

            DatabaseManager::class,

            fn () => new DatabaseManager()

        );
    }

    public function boot(): void
    {
        //
    }
}