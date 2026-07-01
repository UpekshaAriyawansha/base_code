<?php

namespace Modules\Setting\Infrastructure\Persistence;

use Modules\Setting\Domain\Models\Setting;

class SettingRepository
{
    public function all(): array
    {
        return Setting::all();
    }

    public function find(int $id): ?Setting
    {
        return Setting::find($id);
    }

    public function create(array $data): bool
    {
        return (bool) Setting::create($data);
    }

    public function update(int $id, array $data): bool
    {
        return (bool) Setting::query()
            ->table('settings')
            ->where('id', $id)
            ->update($data);
    }

    public function delete(int $id): bool
    {
        return (bool) Setting::query()
            ->table('settings')
            ->where('id', $id)
            ->delete();
    }

    public function findByKey(string $key): ?array
    {
        $result = Setting::query()
            ->table('settings')
            ->where('setting_key', $key)
            ->first();

        return $result ?: null;
    }

    /**
     * Save text setting
     */
    public function saveSetting(
        string $key,
        string $value,
        string $type = 'text'
    ): bool {

        $existing = $this->findByKey($key);

        if ($existing) {

            return (bool) Setting::query()
                ->table('settings')
                ->where('setting_key', $key)
                ->update([
                    'setting_value' => $value,
                    'setting_type'  => $type
                ]);
        }

        return (bool) Setting::create([
            'setting_key'   => $key,
            'setting_value' => $value,
            'setting_type'  => $type
        ]);
    }

    /**
     * Save image as Base64
     */
    public function saveImage(
        string $key,
        string $imageData
    ): bool {

        return $this->saveSetting(
            $key,
            $imageData,
            'image'
        );
    }

    public function getSettingsMap(): array
    {
        $rows = Setting::all();

        $settings = [];

        foreach ($rows as $row) {

            $key = is_array($row)
                ? $row['setting_key']
                : $row->setting_key;

            $value = is_array($row)
                ? $row['setting_value']
                : $row->setting_value;

            $settings[$key] = $value;
        }

        return $settings;
    }

    public function saveMany(array $settings): bool
    {
        foreach ($settings as $key => $value) {

            $type = 'text';

            if (
                is_string($value) &&
                str_starts_with(
                    $value,
                    'data:image'
                )
            ) {
                $type = 'image';
            }

            $this->saveSetting(
                $key,
                (string) $value,
                $type
            );
        }

        return true;
    }

public function getGeneralSettings(): array
{
    return [

        'branding.app_name' =>
            $this->findByKey('branding.app_name')['setting_value'] ?? '',

        'app.timezone' =>
            $this->findByKey('app.timezone')['setting_value'] ?? 'UTC',

        'branding.mode' =>
            $this->findByKey('branding.mode')['setting_value'] ?? 'text',

        'branding.logo' =>
            $this->findByKey('branding.logo')['setting_value'] ?? '',

        // Theme colors
        'theme.primary_color' =>
            $this->findByKey('theme.primary_color')['setting_value'] ?? '#ffffff',

        'theme.secondary_color' =>
            $this->findByKey('theme.secondary_color')['setting_value'] ?? '#e0e0e0',

        'theme.accent_color' =>
            $this->findByKey('theme.accent_color')['setting_value'] ?? '#d6d6d6',

        'theme.text_color' =>
            $this->findByKey('theme.text_color')['setting_value'] ?? '#060606',
    ];
}

// public function getGeneralSettings(): array
// {
//     return [
//         'branding.app_name' =>
//             $this->findByKey('branding.app_name')['setting_value'] ?? '',

//         'app.timezone' =>
//             $this->findByKey('app.timezone')['setting_value'] ?? 'UTC',

//         'branding.mode' =>
//             $this->findByKey('branding.mode')['setting_value'] ?? 'text',

//         'branding.logo' =>
//             $this->findByKey('branding.logo')['setting_value'] ?? '',
//     ];
// }

public function saveGeneralSettings(array $data): bool
{
    $this->saveSetting(
        'branding.app_name',
        $data['branding.app_name'] ?? ''
    );

    $this->saveSetting(
        'app.timezone',
        $data['app.timezone'] ?? 'UTC'
    );

    $this->saveSetting(
        'branding.mode',
        $data['branding.mode'] ?? 'text'
    );

    if (isset($data['branding.logo'])) {
        $this->saveSetting(
            'branding.logo',
            $data['branding.logo']
        );
    }

    // Theme colors
    $this->saveSetting(
        'theme.primary_color',
        $data['theme.primary_color'] ?? '#ffffff'
    );

    $this->saveSetting(
        'theme.secondary_color',
        $data['theme.secondary_color'] ?? '#cccccc'
    );

    $this->saveSetting(
        'theme.accent_color',
        $data['theme.accent_color'] ?? '#d6d6d6'
    );

    $this->saveSetting(
        'theme.text_color',
        $data['theme.text_color'] ?? '#000000'
    );

    return true;
}

// public function saveGeneralSettings(
//     array $data
// ): bool {

//     $this->saveSetting(
//         'branding.app_name',
//         $data['branding.app_name'] ?? ''
//     );

//     $this->saveSetting(
//         'app.timezone',
//         $data['app.timezone'] ?? 'UTC'
//     );

//     $this->saveSetting(
//         'branding.mode',
//         $data['branding.mode'] ?? 'text'
//     );

//     if (!empty($data['branding.logo'])) {

//         $this->saveImage(
//             'branding.logo',
//             $data['branding.logo']
//         );
//     }

//     return true;
// }

















}