<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdatePersonalRequest extends FormRequest
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
            'nombre'             => 'required|string|max:30|regex:/^[\pL\s\-]+$/u',
            'ape_paterno'        => 'required|string|max:30|regex:/^[\pL\s\-]+$/u',
            'ape_materno'        => 'nullable|string|max:30|regex:/^[\pL\s\-]+$/u',
            'fecha_nac'          => 'required|date|before:today',
            'ci'                 => [
                                        'required',
                                        'string',
                                        'max:20',
                                        Rule::unique('personas', 'ci')->ignore($this->route('personale'), 'id_persona'),
                                    ],
            'celular'            => ['required','regex:/^[67]\d{7}$/'], // empieza con 6 o 7 y 8 dígitos
            'ciudad'             => 'required|string|max:100',
            'zona'               => 'required|string|max:100',
            'calle'              => 'nullable|string|max:100',
            'nro'                => 'nullable|string|max:20',
            'fecha_ingreso'   => 'nullable|date|before_or_equal:today',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required'       => 'El nombre es obligatorio.',
            'nombre.max'            => 'El nombre no debe superar los 30 caracteres.',
            'nombre.regex'          => 'El nombre solo debe contener letras y espacios.',

            'ape_paterno.required'  => 'El apellido paterno es obligatorio.',
            'ape_paterno.max'       => 'El apellido paterno no debe superar los 30 caracteres.',
            'ape_paterno.regex'     => 'El apellido paterno solo debe contener letras y espacios.',

            
            'ape_materno.max'       => 'El apellido materno no debe superar los 30 caracteres.',
            'ape_materno.regex'     => 'El apellido materno solo debe contener letras y espacios.',

            'fecha_nac.required'    => 'La fecha de nacimiento es obligatoria.',
            'fecha_nac.date'        => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_nac.before'      => 'La fecha de nacimiento debe ser anterior a hoy.',

            'ci.required'           => 'El carnet de identidad es obligatorio.',
            'ci.unique'             => 'El carnet de identidad ya está registrado.',
            'ci.max'                => 'El carnet de identidad no debe superar los 20 caracteres.',

            'celular.required'      => 'El número de celular es obligatorio.',
            'celular.regex'         => 'El número de celular debe empezar con 6 o 7 y tener exactamente 8 dígitos.',

            'ciudad.required'       => 'La ciudad es obligatoria.',
            'ciudad.max'            => 'La ciudad no debe superar los 100 caracteres.',

            'zona.required'         => 'La zona es obligatoria.',
            'zona.max'              => 'La zona no debe superar los 100 caracteres.',

            'calle.max'             => 'La calle no debe superar los 100 caracteres.',
            'nro.max'               => 'El número no debe superar los 20 caracteres.',

            'fecha_ingreso.date'     => 'La fecha de ingreso debe ser una fecha válida.',
            'fecha_ingreso.before_or_equal' => 'La fecha de ingreso no puede ser futura.',

        ];
    }
}
