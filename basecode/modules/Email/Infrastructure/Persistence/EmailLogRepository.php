<?php

namespace Modules\Email\Infrastructure\Persistence;

use Modules\Email\Domain\Models\EmailLog;

class EmailLogRepository
{
    public function create(array $data): bool
    {
        return (bool) EmailLog::create($data);
    }

    public function all(): array
    {
        return EmailLog::query()
            ->orderBy('id', 'DESC')
            ->get();
    }
}