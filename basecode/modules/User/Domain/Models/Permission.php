<?php

namespace Modules\User\Domain\Models;

use Src\Infrastructure\Database\Model;

class Permission extends Model
{
    protected static string $table = 'permissions';
}