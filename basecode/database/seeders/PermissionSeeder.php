<?php

use Src\Infrastructure\Database\Seeder;
use Modules\Permission\Domain\Models\Permission;
use Modules\Role\Domain\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        echo "PermissionSeeder is running..." . PHP_EOL;

        $permissions = [

            // USER MANAGEMENT
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            // ROLE MANAGEMENT
            'roles.view',
            'roles.create',
            'roles.update',
            'roles.delete',
            'roles.assign',

            // PERMISSION MANAGEMENT
            'permissions.view',
            'permissions.create',
            'permissions.update',
            'permissions.delete',
            'permissions.assign',

            // SETTINGS
            'settings.view',
            'settings.update',

            // UPLOAD
            'upload.create',

            // ADMIN
            'admin.access',
        ];

        /*
        |--------------------------------------------------------------------------
        | 1. CREATE PERMISSIONS (SAFE UPSERT STYLE)
        |--------------------------------------------------------------------------
        */
        foreach ($permissions as $permissionName) {

            $exists = Permission::query()
                ->table('permissions')
                ->where('slug', $permissionName)
                ->first();

            if (!$exists) {

                Permission::create([
                    'name'        => $permissionName,
                    'slug'        => $permissionName,
                    'description' => null
                ]);
            }
        }

        echo "Permissions inserted successfully." . PHP_EOL;

        /*
        |--------------------------------------------------------------------------
        | 2. GET ADMIN ROLE
        |--------------------------------------------------------------------------
        */
        $adminRole = Role::query()
            ->table('roles')
            ->where('name', 'admin')
            ->first();

        if (!$adminRole) {
            echo "Admin role not found. Skipping assignment." . PHP_EOL;
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | 3. ASSIGN PERMISSIONS TO ADMIN ROLE (SAFE)
        |--------------------------------------------------------------------------
        */
        foreach ($permissions as $permissionName) {

            $permission = Permission::query()
                ->table('permissions')
                ->where('slug', $permissionName)
                ->first();

            if (!$permission) {
                continue;
            }

            $exists = Role::query()
                ->table('role_permissions')
                ->where('role_id', $adminRole['id'])
                ->where('permission_id', $permission['id'])
                ->first();

            if (!$exists) {

                Role::query()
                    ->table('role_permissions')
                    ->insert([
                        'role_id'       => $adminRole['id'],
                        'permission_id' => $permission['id']
                    ]);
            }
        }

        echo "Permissions assigned to admin successfully." . PHP_EOL;
    }
}