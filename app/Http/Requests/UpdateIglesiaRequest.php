<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
class UpdateIglesiaRequest extends FormRequest
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
            'nombre' => [
                            'required',
                            'string',
                            'max:255',
                            function ($attribute, $value, $fail) {
                                $id = $this->route('iglesia'); // id actual del registro (update)
                                $exists = DB::table('iglesias')
                                    ->whereRaw("unaccent(lower(nombre)) = unaccent(lower(?))", [$value])
                                    ->where('id_iglesia', '<>', $id) // ignorar el registro actual
                                    ->exists();

                                if ($exists) {
                                    $fail('Ya existe otra iglesia con este nombre (sin importar mayúsculas, minúsculas ni acentos).');
                                }
                            },
                        ],
            'codigo' => 'required|integer|min:0', // >= 0
            'feligresia' => 'nullable|integer|min:0', // >= 0
            'feligrasia_asistente' => 'nullable|integer|min:0', // >= 0
            'ciudad' => 'required|string|max:255',
            'zona' => 'required|string|max:255',
            'calle' => 'nullable|string|max:255',
            'nro' => 'nullable|string|max:50',
            'lugar' => 'required|in:ALTIPLANO,EL ALTO',
            'tipo' => 'required|in:Iglesia,Grupo,Filial,filial,Grupo Cerrado,Grupo-CERRADO,I/Grupo',
            'distrito_id' => 'nullable|exists:distritos,id_distrito',
        ];
    }
     public function messages()
    {
        return [
            'nombre.unique' => 'Ya existe otra iglesia con este nombre (sin importar mayúsculas o minúsculas).',
            'nombre.required' => 'El nombre de la iglesia es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',

            'codigo.required' => 'El código es obligatorio.',
            'codigo.integer' => 'El código debe ser un número entero.',
            'codigo.min' => 'El código no puede ser negativo.',

            'feligresia.integer' => 'La feligresía debe ser un número.',
            'feligresia.min' => 'La feligresía no puede ser negativa.',

            'feligrasia_asistente.integer' => 'La feligresía asistente debe ser un número.',
            'feligrasia_asistente.min' => 'La feligresía asistente no puede ser negativa.',

            'ciudad.required' => 'La ciudad es obligatoria.',
            'ciudad.string' => 'La ciudad debe ser un texto válido.',
            'ciudad.max' => 'La ciudad no puede superar los 255 caracteres.',

            'zona.required' => 'La zona es obligatoria.',
            'zona.string' => 'La zona debe ser un texto válido.',
            'zona.max' => 'La zona no puede superar los 255 caracteres.',

            'calle.string' => 'La calle debe ser un texto válido.',
            'calle.max' => 'La calle no puede superar los 255 caracteres.',

            'nro.string' => 'El número debe ser un texto válido.',
            'nro.max' => 'El número no puede superar los 50 caracteres.',

            'lugar.required' => 'El campo "lugar" es obligatorio.',
            'lugar.in' => 'El lugar debe ser ALTIPLANO o EL ALTO.',

            'tipo.required' => 'El tipo de iglesia es obligatorio.',
            'tipo.in' => 'El tipo de iglesia seleccionado no es válido.',

            'distrito_id.exists' => 'El distrito seleccionado no existe en el sistema.',
        ];
    }
}
