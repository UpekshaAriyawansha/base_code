<?php

namespace Modules\User\Application\Services;

use Modules\User\Infrastructure\Persistence\UserRepository;
use Src\Infrastructure\Database\DatabaseManager;
use Src\Infrastructure\Cache\CacheManager;
use Modules\User\Presentation\Resources\UserResource;
use Src\Core\Exceptions\BusinessException;
use Src\Infrastructure\Events\EventDispatcher;

use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class UserService
{
    private UserRepository $repository;
    private DatabaseManager $db;
    private EventDispatcher $events;

    public function __construct(
        UserRepository $repository,
        DatabaseManager $db,
        EventDispatcher $events
    ) {
        $this->repository = $repository;
        $this->db = $db;
        $this->events = $events;
    }

    /*
    |--------------------------------------------------------------------------
    | LIST USERS
    |--------------------------------------------------------------------------
    */
    public function all(): array
    {
        $users = $this->repository->all();
        return UserResource::collection($users);
    }

    /*
    |--------------------------------------------------------------------------
    | FIND USER
    |--------------------------------------------------------------------------
    */
    public function find(int $id): ?array
    {
        $user = $this->repository->findById($id);
        return $user ? UserResource::make($user) : null;
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE USER + AUDIT (EVENT)
    |--------------------------------------------------------------------------
    */
    public function create(array $data): string
{
    $this->db->begin();

    try {

        $data['password'] = password_hash(
            $data['password'],
            PASSWORD_BCRYPT
        );

        $id = $this->repository->create($data);

        CacheManager::driver()->forget('users.all');

        (new AuditLogService(
            new AuditLogRepository()
        ))->log(
            'USER_CREATED',
            'USER',
            $_SERVER['user']['id'] ?? null,
            (int)$id,
            'USER',
            'User created',
            [
                'email' => $data['email'] ?? null,
                'role_id' => $data['role_id'] ?? null
            ]
        );

        $this->db->commit();

        return (string)$id;

    } catch (\Throwable $e) {

        $this->db->rollback();

        throw $e;
    }
}
    /*
    |--------------------------------------------------------------------------
    | UPDATE USER + AUDIT (EVENT)
    |--------------------------------------------------------------------------
    */
    public function update(
    int $id,
    array $data
): bool {

    if (!empty($data['password'])) {

        $data['password'] =
            password_hash(
                $data['password'],
                PASSWORD_BCRYPT
            );
    }

    $updated =
        $this->repository->update(
            $id,
            $data
        );

    if ($updated) {

        (new AuditLogService(
            new AuditLogRepository()
        ))->log(
            'USER_UPDATED',
            'USER',
            $_SERVER['user']['id'] ?? null,
            $id,
            'USER',
            'User updated',
            [
                'email' => $data['email'] ?? null
            ]
        );
    }

    return $updated;
}

    /*
    |--------------------------------------------------------------------------
    | DELETE USER + AUDIT (DIRECT LOG FIXED)
    |--------------------------------------------------------------------------
    */
    public function delete(
    int $id
): bool {

    $deleted =
        $this->repository->delete($id);

    if ($deleted) {

        (new AuditLogService(
            new AuditLogRepository()
        ))->log(
            'USER_DELETED',
            'USER',
            $_SERVER['user']['id'] ?? null,
            $id,
            'USER',
            'User deleted'
        );
    }

    return $deleted;
}


}