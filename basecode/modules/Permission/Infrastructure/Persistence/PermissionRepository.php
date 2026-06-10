<?php

namespace Modules\Permission\Infrastructure\Persistence;

use Modules\Permission\Domain\Models\Permission;

class PermissionRepository
{
    public function all(): array
    {
        return Permission::all();
    }

    public function create(
        array $data
    ): bool {

        return Permission::create(
            $data
        );
    }
}