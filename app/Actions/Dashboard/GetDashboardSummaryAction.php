<?php

namespace App\Actions\Dashboard;

use App\Models\Client;
use App\Models\Product;
use App\Models\Proposal;
use App\Models\ProposalStatusHistory;

class GetDashboardSummaryAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(): array
    {
        return [
            'totals' => [
                'proposals' => Proposal::query()->count(),
                'clients' => Client::query()->count(),
                'products' => Product::query()->count(),
            ],
            'proposals_by_status' => Proposal::query()
                ->selectRaw('status, COUNT(*) as total')
                ->groupBy('status')
                ->pluck('total', 'status'),
            'recent_clients' => Client::query()->latest()->limit(5)->get(),
            'recent_products' => Product::query()->latest()->limit(5)->get(),
            'recent_activity' => ProposalStatusHistory::query()->latest()->limit(10)->get(),
        ];
    }
}
