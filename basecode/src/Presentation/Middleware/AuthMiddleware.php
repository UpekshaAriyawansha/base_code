<?php

namespace Src\Presentation\Middleware;

use Src\Infrastructure\Auth\JwtService;
use Src\Presentation\Http\Response;

class AuthMiddleware
{
    public function handle(
        callable $next
    )
    {
        $headers =
            getallheaders();

        $authorization =
            $headers['Authorization']
            ?? $headers['authorization']
            ?? '';

        if (!$authorization) {

            Response::json(

                [
                    'message' =>
                        'Unauthorized'
                ],

                401

            );

            return null;
        }

        $token =
            str_replace(

                'Bearer ',

                '',

                $authorization

            );

        try {

            $jwt =
                new JwtService();

            $decoded =
                $jwt->decode(
                    $token
                );

            $_SERVER['user'] =
                (array)
                $decoded->data;

            return $next();

        } catch (\Throwable $e) {

            Response::json(

                [

                    'message' =>
                        $e->getMessage(),

                    'file' =>
                        $e->getFile(),

                    'line' =>
                        $e->getLine()

                ],

                500

            );

            return null;
        }
    }
}