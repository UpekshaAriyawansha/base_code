<?php

namespace Modules\Audit\Domain\Models;

use Src\Infrastructure\Database\Model;

class AuditLog extends Model
{
    protected static string $table = 'audit_logs';
}