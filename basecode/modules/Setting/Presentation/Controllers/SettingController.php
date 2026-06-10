<?php

namespace Modules\Setting\Presentation\Controllers;

use Modules\Setting\Application\Services\SettingService;
use Src\Presentation\Controllers\Controller;

class SettingController extends Controller
{
    private SettingService $service;

    public function __construct(
        SettingService $service
    ) {
        $this->service = $service;
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Settings
    |--------------------------------------------------------------------------
    | GET /api/settings
    */

public function index(): void
{
    $settings =
        $this->service
            ->settings();

    $this->success(
        'Settings loaded',
        $settings
    );
}

    /*
    |--------------------------------------------------------------------------
    | Get Setting By Key
    |--------------------------------------------------------------------------
    | GET /api/settings/{key}
    */

    public function show(
        string $key
    ): void {

        $setting =
            $this->service->findByKey(
                $key
            );

        if (!$setting) {

            $this->error(
                'Setting not found',
                404
            );

            return;
        }

        $this->success(
            'Setting',
            $setting
        );
    }


// public function create(): void
// {
//     $data =
//         json_decode(
//             file_get_contents(
//                 'php://input'
//             ),
//             true
//         );

//     if (!$data) {

//         $this->error(
//             'Invalid payload',
//             422
//         );

//         return;
//     }

//     $this->service
//         ->saveSettings(
//             $data
//         );

//     $this->success(
//         'Settings saved'
//     );
// }






    /*
    |--------------------------------------------------------------------------
    | Update Setting
    |--------------------------------------------------------------------------
    | PUT /api/settings/{id}
    */

    public function update(
        int $id
    ): void {

        $data =
            json_decode(
                file_get_contents(
                    'php://input'
                ),
                true
            );

        $updated =
            $this->service->update(
                $id,
                $data
            );

        $this->success(
            'Setting updated',
            [
                'updated' => $updated
            ]
        );
    }

//     public function save(): void
// {
//     $data =
//         json_decode(
//             file_get_contents(
//                 'php://input'
//             ),
//             true
//         );

//     $this->service
//         ->saveSettings(
//             $data
//         );

//     $this->success(
//         'Settings updated'
//     );
// }


public function save(): void
{
    $data =
        json_decode(
            file_get_contents(
                'php://input'
            ),
            true
        );

    if (!$data) {

        $this->error(
            'Invalid payload',
            422
        );

        return;
    }

    $this->service
        ->saveGeneralSettings(
            $data
        );

    $this->success(
        'Settings saved successfully'
    );
}

    /*
    |--------------------------------------------------------------------------
    | Delete Setting
    |--------------------------------------------------------------------------
    | DELETE /api/settings/{id}
    */

    public function delete(
        int $id
    ): void {

        $deleted =
            $this->service->delete(
                $id
            );

        $this->success(
            'Setting deleted',
            [
                'deleted' => $deleted
            ]
        );
    }

    public function general(): void
{
    $this->success(
        'Settings',
        $this->service
            ->getGeneralSettings()
    );
}
}