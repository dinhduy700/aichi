<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class HachakuRequest extends FormRequest
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
            'hachaku_cd'        => 'required|numeric_string|max:255',
            'kana'              => 'max:255',
            'hachaku_nm'        => 'max:255',
            'atena_ninusi_id'   => 'nullable|max:255|atena_ninusi_id_exists',
            'jyusyo1_nm'        => 'max:255',
            'jyusyo2_nm'        => 'max:255',
            'tel'               => 'max:255',
            'fax'               => 'max:255',
            'ninusi_id'         => 'nullable|max:255|ninusi_id_exists',
            'kyumin_flg'        => 'max:1',
        ];

        if($this->route('hachakuCd') == '') {
            $rules['hachaku_cd'] .= '|hachaku_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_hachaku');
    }

    public function messages(): array
    {
        return [
            'hachaku_cd.hachaku_exists'                 => trans('messages.E0003'),
            'atena_ninusi_id.atena_ninusi_id_exists'    => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_id.ninusi_id_exists'                => trans('messages.E0005', ['table' => '荷主マスタ']),

            'hachaku_cd.numeric_string'                 => trans('messages.E0017'),
        ];
    }

    public function withValidator($validator) 
    {
        $validator->addExtension('hachaku_exists', function ($attribute, $value, $parameters, $validator) {
            $hachakuExists = DB::table('m_hachaku')
                ->where('hachaku_cd', $value)
                ->exists();

            return !$hachakuExists;
        });

        $validator->addExtension('atena_ninusi_id_exists', function ($attribute, $value, $parameters, $validator) {
          $ninusiExists = DB::table('m_ninusi')
              ->where('ninusi_cd', $value)
              ->exists();

          return $ninusiExists;
        });

        $validator->addExtension('ninusi_id_exists', function ($attribute, $value, $parameters, $validator) {
          $ninusiExists = DB::table('m_ninusi')
              ->where('ninusi_cd', $value)
              ->exists();

          return $ninusiExists;
        });
    }
}