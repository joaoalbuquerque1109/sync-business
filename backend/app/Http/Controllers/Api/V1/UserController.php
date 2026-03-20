<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()->with('roles.permissions')->paginate((int) $request->integer('per_page', 15));

        return $this->success(UserResource::collection($users), meta: [
            'current_page' => $users->currentPage(),
            'per_page' => $users->perPage(),
            'total' => $users->total(),
            'last_page' => $users->lastPage(),
        ]);
    }

    public function store(Request $request)
    {
        return $this->success($request->all(), 'Endpoint de criação de usuário pronto para implementação completa');
    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user->load('roles.permissions')));
    }

    public function update(Request $request, User $user)
    {
        return $this->success(compact('user'), 'Endpoint de edição de usuário pronto para implementação completa');
    }

    public function destroy(User $user)
    {
        return $this->success(['id' => $user->id], 'Endpoint de remoção de usuário pronto para implementação completa');
    }

    public function updateStatus(User $user)
    {
        return $this->success(['id' => $user->id], 'Status do usuário atualizado');
    }

    public function updatePassword(User $user)
    {
        return $this->success(['id' => $user->id], 'Senha do usuário atualizada');
    }
}
