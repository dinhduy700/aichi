<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use Illuminate\Support\Facades\Validator;
class OrderEntryRequest extends FormRequest
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
            'yosya_tukoryo_kin',
            'kaisyu_kin',
            'seikyu_tanka',
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
        return [
            'bumon_cd' => 'bumon_exists|nullable',
            'bumon_nm' => 'nullable',

            'hatuti_cd' => 'hatuti_exists|nullable',
            'hatuti_nm' => 'nullable',

            'genkin_cd' => 'genkin_exists|nullable',
            'genkin_nm' => 'nullable',

            'ninusi_cd' => 'ninusi_exists|nullable',
            'ninusi_nm' => 'nullable',

            'syuka_dt' => 'date_format:Y/m/d,Y-m-d|nullable',

            'haitatu_dt' => 'date_format:Y/m/d,Y-m-d|nullable',

            'hachaku_cd' => 'hachaku_exists|nullable',
            'hachaku_nm' => 'nullable',

            'su' => ['regex:/^-?(0|[1-9]\d{0,6})(\.\d{1,3})?(?<!-0)$/', 'nullable'],

            'hinmei_cd' =>  'hinmei_exists|nullable',
            'hinmoku_nm' => 'nullable',

            'tani_cd' => 'tani_exists|nullable',
            'tani_nm' => 'nullable',
            'jyotai' => 'nullable|max:255',
            'sitadori' => 'nullable|max:255',

            'gyosya_cd' => 'gyosya_exists|nullable',
            'gyosya_nm' => 'nullable',
            'tyuki' => 'nullable|max:255',

            'tanka_kbn' => 'nullable|tanka_exists',
            'tanka_nm' => 'nullable',

            'seikyu_tanka' => ['regex:/^-?(0|[1-9]\d{0,6})(\.\d{1,2})?(?<!-0)$/', 'nullable'],

            'unchin_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tyukei_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tukoryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'syuka_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tesuryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'biko_cd' => 'nullable|exists:m_biko,biko_cd',
            'biko' => 'nullable',
            'syaryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'unten_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'unchin_mikakutei_kbn' => 'unchin_kakutei_exists|nullable',
            'unchin_mikakutei_nm' => 'nullable',

            'yousya_cd' => 'nullable|exists:m_yousya,yousya_cd',
            'yousya_nm' => 'nullable',

            'yosya_tyukei_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'yosya_tukoryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'okurijyo_no' => 'nullable|max:255',
            'jyutyu_kbn' => 'nullable|jyutyu_exists',
            'jyutyu_nm' => 'nullable',
            'kaisyu_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'kaisyu_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'add_tanto_cd' => 'nullable|exists:m_jyomuin,jyomuin_cd',
            'add_tanto_nm' => 'nullable',
            'haitatu_tel' => 'nullable|max:255',
            
        ];
    }

    public function attributes(): array
    {
        return [
            'seikyu_tanka' => '請求単価',
            'unchin_kin' => '基本運賃',
            'tyukei_kin' => '中継料',
            'tukoryo_kin' => '通行料等',
            'syuka_kin' => '集荷料',
            'tesuryo_kin' => '手数料',
            'syaryo_kin' => '車両金額',
            'unten_kin' => '運転者金額',
            'yosya_tyukei_kin' => '庸車料',
            'yosya_tukoryo_kin' => '庸車通行料等',
            'kaisyu_kin' => '回収金額',
            'su' => '数量'
        ];
    }   

    public function messages(): array
    {
        return [
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
            'unchin_kin.regex' =>  trans('messages.E0008', ['int' => 7]),
            'tyukei_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'tukoryo_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'syuka_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'tesuryo_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'biko_cd.exists' => trans('messages.E0005', ['table' => '備考マスタ']),
            'syaryo_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'unten_kin.regex' => trans('messages.E0008', ['int' => 7]),
            // 'unchin_mikakutei_kbn.in' => trans('messages.E0007', ['attribute' => '運賃確定区分']),
            'add_tanto_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'yousya_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'yosya_tyukei_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'yosya_tukoryo_kin.regex' => trans('messages.E0008', ['int' => 7]),
            'genkin_cd.genkin_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=genkin']),
            'unchin_mikakutei_kbn.unchin_kakutei_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=unchinkakutei']),

            'tanka_kbn.tanka_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tanka']),
            'seikyu_tanka.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 2]),

            'jyutyu_kbn.jyutyu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=jyutyu']),

            'kaisyu_kin.regex' => trans('messages.E0008', ['int' => 7]),
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

        $validator->addExtension('tanka_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('tanka_kbn'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANKA'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('jyutyu_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', request()->input('jyutyu_kbn'))
                ->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'))
                ->exists();
            return $exists;
        });
    }
}
