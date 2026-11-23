<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'device_name' => ['sometimes', 'string', 'max:100'],
        ]);

        $user = User::where('email', $datos['email'])->first();
        if (! $user || ! Hash::check($datos['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas.'],
            ]);
        }

        // Prototipo: todo usuario existente obtiene permisos CRUD de ambos módulos.
        // Para producción, sustituir por permisos/roles asignados en servidor.
        $abilities = ['productos:read', 'productos:write', 'empleados:read', 'empleados:write'];
        $token = $user->createToken($datos['device_name'] ?? 'postman', $abilities, now()->addDays(7));

        return response()->json([
            'status' => 'success',
            'token_type' => 'Bearer',
            'access_token' => $token->plainTextToken,
            'expires_at' => $token->accessToken->expires_at?->toIso8601String(),
            'user' => ['id' => $user->id, 'name' => $user->name, 'email' => $user->email],
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json(['status' => 'success', 'data' => $request->user()->only('id', 'name', 'email')]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()?->delete();
        return response()->json(['status' => 'success', 'message' => 'Token revocado correctamente.']);
    }
}
