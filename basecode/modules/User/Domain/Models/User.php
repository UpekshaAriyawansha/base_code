<?php

namespace Modules\User\Domain\Models;

use Src\Infrastructure\Database\Model;

use Modules\User\Domain\Models\Role;

class User
extends Model
{
    protected static string $table =
        'users';

    public function role(): ?array
{
    return $this->belongsTo(

        Role::class,

        'role_id'

    );
}
}