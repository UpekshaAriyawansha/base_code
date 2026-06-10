<?php

namespace Src\Presentation\Routing;

use Src\Presentation\Middleware\Pipeline;

class Router
{
    private array $routes = [];

    public function get(
        string $path,
        callable $handler,
        array $middlewares = []
    ): void {

        $this->addRoute(
            'GET',
            $path,
            $handler,
            $middlewares
        );
    }

    public function post(
        string $path,
        callable $handler,
        array $middlewares = []
    ): void {

        $this->addRoute(
            'POST',
            $path,
            $handler,
            $middlewares
        );
    }

    public function put(
        string $path,
        callable $handler,
        array $middlewares = []
    ): void {

        $this->addRoute(
            'PUT',
            $path,
            $handler,
            $middlewares
        );
    }

    public function delete(
        string $path,
        callable $handler,
        array $middlewares = []
    ): void {

        $this->addRoute(
            'DELETE',
            $path,
            $handler,
            $middlewares
        );
    }

    private function addRoute(
        string $method,
        string $path,
        callable $handler,
        array $middlewares
    ): void {

        $this->routes[$method][] = [

            'path' => $path,

            'handler' => $handler,

            'middlewares' => $middlewares

        ];
    }

    public function dispatch(
        string $method,
        string $uri
    ): void {

        $routes =
            $this->routes[$method]
            ?? [];

        foreach ($routes as $route) {

            $pattern = preg_replace(

                '#\{([^/]+)\}#',

                '([^/]+)',

                $route['path']

            );

            $pattern =
                "#^{$pattern}$#";

            if (

                preg_match(

                    $pattern,

                    $uri,

                    $matches

                )

            ) {

                array_shift(
                    $matches
                );

                $pipeline =
                    new Pipeline();

                $pipeline->process(

                    $route['middlewares']
                    ?? [],

                    function () use (
                        $route,
                        $matches
                    ) {

                        return call_user_func_array(

                            $route['handler'],

                            $matches

                        );
                    }

                );

                return;
            }
        }

        http_response_code(404);

        echo "404 Not Found";
    }
}