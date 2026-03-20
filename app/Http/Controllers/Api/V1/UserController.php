<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\User\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

    public function store(StoreUserRequest $request)
    {
        $user = DB::transaction(function () use ($request): User {
            $user = User::query()->create([
                'name' => $request->string('name')->toString(),
                'username' => $request->filled('username') ? $request->string('username')->toString() : null,
                'email' => $request->string('email')->toString(),
                'password' => Hash::make($request->string('password')->toString()),
                'status' => $request->string('status')->toString(),
            ]);

            $user->roles()->sync($request->input('role_ids', []));

            return $user->load('roles.permissions');
        });

        return $this->success(new UserResource($user), 'Usuario criado com sucesso');
    }

    public function show(User $user)
    {
        return $this->success(new UserResource($user->load('roles.permissions')));
    }

    public function update(Request $request, User $user)
    {
        return $this->success(['id' => $user->id], 'Edicao de usuario ainda nao implementada');
    }

    public function destroy(User $user)
    {
        return $this->success(['id' => $user->id], 'Remocao de usuario ainda nao implementada');
    }

    public function updateStatus(User $user)
    {
        return $this->success(['id' => $user->id], 'Status do usuario atualizado');
    }

    public function updatePassword(User $user)
    {
        return $this->success(['id' => $user->id], 'Senha do usuario atualizada');
    }
}
