<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EstudianteRequest extends FormRequest
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
            'ape_materno' => 'required|string|max:100',
            'sexo' => 'nullable',
            'opcion_contacto' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:1|max:120',
            'celular' => 'nullable|regex:/^[0-9]{8,12}$/',
            'estado_civil' => 'nullable|string|max:50',
            'ci' => 'nullable|string|max:20',
            'curso_biblico_usado' => 'nullable|string|max:255',
            'bautizado' => 'boolean',
            'iglesia_id' => 'required|exists:iglesias,id_iglesia',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del estudiante es obligatorio.',
            'nombre.string' => 'El nombre debe contener solo texto.',
            'nombre.max' => 'El nombre no debe superar los 100 caracteres.',

            'ape_paterno.required' => 'El apellido paterno es obligatorio.',
            'ape_materno.required' => 'El apellido materno es obligatorio.',

            'sexo.in' => 'El sexo debe ser M (Masculino) o F (Femenino).',

            'edad.integer' => 'La edad debe ser un número entero.',
            'edad.min' => 'La edad mínima permitida es 1.',
            'edad.max' => 'La edad máxima permitida es 120.',

            'celular.regex' => 'El número de celular debe contener entre 8 y 12 dígitos.',

            'ci.unique' => 'El número de CI ya está registrado.',
            'ci.max' => 'El número de CI no debe superar los 20 caracteres.',

            'curso_biblico_usado.max' => 'El nombre del curso bíblico no debe superar los 255 caracteres.',

            'bautizado.boolean' => 'El campo bautizado debe ser verdadero o falso.',

            'iglesia_id.required' => 'Debe seleccionar una iglesia.',
            'iglesia_id.exists' => 'La iglesia seleccionada no existe en el sistema.',
        ];
    }
}
