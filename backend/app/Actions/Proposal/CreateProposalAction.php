<?php

namespace App\Actions\Proposal;

use App\Models\Proposal;
use App\Services\ProposalCalculationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class CreateProposalAction
{
    public function __construct(private readonly ProposalCalculationService $calculationService)
    {
    }

    public function execute(array $payload): Proposal
    {
        return DB::transaction(function () use ($payload): Proposal {
            $calculated = $this->calculationService->calculate($payload['items'] ?? []);

            /** @var Proposal $proposal */
            $proposal = Proposal::query()->create([
                ...Arr::except($payload, ['items']),
                'subtotal_amount' => $calculated['subtotal'],
                'discount_amount' => $calculated['discount'],
                'total_amount' => $calculated['total'],
            ]);

            $proposal->items()->createMany($calculated['normalized_items']);

            return $proposal->load(['client', 'responsibleUser', 'items']);
        });
    }
}
