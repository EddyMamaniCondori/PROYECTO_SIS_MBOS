<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVisitaCapellanRequest extends FormRequest
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
            'id_mensual'=> 'required',
            'fecha_visita' => [
                'required',
                'date',
                'after_or_equal:' . now()->subYears(1)->format('Y-m-d'),
                'before_or_equal:' . now()->format('Y-m-d'),
            ],
            'hora' => 'nullable|date_format:H:i:s',
            'nombre_visitado' => 'required|string|max:150',
            'cant_present' => 'required|integer|min:1',
            'telefono' => 'nullable|regex:/^[6-7][0-9]{7}$/',
            'motivo' => 'nullable|string|max:255',
            'descripcion_lugar' => 'nullable|string|max:255',
            'id_ue'=> 'required|exists:unidad_educativas,id_ue'
        ];
    }
    public function messages(): array
    {
        return [
                // fecha_visita
                'fecha_visita.required' => 'La fecha de visita es obligatoria.',
                'fecha_visita.date' => 'La fecha de visita debe ser una fecha válida.',
                'fecha_visita.after_or_equal' => 'La fecha de visita no puede ser anterior a un año atrás.',
                'fecha_visita.before_or_equal' => 'La fecha de visita no puede ser futura.',
                
                // hora
                'hora.date_format' => 'La hora debe tener el formato HH:MM (ejemplo: 14:30).',
                
                // nombre_visitado
                'nombre_visitado.required' => 'El nombre del visitado es obligatorio.',
                'nombre_visitado.string' => 'El nombre del visitado debe ser un texto válido.',
                'nombre_visitado.max' => 'El nombre del visitado no puede exceder los 150 caracteres.',
                
                // cant_present
                'cant_present.required' => 'La cantidad de presentes es obligatoria.',
                'cant_present.integer' => 'La cantidad de presentes debe ser un número entero.',
                'cant_present.min' => 'Debe haber al menos 1 persona presente.',
                
                // telefono
                'telefono.regex' => 'El teléfono debe ser un número boliviano válido de 8 dígitos que inicie con 6 o 7.',
                
                // motivo
                'motivo.string' => 'El motivo debe ser un texto válido.',
                'motivo.max' => 'El motivo no puede exceder los 255 caracteres.',
                
                // descripcion_lugar
                'descripcion_lugar.string' => 'La descripción del lugar debe ser un texto válido.',
                'descripcion_lugar.max' => 'La descripción del lugar no puede exceder los 255 caracteres.',
        ];
    }
}
