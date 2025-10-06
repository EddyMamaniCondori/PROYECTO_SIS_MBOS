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
         return [
            'mes' => 'required|string|max:20',
            'anio' => 'required|integer|min:2000|max:2100',
            'desafio_visitacion' => 'required|integer|min:0',
            'desafio_bautiso' => 'required|integer|min:0',
            'desafio_inst_biblicos' => 'required|integer|min:0',
            'desafios_est_biblicos' => 'required|integer|min:0',
            'visitas_alcanzadas' => 'required|integer|min:0',
            'bautisos_alcanzados' => 'required|integer|min:0',
            'instructores_alcanzados' => 'required|integer|min:0',
            'estudiantes_alcanzados' => 'required|integer|min:0',
            'iglesia_id' => 'required|exists:iglesias,id_iglesia',
            'pastor_id' => 'required|exists:pastors,id_pastor',
        ];

        return [
            'mes.required' => 'El mes es obligatorio.',
            'anio.required' => 'El año es obligatorio.',
            'anio.min' => 'El año no puede ser menor a 2000.',
            'anio.max' => 'El año no puede ser mayor a 2100.',
            'desafio_visitacion.required' => 'Debe ingresar el desafío de visitación.',
            'desafio_bautiso.required' => 'Debe ingresar el desafío de bautismo.',
            'desafio_inst_biblicos.required' => 'Debe ingresar el desafío de instructores bíblicos.',
            'desafios_est_biblicos.required' => 'Debe ingresar el desafío de estudiantes bíblicos.',
            'iglesia_id.required' => 'Debe seleccionar una iglesia.',
            'iglesia_id.exists' => 'La iglesia seleccionada no existe.',
            'pastor_id.required' => 'Debe seleccionar un pastor.',
            'pastor_id.exists' => 'El pastor seleccionado no existe.',
        ];
    }
}
