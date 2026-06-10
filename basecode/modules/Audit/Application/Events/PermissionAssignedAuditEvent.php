<?php

namespace Modules\Audit\Application\Events;

class PermissionAssignedAuditEvent
{
    public function __construct(
        public ?int $userId,
        public int $roleId,
        public int $permissionId,
        public array $metadata = []
    ) {}
}