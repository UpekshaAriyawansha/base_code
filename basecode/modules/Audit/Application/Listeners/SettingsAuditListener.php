<?php

namespace Modules\Audit\Application\Listeners;

use Modules\Audit\Infrastructure\Persistence\AuditLogRepository;
use Modules\Audit\Application\Events\SettingsAuditEvent;

class SettingsAuditListener
{
    public function __construct(
        private AuditLogRepository $repository
    ) {}

public function handle(
    SettingsAuditEvent $event
): void {

    error_log('SETTINGS AUDIT INSERT');

    $result = $this->repository->create([
        'event_type' => $event->action,
        'module'     => $event->module,
        'user_id'    => $event->userId,
        'entity_id'  => $event->entityId,
        'description'=> 'Settings updated',
        'metadata'   => json_encode(
            $event->metadata
        )
    ]);

    error_log(
        'AUDIT RESULT: ' .
        json_encode($result)
    );
}

    //     error_log(
    //         'SETTINGS AUDIT INSERT'
    //     );

    //     $this->repository->create([
    //         'event_type' => $event->action,
    //         'module'     => $event->module,
    //         'user_id'    => $event->userId,
    //         'entity_id'  => $event->entityId,
    //         'description'=> 'Settings updated',
    //         'metadata'   => json_encode(
    //             $event->metadata
    //         )
    //     ]);
    // }
}