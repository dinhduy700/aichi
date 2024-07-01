<?php

namespace App\Http\Requests\Nyusyuko\Nyuryoku;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class NyusyukoInputsRequest extends FormRequest
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
            'su',
            'situryo',
            'irisu',
            'case_su',
            'hasu',
            'jyuryo'
        ];

        $nyusyukoMeisai = $this->nyusyuko_meisai;
        if(!empty($nyusyukoMeisai)) {
            foreach ($nyusyukoMeisai as &$meisai) {
                foreach ($fields as $field) {
                    $value = !empty($meisai[$field]) ? $meisai[$field] : null;
                    if (!is_null($value)) {
                        $cleanedValue = floatval(str_replace(',', '', $value));
                        $meisai[$field] = $cleanedValue;
                    }
                }
            }

            // Merge vào request
            $this->merge(['nyusyuko_meisai' => $nyusyukoMeisai]);
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
            'nyusyuko_head.ninusi_cd' => 'required|ninusi_exists',
            'nyusyuko_head.hachaku_cd' => 'nullable|hachaku_exists',
            'nyusyuko_head.hatuti_cd' => 'nullable|hatuti_exists',
            'nyusyuko_head.denpyo_dt' => 'nullable|date_format:Y/m/d,Y-m-d',
            'nyusyuko_head.kisan_dt'  => 'nullable|date_format:Y/m/d,Y-m-d',
            'nyusyuko_head.nouhin_dt' => 'nullable|date_format:Y/m/d,Y-m-d',
            'nyusyuko_head.nieki_futan_kbn' => 'nullable|in:1,2',
            'nyusyuko_head.todokesaki_nm' => 'max:255|nullable',
            'nyusyuko_head.haitatu_jyusyo1' => 'max:255|nullable',
            'nyusyuko_head.haitatu_jyusyo2' => 'max:255|nullable',
            'nyusyuko_head.haitatu_atena' => 'max:255|nullable',
            'nyusyuko_head.haitatu_tel' => 'max:255|nullable',
            'nyusyuko_head.hatuti_nm' => 'max:255|nullable',
            'nyusyuko_head.hatuti_jyusyo1' => 'max:255|nullable',
            'nyusyuko_head.hatuti_jyusyo2' => 'max:255|nullable',
            'nyusyuko_head.hatuti_tel' => 'max:255|nullable',
            'uriage.souryo_kbn' => 'nullable|in:0,1',
            'uriage.syaban' => 'nullable|max:255',
            'uriage.jyomuin_cd' => 'nullable|exists:m_jyomuin,jyomuin_cd',
            'uriage.yousya_cd' => 'nullable|exists:m_yousya,yousya_cd',
            'uriage.biko' => 'nullable|max:255',
            
            'uriage.unchin_mikakutei_kbn' => 'unchin_kakutei_exists|nullable',
            'uriage.unchin_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'uriage.tyukei_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'uriage.tukoryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'uriage.tesuryo_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'uriage.nieki_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'uriage.syuka_kin' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'uriage.menzei_kbn' => 'nullable|in:0,1',

            'nyusyuko_meisai.*.hinmei_cd' => 'required|hinmei_exists',
            'nyusyuko_meisai.*.hinmei_nm' => 'nullable|exists:m_soko_hinmei,hinmei_nm',
            'nyusyuko_meisai.*.nyuko_dt' => 'nullable|date_format:Y/m/d,Y-m-d',
            'nyusyuko_meisai.*.situryo' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'nyusyuko_meisai.*.irisu' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'nyusyuko_meisai.*.hasu' => ['regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/', 'nullable'],
            'nyusyuko_meisai.*.su' => ['required', 'regex:/^-?(0|[1-9]\d{0,6})(?<!-0)$/'],
            'nyusyuko_meisai.*.tani_cd' => 'tani_exists|nullable',
            'nyusyuko_meisai.*.tani_nm' => 'tani_exists|nullable',
            'nyusyuko_meisai.*.jyuryo' => 'max:255|nullable',
            'nyusyuko_meisai.*.soko_cd' => 'required|exists:m_soko,soko_cd',
            'nyusyuko_meisai.*.location' => 'max:255|nullable',
            'nyusyuko_meisai.*.biko' => 'max:255|nullable'
        ];
        if($this->nyusyuko_head['nyusyuko_kbn'] == 5) {
            $rules['nyusyuko_meisai.*.soko_cd_to'] = 'required|exists:m_soko,soko_cd';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nyusyuko_head.ninusi_cd.ninusi_exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'nyusyuko_head.hachaku_cd.hachaku_exists' => trans('messages.E0005', ['table' => '発着地マスタ']),
            'nyusyuko_head.hatuti_cd.hatuti_exists' => trans('messages.E0005', ['table' => '発地マスタ']),
            'nyusyuko_head.nieki_futan_kbn.in' => '1：有償、2；無償',
            '*.*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            'uriage.jyomuin_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'uriage.yousya_cd.exists' => trans('messages.E0005', ['table' => '運転者マスタ']),
            'uriage.unchin_mikakutei_kbn.unchin_kakutei_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=unchinkakutei']),
            'uriage.unchin_kin.regex' => trans('messages.E0020', ['attribute' => '基本運賃']),
            'uriage.tyukei_kin.regex' => trans('messages.E0020', ['attribute' => '中継料']),
            'uriage.tukoryo_kin.regex' => trans('messages.E0020', ['attribute' => '通行料等']),
            'uriage.tesuryo_kin.regex' => trans('messages.E0020', ['attribute' => '手数料']),
            'uriage.nieki_kin.regex' => trans('messages.E0020', ['attribute' => '荷役料']),
            'uriage.syuka_kin.regex' => trans('messages.E0020', ['attribute' => '集荷料']),
            'uriage.souryo_kbn' => '0: 課税, 1: 免税',

            'nyusyuko_meisai.*.hinmei_cd.hinmei_exists' => trans('messages.E0005', ['table' => '品名マスタ']),
            'nyusyuko_meisai.*.hinmei_nm.exists' => trans('messages.E0005', ['table' => '品名マスタ']),
            'nyusyuko_meisai.*.*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            'nyusyuko_meisai.*.situryo.regex' => trans('messages.E0008'),    
            'nyusyuko_meisai.*.irisu.regex' => trans('messages.E0008', ['int' => 7]),
            'nyusyuko_meisai.*.hasu.regex' => trans('messages.E0008', ['int' => 7]),
            'nyusyuko_meisai.*.su.regex' => trans('messages.E0008', ['int' => 7]),
            'nyusyuko_meisai.*.tani_cd.tani_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tani']),
            'nyusyuko_meisai.*.tani_nm.tani_exists' => trans('messages.E0005', ['table' => '名称マスタの名称区分=tani']),
            'nyusyuko_meisai.*.soko_cd.exists' => trans('messages.E0005', ['table' => '倉庫マスタ']),
            'nyusyuko_meisai.*.soko_cd_to.exists' => trans('messages.E0005', ['table' => '倉庫マスタ']),
        ];
    }

    public function attributes(): array
    {
        return [
            'nyusyuko_head.ninusi_cd' => '荷主',
            'nyusyuko_head.hachaku_cd' => '荷届先',
            'nyusyuko_head.hatuti_cd' => '荷送り人',
            'nyusyuko_head.haitatu_jyusyo1' => '住　所 ',
            'nyusyuko_head.haitatu_jyusyo2' => '住　所 ',
            'nyusyuko_head.hatuti_jyusyo1' => '住　所',
            'nyusyuko_head.hatuti_jyusyo2' => '住　所',
            'nyusyuko_head.haitatu_tel' => '配達TEL',
            'nyusyuko_head.hatuti_tel' => '発地TEL',
            'nyusyuko_head.denpyo_dt' => '伝票日付',
            'nyusyuko_head.kisan_dt' => '起算日',
            'nyusyuko_head.nouhin_dt' => '納品日',
            'nyusyuko_head.nieki_futan_kbn' => '荷役料負担',
            '*.biko' => '摘要',
            '*.souryo_kbn' => '送料区分',
            '*.syaban' => '車番',
            '*.menzei_kbn' => '免税区分',
            '*.situryo' => '質量',
            '*.irisu' => '入り数',
            '*.hasu' => 'ケース数',
            '*.su' => '数量',
            '*.jyuryo' => '重量／㎥',
            '*.soko_cd' => '倉庫CD',
            '*.location' => 'ロケーション',
            '*.biko' => '備考',
            'nyusyuko_meisai.*.soko_cd_to' => '移動先倉庫' 
        ];
    }

    public function withValidator($validator)
    {
        $validator->addExtension('ninusi_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_ninusi')
                ->where('ninusi_cd', $value)
                ->exists();
            return $exists;
        });

        $validator->addExtension('hachaku_exists', function ($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_hachaku')
                ->where('hachaku_cd', $value)
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

        $validator->addExtension('unchin_kakutei_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_UNCHINKAKUTEI'))
                ->exists();
            return $exists;
        });

        $validator->addExtension('hinmei_exists', function($attribute, $value, $parameters, $validator) {
            $ninusiCd = request()->input('nyusyuko_head.ninusi_cd');
            $exists = DB::table('m_soko_hinmei')->where('hinmei_cd', $value)->where('ninusi_cd', $ninusiCd)->exists();
            return $exists;
        });

        $validator->addExtension('tani_exists', function($attribute, $value, $parameters, $validator) {
            $exists = DB::table('m_meisyo');
            $text = $attribute;
            $parts = explode('.', $text);
            $lastElement = end($parts);
            if($lastElement == 'tani_cd') {
                $exists = $exists->where('meisyo_cd', $value);
            } else {
                $exists = $exists->where('meisyo_nm', $value);
            }
            $exists = $exists->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                ->exists();
            return $exists;
        });
    }
}
