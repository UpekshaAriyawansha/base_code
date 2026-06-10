<?php

namespace Modules\User\Presentation\Controllers;

use Modules\User\Infrastructure\Persistence\UserRepository;
use Src\Infrastructure\Auth\JwtService;
use Src\Presentation\Http\Response;

use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class AuthController
{
    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    public function login(): void
    {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        $repository = new UserRepository();

        $user = $repository->findByEmail(
            $email
        );

        if (
            !$user ||
            !password_verify(
                $password,
                $user['password']
            )
        ) {
            Response::json(
                [
                    'message' => 'Invalid credentials'
                ],
                401
            );

            return;
        }

        $permissions = $repository->permissions(
            (int) $user['id']
        );

        /*
        |--------------------------------------------------------------------------
        | AUDIT LOGIN
        |--------------------------------------------------------------------------
        */try {
    $audit = new AuditLogService(
        new AuditLogRepository()
    );

    $audit->log(
        'LOGIN',
        'AUTH',
        (int) $user['id'],
        (int) $user['id'],
        'USER',
        'User logged in',
        ['email' => $user['email']]
    );
} catch (\Throwable $e) {
    error_log("Audit login failed: " . $e->getMessage());
}


        $jwt = new JwtService();

        $token = $jwt->generate([
            'id' => $user['id'],
            'email' => $user['email'],
            'role' => $user['role']
        ]);

        Response::json([
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'first_name' => $user['first_name'] ?? null,
                'last_name' => $user['last_name'] ?? null,
                'email' => $user['email'],
                'role' => $user['role'],
                'permissions' => $permissions
            ]
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */
    public function logout(): void
    {
        $user = $_SERVER['user'] ?? null;

        if ($user) {

            try {

                $audit = new AuditLogService(
                    new AuditLogRepository()
                );

                $audit->log(
                    'LOGOUT',
                    'AUTH',
                    (int) $user['id'],
                    (int) $user['id'],
                    'USER',
                    'User logged out',
                    [
                        'email' => $user['email'] ?? null
                    ]
                );

            } catch (\Throwable $e) {

                error_log(
                    'Audit logout failed: '
                    . $e->getMessage()
                );
            }
        }

        Response::json([
            'message' => 'Logged out successfully'
        ]);
    }
}