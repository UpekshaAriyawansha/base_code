<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\RoleUpdatedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class RoleUpdatedAuditListener
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
        RoleUpdatedAuditEvent $event
    ): void {

        $this->audit->log(
            'ROLE_UPDATED',
            'ROLE',
            $event->userId,
            $event->roleId,
            'ROLE',
            'Role updated',
            $event->metadata
        );
    }
}