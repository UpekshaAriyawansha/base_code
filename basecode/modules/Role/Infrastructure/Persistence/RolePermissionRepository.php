<?php

namespace Modules\Role\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;
use Src\Core\Exceptions\BusinessException;
use PDO;

use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;


class RolePermissionRepository
{



public function assign(
    int $roleId,
    int $permissionId
): bool {

    $db = Database::connection();

    $stmt = $db->prepare("
        INSERT INTO role_permissions
        (role_id, permission_id)
        VALUES (?, ?)
    ");

    $result = $stmt->execute([
        $roleId,
        $permissionId
    ]);

    if ($result) {

        $audit = new AuditLogService(
            new AuditLogRepository()
        );

        $audit->log(
            'PERMISSION_ASSIGNED',
            'ROLE',
            $_SERVER['user']['id'] ?? null,
            $roleId,
            'ROLE',
            'Permission assigned to role',
            [
                'permission_id' => $permissionId
            ]
        );
    }

    return $result;
}

    /*
    |--------------------------------------------------------------------------
    | Get Permissions By Roles
    |--------------------------------------------------------------------------
    */

    public function getPermissionsByRoles(
        array $roleIds
    ): array {

        if (empty($roleIds)) {
            return [];
        }

        $db =
            Database::connection();

        $placeholders =
            implode(
                ',',
                array_fill(
                    0,
                    count($roleIds),
                    '?'
                )
            );

        $stmt =
            $db->prepare(

                "SELECT p.slug
                 FROM permissions p
                 INNER JOIN role_permissions rp
                    ON p.id = rp.permission_id
                 WHERE rp.role_id IN ($placeholders)"

            );

        $stmt->execute(
            $roleIds
        );

        return array_column(

            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            ),

            'slug'

        );
    }

    public function deleteByRole(int $roleId): bool
{
    $db = Database::connection();

    $stmt = $db->prepare("
        DELETE FROM role_permissions
        WHERE role_id = ?
    ");

    return $stmt->execute([$roleId]);
}






}