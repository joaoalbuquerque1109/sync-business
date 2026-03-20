<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Proposal\CreateProposalAction;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function __construct(private readonly CreateProposalAction $createProposalAction)
    {
    }

    public function index(Request $request)
    {
        $proposals = Proposal::query()
            ->with(['client', 'responsibleUser', 'template'])
            ->when($request->filled('status'), fn ($query) => $query->where('status', $request->string('status')))
            ->paginate((int) $request->integer('per_page', 15));

        return $this->success(ProposalResource::collection($proposals), meta: [
            'current_page' => $proposals->currentPage(),
            'per_page' => $proposals->perPage(),
            'total' => $proposals->total(),
            'last_page' => $proposals->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        $proposal = $this->createProposalAction->execute($request->all());

        return $this->success(new ProposalResource($proposal), 'Proposta criada com sucesso');
    }

    public function show(Proposal $proposal)
    {
        return $this->success(new ProposalResource($proposal->load(['client', 'responsibleUser', 'template', 'items', 'versions', 'statusHistories'])));
    }

    public function update(Request $request, Proposal $proposal)
    {
        return $this->success(['id' => $proposal->id], 'Endpoint de edição de proposta pronto para implementação completa');
    }

    public function destroy(Proposal $proposal)
    {
        return $this->success(['id' => $proposal->id], 'Endpoint de exclusão de proposta pronto para implementação completa');
    }

    public function updateStatus(Proposal $proposal)
    {
        return $this->success(['id' => $proposal->id], 'Status da proposta atualizado');
    }

    public function versions(Proposal $proposal)
    {
        return $this->success($proposal->versions()->latest()->get());
    }

    public function storeVersion(Proposal $proposal)
    {
        return $this->success(['id' => $proposal->id], 'Nova versão da proposta registrada');
    }
}
