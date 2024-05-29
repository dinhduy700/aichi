<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JyutyuRequest extends FormRequest
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
            case 'jyutyu.exp.filterValidate':
                return [
                    'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.hatuti_cd_from' => 'nullable|exists:m_hachaku,hachaku_cd',
                    'exp.hatuti_cd_to' => 'nullable|exists:m_hachaku,hachaku_cd',
                    'exp.hachaku_cd_from' => 'nullable|exists:m_hachaku,hachaku_cd',
                    'exp.hachaku_cd_to' => 'nullable|exists:m_hachaku,hachaku_cd',

                    'exp.syuka_dt_from' => 'nullable|date_format:Y/m/d',
                    'exp.syuka_dt_to' => 'nullable|date_format:Y/m/d',

                    'exp.ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',

                    'exp.jyutyu_kbn_from' => $ruleJyutyuKbn = [
                        'nullable',
                        Rule::exists('m_meisyo', 'meisyo_cd')->where(function ($query) {
                            return $query->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'));
                        }),
                    ],
                    'exp.jyutyu_kbn_to' => $ruleJyutyuKbn,
                ];
        }

        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'exp.bumon_cd_from' => '部門コードFrom',
            'exp.bumon_cd_to' => '部門コードTo',
            'exp.hatuti_cd_from' => '発地From',
            'exp.hatuti_cd_to' => '発地To',
            'exp.hachaku_cd_from' => '着地From',
            'exp.hachaku_cd_to' => '着地To',
            'exp.syuka_dt_from' => '集荷日From',
            'exp.syuka_dt_to' => '集荷日To',
            'exp.ninusi_cd_from' => '荷主コードFrom',
            'exp.ninusi_cd_to' => '荷主コードTo',
            'exp.jyutyu_kbn_from' => '受注区分From',
            'exp.jyutyu_kbn_to' => '受注区分To',
        ];
    }

    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.hatuti_cd_from.exists' => trans('messages.E0005', ['table' => '発地着地マスタ']),
            'exp.hatuti_cd_to.exists' => trans('messages.E0005', ['table' => '発地着地マスタ']),
            'exp.hachaku_cd_from.exists' => trans('messages.E0005', ['table' => '発地着地マスタ']),
            'exp.hachaku_cd_to.exists' => trans('messages.E0005', ['table' => '発地着地マスタ']),
            'exp.*.date_format' => trans('messages.E0007'),
            'exp.ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.jyutyu_kbn_from.exists' => trans('messages.E0005', ['table' => '名称マスタ']),
            'exp.jyutyu_kbn_to.exists' => trans('messages.E0005', ['table' => '名称マスタ']),

        ];
    }
}
