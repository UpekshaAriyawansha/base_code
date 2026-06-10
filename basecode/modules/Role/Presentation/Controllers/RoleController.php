<?php

namespace Modules\Role\Presentation\Controllers;

use Modules\Role\Application\Services\RoleService;
use Src\Presentation\Controllers\Controller;

class RoleController extends Controller
{
    public function __construct(
        private RoleService $service
    ) {
    }

    public function index(): void
{
    try {
        $this->success(
            'Roles',
            $this->service->all()
        );
    } catch (\Throwable $e) {
        http_response_code(500);

        echo json_encode([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
    }
}

    public function create(): void
    {
        $data = json_decode(
            file_get_contents(
                'php://input'
            ),
            true
        );

        $id =
            $this->service
                ->create($data);

        $this->success(
            'Role created',
            [
                'id' => $id
            ]
        );
    }

public function show(int $id): void
{
    $role = $this->service->find($id);

    $this->success('Role', $role);
}

    // TODO: Update role
public function update(
    int $id
): void {

    $data =
        json_decode(
            file_get_contents(
                'php://input'
            ),
            true
        );

    $updated =
        $this->service
            ->update(
                $id,
                $data
            );

    $this->success(

        'Role updated',

        [
            'updated' =>
                $updated
        ]

    );
}

// TODO: Delete role
public function delete(
    int $id
): void {

    $deleted =
        $this->service
            ->delete($id);

    $this->success(

        'Role deleted',

        [
            'deleted' =>
                $deleted
        ]

    );
}


public function assignPermission(
    int $roleId
): void {

    $data =
        json_decode(
            file_get_contents(
                'php://input'
            ),
            true
        );

    if (
        !$data ||
        !isset(
            $data['permission_id']
        )
    ) {

        $this->error(
            'permission_id is required',
            422
        );

        return;
    }

    $assigned =
        $this->service
            ->assignPermission(
                $roleId,
                (int) $data['permission_id']
            );

    $this->success(
        'Permission assigned',
        [
            'assigned' => $assigned
        ]
    );
}



    
}