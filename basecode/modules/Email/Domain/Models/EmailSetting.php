<?php

namespace Modules\Email\Domain\Models;

use Src\Infrastructure\Database\Model;

class EmailSetting extends Model
{
    protected static string $table =
        'email_settings';
}