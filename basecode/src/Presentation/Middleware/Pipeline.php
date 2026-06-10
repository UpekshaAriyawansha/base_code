<?php

namespace Src\Presentation\Middleware;

class Pipeline
{
    public function process(
        array $middlewares,
        callable $destination
    )
    {
        $pipeline =
            array_reduce(

                array_reverse(
                    $middlewares
                ),

                function (
                    $next,
                    $middleware
                ) {

                    return function () use (
                        $middleware,
                        $next
                    ) {

                        if (
                            is_array(
                                $middleware
                            )
                        ) {

                            [
                                $class,
                                $params
                            ] = $middleware;

                            return (
                                new $class()
                            )->handle(
                                $next,
                                ...$params
                            );
                        }

                        return (
                            new $middleware()
                        )->handle(
                            $next
                        );
                    };
                },

                $destination

            );

        return $pipeline();
    }
}