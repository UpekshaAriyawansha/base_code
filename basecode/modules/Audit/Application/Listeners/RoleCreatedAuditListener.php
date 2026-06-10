<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\RoleCreatedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class RoleCreatedAuditListener
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
        RoleCreatedAuditEvent $event
    ): void {

        $this->audit->log(
            'ROLE_CREATED',
            'ROLE',
            $event->userId,
            $event->roleId,
            'ROLE',
            'Role created',
            $event->metadata
        );
    }
}