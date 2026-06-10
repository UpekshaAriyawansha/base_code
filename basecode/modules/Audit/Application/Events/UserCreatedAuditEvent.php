<?php

namespace Modules\Audit\Application\Events;

class UserCreatedAuditEvent
{
    public function __construct(
        public ?int $userId,
        public int $entityId,
        public string $description,
        public array $metadata = []
    ) {}
}