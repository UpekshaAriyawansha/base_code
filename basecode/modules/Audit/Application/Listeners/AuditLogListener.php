<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Application\Services\AuditLogService;
use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;

class AuditLogListener
{
    private AuditLogService $service;

    public function __construct()
    {
        $this->service =
            new AuditLogService(
                new AuditLogRepository()
            );
    }

    public function handle(
        object $event
    ): void {

        $this->service->log(

            $event->eventType,

            $event->module,

            $event->userId,

            $event->entityId,

            $event->entityType,

            $event->description,

            $event->metadata ?? []

        );
    }
}