<?php

namespace App\Http\Requests\Picking;

use Illuminate\Foundation\Http\FormRequest;

class PickingGurisutoRequest extends FormRequest
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
            case 'picking.picking_gurisuto.exp.filterValidate':
                return [
                    'exp.bumon_cd_from'     => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to'       => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.kisan_dt_from'     => 'nullable|date_format:Y/m/d',
                    'exp.kisan_dt_to'       => 'nullable|date_format:Y/m/d',
                    'exp.hachaku_cd_from'   => 'nullable|exists:m_hachaku,hachaku_cd',
                    'exp.hachaku_cd_to'     => 'nullable|exists:m_hachaku,hachaku_cd',
                    'exp.syaban_from'       => 'nullable|exists:m_syaryo,syaryo_cd',
                    'exp.syaban_to'         => 'nullable|exists:m_syaryo,syaryo_cd',
                    'exp.jyomuin_cd_from'   => 'nullable|exists:m_jyomuin,jyomuin_cd',
                    'exp.jyomuin_cd_to'     => 'nullable|exists:m_jyomuin,jyomuin_cd',
                    'exp.yousya_cd_from'    => 'nullable|exists:m_yousya,yousya_cd',
                    'exp.yousya_cd_to'      => 'nullable|exists:m_yousya,yousya_cd',
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
            'exp.hachaku_cd_from'   => '荷届け先From',
            'exp.hachaku_cd_to'     => '荷届け先To',
            'exp.jyomuin_cd_from'   => '乗務員コードFrom',
            'exp.jyomuin_cd_to'     => '乗務員コードTo',
            'exp.yousya_cd_from'    => '傭車先コードFrom',
            'exp.yousya_cd_to'      => '傭車先コードTo',
            'exp.syaban_from'       => '車番From',
            'exp.syaban_to'         => '車番To',
        ];
    }


    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists'      => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists'        => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.*.date_format'             => trans('messages.E0007'),
            'exp.jyomuin_cd_from.exists'    => trans('messages.E0005', ['table' => '乗務員マスタ']),
            'exp.jyomuin_cd_to.exists'      => trans('messages.E0005', ['table' => '乗務員マスタ']),
            'exp.hachaku_cd_from.exists'    => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.hachaku_cd_to.exists'      => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.yousya_cd_from.exists'     => trans('messages.E0005', ['table' => '庸車先マスタ']),
            'exp.yousya_cd_to.exists'       => trans('messages.E0005', ['table' => '庸車先マスタ']),
            'exp.syaban_from.exists'        => trans('messages.E0005', ['table' => '車両マスタ']),
            'exp.syaban_to.exists'          => trans('messages.E0005', ['table' => '車両マスタ']),
        ];
    }
}
