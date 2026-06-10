<?php

namespace Modules\Audit\Application\Events;

class RoleDeletedAuditEvent
{
    public function __construct(
        public ?int $userId,
        public int $roleId,
        public array $metadata = []
    ) {}
}