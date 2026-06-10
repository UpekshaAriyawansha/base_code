<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\PermissionAssignedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class PermissionAssignedAuditListener
{
    private AuditLogService $audit;

    public function __construct()
    {
        $this->audit =
            new AuditLogService(
                new AuditLogRepository()
            );
    }

    public function handle(
        PermissionAssignedAuditEvent $event
    ): void {

        $this->audit->log(
            'PERMISSION_ASSIGNED',
            'ROLE',
            $event->userId,
            $event->roleId,
            'ROLE',
            'Permission assigned to role',
            [
                'permission_id' => $event->permissionId,
                ...$event->metadata
            ]
        );
    }
}