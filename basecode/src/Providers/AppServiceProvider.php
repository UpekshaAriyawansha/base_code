<?php

namespace Src\Providers;

use Src\Core\Providers\ServiceProvider;

use Src\Infrastructure\Database\DatabaseManager;
use Src\Infrastructure\Events\EventDispatcher;

use Modules\Role\Application\Services\RoleService;
use Modules\Role\Infrastructure\Persistence\RoleRepository;
use Modules\Role\Infrastructure\Persistence\UserRoleRepository;
use Modules\Role\Infrastructure\Persistence\RolePermissionRepository;

use Modules\Permission\Application\Services\PermissionService;
use Modules\Permission\Infrastructure\Persistence\PermissionRepository;

use Modules\Setting\Application\Services\SettingService;
use Modules\Setting\Infrastructure\Persistence\SettingRepository;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Database Manager
        |--------------------------------------------------------------------------
        */
        $this->app->singleton(
            DatabaseManager::class,
            fn () => new DatabaseManager()
        );

        /*
        |--------------------------------------------------------------------------
        | Event Dispatcher
        |--------------------------------------------------------------------------
        */
        $this->app->singleton(
            EventDispatcher::class,
            fn () => new EventDispatcher()
        );

        /*
        |--------------------------------------------------------------------------
        | ROLE
        |--------------------------------------------------------------------------
        */
        $this->app->singleton(
            RoleRepository::class,
            fn () => new RoleRepository()
        );

        $this->app->singleton(
            UserRoleRepository::class,
            fn () => new UserRoleRepository()
        );

        $this->app->singleton(
            RolePermissionRepository::class,
            fn () => new RolePermissionRepository()
        );

        $this->app->singleton(
            RoleService::class,
            fn () => new RoleService(
                $this->app->make(RoleRepository::class),
                $this->app->make(UserRoleRepository::class),
                $this->app->make(RolePermissionRepository::class),
                $this->app->make(EventDispatcher::class) // optional if your RoleService uses events
            )
        );

        /*
        |--------------------------------------------------------------------------
        | PERMISSION
        |--------------------------------------------------------------------------
        */
        $this->app->singleton(
            PermissionRepository::class,
            fn () => new PermissionRepository()
        );

        $this->app->singleton(
            PermissionService::class,
            fn () => new PermissionService(
                $this->app->make(PermissionRepository::class)
            )
        );

        /*
        |--------------------------------------------------------------------------
        | SETTINGS  ❌ FIXED HERE
        |--------------------------------------------------------------------------
        */
$this->app->singleton(
    \Modules\Audit\Infrastructure\Persistence\AuditLogRepository::class,
    fn () => new \Modules\Audit\Infrastructure\Persistence\AuditLogRepository()
);

$this->app->singleton(
    SettingService::class,
    fn () => new SettingService(
        $this->app->make(SettingRepository::class),
        $this->app->make(\Src\Infrastructure\Events\EventDispatcher::class)
    )
);
    }

    public function boot(): void
    {
        //
    }
}