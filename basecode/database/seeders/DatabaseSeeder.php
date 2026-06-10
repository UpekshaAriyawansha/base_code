<?php

use Src\Infrastructure\Database\Seeder;
use Modules\User\Domain\Models\User;
use Modules\Setting\Domain\Models\Setting;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        echo "DatabaseSeeder is running..." . PHP_EOL;

        // USER
        $user = User::query()
            ->table('users')
            ->where('email', 'admin2@example.com')
            ->first();

        if (!$user) {
            User::create([
                'first_name' => 'Seed-Admin',
                'last_name'  => 'User',
                'email'      => 'admin10@example.com',
                'password'   => password_hash('password', PASSWORD_BCRYPT)
            ]);
        }

        // SETTINGS
        $settings = [
            'site_name' => 'BaseCode',
            'timezone'  => 'Asia/Colombo',
            'app_name'  => 'BaseCode Framework'
        ];

        foreach ($settings as $key => $value) {

            $exists = Setting::query()
                ->table('settings')
                ->where('setting_key', $key)
                ->first();

            if (!$exists) {
                Setting::create([
                    'setting_key' => $key,
                    'setting_value' => $value
                ]);
            }
        }

        echo "DatabaseSeeder completed." . PHP_EOL;
    }
}