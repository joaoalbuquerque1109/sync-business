<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Dashboard\GetDashboardSummaryAction;

class DashboardController extends Controller
{
    public function __construct(private readonly GetDashboardSummaryAction $summaryAction)
    {
    }

    public function summary()
    {
        return $this->success($this->summaryAction->execute());
    }
}
