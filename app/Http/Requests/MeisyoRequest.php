<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class MeisyoRequest extends FormRequest
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
            'meisyo_kbn' => 'required|max:255',
            'meisyo_cd' => 'required|max:255',
            'kana' => 'max:255',
            'meisyo_nm' => 'max:255',
            'sekisai_kbn' => 'max:1',
            'jyuryo_kansan' => 'decimal_ex:7,3',
        ];

        if(!$this->route('meisyoCd') && !$this->route('meisyoKbn')) {
            $rules['meisyo_cd'] .= '|meisyo_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_meisyo');
    }

    public function messages(): array
    {
        return [
            'meisyo_kbn.required' => trans('messages.E0001'),
            'meisyo_cd.meisyo_exists' => trans('messages.E0003')
        ];
    }

    public function withValidator($validator)
    {

        $validator->addExtension('meisyo_exists', function ($attribute, $value, $parameters, $validator) {
            $meisyoExists = DB::table('m_meisyo')
                ->where('meisyo_cd', $value)
                ->where('meisyo_kbn', request()->input('meisyo_kbn'))
                ->exists();

            return !$meisyoExists;
        });
    }
}
