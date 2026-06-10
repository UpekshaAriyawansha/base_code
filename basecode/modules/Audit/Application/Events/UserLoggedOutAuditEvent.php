<?php

namespace Modules\Audit\Application\Events;

class UserLoggedOutAuditEvent
{
    public function __construct(
        public int $userId,
        public array $metadata = []
    ) {}
}