<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class UriageRequest extends FormRequest
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
            'unchin_kin',
            'tyukei_kin',
            'tukoryo_kin',
            'syuka_kin',
            'tesuryo_kin',
            'syaryo_kin',
            'unten_kin',
            'yosya_tyukei_kin',
            'yosya_kin_tax',
            'su'
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
        switch ($this->route()->getName()) {
            case 'uriage.exp.filterValidate':
                return [
                    'exp.bumon_cd_from' => 'nullable|string|max:4|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to' => 'nullable|string|max:4|exists:m_bumon,bumon_cd',
                    'exp.unso_dt_from' => 'nullable|date_format:Y/m/d',
                    'exp.unso_dt_to' => 'nullable|date_format:Y/m/d',
                    'exp.ninusi_cd_from' => 'nullable|string|max:10|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to' => 'nullable|string|max:10|exists:m_ninusi,ninusi_cd',
                ];
        }
        return [
            'bumon_cd' => 'bumon_exists|nullable',
            'ninusi_cd' => 'ninusi_exists|nullable',
            'hatuti_cd' => 'hatuti_exists|nullable',
            'syuka_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'haitatu_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'hachaku_cd' => 'hachaku_exists|nullable',
            'syubetu_cd' => 'syubetu_exists|nullable',
            'hinmei_cd' =>  'hinmei_exists|nullable',
            'su' => ['regex:/^-?(0|[1-9]\d{0,6})(\.\d{1,3})?(?<!-0)$/', 'nullable'],
            'tani_cd' => 'tani_exists|nullable',
            'unso_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'jyotai' => 'nullable|max:255',
            'sitadori' => 'nullable|max:255',
            'gyosya_cd' => 'gyosya_exists|nullable',
            'genkin_cd' => 'genkin_exists|nullable',
            'unchin_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tyukei_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tukoryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'syuka_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tesuryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'biko_cd' => 'nullable|exists:m_biko,biko_cd',
            'syaryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'unten_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            // 'unchin_mikakutei_kbn' => 'in:0,1,9|nullable',
            'jyomuin_cd' => 'nullable|exists:m_jyomuin,jyomuin_cd',
            'yousya_cd' => 'nullable|exists:m_yousya,yousya_cd',
            'yosya_tyukei_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'yosya_tukoryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'denpyo_send_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'nipou_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'unchin_mikakutei_kbn' => 'unchin_kakutei_exists|nullable',
            'syaban' => 'nullable|max:255'
        ];
    }

    public function attributes(): array
    {
        return [
            'exp.bumon_cd_from' => '部門コードFrom',
            'exp.bumon_cd_to' => '部門コードTo',
            'exp.unso_dt_from' => '運送日From',
            'exp.unso_dt_to' => '運送日To',
            'exp.ninusi_cd_from' => '荷主コードFrom',
            'exp.ninusi_cd_to' => '荷主コードTo',
            'exp.uriage_den_no_from' => '売上NOFrom',
            'exp.uriage_den_no_to' => '売上NOTo',
            'su' => '数量'
        ];
    }

    public function messages(): array
    {
        return [
            'exp.bumon_cd_from.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.*.date_format' => trans('messages.E0007'),
            'exp.ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            //
            'bumon_cd.bumon_exists' => trans('messages.E0005', ['table' => '部門マスタ']),

            'hachaku_cd.hachaku_exists' => trans('messages.E0005', ['table' => '発着地マスタ']),

            '*.date_format' => trans('messages.E0007', ['attribute' => '日付']),

            'hachaku_cd.hachaku_exists' => trans('messages.E0005', ['table' => '発着地マスタ']),

            'syubetu_cd.syubetu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=syubetu']),

            'ninusi_cd.ninusi_exists' => trans('messages.E0005', ['table' => '荷主マスタ']),

            'hinmei_cd.hinmei_exists' => trans('messages.E0005', ['table' => '品名マスタ']),
            'hatuti_cd.hatuti_exists' => trans('messages.E0005', ['table' => '発地マスタ']),
            'su.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 3]),
            'tani_cd.tani_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tani']),
            'gyosya_cd.gyosya_exists' => trans('messages.E0005', ['table' => '名称マスタ']),
            'unchin_kin.regex' => trans('messages.E0020', ['attribute' => '基本運賃']),
            'tyukei_kin.regex' => trans('messages.E0020', ['attribute' => '中継料']),
            'tukoryo_kin.regex' => trans('messages.E0020', ['attribute' => '通行料等']),
            'syuka_kin.regex' => trans('messages.E0020', ['attribute' => '集荷料']),
            'tesuryo_kin.regex' => trans('messages.E0020', ['attribute' => '手数料']),
            'biko_cd.exists' => trans('messages.E0005', ['table' => '備考マスタ']),
            'syaryo_kin.regex' => trans('messages.E0020', ['attribute' => '車両金額']),
            'unten_kin.regex' => trans('messages.E0020', ['attribute' => '運転者金額']),
            // 'unchin_mikakutei_kbn.in' => trans('messages.E0007', ['attribute' => '運賃確定区分']),
            'jyomuin_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'yousya_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'yosya_tyukei_kin.regex' => trans('messages.E0020', ['attribute' => '庸車料']),
            'yosya_tukoryo_kin.regex' => trans('messages.E0020', ['attribute' => '庸車通行料等']),
            'genkin_cd.genkin_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=genkin']),
            'unchin_mikakutei_kbn.unchin_kakutei_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=unchinkakutei']),
        ];
    }

    public function withValidator($validator)
    {

        $validator->addExtension('bumon_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_bumon')
                ->where('bumon_cd', request()->input('bumon_cd'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('ninusi_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_ninusi')
                ->where('ninusi_cd', request()->input('ninusi_cd'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('hatuti_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = \DB::table('m_hachaku')
                    ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'))
                    ->where('hachaku_cd', $value)
                    ->exists();
            return $exists;
        });

        $validator->addExtension('hachaku_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_hachaku')
                ->where('hachaku_cd', request()->input('hachaku_cd'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('syubetu_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('syubetu_cd'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_SYUBETU'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('tani_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('tani_cd'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('gyosya_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('gyosya_cd'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('hinmei_exists', function($attribute, $value, $parameters, $validator) {
            if (!preg_match('/^[0-9]+$/', $value)) {
                return false;
            }
            $exists = DB::table('m_hinmei')
                ->leftJoin('m_hinmoku', 'm_hinmei.hinmoku_cd', 'm_hinmoku.hinmoku_cd')
                ->where('m_hinmei.hinmei_cd', $value)
                ->exists();
            return $exists;
        });

        $validator->addExtension('genkin_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('genkin_cd'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_GENKIN'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('unchin_kakutei_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('unchin_mikakutei_kbn'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))
                ->exists();
            return $exists;
        });
    }
}
