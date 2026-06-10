<?php

namespace Modules\Setting\Application\Services;

use Modules\Setting\Infrastructure\Persistence\SettingRepository;
use Modules\Audit\Application\Events\SettingsAuditEvent;
use Src\Infrastructure\Events\EventDispatcher;

class SettingService
{
    public function __construct(
        private SettingRepository $repository,
        private EventDispatcher $events
    ) {}

    public function all(): array
    {
        return $this->repository->all();
    }

    public function create(array $data): bool
    {
        $result = $this->repository->create($data);

        $this->events->dispatch(
            new SettingsAuditEvent(
                'SETTING_CREATED',
                $_SERVER['user']['id'] ?? null,
                'SETTINGS',
                null,
                $data
            )
        );

        return $result;
    }

    public function update(int $id, array $data): bool
    {
        $result = $this->repository->update($id, $data);

        $this->events->dispatch(
            new SettingsAuditEvent(
                'SETTING_UPDATED',
                $_SERVER['user']['id'] ?? null,
                'SETTINGS',
                $id,
                $data
            )
        );

        return $result;
    }

    public function delete(int $id): bool
    {
        $result = $this->repository->delete($id);

        $this->events->dispatch(
            new SettingsAuditEvent(
                'SETTING_DELETED',
                $_SERVER['user']['id'] ?? null,
                'SETTINGS',
                $id,
                []
            )
        );

        return $result;
    }

    public function findByKey(string $key): ?array
    {
        return $this->repository->findByKey($key);
    }

    public function settings(): array
    {
        return $this->repository->getSettingsMap();
    }

    public function getGeneralSettings(): array
    {
        return $this->repository->getGeneralSettings();
    }

   public function saveGeneralSettings(array $data): bool
{
    $result = $this->repository->saveGeneralSettings($data);

    error_log('SETTINGS SERVICE CALLED');

    error_log('DISPATCHING SETTINGS EVENT');

    $this->events->dispatch(
        new SettingsAuditEvent(
            'SETTINGS_SAVED',
            $_SERVER['user']['id'] ?? null,
            'SETTINGS',
            null,
            $data
        )
    );

    return $result;
}
}