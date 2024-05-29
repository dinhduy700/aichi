<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class SokoHinmeiRequest extends FormRequest
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
            'ninusi_cd'         => 'bail|required|max:255',
            'hinmei_cd'         => 'required|regex:/^[a-zA-Z0-9]+$/|max:255',
            'kana'              => 'max:255',
            'hinmei_nm'         => 'max:255',
            'kikaku'            => 'max:255',
            'ondo'              => 'max:1',
            'zaiko_kbn'         => 'max:1',
            'case_cd'           => 'nullable|meisyo_exists|max:255',
            'irisu'             => 'decimal_ex:10,0',
            'hasu_kiriage'      => 'decimal_ex:10,0',
            'bara_tani'         => 'nullable|meisyo_exists|max:255',
            'bara_tani_juryo'   => 'decimal_ex:7,3',
            'uke_tanka'         => 'decimal_ex:7,3',
            'seikyu_hinmei_cd'  => 'nullable|bail|decimal_ex:10,0|hinmei_exists',
            'keisan_kb'         => 'max:1',
            'seikyu_keta'       => 'max:1',
            'seikyu_bunbo'      => 'decimal_ex:10,0',
            'nieki_nyuko_tanka' => 'decimal_ex:8,2',
            'nieki_syuko_tanka' => 'decimal_ex:8,2',
            'hokanryo_kin'      => 'decimal_ex:8,2',
            'bumon_cd'          => 'nullable|bumon_exists|max:255',
            'kyumin_flg'        => 'max:1',
        ];

        switch ($this->mode) {
          case 'create':
          case 'copy':
            $rules['ninusi_cd'] .= '|ninusi_exists';
            break;

          default:
            break;
        }


        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_soko_hinmei');
    }

    public function messages(): array
    {
        return [
            'ninusi_exists'         =>  trans('messages.E0005', ['table' => '荷主マスタ']),
            'meisyo_exists'         =>  trans('messages.E0005', ['table' => '名称マスタ']),
            'hinmei_exists'         =>  trans('messages.E0005', ['table' => '品名マスタ']),
            'bumon_exists'          =>  trans('messages.E0005', ['table' => '部門マスタ']),
            'soko_hinmei_exists'    =>  trans('messages.E0003'),

            'irisu.decimal_ex'              =>  trans('messages.E0008'),
            'hasu_kiriage.decimal_ex'       =>  trans('messages.E0008'),
            'seikyu_bunbo.decimal_ex'       =>  trans('messages.E0008'),
            'seikyu_hinmei_cd.decimal_ex'   =>  trans('messages.E0008'),

            'hinmei_cd.regex'               =>  trans('messages.E0018'),
        ];
    }

    public function withValidator($validator)
    {
        $validator->addExtension('ninusi_exists', function ($attribute, $value, $parameters, $validator) {
            $ninusiExists = DB::table('m_ninusi')
                ->where('ninusi_cd', $value)
                ->exists();

            return $ninusiExists;
        });

        $validator->addExtension('meisyo_exists', function ($attribute, $value, $parameters, $validator) {
            $meisyoExists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', configParam('MEISYO_KBN_TANI'))
                ->exists();
            return $meisyoExists;
        });

        $validator->addExtension('hinmei_exists', function ($attribute, $value, $parameters, $validator) {
            $hinmeiExists = DB::table('m_hinmei')
                ->where('hinmei_cd', $value)
                ->exists();
            return $hinmeiExists;
        });

        $validator->addExtension('bumon_exists', function ($attribute, $value, $parameters, $validator) {
            //07_AICHI_KOUSOKU_UNYU-338 倉庫商品マスタの部門コードにゼロを入力した場合、マスタ存在チェックしません。
            if ($attribute == 'bumon_cd' && $this->filled($attribute) && $value == 0) return true;

            $bumonExists = DB::table('m_bumon')
                ->where('bumon_cd', $value)
                ->exists();
            return $bumonExists;
        });

        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                switch ($this->mode) {
                    case 'create':
                    case 'copy':
                        if (!empty($this->ninusi_cd) && !request()->filled($this->hinmei_cd)) {
                            $sokoHinmeiExists = DB::table('m_soko_hinmei')
                                ->where('ninusi_cd', $this->ninusi_cd)
                                ->where('hinmei_cd', $this->hinmei_cd)
                                ->exists();
                            if ($sokoHinmeiExists) {
                                $validator->errors()->add('hinmei_cd', trans('messages.E0003'));
                                return true;
                            }
                        }
                        break;

                    default:
                        break;
                }
            });
        }

    }
}
