<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PastorRequest extends FormRequest
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
            'nombre'       => 'required|string|max:30|regex:/^[\pL\s\-]+$/u',
            'ape_paterno'  => 'required|string|max:30|regex:/^[\pL\s\-]+$/u',
            'ape_materno'  => 'required|string|max:30|regex:/^[\pL\s\-]+$/u',
            'fecha_nac'   => 'required|date|before:today',
            'ci'            => 'required',
            'edad'      => 'required',
            'fecha_ordenacion'   => 'required|date',
            'cargo'    => 'required|string|max:80',
        ];
    }
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre no debe superar los 30 caracteres.',
            'nombre.regex' => 'El nombre solo debe contener letras y espacios.',

            'ape_paterno.required' => 'El apellido paterno es obligatorio.',
            'ape_paterno.max' => 'El apellido paterno no debe superar los 30 caracteres.',
            'ape_paterno.regex' => 'El apellido paterno solo debe contener letras y espacios.',

            'ape_materno.required' => 'El apellido materno es obligatorio.',
            'ape_materno.max' => 'El apellido materno no debe superar los 30 caracteres.',
            'ape_materno.regex' => 'El apellido materno solo debe contener letras y espacios.',

            'fecha_naci.required' => 'La fecha de nacimiento es obligatoria.',
            'fecha_naci.date' => 'La fecha de nacimiento debe ser una fecha válida.',
            'fecha_naci.before' => 'La fecha de nacimiento debe ser anterior a hoy.',

            'celular.required' => 'El número de celular es obligatorio.',
            'celular.regex' => 'El número de celular debe empezar con 6 o 7 y tener exactamente 8 dígitos.',

            'domicilio.required' => 'El domicilio es obligatorio.',
            'domicilio.max' => 'El domicilio no debe superar los 80 caracteres.',
        ];
    }
}
