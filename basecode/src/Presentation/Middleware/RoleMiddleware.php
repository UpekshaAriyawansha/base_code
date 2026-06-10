<?php

namespace Src\Presentation\Middleware;

use Src\Presentation\Http\Response;

class RoleMiddleware
{
    public function handle(
        callable $next,
        string $role
    )
    {
        $user =
            $_SERVER['user']
            ?? null;

        if (!$user) {

            Response::json(

                [
                    'message' =>
                        'Unauthorized'
                ],

                401

            );

            return null;
        }

        if (

            ($user['role'] ?? null)
            !==
            $role

        ) {

            Response::json(

                [
                    'message' =>
                        'Forbidden'
                ],

                403

            );

            return null;
        }

        return $next();
    }
}