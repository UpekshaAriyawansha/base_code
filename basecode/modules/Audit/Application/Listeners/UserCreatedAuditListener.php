<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\UserCreatedAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class UserCreatedAuditListener
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
        UserCreatedAuditEvent $event
    ): void {

        $this->audit->log(
            'USER_CREATED',
            'USER',
            $event->userId,
            $event->entityId,
            'USER',
            $event->description,
            $event->metadata
        );
    }
}