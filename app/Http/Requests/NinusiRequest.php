<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use App\Rules\OnlySingleByte;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class NinusiRequest extends FormRequest
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
        $kiXRule = ['nullable', 'integer', 'min:1', 'max:31'];
        $rules = [
            'ninusi_cd' => 'required|numeric_string|max:255',
            'kana' => 'max:255',
            'ninusi1_nm' => 'max:255',
            'ninusi2_nm' => 'max:255',
            'ninusi_ryaku_nm' => 'max:255',
            'bumon_cd' => 'nullable|bumon_exists|max:255',
            'yubin_no' => 'max:255',
            'jyusyo1_nm' => 'max:255',
            'jyusyo2_nm' => 'max:255',
            'tel' => 'max:255',
            'fax' => 'max:255',
            'seikyu_kbn' => 'max:255',
            'seikyu_cd' => 'nullable|ninusi_exists|max:255',
            'seikyu_mu_kbn' => 'max:1',
            'simebi1' => 'nullable|required_without:seikyu_cd|integer|min:1|max:31',
            'simebi2' => 'nullable|required_with:simebi3|integer|min:1|max:31|gte:simebi1',
            'simebi3' => array_merge(explode('|', 'bail|nullable|integer|min:1|max:31'),[
                Rule::when(!empty($this->simebi2), ['gte:simebi2'])
            ]),
            'mikakutei_seigyo_kbn' => 'max:1',
            'kin_hasu_kbn' => 'max:1',
            'kin_hasu_tani' => 'max:1',
            'zei_keisan_kbn' => 'max:1',
            'zei_hasu_kbn' => 'max:255',
            'zei_hasu_tani' => 'max:255',
            'urikake_saki_cd' => 'nullable|ninusi_exists|max:255',
            'nyukin_umu_kbn' => 'max:1',
            'kaisyu1_dd' => 'nullable|integer|min:1|max:9',
            'kaisyu2_dd' => 'nullable|integer|min:1|max:31',
            'comennt' => 'max:255',
            'seikyu_teigi_no' => 'max:255',
            'unchin_teigi_no' => 'max:255',
            'kensaku_kbn' => 'max:1',
            'unso_bi_kbn' => 'max:1',
            'nebiki_ritu' => 'max:255',
            'nebiki_hasu_kbn' => 'max:1',
            'nebiki_hasu_tani' => 'max:255',
            'mail' => ['nullable',new OnlySingleByte,'max:255'],
            'okurijyo_hako_kbn' => 'max:1',
            'biko' => 'max:255',
            'kyumin_flg' => 'max:1',

            'lot_kanri_kbn' => 'max:1',
            'kisei_kbn' => 'max:1',
            'ki1_from' => array_merge($kiXRule, [
                new RequiredIf($this->kisei_kbn >= 1),
            ]),
            'ki1_to' => array_merge($kiXRule, [
                new RequiredIf($this->kisei_kbn >= 1),
            ]),
            'ki2_from' => array_merge($kiXRule, [
                new RequiredIf($this->kisei_kbn >= 2),
            ]),
            'ki2_to' => array_merge($kiXRule, [
                new RequiredIf($this->kisei_kbn >= 2),
            ]),
            'ki3_from' => array_merge($kiXRule, [
                new RequiredIf($this->kisei_kbn >= 3),
            ]),
            'ki3_to' => array_merge($kiXRule, [
                new RequiredIf($this->kisei_kbn >= 3),
            ]),
            'sekisu_kbn' => 'max:1',
            'soko_hokan_hasu_kbn' => 'max:1',
            'soko_hokan_hasu_tani' => 'max:1',
            'hokanryo_meisyo' => 'max:255',
            'nieki_sansyutu_kbn' => 'max:1',
            'nieki_hokan_hasu_kbn' => 'max:1',
            'nieki_hokan_hasu_tani' => 'max:1',
            'nieki_nyuko_nm' => 'max:255',
            'nieki_syuko_nm' => 'max:255',
            'nieki_nieki_nm' => 'max:255',
            'soko_seikyu_cd' => 'nullable|ninusi_exists|max:255',
            'soko_bumon_cd' => 'nullable|bumon_exists|max:255',
            'nyuko_tanka' => $tankaRule = ['regex:/^-?(0|[1-9]\d{0,6})(\.\d{1,2})?(?<!-0)$/', 'nullable'],
            'syuko_tanka' => $tankaRule,
            'hokan_tanka' => $tankaRule,
            'lot1_nm' => 'max:255',
            'lot2_nm' => 'max:255',
            'lot3_nm' => 'max:255',
        ];

        if ($this->route('ninusiCd') === null) {
            $rules['ninusi_cd'] .= '|unique:m_ninusi,ninusi_cd';
        }
        return $rules;
    }

    public function messages(): array
    {
        $kiMsgs = [];
        for($i=1; $i<=3; $i++) {
            $kiMsgs["ki{$i}_from.min"] = $kiMsgs["ki{$i}_from.max"] =
            $kiMsgs["ki{$i}_to.min"] = $kiMsgs["ki{$i}_to.max"] = trans('messages.E0013');
        }
        return array_merge([
            'bumon_cd.bumon_exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'ninusi_exists' =>  trans('messages.E0005', ['table' => '荷主マスタ']),
            'integer' => trans('messages.E0013'),
            'min' => trans('messages.E0013'),
            'ninusi_cd.numeric_string' => trans('messages.E0017'),
            'kaisyu1_dd.min' => trans('messages.E0025', ['max' => 9]),
            'kaisyu1_dd.max' => trans('messages.E0025'),
            'kaisyu2_dd.max' => trans('messages.E0013'),
            'simebi1.max' => trans('messages.E0013'),
            'simebi1.required_without' => trans('messages.E0002'),
            'simebi2.max' => trans('messages.E0013'),
            'simebi3.max' => trans('messages.E0013'),
            'simebi2.gte' => trans('messages.E0007'),
            'simebi3.gte' => trans('messages.E0007'),
            'soko_bumon_cd.bumon_exists' => trans('messages.E0005', ['table' => '部門マスタ']) ,
            'nyuko_tanka.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 2]),
            'syuko_tanka.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 2]),
            'hokan_tanka.regex' => trans('messages.E0009', ['int' => 7, 'dec' => 2]),
        ], $kiMsgs);
    }

    public function attributes(): array
    {
        $attributes = trans('attributes.m_ninusi');
        return $attributes;
    }

    public function withValidator($validator)
    {
        $validator->addExtension('bumon_exists', function ($attribute, $value, $parameters, $validator) {
            $bumonExists = DB::table('m_bumon')
                ->where('bumon_cd', $value)
                ->exists();
            return $bumonExists;
        });

        $validator->addExtension('ninusi_exists', function ($attribute, $value, $parameters, $validator) {
            $ninusiExists = DB::table('m_ninusi')
                ->where('ninusi_cd', $value)
                ->exists();
            return $ninusiExists;
        });

        $validator->after(function () use ($validator) {
            if (!empty($this->kisei_kbn) && $this->filled('simebi1') && $this->filled('simebi2')) {
                $validator->errors()->add('kisei_kbn', trans('messages.E0028'));
            }
            if (!empty($this->kisei_kbn)) {
                $msg26 = trans('messages.E0026');
                $circle = array_fill(1, 31, 0);
                switch ($this->kisei_kbn) {
                    case 1:
                        if ($this->ki1_to != $this->simebi1) $validator->errors()->add('kisei_kbn', trans('messages.E0030'));
                        elseif ($this->ki1_from != $this->getCircle31($this->ki1_to, 'next')) $validator->errors()->add('ki1_from', $msg26);
                        break;
                    case 2:
                        if ($this->ki2_to != $this->simebi1) $validator->errors()->add('kisei_kbn', trans('messages.E0031'));
                        elseif ($this->ki1_from != $this->getCircle31($this->ki2_to, 'next')) $validator->errors()->add('ki1_from', $msg26);
                        elseif ($this->ki2_from != $this->getCircle31($this->ki1_to, 'next')) $validator->errors()->add('ki2_from', $msg26);
                        else {
                            $this->fillCircle31($circle, $this->ki1_from, $this->ki1_to);
                            $this->fillCircle31($circle, $this->ki2_from, $this->ki2_to);
                            if (max($circle)>1) {
                                $validator->errors()->add('ki1_from', trans('messages.E0027'));
                                $validator->errors()->add('ki2_from', '');
                            }
                            elseif (array_sum($circle)!=31) {
                                $validator->errors()->add('ki1_from', $msg26);
                                $validator->errors()->add('ki2_from', '');
                            }
                        }
                        break;
                    case 3:
                        if ($this->ki3_to != $this->simebi1) $validator->errors()->add('kisei_kbn', trans('messages.E0032'));
                        elseif ($this->ki1_from != $this->getCircle31($this->ki3_to, 'next')) $validator->errors()->add('ki1_from', $msg26);
                        elseif ($this->ki2_from != $this->getCircle31($this->ki1_to, 'next')) $validator->errors()->add('ki2_from', $msg26);
                        elseif ($this->ki3_from != $this->getCircle31($this->ki2_to, 'next')) $validator->errors()->add('ki3_from', $msg26);
                        else {
                            $this->fillCircle31($circle, $this->ki1_from, $this->ki1_to);
                            $this->fillCircle31($circle, $this->ki2_from, $this->ki2_to);
                            $this->fillCircle31($circle, $this->ki3_from, $this->ki3_to);
                            if (max($circle)>1) {
                                $validator->errors()->add('ki1_from', trans('messages.E0027'));
                                $validator->errors()->add('ki2_from', '');
                                $validator->errors()->add('ki3_from', '');
                            }
                            elseif (array_sum($circle)!=31) {
                                $validator->errors()->add('ki1_from', $msg26);
                                $validator->errors()->add('ki2_from', '');
                                $validator->errors()->add('ki3_from', '');
                            }
                        }
                        break;
                }
            }
        });
    }

    private function getCircle31($point, $type='next') {
        $point = $type == 'next'
            ? ($point+1)
            : ($type == 'prev' ? ($point-1) : $point);

        if ($point > 31) return $point%31;
        if ($point < 1) return 31 - abs($point);
        return $point;
    }
    private function fillCircle31(&$circle, $from, $to) {
        $i=$from;
        while ($i!=$to) {
            $circle[$i]+=1;
            $i=$this->getCircle31($i, 'next');
        }
        $circle[$i]+=1;
    }
}
