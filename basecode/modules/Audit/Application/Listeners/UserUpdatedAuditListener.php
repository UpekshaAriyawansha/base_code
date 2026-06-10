<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\UserUpdatedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class UserUpdatedAuditListener
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
        UserUpdatedAuditEvent $event
    ): void {

        $this->audit->log(
            'USER_UPDATED',
            'USER',
            $event->userId,
            $event->entityId,
            'USER',
            $event->description,
            $event->metadata
        );
    }
}