<?php

namespace Modules\Audit\Application\Services;

use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class AuditLogService
{
    public function __construct(
        private AuditLogRepository $repository
    ) {}

    public function log(
        string $eventType,
        string $module,
        ?int $userId,
        ?int $entityId,
        ?string $entityType,
        string $description,
        array $metadata = []
    ): bool {

        return $this->repository->create([

            'event_type' => $eventType,

            'module' => $module,

            'user_id' => $userId,

            'entity_id' => $entityId,

            'entity_type' => $entityType,

            'description' => $description,

            'metadata' => json_encode($metadata),

            'ip_address' =>
                $_SERVER['REMOTE_ADDR'] ?? null

        ]);
    }

    public function paginate(
        array $filters
    ): array {

        return $this->repository->paginate(

            $filters['page'] ?? 1,

            $filters['per_page'] ?? 20,

            $filters['event_type'] ?? null,

            $filters['module'] ?? null,

            $filters['user_id'] ?? null

        );
    }

    public function export(
        array $filters
    ): array {

        return $this->repository->export(

            $filters['event_type'] ?? null,

            $filters['module'] ?? null,

            $filters['user_id'] ?? null

        );
    }

    public function all(): array
    {
        return $this->repository->export();
    }
}