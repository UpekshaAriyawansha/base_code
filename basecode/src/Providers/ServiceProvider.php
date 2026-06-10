<?php

namespace Src\Providers;

use Src\Core\Container\Container;

abstract class ServiceProvider
{
    protected Container $container;

    public function __construct(
        Container $container
    ) {

        $this->container =
            $container;
    }

    abstract public function register():
        void;
}