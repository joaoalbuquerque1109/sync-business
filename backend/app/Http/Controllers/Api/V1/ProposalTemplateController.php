<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ProposalTemplate;
use Illuminate\Http\Request;

class ProposalTemplateController extends Controller
{
    public function index()
    {
        return $this->success(ProposalTemplate::query()->get());
    }

    public function store(Request $request)
    {
        return $this->success($request->all(), 'Endpoint de criação de template pronto para implementação completa');
    }

    public function show(ProposalTemplate $proposalTemplate)
    {
        return $this->success($proposalTemplate);
    }

    public function update(Request $request, ProposalTemplate $proposalTemplate)
    {
        return $this->success(['id' => $proposalTemplate->id], 'Endpoint de edição de template pronto para implementação completa');
    }

    public function destroy(ProposalTemplate $proposalTemplate)
    {
        return $this->success(['id' => $proposalTemplate->id], 'Endpoint de exclusão de template pronto para implementação completa');
    }
}
