<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthApiController extends Controller
{
    /**
     * Login para la aplicación móvil.
     * Devuelve un token Sanctum si las credenciales son válidas.
     *
     * POST /api/login
     * Body: { "email": "...", "password": "..." }
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt([
            'email'    => $request->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        /** @var \App\Models\Persona $persona */
        $persona = Auth::user();

        // Solo pastores pueden usar la app móvil
        if (! $persona->hasRole('Pastor')) {
            Auth::logout();
            return response()->json([
                'message' => 'Esta aplicación es exclusiva para pastores.',
            ], 403);
        }

        // Revoca tokens anteriores del mismo dispositivo (opcional: limpia sesiones viejas)
        $persona->tokens()->where('name', 'mbos-app')->delete();

        $token = $persona->createToken('mbos-app')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user'  => [
                'id_persona'  => $persona->id_persona,
                'nombre'      => $persona->nombre,
                'ape_paterno' => $persona->ape_paterno,
                'ape_materno' => $persona->ape_materno,
                'email'       => $persona->email,
                'celular'     => $persona->celular,
                'ciudad'      => $persona->ciudad,
                'roles'       => $persona->getRoleNames(),
            ],
        ]);
    }

    /**
     * Cierra la sesión revocando el token actual.
     *
     * POST /api/logout  (requiere Bearer token)
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    /**
     * Devuelve los datos del usuario autenticado.
     *
     * GET /api/me  (requiere Bearer token)
     */
    public function me(Request $request): JsonResponse
    {
        /** @var \App\Models\Persona $persona */
        $persona = $request->user();

        return response()->json([
            'id_persona'  => $persona->id_persona,
            'nombre'      => $persona->nombre,
            'ape_paterno' => $persona->ape_paterno,
            'ape_materno' => $persona->ape_materno,
            'email'       => $persona->email,
            'celular'     => $persona->celular,
            'ciudad'      => $persona->ciudad,
            'roles'       => $persona->getRoleNames(),
        ]);
    }
}
