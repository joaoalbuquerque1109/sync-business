<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function store(StoreClientRequest $request)
    {
        $client = Client::query()->create([
            ...$request->validated(),
            'source' => 'manual',
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        return $this->success(new ClientResource($client), 'Cliente criado com sucesso');
    }

    public function show(Client $client)
    {
        return $this->success(new ClientResource($client->load(['addresses', 'contacts'])));
    }

    public function update(Request $request, Client $client)
    {
        return $this->success(['id' => $client->id], 'Edicao de cliente ainda nao implementada');
    }

    public function destroy(Client $client)
    {
        return $this->success(['id' => $client->id], 'Exclusao de cliente ainda nao implementada');
    }
}
