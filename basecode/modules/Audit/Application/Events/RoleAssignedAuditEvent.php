<?php

namespace Modules\Audit\Application\Events;

class RoleAssignedAuditEvent
{
    public function __construct(
        public ?int $userId,
        public int $targetUserId,
        public int $roleId,
        public array $metadata = []
    ) {}
}