<?php

namespace App\Http\Controllers\Api\V1;

use App\Actions\Auth\LoginAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(private readonly LoginAction $loginAction)
    {
    }

    public function login(LoginRequest $request)
    {
        $user = $this->loginAction->execute(
            login: $request->string('login')->toString(),
            password: $request->string('password')->toString(),
        );

        $token = $user->createToken('web')->plainTextToken;

        return $this->success([
            'user' => new UserResource($user->load('roles.permissions')),
            'token' => $token,
        ], 'Login realizado com sucesso');
    }

    public function logout(Request $request)
    {
        $request->user()?->currentAccessToken()?->delete();

        return $this->success(null, 'Logout realizado com sucesso');
    }

    public function me(Request $request)
    {
        return $this->success(new UserResource($request->user()?->load('roles.permissions')));
    }
}
