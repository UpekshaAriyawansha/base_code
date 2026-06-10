<?php

namespace Src\Providers;

use Src\Core\Providers\ServiceProvider;
use Src\Infrastructure\Events\EventDispatcher;

class EventServiceProvider
extends ServiceProvider
{
    public function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Event Dispatcher
        |--------------------------------------------------------------------------
        */

        $this->app->singleton(

            EventDispatcher::class,

            fn () => new EventDispatcher()

        );
    }

    public function boot(): void
    {
        //
    }
}