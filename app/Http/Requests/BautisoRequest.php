<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BautisoRequest extends FormRequest
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
            'sexo' => 'required|string', // o M/F según tu app
            'fecha_nacimiento' => 'nullable|date|before:today',
            'fecha_bautizo' => 'required|date|before_or_equal:today',
            'estudiante_biblico' => 'required|boolean',
            'iglesia_id' => 'required|exists:iglesias,id_iglesia',
        ];
    }

     /**
     * Mensajes personalizados (opcional)
     */
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'ape_paterno.required' => 'El apellido paterno es obligatorio.',
            'sexo.required' => 'Debe seleccionar el sexo.',
            'fecha_bautizo.required' => 'La fecha de bautizo es obligatoria.',
            'fecha_bautizo.before_or_equal' => 'La fecha de bautizo no puede ser futura.',
            'estudiante_biblico.required' => 'Debe indicar si es estudiante bíblico.',
            'estudiante_biblico.boolean' => 'El valor de estudiante bíblico debe ser Sí o No.',
            'iglesia_id.required' => 'Debe seleccionar una iglesia.',
            'iglesia_id.exists' => 'La iglesia seleccionada no es válida.',
        ];
    }
}
