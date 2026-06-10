<?php

namespace Modules\Audit\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;
use Modules\Audit\Application\Services\AuditLogService;

class AuditLogController extends Controller
{
    public function __construct(
        private AuditLogService $service
    ) {}

    /*
    |--------------------------------------------------------------------------
    | AUDIT LOG LIST
    |--------------------------------------------------------------------------
    */
    public function index(): void
    {
        $filters = [

            'page' =>
                (int) (
                    $_GET['page']
                    ?? 1
                ),

            'per_page' =>
                (int) (
                    $_GET['per_page']
                    ?? 10
                ),

            'event_type' =>
                $_GET['event_type']
                ?? null,

            'module' =>
                $_GET['module']
                ?? null,

            'user_id' =>
                isset($_GET['user_id'])
                ? (int) $_GET['user_id']
                : null

        ];

        $this->success(
            'Audit logs',
            $this->service->paginate(
                $filters
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | EXPORT CSV
    |--------------------------------------------------------------------------
    */
public function export(): void
{
    $filters = [
        'event_type' => $_GET['event_type'] ?? null,
        'module' => $_GET['module'] ?? null,
        'user_id' => isset($_GET['user_id']) ? (int) $_GET['user_id'] : null
    ];

    $logs = $this->service->export($filters);

    // 🔥 IMPORTANT: clean output buffer
    if (ob_get_length()) {
        ob_end_clean();
    }

    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="audit_logs.csv"');
    header('Cache-Control: no-store, no-cache');

    $output = fopen('php://output', 'w');

    fputcsv($output, [
        'ID',
        'Event Type',
        'Module',
        'User ID',
        'Entity ID',
        'Description',
        'IP Address',
        'Created At'
    ]);

    foreach ($logs as $log) {
        fputcsv($output, [
            $log['id'] ?? '',
            $log['event_type'] ?? '',
            $log['module'] ?? '',
            $log['user_id'] ?? '',
            $log['entity_id'] ?? '',
            $log['description'] ?? '',
            $log['ip_address'] ?? '',
            $log['created_at'] ?? ''
        ]);
    }

    fclose($output);
    exit;
}
}