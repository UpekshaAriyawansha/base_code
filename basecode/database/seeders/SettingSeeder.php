<?php

use Src\Infrastructure\Database\Seeder;
use Modules\Setting\Domain\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [

            'branding.app_name'
                => 'BaseCode',

            'branding.mode'
                => 'text',

            'branding.logo'
                => '',

            'app.timezone'
                => 'Asia/Colombo',

            'mail.host'
                => '',

            'mail.port'
                => '587',

            'mail.username'
                => '',

            'mail.password'
                => '',

            'mail.encryption'
                => 'tls',

            'mail.sender_name'
                => 'BaseCode',

            'mail.sender_address'
                => ''

        ];

        foreach ($settings as $key => $value) {

            Setting::create([

                'setting_key'
                    => $key,

                'setting_value'
                    => $value

            ]);
        }
    }
}