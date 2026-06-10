<?php

namespace Modules\Role\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;

class RolePermissionRepository
{
    public function assign(
        int $roleId,
        int $permissionId
    ): bool {

        $db = Database::connection();

        $stmt = $db->prepare(
            "INSERT INTO role_permissions
            (role_id, permission_id)
            VALUES (?, ?)"
        );

        return $stmt->execute([
            $roleId,
            $permissionId
        ]);
    }
}