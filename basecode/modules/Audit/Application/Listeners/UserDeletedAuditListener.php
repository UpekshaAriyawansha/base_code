<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\UserDeletedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class UserDeletedAuditListener
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
        UserDeletedAuditEvent $event
    ): void {

        $this->audit->log(
            'USER_DELETED',
            'USER',
            $event->userId,
            $event->entityId,
            'USER',
            $event->description,
            $event->metadata
        );
    }
}