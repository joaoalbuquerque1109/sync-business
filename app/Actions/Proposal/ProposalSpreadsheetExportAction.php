<?php

namespace App\Actions\Proposal;

use App\Models\Proposal;

class ProposalSpreadsheetExportAction
{
    /**
     * @return array<string, mixed>
     */
    public function execute(Proposal $proposal): array
    {
        return [
            'proposal_id' => $proposal->id,
            'number' => $proposal->number,
            'status' => $proposal->status,
            'template' => optional($proposal->template)->name,
            'message' => 'Exportação Excel preparada para implementação com template cadastrado.',
        ];
    }
}
