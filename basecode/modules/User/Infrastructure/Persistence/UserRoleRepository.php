
<!-- namespace Modules\User\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;
use PDO;

class UserRoleRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    /**
     * Assign role to user (replace existing)
     */
    public function assign(int $userId, int $roleId): bool
    {
        // delete old role first (clean RBAC logic)
        $this->remove($userId);

        $stmt = $this->db->prepare("
            INSERT INTO user_roles (user_id, role_id)
            VALUES (?, ?)
        ");

        return $stmt->execute([$userId, $roleId]);
    }

    /**
     * Remove role
     */
    public function remove(int $userId): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM user_roles WHERE user_id = ?
        ");

        return $stmt->execute([$userId]);
    }

    /**
     * Get user role
     */
    public function getRole(int $userId): ?array
    {
        $stmt = $this->db->prepare("
            SELECT roles.*
            FROM roles
            INNER JOIN user_roles 
                ON roles.id = user_roles.role_id
            WHERE user_roles.user_id = ?
            LIMIT 1
        ");

        $stmt->execute([$userId]);

        $role = $stmt->fetch(PDO::FETCH_ASSOC);

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




} -->


// namespace Modules\Role\Infrastructure\Persistence;

// use Src\Infrastructure\Database\Database;

// class UserRoleRepository
// {
//     public function assign(
//         int $userId,
//         int $roleId
//     ): bool {

//         $db = Database::connection();

//         $stmt = $db->prepare(
//             "INSERT INTO user_roles
//             (user_id, role_id)
//             VALUES (?, ?)"
//         );

//         return $stmt->execute([
//             $userId,
//             $roleId
//         ]);
//     }

// }

