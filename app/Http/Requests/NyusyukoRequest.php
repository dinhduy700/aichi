<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NyusyukoRequest extends FormRequest
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
            case 'nyusyuko.nipou.nipouFilterValidate':
                return [
                        'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
                        'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
                        'exp.ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
                        'exp.ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',
                        'exp.kijyun_dt' => 'required|date_format:Y/m/d',
                        'exp.hinmei_cd_from' => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                        'exp.hinmei_cd_to' => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                    ];
            case 'nyusyuko.exp.zaikoFilterValidate':
                return [
                    'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.kijyun_dt' => 'nullable|date_format:Y/m/d',
                    'exp.hinmei_cd_from' => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                    'exp.hinmei_cd_to' => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                ];
            case 'nyusyuko.zaikoList.zaikoListFilterValidate':
                return [
                    'exp.kijyun_dt' => 'required|date_format:Y/m/d',
                    'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.soko_cd_from' => 'nullable|exists:m_soko,soko_cd',
                    'exp.soko_cd_to' => 'nullable|exists:m_soko,soko_cd',
                    'exp.ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.hinmei_cd_from' => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                    'exp.hinmei_cd_to' => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                ];
        }

        return [
            //
        ];
    }

    public function attributes()
    {
        switch ($this->route()->getName()) {
            case 'nyusyuko.nipou.nipouFilterValidate':
                return [
                    'exp.kijyun_dt' => '基準日',
                    'exp.bumon_cd_from' => '部門CD（開始）',
                    'exp.bumon_cd_to' => '部門CD（終了）',
                    'exp.ninusi_cd_from' => '荷主CD（開始）',
                    'exp.ninusi_cd_to' => '荷主CD（終了）',
                    'exp.hinmei_cd_from' => '商品CD（開始）',
                    'exp.hinmei_cd_to' => '商品CD（終了）',
                ];
            case 'nyusyuko.exp.zaikoFilterValidate':
                return [
                    'exp.bumon_cd_from' => '部門コードFrom',
                    'exp.bumon_cd_to' => '部門コードTo',
                    'exp.ninusi_cd_from' => '荷主コードFrom',
                    'exp.ninusi_cd_to' => '荷主コードTo',
                    'exp.kijyun_dt' => '基準日',
                    'exp.hinmei_cd_from' => '商品From',
                    'exp.hinmei_cd_to' => '商品To',
                ];
            case 'nyusyuko.zaikoList.zaikoListFilterValidate':
                return [
                    'exp.kijyun_dt' => '基準日',
                    'exp.bumon_cd_from' => '部門コードFrom',
                    'exp.bumon_cd_to' => '部門コードTo',
                    'exp.soko_cd_from' => '倉庫コードFrom',
                    'exp.soko_cd_to' => '倉庫コードTo',
                    'exp.ninusi_cd_from' => '荷主コードFrom',
                    'exp.ninusi_cd_to' => '荷主コードTo',
                    'exp.hinmei_cd_from' => '商品From',
                    'exp.hinmei_cd_to' => '商品To',
                ];

            default: return [];
        }
    }

    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.*.date_format' => trans('messages.E0007'),
            'exp.hinmei_cd_from.exists' => trans('messages.E0005', ['table' => '倉庫商品マスタ']),
            'exp.hinmei_cd_to.exists' => trans('messages.E0005', ['table' => '倉庫商品マスタ']),
            'exp.soko_cd_from.exists' => trans('messages.E0005', ['table' => '倉庫マスタ']),
            'exp.soko_cd_to.exists' => trans('messages.E0005', ['table' => '倉庫マスタ']),
        ];
    }
}
