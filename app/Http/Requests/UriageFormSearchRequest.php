<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
class UriageFormSearchRequest extends FormRequest
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
            'hed_bumon_cd' => 'exists:m_bumon,bumon_cd|nullable',
            'hed_jyomuin_cd' => 'exists:m_jyomuin,jyomuin_cd|nullable',
            'hed_unso_dt_from' => 'nullable|date_format:Y/m/d,Y-m-d',
            'hed_unso_dt_to' => 'nullable|date_format:Y/m/d,Y-m-d|after_or_equal:hed_unso_dt_from',
            'hed_add_tanto_cd' => 'exists:m_jyomuin,jyomuin_cd|nullable',
            'hed_jyutyu_kbn' => 'jyutyu_exists|nullable',

            'bumon_cd_from' => 'exists:m_bumon,bumon_cd|nullable',
            'bumon_cd_to' => 'exists:m_bumon,bumon_cd|nullable',

            'hatuti_cd_from' => 'hatuti_exists|nullable',
            'hatuti_cd_to' => 'hatuti_exists|nullable',

            // 'genkin_cd' => 'genkin_exist|nullable',

            'ninusi_cd_from' => 'exists:m_ninusi,ninusi_cd|nullable',
            'ninusi_cd_to' => 'exists:m_ninusi,ninusi_cd|nullable',

            'syuka_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'syuka_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:syuka_dt_from',

            'haitatu_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'haitatu_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:haitatu_dt_from',

            'hachaku_cd_from' => 'exists:m_hachaku,hachaku_cd|nullable',
            'hachaku_cd_to' => 'exists:m_hachaku,hachaku_cd|nullable',
            'hinmei_cd_from' => 'hinmei_exists|nullable',
            'hinmei_cd_to'=> 'hinmei_exists|nullable',

            'su_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'su_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'tani' => 'tani_exists|nullable',

            'jyotai' => 'nullable',
            'sitadori' => 'nullable',
            'gyosya_cd_from' => 'gyosya_exists|nullable',
            'gyosya_cd_to' => 'gyosya_exists|nullable',

            'tyuki_from' => 'nullable',
            'tyuki_to' => 'nullable',
            'tanka_kbn_from' => 'nullable|tanka_exists',
            'tanka_kbn_to' => 'nullable|tanka_exists',

            'seikyu_tanka_from' => ['regex:/^-?(0|[1-9]\d{0,6})(\.\d{1,2})?(?<!-0)$/', 'nullable'],
            'seikyu_tanka_to' =>['regex:/^-?(0|[1-9]\d{0,6})(\.\d{1,2})?(?<!-0)$/', 'nullable'],

            'unchin_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'unchin_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'tyukei_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tyukei_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'tukoryo_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tukoryo_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'syuka_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'syuka_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'tesuryo_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'tesuryo_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'syaryo_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'syaryo_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'unten_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'unten_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            // 'unchin_mikakutei_kbn' => 

            'yousya_cd_from' => 'nullable|exists:m_yousya,yousya_cd',
            'yousya_cd_to' => 'nullable|exists:m_yousya,yousya_cd',

            'yosya_tyukei_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'yosya_tyukei_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'yosya_tukoryo_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'yosya_tukoryo_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'okurijyo_no_from' => 'nullable',
            'okurijyo_no_to' => 'nullable',

            'jyutyu_kbn_from' => 'nullable|jyutyu_exists',
            'jyutyu_kbn_to' => 'nullable|jyutyu_exists',
            'kaisyu_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',

            'kaisyu_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:kaisyu_dt_from',

            'kaisyu_kin_from' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'kaisyu_kin_to' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],

            'jyomuin_cd_from' => 'nullable|exists:m_jyomuin,jyomuin_cd',
            'jyomuin_cd_to' => 'nullable|exists:m_jyomuin,jyomuin_cd',
       
            'haitatu_tel_from' => 'nullable|max:255',
            'haitatu_tel_to' => 'nullable|max:255',

            'syubetu_cd_from' => 'syubetu_exists|nullable',
            'syubetu_cd_to' => 'syubetu_exists|nullable',

            'unso_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'unso_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:unso_dt_from',

            'jyotai' => 'nullable|max:255',
            'sitadori' => 'nullable|max:255',
            'syaban' => 'nullable|max:255',

            'denpyo_sofu_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'denpyo_sofu_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:denpyo_sofu_dt_from',

            'nipou_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'nipou_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:nipou_dt_from',

            'hed_syuka_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'hed_syuka_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:hed_syuka_dt_from',

            'hed_haitatu_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'hed_haitatu_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:hed_haitatu_dt_from',

            'nipou_no_from' => ['regex:/^-?(0|[1-9]\d{0,9})(?<!-0)$/', 'nullable'],
            'nipou_no_to' => ['regex:/^-?(0|[1-9]\d{0,9})(?<!-0)$/', 'nullable'],

            'uriage_den_no_from' => ['regex:/^-?(0|[1-9]\d{0,10})(?<!-0)$/', 'nullable'],
            'uriage_den_no_to' => ['regex:/^-?(0|[1-9]\d{0,10})(?<!-0)$/', 'nullable'],

            'yosya_kin_tax_from' => ['regex:/^-?(0|[1-9]\d{0,9})(?<!-0)$/', 'nullable'],
            'yosya_kin_tax_to' => ['regex:/^-?(0|[1-9]\d{0,9})(?<!-0)$/', 'nullable'],

            'jikoku_from' => 'date_format:H:i:s|nullable',
            'jikoku_to' => 'date_format:H:i:s,Y-m-d|nullable', 

            'add_tanto_cd_from' => 'nullable|exists:m_jyomuin,jyomuin_cd',
            'add_tanto_cd_to' => 'nullable|exists:m_jyomuin,jyomuin_cd',

            'denpyo_send_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'denpyo_send_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:denpyo_send_dt_from',
        ];

        if($this->route()->getName() == 'uriage.uriage_entry.validate_form_search_uriage') {
            $rules['hed_unso_dt_from'] = 'required|date_format:Y/m/d,Y-m-d';
            $rules['hed_unso_dt_to'] = 'required|date_format:Y/m/d,Y-m-d|after_or_equal:hed_unso_dt_from';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'hed_bumon_cd' => '部門',
            'hed_jyomuin_cd' => '入力担当',
            'hed_unso_dt_from' => '運送日',
            'hed_unso_dt_to' => '運送日',
            'hed_add_tanto_cd' => '入力担当',
            'hed_jyutyu_kbn' => '受注区分',

            'bumon_cd_from' => '部門',
            'bumon_cd_to' => '部門',

            'hatuti_cd_from' =>  '発地',
            'hatuti_cd_to' => '発地',

            // 'genkin_cd' => 'genkin_exist|nullable',

            'ninusi_cd_from' => '荷主',
            'ninusi_cd_to' => '荷主',

            'syuka_dt_from' => '集荷日',
            'syuka_dt_to' => '集荷日',

            'haitatu_dt_from' => '配達日',
            'haitatu_dt_to' => '配達日',

            'hachaku_cd_from' => '着地',
            'hachaku_cd_to' => '着地',
            'hinmei_cd_from' => '品名',
            'hinmei_cd_to'=> '品名',

            'su_from' => '数量',
            'su_to' => '数量',

            'tani' => '単位',

            'jyotai' => '状態',
            'sitadori' => '下取',
            'gyosya_cd_from' => '業者',
            'gyosya_cd_to' => '業者',

            'tyuki_from' => 'nullable',
            'tyuki_to' => 'nullable',
            'tanka_kbn_from' => '単価区分',
            'tanka_kbn_to' => '単価区分',

            'seikyu_tanka_from' => '請求単価',
            'seikyu_tanka_to' => '請求単価',

            'unchin_kin_from' => '基本運賃',
            'unchin_kin_to' => '基本運賃',

            'tyukei_kin_from' => '中継料',
            'tyukei_kin_to' => '中継料',

            'tukoryo_kin_from' => '通行料等',
            'tukoryo_kin_to' => '通行料等',

            'syuka_kin_from' => '集荷料',
            'syuka_kin_to' => '集荷料',

            'tesuryo_kin_from' => '手数料',
            'tesuryo_kin_to' => '手数料',

            'syaryo_kin_from' => '車両金額',
            'syaryo_kin_to' => '車両金額',

            'unten_kin_from' => '運転者金額',
            'unten_kin_to' => '運転者金額',

            // 'unchin_mikakutei_kbn' => 

            'yousya_cd_from' => '庸車先',
            'yousya_cd_to' => '庸車先',

            'yosya_tyukei_kin_from' => '庸車料',
            'yosya_tyukei_kin_to' => '庸車料',

            'yosya_tukoryo_kin_from' => '庸車通行料等',
            'yosya_tukoryo_kin_to' => '庸車通行料等',

            'okurijyo_no_from' => '送り状番号',
            'okurijyo_no_to' => '送り状番号',

            'jyutyu_kbn_from' => '受注区分',
            'jyutyu_kbn_to' => '受注区分',

            'kaisyu_dt_from' => '回収日',
            'kaisyu_dt_to' => '回収日',

            'kaisyu_kin_from' => '回収金額',
            'kaisyu_kin_to' => '回収金額',

            'jyomuin_cd_from' => '入力担当CD ',
            'jyomuin_cd_to' => '入力担当CD ',
       
            'haitatu_tel_from' => '配達TEL',
            'haitatu_tel_to' => '配達TEL',

            'syubetu_cd_from' => '種別',
            'syubetu_cd_to' => '種別',

            'denpyo_sofu_dt_from' => '伝票送付日',
            'denpyo_sofu_dt_to' => '伝票送付日',
            'nipou_dt_from' => '日報日',
            'nipou_dt_to' => '日報日',

            'hed_haitatu_dt_from' => '配達日',
            'hed_haitatu_dt_to' => '配達日',

            'hed_syuka_dt_from' => '集荷日',
            'hed_syuka_dt_to' => '集荷日',

            'nipou_no_from' => '日報NO',
            'nipou_no_to' => '日報NO',

            'uriage_den_no_from' => '売上番号',
            'uriage_den_no_to' => '売上番号',

            'yosya_kin_tax_from' => '消費税',
            'yosya_kin_tax_to' => '消費税',

            'jikoku_from' => '配達時刻',
            'jikoku_to' => '配達時刻',      
            'add_tanto_cd_from' => '入力担当CD',
            'add_tanto_cd_to' => '入力担当CD'
        ];
    }   

    public function messages(): array
    {
        return [
            'hed_bumon_cd.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'hed_jyomuin_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'hed_add_tanto_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'hed_jyutyu_kbn.jyutyu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=jyutyu']),

            'bumon_cd_from.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'bumon_cd_to.exists' => trans('messages.E0005', ['table' => '部門マスタ']),

            'hatuti_cd_from.hatuti_exists' => trans('messages.E0005', ['table' => '発地マスタ']),
            'hatuti_cd_to.hatuti_exists' => trans('messages.E0005', ['table' => '発地マスタ']),

            'ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),

            'jikoku_from.date_format' => trans('messages.E0007', ['attribute' => '時刻']),
            'jikoku_to.date_format' => trans('messages.E0007', ['attribute' => '時刻']),
            '*.date_format' => trans('messages.E0007', ['attribute' => '日付']),

            'hachaku_cd_from.exists' => trans('messages.E0005', ['table' => '発着地マスタ']),
            'hachaku_cd_to.exists' => trans('messages.E0005', ['table' => '発着地マスタ']),

            'hinmei_cd_from.hinmei_exists' => trans('messages.E0005', ['table' => '品名マスタ']),
            'hinmei_cd_to.hinmei_exists' => trans('messages.E0005', ['table' => '品名マスタ']),

            // 'syubetu_cd.syubetu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=syubetu']),
            'su_from.regex' => trans('messages.E0020', ['attribute' => '数量']),
            'su_to.regex' => trans('messages.E0020', ['attribute' => '数量']),

            'tani.tani_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tani']),

            'gyosya_cd_from.gyosya_exists' => trans('messages.E0005', ['table' => '名称マスタ']),
            'gyosya_cd_to.gyosya_exists' => trans('messages.E0005', ['table' => '名称マスタ']),

            'tanka_kbn_from.tanka_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tanka']),
            'tanka_kbn_to.tanka_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tanka']),

            'seikyu_tanka_from.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 2]),
            'seikyu_tanka_to.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 2]),

            'unchin_kin_from' => trans('messages.E0020', ['attribute' => '基本運賃']),
            'unchin_kin_to' => trans('messages.E0020', ['attribute' => '基本運賃']),

            'tyukei_kin_from.regex' => trans('messages.E0020', ['attribute' => '中継料']),
            'tyukei_kin_to.regex' => trans('messages.E0020', ['attribute' => '中継料']),

            'tukoryo_kin_to.regex' => trans('messages.E0020', ['attribute' => '通行料等']),
            'tukoryo_kin_from.regex' => trans('messages.E0020', ['attribute' => '通行料等']),

            'syuka_kin_from.regex' => trans('messages.E0020', ['attribute' => '集荷料']),
            'syuka_kin_to.regex' => trans('messages.E0020', ['attribute' => '集荷料']),

            'tesuryo_kin_from.regex' => trans('messages.E0020', ['attribute' => '手数料']),
            'tesuryo_kin_to.regex' => trans('messages.E0020', ['attribute' => '手数料']),

            'syaryo_kin_from.regex' => trans('messages.E0020', ['attribute' => '車両金額']),
            'syaryo_kin_to.regex' => trans('messages.E0020', ['attribute' => '車両金額']),

            'unten_kin_from.regex' => trans('messages.E0020', ['attribute' => '運転者金額']),
            'unten_kin_to.regex' => trans('messages.E0020', ['attribute' => '運転者金額']),

            'yousya_cd_from.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'yousya_cd_to.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),

            'yosya_tyukei_kin_from.regex' => trans('messages.E0020', ['attribute' => '庸車料']),
            'yosya_tyukei_kin_to.regex' => trans('messages.E0020', ['attribute' => '庸車料']),

            'yosya_tukoryo_kin_from.regex' => trans('messages.E0020', ['attribute' => '庸車通行料等']),
            'yosya_tukoryo_kin_to.regex' => trans('messages.E0020', ['attribute' => '庸車通行料等']),

            'jyutyu_kbn_from.jyutyu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=jyutyu']),
            'jyutyu_kbn_to.jyutyu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=jyutyu']),

            'kaisyu_kin_from.regex' => trans('messages.E0008', ['int' => 7]),
            'kaisyu_kin_to.regex' => trans('messages.E0008', ['int' => 7]),

            'jyomuin_cd_from.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'jyomuin_cd_to.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),

            '*.syubetu_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=syubetu']),

            'nipou_no_from.regex' => trans('messages.E0008', ['int' => 10]),
            'nipou_no_to.regex' => trans('messages.E0008', ['int' => 10]),

            'uriage_den_no_from.regex' => trans('messages.E0008', ['int' => 11]),
            'uriage_den_no_to.regex' => trans('messages.E0008', ['int' => 11]),

            'yosya_kin_tax_from.regex' => trans('messages.E0008', ['int' => 10]),
            'yosya_kin_tax_to.regex' => trans('messages.E0008', ['int' => 10]),

            'add_tanto_cd_from.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'add_tanto_cd_to.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),

            '*.after_or_equal' => trans('messages.E0006', ['attrFrom' => '日From', 'attrTo' => '日To']),
        ];
    }

    public function withValidator($validator)
    {

        $validator->addExtension('hatuti_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = \DB::table('m_hachaku')
                    ->select('*', \DB::raw('hachaku_cd as hatuti_cd'), \DB::raw('hachaku_nm as hatuti_nm'))
                    ->where('hachaku_cd', $value)
                    ->exists();
            return $exists;
        });


        $validator->addExtension('genkin_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_GENKIN'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('hinmei_exists', function($attribute, $value, $parameters, $validator) {
            if (!preg_match('/^[0-9]+$/', $value)) {
                return false;
            }
            $exists = DB::table('m_hinmei')
                ->join('m_hinmoku', 'm_hinmei.hinmoku_cd', 'm_hinmoku.hinmoku_cd')
                ->where('m_hinmei.hinmei_cd', $value)
                ->exists();
            return $exists;
        });

        $validator->addExtension('tani_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('gyosya_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_GYOSYA'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('tanka_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANKA'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('jyutyu_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_JYUTYU'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('syubetu_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_SYUBETU'))
                ->exists();
            return $exists;
        });
    }
}
