<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInstructorRequest extends FormRequest
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
            'nombre' => 'required|string|max:100',
            'ape_paterno' => 'required|string|max:100',
            'ape_materno' => 'nullable|string|max:100',
            'sexo' => 'required|in:M,F',
           'fecha_nacimiento' => [
                'required',
                'date',
                'after_or_equal:' . now()->subYears(100)->format('Y-m-d'),
                'before_or_equal:' . now()->format('Y-m-d'),
            ],
            'celular' => 'nullable|regex:/^[6-7][0-9]{7}$/',
            'id_iglesia' => 'required|exists:iglesias,id_iglesia',
        ];
    }
    public function messages(): array
{
    return [
        // Nombre
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.string' => 'El nombre debe ser un texto válido.',
        'nombre.max' => 'El nombre no puede exceder los 100 caracteres.',
        
        // Apellido Paterno
        'ape_paterno.required' => 'El apellido paterno es obligatorio.',
        'ape_paterno.string' => 'El apellido paterno debe ser un texto válido.',
        'ape_paterno.max' => 'El apellido paterno no puede exceder los 100 caracteres.',
        
        // Apellido Materno
        'ape_materno.string' => 'El apellido materno debe ser un texto válido.',
        'ape_materno.max' => 'El apellido materno no puede exceder los 100 caracteres.',
        
        // Sexo
        'sexo.required' => 'El sexo es obligatorio.',
        'sexo.in' => 'El sexo debe ser Masculino (M) o Femenino (F).',
        
        // Fecha de Nacimiento
        'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatorio.',
        'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida.',
        'fecha_nacimiento.after_or_equal' => 'La fecha de nacimiento no puede ser mayor a 100 años atrás.',
        'fecha_nacimiento.before_or_equal' => 'La fecha de nacimiento no puede ser una fecha futura.',
        
        // Celular
        'celular.regex' => 'El celular debe tener 8 dígitos y comenzar con 6 o 7.',
        
        // Iglesia
        'id_iglesia.required' => 'La iglesia es obligatoria.',
        'id_iglesia.exists' => 'La iglesia seleccionada no existe.',
    ];
}
}
