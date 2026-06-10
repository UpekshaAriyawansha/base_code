<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Events\UserLoggedInAuditEvent;
use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class LoginAuditListener
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
        UserLoggedInAuditEvent $event
    ): void {

        $this->audit->log(
            'LOGIN',
            'AUTH',
            $event->userId,
            $event->userId,
            'USER',
            'User logged into system',
            $event->metadata
        );
    }
}