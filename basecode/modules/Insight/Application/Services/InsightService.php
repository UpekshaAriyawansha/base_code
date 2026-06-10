<?php

namespace Modules\Insight\Application\Services;

use Modules\User\Domain\Models\User;
use Modules\Role\Domain\Models\Role;
use Modules\Permission\Domain\Models\Permission;
use Modules\Setting\Domain\Models\Setting;

class InsightService
{
    public function getInsights(): array
    {
        return [
            "users_count"       => count(User::all()),
            "roles_count"       => count(Role::all()),
            "permissions_count" => count(Permission::all()),
            "settings_count"    => count(Setting::all()),

            "latest_users" => array_slice(
                User::query()->table('users')->orderBy('id', 'DESC')->get(),
                0,
                5
            ),
        ];
    }
}