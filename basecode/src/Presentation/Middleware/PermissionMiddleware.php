<?php

namespace Src\Presentation\Middleware;

use Src\Presentation\Http\Response;
use Modules\Role\Infrastructure\Persistence\UserRoleRepository;
use Modules\Role\Infrastructure\Persistence\RolePermissionRepository;

class PermissionMiddleware
{
    public function handle(
        callable $next,
        string $permission
    )
    {
        $user =
            $_SERVER['user']
            ?? null;

        if (!$user) {

            Response::json(
                [
                    'message' => 'Unauthorized'
                ],
                401
            );

            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | Load User Roles
        |--------------------------------------------------------------------------
        */

        $userRoleRepository =
            new UserRoleRepository();

        $roleIds =
            $userRoleRepository
                ->getRoleIds(
                    (int) $user['id']
                );

        /*
        |--------------------------------------------------------------------------
        | Load Permissions
        |--------------------------------------------------------------------------
        */

        $rolePermissionRepository =
            new RolePermissionRepository();

        $permissions =
            $rolePermissionRepository
                ->getPermissionsByRoles(
                    $roleIds
                );

        /*
        |--------------------------------------------------------------------------
        | Permission Check
        |--------------------------------------------------------------------------
        */

        if (
            !in_array(
                $permission,
                $permissions
            )
        ) {

            Response::json(
                [
                    'message' => 'Forbidden'
                ],
                403
            );

            return null;
        }

        return $next();
    }
}