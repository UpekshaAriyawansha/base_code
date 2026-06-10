<?php

namespace Modules\Audit\Application\Events;

class RoleUpdatedAuditEvent
{
    public function __construct(
        public ?int $userId,
        public int $roleId,
        public array $metadata = []
    ) {}
}