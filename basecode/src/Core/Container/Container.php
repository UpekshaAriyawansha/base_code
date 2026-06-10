<?php

namespace Src\Core\Container;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;

class Container
{
    /**
     * Transient bindings
     */
    private array $bindings = [];

    /**
     * Singleton instances
     */
    private array $instances = [];

    /*
    |--------------------------------------------------------------------------
    | Bind
    |--------------------------------------------------------------------------
    */

    public function bind(
        string $abstract,
        callable $resolver
    ): void {

        $this->bindings[$abstract] =
            $resolver;
    }

    /*
    |--------------------------------------------------------------------------
    | Singleton
    |--------------------------------------------------------------------------
    */

    public function singleton(
        string $abstract,
        callable $resolver
    ): void {

        $this->instances[$abstract] =
            $resolver();
    }

    /*
    |--------------------------------------------------------------------------
    | Get Alias
    |--------------------------------------------------------------------------
    */

    public function get(
        string $abstract
    ): mixed {

        return $this->make(
            $abstract
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Dependency
    |--------------------------------------------------------------------------
    */

    public function make(
        string $abstract
    ): mixed {

        /*
        |--------------------------------------------------------------------------
        | Existing Singleton
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $this->instances[$abstract]
            )
        ) {

            return $this->instances[$abstract];
        }

        /*
        |--------------------------------------------------------------------------
        | Bound Resolver
        |--------------------------------------------------------------------------
        */

        if (
            isset(
                $this->bindings[$abstract]
            )
        ) {

            return $this->bindings[$abstract]();
        }

        /*
        |--------------------------------------------------------------------------
        | Class Exists
        |--------------------------------------------------------------------------
        */

        if (
            !class_exists($abstract)
        ) {

            throw new \Exception(

                "Class {$abstract} not found."

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Reflection
        |--------------------------------------------------------------------------
        */

        $reflection =
            new ReflectionClass(
                $abstract
            );

        /*
        |--------------------------------------------------------------------------
        | Not Instantiable
        |--------------------------------------------------------------------------
        */

        if (
            !$reflection->isInstantiable()
        ) {

            throw new \Exception(

                "Class {$abstract} is not instantiable."

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Constructor
        |--------------------------------------------------------------------------
        */

        $constructor =
            $reflection
                ->getConstructor();

        /*
        |--------------------------------------------------------------------------
        | No Constructor
        |--------------------------------------------------------------------------
        */

        if (!$constructor) {

            return new $abstract();
        }

        /*
        |--------------------------------------------------------------------------
        | Resolve Dependencies
        |--------------------------------------------------------------------------
        */

        $dependencies = [];

        foreach (
            $constructor->getParameters()
            as $parameter
        ) {

            $dependencies[] =
                $this->resolveParameter(
                    $parameter
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Create Instance
        |--------------------------------------------------------------------------
        */

        return $reflection
            ->newInstanceArgs(
                $dependencies
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Resolve Constructor Parameter
    |--------------------------------------------------------------------------
    */

    private function resolveParameter(
        ReflectionParameter $parameter
    ): mixed {

        $type =
            $parameter->getType();

        /*
        |--------------------------------------------------------------------------
        | No Type Hint
        |--------------------------------------------------------------------------
        */

        if (!$type) {

            if (
                $parameter->isDefaultValueAvailable()
            ) {

                return $parameter
                    ->getDefaultValue();
            }

            throw new \Exception(

                "Cannot resolve parameter \${$parameter->getName()}"

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Built-in Types
        |--------------------------------------------------------------------------
        */

        if (
            $type instanceof ReflectionNamedType
            &&
            $type->isBuiltin()
        ) {

            if (
                $parameter->isDefaultValueAvailable()
            ) {

                return $parameter
                    ->getDefaultValue();
            }

            throw new \Exception(

                "Cannot resolve built-in parameter \${$parameter->getName()}"

            );
        }

        /*
        |--------------------------------------------------------------------------
        | Class Dependency
        |--------------------------------------------------------------------------
        */

        return $this->make(
            $type->getName()
        );
    }
}