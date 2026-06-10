<?php

namespace Modules\Audit\Infrastructure\Persistence;

use Modules\Audit\Domain\Models\AuditLog;

class AuditLogRepository
{
    public function create(
        array $data
    ): bool {

        return AuditLog::create(
            $data
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINATION + FILTERS
    |--------------------------------------------------------------------------
    */
    public function paginate(
        int $page = 1,
        int $perPage = 20,
        ?string $eventType = null,
        ?string $module = null,
        ?int $userId = null
    ): array {

        $query =
            AuditLog::query();

        if ($eventType) {

            $query->where(
                'event_type',
                '=',
                $eventType
            );
        }

        if ($module) {

            $query->where(
                'module',
                '=',
                $module
            );
        }

        if ($userId) {

            $query->where(
                'user_id',
                '=',
                $userId
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TOTAL (WITH FILTERS)
        |--------------------------------------------------------------------------
        */

        $total =
            count(
                $query->get()
            );

        $offset =
            ($page - 1)
            * $perPage;

        /*
        |--------------------------------------------------------------------------
        | DATA
        |--------------------------------------------------------------------------
        */

        $query =
            AuditLog::query();

        if ($eventType) {
            $query->where(
                'event_type',
                '=',
                $eventType
            );
        }

        if ($module) {
            $query->where(
                'module',
                '=',
                $module
            );
        }

        if ($userId) {
            $query->where(
                'user_id',
                '=',
                $userId
            );
        }

        $data =
            $query
                ->orderBy(
                    'id',
                    'DESC'
                )
                ->limit($perPage)
                ->offset($offset)
                ->get();

        return [

            'data' => $data,

            'pagination' => [

                'page' =>
                    $page,

                'per_page' =>
                    $perPage,

                'total' =>
                    $total,

                'total_pages' =>
                    max(
                        1,
                        (int) ceil(
                            $total /
                            max(1, $perPage)
                        )
                    )

            ]

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT
    |--------------------------------------------------------------------------
    */
    public function export(
        ?string $eventType = null,
        ?string $module = null,
        ?int $userId = null
    ): array {

        $query =
            AuditLog::query();

        if ($eventType) {

            $query->where(
                'event_type',
                '=',
                $eventType
            );
        }

        if ($module) {

            $query->where(
                'module',
                '=',
                $module
            );
        }

        if ($userId) {

            $query->where(
                'user_id',
                '=',
                $userId
            );
        }

        return $query
            ->orderBy(
                'id',
                'DESC'
            )
            ->get();
    }
}