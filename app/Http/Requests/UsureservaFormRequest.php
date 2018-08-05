<?php

namespace sistemaReserva\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UsureservaFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
         return [
            'fecha' => 'required',
            'horainicio' => 'required',
            'horafinal' => 'required',
            'horallegada' => 'nullable',
            'tiempoespera'=>'nullable',
            'cantidad'=>'required',
            'id'=>'nullable',
            'idarea'=>'required',
            'codigoqr'=>'nullable',
        ];
    }
}
