<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class HinmeiRequest extends FormRequest
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
        $rules = [
            'hinmei_cd' => 'required|decimal_ex:10,0',
            'kana' => 'max:255',
            'hinmei_nm' => 'max:255',
            'hinmei2_cd' => 'max:255',
            'hinmoku_cd' => 'max:255',
            'tani_cd' => 'max:255',
            'tani_jyuryo' => 'decimal_ex:7,3',
            'haisya_tani_jyuryo' => 'decimal_ex:7,3',
            'syoguti_kbn1' => 'max:255',
            'syoguti_kbn2' => 'max:255',
            'ninusi_id' => 'max:255',
            'bumon_cd' => 'max:255',
            'kyumin_flg' => 'max:1',
        ];

        if ($this->filled('hinmoku_cd')) {
            $rules['hinmoku_cd'] .= '|exists:m_hinmoku,hinmoku_cd';
        }

        if ($this->filled('tani_cd')) {
            $rules['tani_cd'] .= '|tani_exists';
        }

        if ($this->filled('ninusi_id') && $this->ninusi_id != '0') {
            $rules['ninusi_id'] .= '|exists:m_ninusi,ninusi_cd';
        }

        if ($this->filled('bumon_cd') && $this->bumon_cd != '0') {
            $rules['bumon_cd'] .= '|exists:m_bumon,bumon_cd';
        }

        if (!$this->route('hinmeiCd') && $this->route('hinmeiCd') !== '0') {
            $rules['hinmei_cd'] .= '|bail|hinmei_exists';
        }

        return $rules;
    }
    public function attributes(): array
    {
        return trans('attributes.m_hinmei');
    }

    public function messages(): array
    {
        return [
            'hinmei_cd.hinmei_exists' => trans('messages.E0003'),
            'hinmei_cd.decimal_ex' => trans('messages.E0008'),
            'hinmoku_cd.exists' => trans('messages.E0005', ['table' => '品目マスタ']),
            'tani_cd.tani_exists' => trans('messages.E0005', ['table' => '名称マスタ']),
            'ninusi_id.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'bumon_cd.exists' => trans('messages.E0005', ['table' => '部門マスタ'])
        ];
    }
    public function withValidator($validator)
    {
        $validator->addExtension('hinmei_exists', function ($attribute, $value, $parameters, $validator) {
            $hinmeiCdExists = DB::table('m_hinmei')
                ->where('hinmei_cd', $value)
                ->exists();
            return !$hinmeiCdExists;
        });

        $validator->addExtension('tani_exists', function ($attribute, $value, $parameters, $validator) {
            $taniExists = DB::table('m_meisyo')
                ->where([
                    ['meisyo_cd', $value],
                    ['meisyo_kbn', '=', configParam('MEISYO_KBN_TANI')]
                ])
                ->exists();
            return $taniExists;
        });
    }
}
