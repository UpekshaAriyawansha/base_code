<?php

namespace Modules\Insight\Presentation\Controllers;

use Src\Presentation\Controllers\Controller;
use Modules\Insight\Application\Services\InsightService;

class InsightController extends Controller
{
    public function __construct(
        private InsightService $service
    ) {}

    public function index(): void
    {
        $data = $this->service->getInsights();

        $this->success("Insights loaded", $data);
    }
}