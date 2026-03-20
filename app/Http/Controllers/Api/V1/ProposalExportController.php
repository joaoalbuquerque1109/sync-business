<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Proposal\ProposalSpreadsheetExportAction;
use App\Models\Proposal;

class ProposalExportController extends Controller
{
    public function __construct(private readonly ProposalSpreadsheetExportAction $exportAction)
    {
    }

    public function excel(Proposal $proposal)
    {
        return $this->success($this->exportAction->execute($proposal), 'Exportação Excel preparada com sucesso');
    }
}
