<?php

namespace Modules\User\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;
use Modules\User\Application\Services\UserService;
use Modules\Role\Application\Services\RoleService;
use Src\Presentation\Http\Response;

use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class UserController extends Controller
{
    private UserService $service;
    private RoleService $roleService;

    public function __construct(
        UserService $service,
        RoleService $roleService
    ) {
        $this->service = $service;
        $this->roleService = $roleService;
    }

    /*
    |--------------------------------------------------------------------------
    | LIST USERS
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        $users = $this->service->all();

        Response::json($users);
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW USER
    |--------------------------------------------------------------------------
    */
    public function show(int $id): void
    {
        $user = $this->service->find($id);

        if (!$user) {
            $this->error('User not found', 404);
            return;
        }

        $this->json($user);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER
    |--------------------------------------------------------------------------
    */
    public function create(): void
    {
        $data = (
            new \Modules\User\Presentation\Requests\StoreUserRequest()
        )->validate();

        $id = $this->service->create($data);

        $this->success(
            'User created',
            [
                'id' => $id
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE USER
    |--------------------------------------------------------------------------
    */
    public function update(int $id): void
    {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        $updated = $this->service->update(
            $id,
            $data
        );

        $this->success(
            'User updated',
            [
                'updated' => $updated
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE USER
    |--------------------------------------------------------------------------
    */
    public function delete(int $id): void
    {
        $deleted = $this->service->delete($id);

        $this->success(
            'User deleted',
            [
                'deleted' => $deleted
            ]
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ASSIGN ROLE TO USER
    |--------------------------------------------------------------------------
    */
    public function assignRole(int $id): void
    {
        $data = json_decode(
            file_get_contents("php://input"),
            true
        );

        if (
            !$data ||
            !isset($data['role_id'])
        ) {
            $this->error(
                'role_id is required',
                422
            );
            return;
        }

        $assigned = $this->roleService->assignRole(
            $id,
            (int) $data['role_id']
        );

        /*
        |--------------------------------------------------------------------------
        | AUDIT: ROLE ASSIGNED
        |--------------------------------------------------------------------------
        */
        try {

            $audit = new AuditLogService(
                new AuditLogRepository()
            );

            $audit->log(
                'ROLE_ASSIGNED',
                'ROLE',
                $_SERVER['user']['id'] ?? null,
                $id,
                'USER',
                'Role assigned to user',
                [
                    'role_id' => (int) $data['role_id']
                ]
            );

        } catch (\Throwable $e) {

            error_log(
                'Role assignment audit failed: '
                . $e->getMessage()
            );
        }

        $this->success(
            'Role assigned',
            [
                'assigned' => $assigned
            ]
        );
    }
}