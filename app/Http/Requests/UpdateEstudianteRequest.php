<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstudianteRequest extends FormRequest
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
            'ape_materno' => 'string|max:100',
            'sexo' => 'required',
            'opcion_contacto' => 'nullable|string|max:255',
            'edad' => 'nullable|integer|min:0|max:120',
            'celular' => 'nullable|regex:/^[6-7][0-9]{7}$/',
            'estado_civil' => 'required|string|max:50',
            'ci' => 'nullable|string|max:20',
            'curso_biblico_usado' => 'required|string|max:255',
            'bautizado' => 'required|boolean',
            'id_iglesia' => 'required|exists:iglesias,id_iglesia',
        ];
        
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no debe superar los 100 caracteres.',

            'ape_paterno.required' => 'El apellido paterno es obligatorio.',
            'ape_paterno.string' => 'El apellido paterno debe ser un texto válido.',
            'ape_paterno.max' => 'El apellido paterno no debe superar los 100 caracteres.',

            'ape_materno.string' => 'El apellido materno debe ser un texto válido.',
            'ape_materno.max' => 'El apellido materno no debe superar los 100 caracteres.',

            'sexo.required' => 'Debe seleccionar el sexo.',

            'opcion_contacto.string' => 'La opción de contacto debe ser un texto.',
            'opcion_contacto.max' => 'La opción de contacto no debe superar los 255 caracteres.',

            'edad.integer' => 'La edad debe ser un número entero.',
            'edad.min' => 'La edad no puede ser negativa.',
            'edad.max' => 'La edad no puede superar los 120 años.',

            'celular.regex' => 'El número de celular debe tener 8 dígitos y comenzar con 6 o 7.',

            'estado_civil.required' => 'Debe seleccionar el estado civil.',
            'estado_civil.string' => 'El estado civil debe ser un texto válido.',
            'estado_civil.max' => 'El estado civil no debe superar los 50 caracteres.',

            'ci.string' => 'El número de CI debe ser un texto válido.',
            'ci.max' => 'El número de CI no debe superar los 20 caracteres.',

            'curso_biblico_usado.required' => 'Debe indicar el curso bíblico usado.',
            'curso_biblico_usado.string' => 'El curso bíblico debe ser un texto válido.',
            'curso_biblico_usado.max' => 'El nombre del curso bíblico no debe superar los 255 caracteres.',

            'bautizado.required' => 'Debe indicar si la persona está bautizada.',
            'bautizado.boolean' => 'El valor de bautizado no es válido.',

            'id_iglesia.required' => 'Debe seleccionar una iglesia.',
            'id_iglesia.exists' => 'La iglesia seleccionada no existe o no es válida.',
        ];
    }
}
