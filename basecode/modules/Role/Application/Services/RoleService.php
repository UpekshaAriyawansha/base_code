<?php

namespace Modules\Role\Application\Services;

use Modules\Role\Infrastructure\Persistence\RoleRepository;
use Modules\Role\Infrastructure\Persistence\UserRoleRepository;
use Modules\Role\Infrastructure\Persistence\RolePermissionRepository;
use Src\Core\Exceptions\BusinessException;

use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class RoleService
{
    private AuditLogService $audit;

    public function __construct(
        private RoleRepository $repository,
        private UserRoleRepository $userRoleRepository,
        private RolePermissionRepository $rolePermissionRepository
    ) {
        $this->audit = new AuditLogService(
            new AuditLogRepository()
        );
    }

    public function all(): array
    {
        return $this->repository->all();
    }

    public function find(int $id): ?array
    {
        return $this->repository->find($id);
    }

    public function create(array $data): int
    {
        try {

            $roleId = $this->repository->create($data);

            if (!empty($data['permissions'])) {

                foreach ($data['permissions'] as $permissionId) {

                    $this->rolePermissionRepository->assign(
                        $roleId,
                        (int)$permissionId
                    );
                }
            }

            $this->audit->log(
                'ROLE_CREATED',
                'ROLE',
                $_SERVER['user']['id'] ?? null,
                $roleId,
                'ROLE',
                'Role created',
                [
                    'name' => $data['name'] ?? null,
                    'slug' => $data['slug'] ?? null
                ]
            );

            return $roleId;

        } catch (\Throwable $e) {

            if (
                str_contains(
                    $e->getMessage(),
                    'Duplicate entry'
                )
            ) {
                throw new BusinessException(
                    'Role slug already exists'
                );
            }

            throw $e;
        }
    }

    public function update(
        int $id,
        array $data
    ): bool {

        $updated =
            $this->repository->update(
                $id,
                $data
            );

        $this->rolePermissionRepository
            ->deleteByRole($id);

        if (!empty($data['permissions'])) {

            foreach (
                $data['permissions']
                as $permissionId
            ) {

                $this->rolePermissionRepository
                    ->assign(
                        $id,
                        (int)$permissionId
                    );
            }
        }

        if ($updated) {

            $this->audit->log(
                'ROLE_UPDATED',
                'ROLE',
                $_SERVER['user']['id'] ?? null,
                $id,
                'ROLE',
                'Role updated',
                [
                    'name' => $data['name'] ?? null,
                    'slug' => $data['slug'] ?? null
                ]
            );
        }

        return $updated;
    }

    public function delete(
        int $id
    ): bool {

        $deleted =
            $this->repository->delete($id);

        if ($deleted) {

            $this->audit->log(
                'ROLE_DELETED',
                'ROLE',
                $_SERVER['user']['id'] ?? null,
                $id,
                'ROLE',
                'Role deleted'
            );
        }

        return $deleted;
    }

    public function assignRole(
        int $userId,
        int $roleId
    ): bool {

        return $this->userRoleRepository
            ->assign(
                $userId,
                $roleId
            );
    }

    public function assignPermission(
        int $roleId,
        int $permissionId
    ): bool {

        return $this->rolePermissionRepository
            ->assign(
                $roleId,
                $permissionId
            );
    }

    public function userRoles(
        int $userId
    ): array {

        return $this->userRoleRepository
            ->getRolesByUser(
                $userId
            );
    }
}