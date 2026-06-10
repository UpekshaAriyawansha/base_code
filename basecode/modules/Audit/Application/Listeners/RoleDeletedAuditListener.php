<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\RoleDeletedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class RoleDeletedAuditListener
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
        RoleDeletedAuditEvent $event
    ): void {

        $this->audit->log(
            'ROLE_DELETED',
            'ROLE',
            $event->userId,
            $event->roleId,
            'ROLE',
            'Role deleted',
            $event->metadata
        );
    }
}