<?php

namespace Modules\Audit\Application\Events;

class LoginAuditEvent
{
    public string $eventType = 'user.login';
    public string $module = 'auth';
    public string $entityType = 'user';

    public function __construct(
        public int $userId,
        public int $entityId,
        public string $description,
        public array $metadata = []
    ) {}
}