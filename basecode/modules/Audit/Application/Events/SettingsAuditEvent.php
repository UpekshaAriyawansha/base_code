<?php

namespace Modules\Audit\Application\Events;

class SettingsAuditEvent
{
    public function __construct(
        public string $action,        // CREATED | UPDATED | DELETED | SAVED
        public ?int $userId,
        public string $module,        // SETTINGS
        public ?int $entityId,
        public array $metadata = []
    ) {}
}