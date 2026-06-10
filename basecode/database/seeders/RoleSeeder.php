<?php

use Src\Infrastructure\Database\Seeder;
use Modules\Role\Domain\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        echo "RoleSeeder is running..." . PHP_EOL;

        $roles = ['admin', 'user1'];

        foreach ($roles as $roleName) {

            $exists = Role::query()
                ->table('roles')
                ->where('name', $roleName)
                ->first();

            if (!$exists) {

                Role::query()
                    ->table('roles')
                    ->insert([
                        'name' => $roleName
                    ]);
            }
        }

        echo "Roles seeded successfully." . PHP_EOL;
    }
}