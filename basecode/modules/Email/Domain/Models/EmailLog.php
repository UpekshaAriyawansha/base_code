<?php

namespace Modules\Email\Domain\Models;

use Src\Infrastructure\Database\Model;

class EmailLog extends Model
{
    protected static string $table = 'email_logs';
}