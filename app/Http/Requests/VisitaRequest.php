<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisitaRequest extends FormRequest
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
            'fecha' => 'required|date',
            'hora' => 'nullable|date_format:H:i',
            'nombre_visitado' => 'required|string|max:150',
            'cant_present' => 'required|integer|min:1',
            'telefono' => 'nullable|regex:/^[0-9]{7,15}$/',
            'motivo' => 'nullable|string|max:255',
            'descripcion_lugar' => 'nullable|string|max:255',
            'pastor_id' => 'required|integer|exists:pastors,id_pastor',
            'iglesia_id' => 'required|integer|exists:iglesias,id_iglesia',
        ];
    }

     public function messages(): array
    {
        return [
            'fecha.required' => 'La fecha de la visita es obligatoria.',
            'fecha.date' => 'La fecha debe tener un formato válido (AAAA-MM-DD).',

            'hora.date_format' => 'La hora debe tener un formato válido (HH:mm).',

            'nombre_visitado.required' => 'Debe ingresar el nombre de la persona visitada.',
            'nombre_visitado.string' => 'El nombre debe ser un texto válido.',
            'nombre_visitado.max' => 'El nombre no puede tener más de 150 caracteres.',

            'cant_present.required' => 'Debe indicar la cantidad de personas presentes.',
            'cant_present.integer' => 'La cantidad debe ser un número entero.',
            'cant_present.min' => 'Debe haber al menos una persona presente.',

            'telefono.regex' => 'El número de teléfono debe tener entre 7 y 15 dígitos.',

            'motivo.string' => 'El motivo debe ser un texto válido.',
            'motivo.max' => 'El motivo no puede tener más de 255 caracteres.',

            'descripcion_lugar.string' => 'La descripción del lugar debe ser texto.',
            'descripcion_lugar.max' => 'La descripción del lugar no puede tener más de 255 caracteres.',

            'pastor_id.required' => 'Debe seleccionar un pastor.',
            'pastor_id.exists' => 'El pastor seleccionado no existe en el sistema.',

            'iglesia_id.required' => 'Debe seleccionar una iglesia.',
            'iglesia_id.exists' => 'La iglesia seleccionada no existe en el sistema.',
        ];
    }
}
