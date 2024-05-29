<?php

namespace App\Http\Requests\Nyusyuko\Nyuryoku;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class NyusyukoMeisaiRequest extends FormRequest
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
        $fields = [
            'irisu',
            'situryo',
            'case_su',
            'hasu',
            'su',
            'jyuryo'
        ];
        foreach ($fields as $field) {
            $value = $this->$field;
            if (!is_null($value)) {
                $cleanedValue = floatval(str_replace(',', '', $value));
                $this->merge([
                    $field => $cleanedValue,
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
        $rules = [
            'hinmei_cd' => 'hinmei_exists|required',
            'nyuko_dt' => 'nullable|date_format:Y/m/d,Y-m-d',
            'situryo' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'irisu' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'hasu' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'su' => ['required', 'regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/'],
            'tani_cd' => 'tani_exists|nullable',
            'tani_nm' => 'tani_exists|nullable',
            'jyuryo' => 'max:255|nullable',
            'soko_cd' => 'required|exists:m_soko,soko_cd',
            'location' => 'max:255|nullable',
            'biko' => 'max:255|nullable'
        ];

        if(array_key_exists('soko_cd_to', $this->all())) {
            $rules['soko_cd_to'] = 'required|exists:m_soko,soko_cd';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'hinmei_cd.hinmei_exists' => trans('messages.E0005', ['table' => '品名マスタ']),
            '*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            'situryo.regex' => trans('messages.E0008'),
            'irisu.regex' => trans('messages.E0008'),
            'hasu.regex' => trans('messages.E0008'),
            'su.regex' => trans('messages.E0008'),
            'tani_cd.tani_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tani']),
            'tani_nm.tani_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tani']),
            'soko_cd.exists' => trans('messages.E0005', ['table' => '倉庫マスタ']),
            'soko_cd_to.exists' => trans('messages.E0005', ['table' => '倉庫マスタ'])
        ];
    }

    public function attributes(): array
    {
        return [
            'situryo' => '質量',
            'irisu' => '入り数',
            'hasu' => 'ケース数',
            'su' => '数量',
            'jyuryo' => '重量／㎥',
            'soko_cd' => '倉庫CD',
            'location' => 'ロケーション',
            'biko' => '備考',
            'hinmei_cd' => '商品',
            'soko_cd_to' => '移動先倉庫'
        ];
    }

    public function withValidator($validator)
    {
        $validator->addExtension('hinmei_exists', function($attribute, $value, $parameters, $validator) {
            return true;
            $exists = DB::table('m_soko_hinmei')->where('hinmei_cd', $value)->exists();
            return $exists;
        });

        $validator->addExtension('tani_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('tani_cd'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                ->exists();
            return $exists;
        });
    }
}
