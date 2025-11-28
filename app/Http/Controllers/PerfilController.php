<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
class PerfilController extends Controller
{
    public function index()
    {
        return view('perfil.index', [
            'user' => auth()->user()
        ]);
    }
    // =====================================
    // 1) ACTUALIZAR DATOS PERSONALES
    // =====================================
    public function updateData(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'fecha_nac'     => 'required|date',
            'celular'       => 'required|string|max:10',
            'ciudad'        => 'required|string|max:100',
            'zona'          => 'required|string|max:100',
            'calle'         => 'nullable|string|max:100',
            'nro'           => 'nullable|string|max:20',
        ]);

        $user->update($request->only(['fecha_nac','celular','ciudad','zona','calle','nro']));

        return back()->with('success', 'Datos personales actualizados correctamente.');
    }

    // =====================================
    // 2) CAMBIAR CONTRASEÑA
    // =====================================
    public function updatePassword(Request $request)
    {
        //dd($request);
        // 1. Validar la entrada
        $request->validate([
            'password' => [
                'required',
                'string',
                'confirmed',
                // Puedes usar la clase Password de Laravel para esto:
                // Password::min(8)->mixedCase()->numbers()->symbols() 
                // O mantener tus regex:
                'regex:/[A-Z]/',
                'regex:/[a-z]/', 
                'regex:/[0-9]/',
            ]
        ], [
            'password.regex' => 'La contraseña debe tener al menos una mayúscula, una minúscula y un número.'
        ]);
        
        // 2. Obtener usuario y actualizar contraseña (La lógica real)
        $user = Auth::user();
        
        // Usamos Hash::make() en lugar de bcrypt() (bcrypt() es obsoleto/alias)
        $user->password = Hash::make($request->password);
        
        $user->save();

        return back()->with('success', 'Contraseña cambiada correctamente.');
    }
    // =====================================
    // 3) SUBIR FOTO DE PERFIL
    // =====================================
    public function updatePhoto(Request $request)
    {
        //dd('hola');
        $request->validate([
            'photo' => 'required|image'
        ]);

        $user = Auth::user();

        // Eliminar foto anterior
        if ($user->foto_perfil && Storage::disk('public')->exists($user->foto_perfil)) {
            Storage::disk('public')->delete($user->foto_perfil);
        }

        // Guardar nueva foto
        //dd($request->file('photo'));
        $path = $request->file('photo')->store('perfiles', 'public');
        //dd($path);
        $user->foto_perfil = $path;
        $user->save();

        return back()->with('success', 'Foto de perfil actualizada correctamente.');
    }



    public function updatePassword213(Request $request)
    {
        $request->validate([
            'password_actual'  => 'required',
            'password_nuevo'   => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = auth()->user();

        if (!Hash::check($request->password_actual, $user->password)) {
            return back()->with('error', 'La contraseña actual es incorrecta.');
        }

        $user->password = Hash::make($request->password_nuevo);
        $user->save();

        return back()->with('success', 'Contraseña actualizada correctamente.');
    }
}
