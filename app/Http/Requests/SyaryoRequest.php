<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use Illuminate\Validation\Rule;

class SyaryoRequest extends FormRequest
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
            'syaryo_cd' => 'required|numeric_string|max:255',
            'syasyu_cd' => 'nullable|syaryu_exists|max:255',
            'jiyo_kbn' => 'max:1',
            'jyomuin_cd' => 'nullable|jyomuin_exists|max:255',
            'yousya_cd' => 'nullable|yousya_exists|max:255',
            'bumon_cd' => 'nullable|bumon_exists|max:255',
            'sekisai_kbn' => 'max:1',
            'sekisai_jyuryo' => 'decimal_ex:7,3',
            'point' => 'decimal_ex:9,1',
            'himoku_ritu' =>'decimal_ex:9,1',
            'haisya_dt' => 'nullable|date_format:Y/m/d',
            'rikuun_cd' => 'nullable|rikuun_exists|max:255',
            'car_number_syubetu' => 'max:255',
            'car_number_kana' => 'max:255',
            'car_number' => 'max:255',
            'haisya_biko' => 'max:255',
            'biko' => 'max:255',
            'kyumin_flg' => 'max:1',
        ];

        if ($this->route('syaryoCd') === null) {
            $rules['syaryo_cd'] .= '|unique:m_syaryo,syaryo_cd';
        }
        return $rules;
    }

    public function attributes(): array
    {
        $attributes = trans('attributes.m_syaryo');
        return $attributes;
    }

    public function messages(): array
    {
        return [
            'syaryo_cd.numeric_string' => trans('messages.E0017'),
            'bumon_exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'jyomuin_exists' =>  trans('messages.E0005', ['table' => '乗務員マスタ']),
            'yousya_exists' =>  trans('messages.E0005', ['table' => '庸車先マスタ']),
            'syaryu_exists' =>  trans('messages.E0005', ['table' => '名称マスタ']),
            'rikuun_exists' =>  trans('messages.E0005', ['table' => '名称マスタ']),
            'date_format' => trans('messages.E0010'),
        ];
    }

    public function withValidator($validator)
    {

        $validator->addExtension('bumon_exists', function ($attribute, $value, $parameters, $validator) {
            $bumonExists = DB::table('m_bumon')
                ->where('bumon_cd', $value)
                ->exists();
            return $bumonExists;
        });

        $validator->addExtension('jyomuin_exists', function ($attribute, $value, $parameters, $validator) {
            $jyomuinExists = DB::table('m_jyomuin')
                ->where('jyomuin_cd', $value)
                ->exists();
            return $jyomuinExists;
        });

        $validator->addExtension('yousya_exists', function ($attribute, $value, $parameters, $validator) {
            $yousyaExists = DB::table('m_yousya')
                ->where('yousya_cd', $value)
                ->exists();
            return $yousyaExists;
        });

        $validator->addExtension('syaryu_exists', function ($attribute, $value, $parameters, $validator) {
            $syaryoExists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_SYASYU'))
                ->exists();
            return $syaryoExists;
        });

        $validator->addExtension('rikuun_exists', function ($attribute, $value, $parameters, $validator) {
            $syaryoExists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_RIKUUN'))
                ->exists();
            return $syaryoExists;
        });
    }
}
