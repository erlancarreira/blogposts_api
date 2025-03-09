<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponser;

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return $this->errorResponse('Credenciais inválidas. Verifique seu email e senha', 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->accessToken;

        return $this->successResponse([
            'message' => 'Login realizado com sucesso',
            'user'    => $user,
            'token'   => $token,
        ]);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'nome'     => $request->nome,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'telefone' => $request->telefone,
            'is_valid' => true,
        ]);

        $token = $user->createToken('auth_token')->accessToken;

        return $this->successResponse([
            "message" => "Usuário registrado com sucesso.",
            'user'    => $user,
            'token'   => $token,
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return $this->successResponse([
            'message' => 'Logout realizado com sucesso.'
        ]);
    }
}
