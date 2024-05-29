<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SagyoRequest extends FormRequest
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
            case 'sagyo.exp.filterValidate':
                return [
                    'exp.bumon_cd_from'     => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to'       => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.jyomuin_cd_from'   => 'nullable|exists:m_jyomuin,jyomuin_cd',
                    'exp.jyomuin_cd_to'     => 'nullable|exists:m_jyomuin,jyomuin_cd',
                    'exp.yousya_cd_from'    => 'nullable|exists:m_yousya,yousya_cd',
                    'exp.yousya_cd_to'      => 'nullable|exists:m_yousya,yousya_cd',
                    'exp.dt_from'           => 'nullable|date_format:Y/m/d',
                    'exp.dt_to'             => 'nullable|date_format:Y/m/d',
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
            'exp.jyomuin_cd_from'   => '運転者CdFrom',
            'exp.jyomuin_cd_to'     => '運転者CdTo',
            'exp.yousya_cd_from'    => '庸車先CdFrom',
            'exp.yousya_cd_to'      => '庸車先CdTo',
            'exp.dt_from'           => '日付From',
            'exp.dt_to'             => '日付To',
        ];
    }

    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists'      => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists'        => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.jyomuin_cd_from.exists'    => trans('messages.E0005', ['table' => '乗務員マスタ']),
            'exp.jyomuin_cd_to.exists'      => trans('messages.E0005', ['table' => '乗務員マスタ']),
            'exp.yousya_cd_from.exists'     => trans('messages.E0005', ['table' => '庸車先マスタ']),
            'exp.yousya_cd_to.exists'       => trans('messages.E0005', ['table' => '庸車先マスタ']),
            'exp.*.date_format'             => trans('messages.E0007'),
        ];
    }
}
