<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login para la app móvil.
     * Devuelve un token Sanctum y los datos del usuario.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        /** @var Persona|null $persona */
        $persona = Persona::where('email', $request->email)->first();

        if (! $persona || ! Hash::check($request->password, $persona->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales no son correctas.'],
            ]);
        }

        if (! $persona->estado) {
            return response()->json([
                'message' => 'Tu cuenta está deshabilitada. Contacta al administrador.',
            ], 403);
        }

        // Verificar que sea Pastor
        if (! $persona->hasRole('Pastor') && ! $persona->hasRole('Super Administrador')) {
            return response()->json([
                'message' => 'No tienes permiso para acceder a esta aplicación.',
            ], 403);
        }

        // Revocar tokens anteriores del mismo dispositivo (opcional: mantener uno activo)
        $persona->tokens()->where('name', 'flutter-app')->delete();

        $token = $persona->createToken('flutter-app')->plainTextToken;

        // Cargar relación pastor si existe
        $persona->load('pastor');

        $roles = $persona->getRoleNames();

        return response()->json([
            'token' => $token,
            'user'  => [
                'id'             => $persona->id_persona,
                'nombre'         => $persona->nombre,
                'ape_paterno'    => $persona->ape_paterno,
                'ape_materno'    => $persona->ape_materno,
                'nombre_completo'=> $persona->getNombreCompleto(),
                'email'          => $persona->email,
                'celular'        => $persona->celular,
                'ciudad'         => $persona->ciudad,
                'ci'             => $persona->ci,
                'estado'         => $persona->estado,
                'roles'          => $roles,
                'es_pastor'      => $persona->hasRole('Pastor'),
                'pastor'         => $persona->pastor,
            ],
        ]);
    }

    /**
     * Cierra sesión revocando el token actual.
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Sesión cerrada correctamente.']);
    }

    /**
     * Devuelve los datos del usuario autenticado.
     */
    public function me(Request $request): JsonResponse
    {
        /** @var Persona $persona */
        $persona = $request->user();
        $persona->load('pastor');

        return response()->json([
            'user' => [
                'id'             => $persona->id_persona,
                'nombre'         => $persona->nombre,
                'ape_paterno'    => $persona->ape_paterno,
                'ape_materno'    => $persona->ape_materno,
                'nombre_completo'=> $persona->getNombreCompleto(),
                'email'          => $persona->email,
                'celular'        => $persona->celular,
                'ciudad'         => $persona->ciudad,
                'ci'             => $persona->ci,
                'estado'         => $persona->estado,
                'roles'          => $persona->getRoleNames(),
                'es_pastor'      => $persona->hasRole('Pastor'),
                'pastor'         => $persona->pastor,
            ],
        ]);
    }
}
