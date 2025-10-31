<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class DistritoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 🟩 NOMBRE: requerido, único sin importar mayúsculas/minúsculas
            'nombre' => [
                'required',
                'string',
                'max:150',
                function ($attribute, $value, $fail) {
                    $exists = DB::table('distritos')
                        ->whereRaw('LOWER(nombre) = ?', [mb_strtolower($value)])
                        ->exists();

                    if ($exists) {
                        $fail('Ya existe un distrito con ese nombre ');
                    }
                },
            ],

            // 🟩 ID_PASTOR: debe existir en pastors y estar activo en persona
            'id_pastor' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $exists = DB::table('pastors as p')
                            ->join('personas as per', 'p.id_pastor', '=', 'per.id_persona')
                            ->where('p.id_pastor', $value)
                            ->where('per.estado', true)
                            ->exists();

                        if (!$exists) {
                            $fail('El pastor seleccionado no existe o está inactivo.');
                        }
                    }
                },
            ],

            // 🟩 ID_GRUPO: debe existir en grupos
            'id_grupo' => 'nullable|exists:grupos,id_grupo',
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del distrito es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 150 caracteres.',
            'id_grupo.exists' => 'El grupo seleccionado no existe.',
        ];
    }
    
}
