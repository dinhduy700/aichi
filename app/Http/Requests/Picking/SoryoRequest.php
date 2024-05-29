<?php

namespace App\Http\Requests\Picking;

use Illuminate\Foundation\Http\FormRequest;

class SoryoRequest extends FormRequest
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
        switch ($this->route()->getName()) {
            case 'picking.soryo.exp.filterValidate':
                return [
                    'exp.bumon_cd_from'     => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to'       => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.kisan_dt_from'     => 'nullable|date_format:Y/m/d',
                    'exp.kisan_dt_to'       => 'nullable|date_format:Y/m/d',
                    'exp.ninusi_cd_from'    => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to'      => 'nullable|exists:m_ninusi,ninusi_cd',
                ];
        }

        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'exp.bumon_cd_from'     => '部門コードFrom',
            'exp.bumon_cd_to'       => '部門コードTo',
            'exp.kisan_dt_from'     => '出庫日From',
            'exp.kisan_dt_to'       => '出庫日To',
            'exp.ninusi_cd_from'    => '荷主コードFrom',
            'exp.ninusi_cd_to'      => '荷主コードTo',
        ];
    }


    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists'      => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists'        => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.*.date_format'             => trans('messages.E0007'),
            'exp.ninusi_cd_from.exists'     => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.ninusi_cd_to.exists'       => trans('messages.E0005', ['table' => '荷主マスタ']),
        ];
    }
}
