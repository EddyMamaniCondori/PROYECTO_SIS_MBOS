<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class UnidadEducativaRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'max:150',
                // Usamos la regla nativa que es mucho más rápida y cacheable por Laravel
                Rule::unique('unidad_educativas', 'nombre')->ignore($this->route('unidad_educativa')),
            ],

            'id_pastor' => [
                'nullable',
                'integer',
                // Reemplazamos el JOIN manual por la regla 'exists' con condiciones adicionales
                Rule::exists('pastors', 'id_pastor')->where(function ($query) {
                    $query->whereExists(function ($q) {
                        $q->select(DB::raw(1))
                        ->from('personas')
                        ->whereRaw('personas.id_persona = pastors.id_pastor')
                        ->where('personas.estado', true);
                    });
                }),
            ],
        ];
    }
}
