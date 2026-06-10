<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\RoleAssignedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class RoleAssignedAuditListener
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
        RoleAssignedAuditEvent $event
    ): void {

        $this->audit->log(
            'ROLE_ASSIGNED',
            'USER',
            $event->userId,
            $event->targetUserId,
            'USER',
            'Role assigned to user',
            [
                'role_id' => $event->roleId,
                ...$event->metadata
            ]
        );
    }
}