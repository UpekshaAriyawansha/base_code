<?php

namespace Modules\User\Infrastructure\Persistence;

use Src\Infrastructure\Database\Database;

use PDO;

use Src\Infrastructure\Database\QueryBuilder;

class UserRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $database = new Database();

        $this->pdo = $database->connection();
    }


    public function findByEmail(
        string $email
    ): ?array {

        $stmt = $this->pdo->prepare(

            "SELECT

                users.*,

                roles.slug AS role

            FROM users

            LEFT JOIN roles
                ON roles.id = users.role_id

            WHERE users.email = :email

            LIMIT 1"

        );

        $stmt->execute([
            'email' => $email
        ]);

        $user =
            $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
    }

public function all(): array
{
    $stmt = $this->pdo->query(

        "SELECT

            users.id,
            users.first_name,
            users.last_name,
            users.email,
            users.status,
            users.role_id,

            roles.name AS role_name,
            roles.slug AS role_slug,

            users.created_at

        FROM users

        LEFT JOIN roles
            ON roles.id = users.role_id

        ORDER BY users.id "

    );

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public function create(array $data): string
{
    $stmt = $this->pdo->prepare("

        INSERT INTO users
        (
            first_name,
            last_name,
            email,
            password,
            role_id,
            status
        )
        VALUES
        (
            :first_name,
            :last_name,
            :email,
            :password,
            :role_id,
            :status
        )

    ");

    $stmt->execute([
        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'email'      => $data['email'],
        'password'   => password_hash($data['password'], PASSWORD_BCRYPT),

        // ✅ IMPORTANT FIX
        'role_id'    => $data['role_id'] ?? null,
        'status'     => $data['status'] ?? 'active'
    ]);

    return $this->pdo->lastInsertId();
}




public function findById(
    int $id
): ?array {

    $stmt = $this->pdo->prepare(

        "SELECT *
         FROM users
         WHERE id = :id
         LIMIT 1"

    );

    $stmt->execute([
        'id' => $id
    ]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    return $user ?: null;
}



public function update(
    int $id,
    array $data
): bool {

    $sql = "

        UPDATE users
        SET
            first_name = :first_name,
            last_name  = :last_name,
            email      = :email,
            role_id    = :role_id,
            status     = :status
    ";

    if (!empty($data['password'])) {

        $sql .= ",
            password = :password
        ";
    }

    $sql .= "
        WHERE id = :id
    ";

    $stmt = $this->pdo->prepare($sql);

    $params = [

        'id' => $id,

        'first_name' => $data['first_name'],
        'last_name'  => $data['last_name'],
        'email'      => $data['email'],
        'role_id'    => $data['role_id'] ?? null,
        'status'     => $data['status'] ?? 'active'
    ];

    if (!empty($data['password'])) {

        $params['password'] =
            $data['password'];
    }

    return $stmt->execute(
        $params
    );
}


public function delete(
    int $id
): bool {

    $stmt = $this->pdo->prepare(

        "DELETE FROM users
         WHERE id = :id"

    );

    return $stmt->execute([
        'id' => $id
    ]);
}


public function permissions(
    int $userId
): array {

    $stmt = $this->pdo->prepare(

        "SELECT permissions.slug

         FROM users

         INNER JOIN roles
            ON roles.id = users.role_id

         INNER JOIN role_permissions
            ON role_permissions.role_id = roles.id

         INNER JOIN permissions
            ON permissions.id =
                role_permissions.permission_id

         WHERE users.id = :id"

    );

    $stmt->execute([
        'id' => $userId
    ]);

    return array_column(

        $stmt->fetchAll(
            \PDO::FETCH_ASSOC
        ),

        'slug'

    );
}

}