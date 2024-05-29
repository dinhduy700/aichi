<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use App\Rules\OnlySingleByte;

class YousyaRequest extends FormRequest
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
            'yousya_cd'                     => 'required|numeric_string|max:255',
            'kana'                          => 'max:255',
            'yousya1_nm'                    => 'max:255',
            'yousya2_nm'                    => 'max:255',
            'yousya_ryaku_nm'               => 'required|max:255',
            'bumon_cd'                      => 'nullable|max:255|bumon_exists',
            'yubin_no'                      => 'max:255',
            'jyusyo1_nm'                    => 'max:255',
            'jyusyo2_nm'                    => 'max:255',
            'tel'                           => 'max:255',
            'fax'                           => 'max:255',
            'siharai_kbn'                   => 'max:255',
            'siharai_cd'                    => 'nullable|max:255|yousya_siharai_cd_exists',
            'seikyu_mu_kbn'                 => 'max:1',
            'yousya_ritu'                   => 'decimal_ex:9,1',
            'siharai_umu_kbn'               => 'max:1',
            'simebi1'                       => 'nullable|integer|min:1|max:31',
            'simebi2'                       => 'nullable|integer|min:1|max:31',
            'simebi3'                       => 'nullable|integer|min:1|max:31',
            'mikakutei_seigyo_kbn'          => 'max:1',
            'kin_hasu_kbn'                  => 'max:1',
            'kin_hasu_tani'                 => 'max:1',
            'zei_keisan_kbn'                => 'max:1',
            'zei_hasu_kbn'                  => 'max:255',
            'zei_hasu_tani'                 => 'max:255',
            'kaikake_saki_cd'               => 'nullable|max:255|yousya_kaikake_saki_cd_exists',
            'siharai_nyuryoku_umu_kbn'      => 'max:1',
            'siharai1_dd'                   => 'nullable|integer|min:1|max:31',
            'siharai2_dd'                   => 'nullable|integer|min:1|max:31',
            'comennt'                       => 'max:255',
            'kensaku_kbn'                   => 'max:1',
            'mail'                          => ['nullable',new OnlySingleByte,'max:255'],
            'haisya_biko'                   => 'max:255',
            'biko'                          => 'max:255',
            'kyumin_flg'                    => 'max:1',
        ];

        if($this->route('yousyaCd') == '') {
            $rules['yousya_cd'] .= '|yousya_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_yousya');
    }

    public function messages(): array
    {
        return [
            'integer'                   => trans('messages.E0013'),
            'max'                       => trans('messages.E0015'),
            'simebi1.min'               => trans('messages.E0013'),
            'simebi1.max'               => trans('messages.E0013'),
            'simebi2.min'               => trans('messages.E0013'),
            'simebi2.max'               => trans('messages.E0013'),
            'simebi3.min'               => trans('messages.E0013'),
            'simebi3.max'               => trans('messages.E0013'),
            'siharai1_dd.min'           => trans('messages.E0013'),
            'siharai1_dd.max'           => trans('messages.E0013'),
            'siharai2_dd.min'           => trans('messages.E0013'),
            'siharai2_dd.max'           => trans('messages.E0013'),
            'yousya_cd.numeric_string'  => trans('messages.E0017'),

            'yousya_cd.yousya_exists'                       => trans('messages.E0003'),
            'bumon_cd.bumon_exists'                         => trans('messages.E0005', ['table' => '部門マスタ']),
            'siharai_cd.yousya_siharai_cd_exists'           => trans('messages.E0005', ['table' => ' 庸車先マスタ']),
            'kaikake_saki_cd.yousya_kaikake_saki_cd_exists' => trans('messages.E0005', ['table' => '庸車先マスタ']),
        ];
    }

    public function withValidator($validator)
    {
        $validator->addExtension('yousya_exists', function ($attribute, $value, $parameters, $validator) {
            $yousyaExists = DB::table('m_yousya')
                ->where('yousya_cd', $value)
                ->exists();

            return !$yousyaExists;
        });

        $validator->addExtension('bumon_exists', function ($attribute, $value, $parameters, $validator) {
            $bumonExists = DB::table('m_bumon')
                ->where('bumon_cd', $value)
                ->exists();

            return $bumonExists;
        });

        $validator->addExtension('yousya_kaikake_saki_cd_exists', function ($attribute, $value, $parameters, $validator) {
            $yousyaKaikakeSakiExists = DB::table('m_yousya')
                ->where('yousya_cd', $value)
                ->exists();

            return $yousyaKaikakeSakiExists;
        });

        $validator->addExtension('yousya_siharai_cd_exists', function ($attribute, $value, $parameters, $validator) {
            $yousyaSiharaiCdExists = DB::table('m_yousya')
                ->where('yousya_cd', $value)
                ->exists();

            return $yousyaSiharaiCdExists;
        });
    }
}
