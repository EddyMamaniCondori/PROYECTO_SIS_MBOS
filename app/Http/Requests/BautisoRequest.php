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
            'cant_bautizo' => 'required|integer|min:0',
            'cant_profesion' => 'required|integer|min:0',
            'cant_rebautismo' => 'required|integer|min:0',
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
            // Cantidades (Nuevos campos)
            'cant_bautizo.required' => 'La cantidad de bautizos es obligatoria.',
            'cant_bautizo.integer' => 'La cantidad de bautizos debe ser un número entero.',
            'cant_bautizo.min' => 'La cantidad de bautizos no puede ser menor a 0.',

            'cant_profesion.required' => 'La cantidad de profesión de fe es obligatoria.',
            'cant_profesion.integer' => 'La cantidad de profesión de fe debe ser un número entero.',
            'cant_profesion.min' => 'La cantidad de profesión de fe no puede ser menor a 0.',

            'cant_rebautismo.required' => 'La cantidad de rebautismos es obligatoria.',
            'cant_rebautismo.integer' => 'La cantidad de rebautismos debe ser un número entero.',
            'cant_rebautismo.min' => 'La cantidad de rebautismos no puede ser menor a 0.',

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
