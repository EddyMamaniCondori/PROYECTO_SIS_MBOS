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
            'tipo' => 'required|string|max:100',
            'fecha_bautizo' => [
                'required',
                'date',
                'after_or_equal:' . now()->subYears(1)->format('Y-m-d'),
                'before_or_equal:' . now()->format('Y-m-d'),
            ],
            'id_iglesia' => 'required|exists:iglesias,id_iglesia',
            'id_distrito' => 'nullable',
            'id_desafio_evento' => 'nullable',
        ];
    }

     /**
     * Mensajes personalizados (opcional)
     */
    public function messages(): array
    {
        return [
            // Tipo
            'tipo.required' => 'El tipo de bautizo es obligatorio.',
            'tipo.string' => 'El tipo de bautizo debe ser un texto válido.',
            'tipo.max' => 'El tipo de bautizo no puede exceder los 100 caracteres.',
            
            // Fecha de Bautizo
            'fecha_bautizo.required' => 'La fecha de bautizo es obligatoria.',
            'fecha_bautizo.date' => 'La fecha de bautizo debe ser una fecha válida.',
            'fecha_bautizo.after_or_equal' => 'La fecha de bautizo no puede ser un año atrás.',
            'fecha_bautizo.before_or_equal' => 'La fecha de bautizo no puede ser una fecha futura.',
            
            // Iglesia
            'id_iglesia.required' => 'La iglesia es obligatoria.',
            'id_iglesia.exists' => 'La iglesia seleccionada no existe.',
        ];
    }
}
