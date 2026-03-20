<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::query()
            ->when($request->filled('search'), fn ($query) => $query->where('company_name', 'like', '%' . $request->string('search') . '%'))
            ->paginate((int) $request->integer('per_page', 15));

        return $this->success(ClientResource::collection($clients), meta: [
            'current_page' => $clients->currentPage(),
            'per_page' => $clients->perPage(),
            'total' => $clients->total(),
            'last_page' => $clients->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        return $this->success($request->all(), 'Endpoint de criação de cliente pronto para implementação completa');
    }

    public function show(Client $client)
    {
        return $this->success(new ClientResource($client->load(['addresses', 'contacts'])));
    }

    public function update(Request $request, Client $client)
    {
        return $this->success(['id' => $client->id], 'Endpoint de edição de cliente pronto para implementação completa');
    }

    public function destroy(Client $client)
    {
        return $this->success(['id' => $client->id], 'Endpoint de exclusão de cliente pronto para implementação completa');
    }
}
