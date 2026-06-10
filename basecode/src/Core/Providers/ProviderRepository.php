<?php

namespace Src\Core\Providers;

use Src\Core\Container\Container;

class ProviderRepository
{
    private Container $app;

    public function __construct(
        Container $app
    ) {

        $this->app =
            $app;
    }

    public function load(
        array $providers
    ): void {

        foreach (
            $providers
            as $provider
        ) {

            $instance =
                new $provider(
                    $this->app
                );

            $instance->register();

            $instance->boot();
        }
    }
}