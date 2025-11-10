<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesafioEventoRequest extends FormRequest
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
        $anioActual = date('Y');
        $inicioAnio = "{$anioActual}-01-01";
        
        return [
            'nombre' => 'required|string|max:100',
            'anio' => "required|integer|min:{$anioActual}",
            'fecha_inicio' => "required|date|after_or_equal:{$inicioAnio}|before_or_equal:{$anioActual}-12-31",
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
        ];
    }
    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del desafío es obligatorio.',
            'nombre.string' => 'El nombre debe ser una cadena de texto.',
            'nombre.max' => 'El nombre no puede superar los 100 caracteres.',
            
            'anio.required' => 'El año es obligatorio.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año no puede ser menor al año actual.',

            'fecha_inicio.required' => 'La fecha de inicio es obligatoria.',
            'fecha_inicio.date' => 'La fecha de inicio debe ser una fecha válida.',
            'fecha_inicio.after_or_equal' => 'La fecha de inicio debe ser del año actual.',
            'fecha_inicio.before_or_equal' => 'La fecha de inicio debe ser del año actual.',


            'fecha_final.required' => 'La fecha final es obligatoria.',
            'fecha_final.date' => 'La fecha final debe ser una fecha válida.',
            'fecha_final.after_or_equal' => 'La fecha final debe ser igual o posterior a la fecha de inicio.',
        ];
    }
}
