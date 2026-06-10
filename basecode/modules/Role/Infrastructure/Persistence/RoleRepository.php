<?php

namespace Modules\Role\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;
use PDO;

class RoleRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }


/**
 * Get single role with permissions
 */
public function find(int $id): ?array
{
    $stmt = $this->db->prepare("
        SELECT
            roles.id,
            roles.name,
            roles.slug,
            roles.description
        FROM roles
        WHERE roles.id = ?
        LIMIT 1
    ");

    $stmt->execute([$id]);

    $role = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$role) {
        return null;
    }

    // Load permissions assigned to role
    $permissionStmt = $this->db->prepare("
        SELECT permissions.slug
        FROM role_permissions
        INNER JOIN permissions
            ON permissions.id = role_permissions.permission_id
        WHERE role_permissions.role_id = ?
    ");

    $permissionStmt->execute([$id]);

    $role['permissions'] = $permissionStmt->fetchAll(PDO::FETCH_COLUMN);

    return $role;
}






    /**
     * Get all roles with permissions (GROUPED)
     */
    public function all(): array
    {
        $stmt = $this->db->query("
            SELECT 
                roles.id,
                roles.name,
                roles.slug,
                roles.description,
                permissions.slug AS permission
            FROM roles
            LEFT JOIN role_permissions 
                ON role_permissions.role_id = roles.id
            LEFT JOIN permissions 
                ON permissions.id = role_permissions.permission_id
        ");

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $roles = [];

        foreach ($rows as $row) {

            $roleId = $row['id'];

            if (!isset($roles[$roleId])) {
                $roles[$roleId] = [
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'slug' => $row['slug'],
                    'description' => $row['description'],
                    'permissions' => []
                ];
            }

            if (!empty($row['permission'])) {
                $roles[$roleId]['permissions'][] = $row['permission'];
            }
        }

        return array_values($roles);
    }


    public function create(array $data): int
{
    $db = \Src\Infrastructure\Database\Database::connection();

    $stmt = $db->prepare("
        INSERT INTO roles (name, slug, description)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([
        $data['name'],
        $data['slug'],
        $data['description'] ?? null
    ]);

    return (int) $db->lastInsertId();
}

    /**
     * Update role
     */
    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE roles
            SET name = ?, slug = ?, description = ?
            WHERE id = ?
        ");

        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'] ?? null,
            $id
        ]);
    }

    /**
     * Delete role
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM roles WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }

}
