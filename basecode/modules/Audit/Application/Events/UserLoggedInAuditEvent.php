<?php

namespace Modules\Audit\Application\Events;

class UserLoggedInAuditEvent
{
    public function __construct(
        public int $userId,
        public array $metadata = []
    ) {}
}