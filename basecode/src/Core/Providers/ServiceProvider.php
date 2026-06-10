<?php

namespace Src\Core\Providers;

use Src\Core\Container\Container;

abstract class ServiceProvider
{
    protected Container $app;

    public function __construct(
        Container $app
    ) {

        $this->app =
            $app;
    }

    /*
    |--------------------------------------------------------------------------
    | Register Services
    |--------------------------------------------------------------------------
    */

    abstract public function register():
        void;

    /*
    |--------------------------------------------------------------------------
    | Boot Services
    |--------------------------------------------------------------------------
    */

    public function boot(): void
    {
        //
    }
}