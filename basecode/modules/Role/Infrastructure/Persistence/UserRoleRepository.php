<?php

namespace Modules\Role\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;
use PDO;

class UserRoleRepository
{
    public function assign(
        int $userId,
        int $roleId
    ): bool {

        $db =
            Database::connection();

        $stmt =
            $db->prepare(

                "INSERT INTO user_roles
                (user_id, role_id)
                VALUES (?, ?)"

            );

        return $stmt->execute([
            $userId,
            $roleId
        ]);
    }

    public function remove(
        int $userId
    ): bool {

        $db =
            Database::connection();

        $stmt =
            $db->prepare(

                "DELETE FROM user_roles
                 WHERE user_id = ?"

            );

        return $stmt->execute([
            $userId
        ]);
    }

    public function getRoleIds(
        int $userId
    ): array {

        $db =
            Database::connection();

        $stmt =
            $db->prepare(

                "SELECT role_id
                 FROM user_roles
                 WHERE user_id = ?"

            );

        $stmt->execute([
            $userId
        ]);

        return array_map(

            fn ($row) =>
                (int) $row['role_id'],

            $stmt->fetchAll(
                PDO::FETCH_ASSOC
            )

        );
    }

    public function getRole(
        int $userId
    ): ?array {

        $db =
            Database::connection();

        $stmt =
            $db->prepare(

                "SELECT r.*
                 FROM roles r
                 INNER JOIN user_roles ur
                    ON r.id = ur.role_id
                 WHERE ur.user_id = ?
                 LIMIT 1"

            );

        $stmt->execute([
            $userId
        ]);

        $role =
            $stmt->fetch(
                PDO::FETCH_ASSOC
            );

        return $role ?: null;
    }

public function getRolesByUser(
    int $userId
): array {

    $db =
        Database::connection();

    $stmt =
        $db->prepare(

            "SELECT r.*
             FROM roles r
             INNER JOIN user_roles ur
                ON r.id = ur.role_id
             WHERE ur.user_id = ?"

        );

    $stmt->execute([
        $userId
    ]);

    return $stmt->fetchAll(
        \PDO::FETCH_ASSOC
    );
}



}