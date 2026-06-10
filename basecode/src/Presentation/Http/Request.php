<?php

namespace Src\Presentation\Http;

class Request
{
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

public function uri(): string
{
    $uri = parse_url(
        $_SERVER['REQUEST_URI'],
        PHP_URL_PATH
    );

    $basePath =
        '/basecode/public';

    if (
        str_starts_with(
            $uri,
            $basePath
        )
    ) {

        $uri =
            substr(
                $uri,
                strlen($basePath)
            );
    }

    return $uri ?: '/';
}

}