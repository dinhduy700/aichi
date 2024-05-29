<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NyukinRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function prepareForValidation()
    {
        $fields =  [
            'genkin_kin',
            'furikomi_kin',
            'furikomi_tesuryo_kin',
            'tegata_kin',
            'sousai_kin',
            'nebiki_kin',
            'sonota_nyu_kin'
        ];
        foreach ($fields as $field) {
            $value = $this->$field;
            if (!is_null($value)) {
                $this->merge([
                    $field => floatval(str_replace(',', '', $value))
                ]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        switch ($this->route()->getName()) {
            case 'nyukin.exp.filterValidate':
                return [
                    'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.nyukin_dt_from' => 'nullable|date_format:Y/m/d',
                    'exp.nyukin_dt_to' => 'nullable|date_format:Y/m/d',

                ];
        }
        return [
            'ninusi_cd' => 'nullable|exists:m_ninusi,ninusi_cd',
            'ninusi_nm' => 'nullable|exists:m_ninusi,ninusi_ryaku_nm',
            'nyukin_dt' => 'nullable|date_format:Y/m/d',
            'seikyu_sime_dt' => 'nullable|date_format:Y/m/d',
            'genkin_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'furikomi_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'furikomi_tesuryo_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'tegata_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'tegata_kijitu_kin' => 'nullable|date_format:Y/m/d',
            'sousai_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'nebiki_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'sonota_nyu_kin' => 'nullable|integer|max:999999999|min:-999999999',
            'biko' => 'nullable|max:255'
        ];
    }

    

    public function messages(): array
    {
        
        return [
            'ninusi_cd.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_nm.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            '*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            '*.integer' => trans('messages.E0008', ['int' => 9]),
            '*.min' => trans('messages.E0008', ['int' => 9]),
            'biko.max' => trans('messages.E0015'),
            '*.max' => trans('messages.E0008', ['int' => 9]),

            'exp.*.date_format' => trans('messages.E0007'),
            'exp.*.exists' => trans('messages.E0005', ['table' => '部門マスタ'])
        ];
    }

    public function attributes(): array
    {
        return [
            'exp.nyukin_dt_from' => '入金日From',
            'exp.nyukin_dt_to' => '入金日To',

            'genkin_kin' => '現金',
            'furikomi_kin' => '振込',
            'furikomi_tesuryo_kin' => '振込手数料',
            'tegata_kin' => '手形',
            'sousai_kin' => '相殺',
            'nebiki_kin' => '値引',
            'sonota_nyu_kin' => 'その他入金',
            'biko' => '備考'
        ];
    }
}
