<?php

namespace Modules\Permission\Application\Services;

use Modules\Permission\Infrastructure\Persistence\PermissionRepository;
use Src\Core\Exceptions\BusinessException;

class PermissionService
{
    public function __construct(
        private PermissionRepository $repository
    ) {
    }

    public function all(): array
    {
        return $this->repository->all();
    }

    public function create(
        array $data
    ): bool {

        try {

            return $this->repository
                ->create($data);

        } catch (\Throwable $e) {

            if (
                str_contains(
                    $e->getMessage(),
                    'Duplicate entry'
                )
            ) {

                throw new BusinessException(
                    'Permission slug already exists'
                );
            }

            throw $e;
        }
    }




    
}