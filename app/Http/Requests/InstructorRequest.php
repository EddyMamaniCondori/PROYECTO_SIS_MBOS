<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstructorRequest extends FormRequest
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
            'fecha_nacimiento' => 'nullable|date',
            'edad' => 'nullable|integer|min:10|max:120',
            'celular' => 'nullable|string|regex:/^[0-9]{8,12}$/',
            'iglesia_id' => 'required|exists:iglesias,id_iglesia',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'ape_paterno.required' => 'El apellido paterno es obligatorio.',
            'sexo.required' => 'Debe seleccionar el sexo.',
            'iglesia_id.required' => 'Debe seleccionar una iglesia.',
            'iglesia_id.exists' => 'La iglesia seleccionada no existe.',
            'celular.regex' => 'El número de celular debe tener entre 8 y 12 dígitos numéricos.',
        ];
    }
}
