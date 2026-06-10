<?php

namespace Modules\Permission\Presentation\Controllers;

use Modules\Permission\Application\Services\PermissionService;
use Src\Presentation\Controllers\Controller;

class PermissionController
extends Controller
{
    public function __construct(
        private PermissionService $service
    ) {
    }

    public function index(): void
    {
        $this->success(

            'Permissions',

            $this->service->all()

        );
    }

    public function create(): void
    {
        $data = json_decode(

            file_get_contents(
                'php://input'
            ),

            true

        );

        $created =
            $this->service
                ->create($data);

        $this->success(

            'Permission created',

            [
                'created' =>
                    $created
            ]

        );
    }


    // TODO: Implement permission update
public function update(
    int $id
): void {

    $this->success(
        'Permission update not implemented yet'
    );
}

// TODO: Implement permission delete
public function delete(
    int $id
): void {

    $this->success(
        'Permission delete not implemented yet'
    );
}
}