<?php

namespace Modules\User\Presentation\Resources;

use Src\Presentation\Resources\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(
        array $user
    ): array {

        return [

            'id' =>
                $user['id'],

            'first_name' =>
                $user['first_name'],

            'last_name' =>
                $user['last_name'],

            'email' =>
                $user['email'],

            'status' =>
                $user['status'] ?? null,

            'role' => [

                'id' =>
                    $user['role_id'] ?? null,

                'name' =>
                    $user['role_name'] ?? null,

                'slug' => $user['role_slug'] ?? null

            ]

        ];
    }
}