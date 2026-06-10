<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\UserLoggedOutAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class LogoutAuditListener
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
        UserLoggedOutAuditEvent $event
    ): void {

        $this->audit->log(
            'LOGOUT',
            'AUTH',
            $event->userId,
            $event->userId,
            'USER',
            'User logged out',
            $event->metadata
        );
    }
}