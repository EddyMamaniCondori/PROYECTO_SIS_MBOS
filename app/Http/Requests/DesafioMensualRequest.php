<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DesafioMensualRequest extends FormRequest
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
        $anioActual = now()->year;
        $primerDiaAnioActual = now()->startOfYear()->format('Y-m-d'); // 2025-01-01

        return [
            'mes' => [
                'required',
                'integer',
                'min:1',
                'max:12'
            ],
            'anio' => [
                'required',
                'integer',
                'min:' . $anioActual, // No permite años anteriores al actual
            ],
            'fecha_limite' => [
                'required',
                'date',
                'after_or_equal:' . $primerDiaAnioActual, // No permite fechas de años anteriores
            ],
        ];

    }
     /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'mes.required' => 'El mes es obligatorio.',
            'mes.integer' => 'El mes debe ser un número entero.',
            'mes.min' => 'El mes debe ser al menos 1 (Enero).',
            'mes.max' => 'El mes no puede ser mayor a 12 (Diciembre).',
            
            'anio.required' => 'El año es obligatorio.',
            'anio.integer' => 'El año debe ser un número entero.',
            'anio.min' => 'El año no puede ser anterior al año actual (' . now()->year . ').',
            
            'fecha_limite.required' => 'La fecha límite es obligatoria.',
            'fecha_limite.date' => 'La fecha límite debe ser una fecha válida.',
            'fecha_limite.after_or_equal' => 'La fecha límite no puede ser de un año anterior al actual.',
        ];
    }

}
