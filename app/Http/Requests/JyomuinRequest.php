<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;
use App\Rules\OnlySingleByte;

class JyomuinRequest extends FormRequest
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
            'jyomuin_cd' => 'required|max:255|numeric_string',
            'kana' => 'max:255',
            'jyomuin_nm' => 'max:255',
            'bumon_cd' => 'max:255',
            'mail' => ['nullable',new OnlySingleByte,'max:255'],
            'mobile_tel' => 'max:255',
            'kyumin_flg'=> 'max:1',
        ];
        if (!$this->route('jyomuinCd') && $this->route('jyomuinCd') !== '0') {
            $rules['jyomuin_cd'] .= '|bail|jyomuin_cd_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_jyomuin');
    }

    public function messages(): array
    {
        return [
            'jyomuin_cd.jyomuin_cd_exists' => trans('messages.E0003'),
            'jyomuin_cd.numeric_string' => trans('messages.E0017')
        ];
    }

    public function withValidator($validator) 
    {
        $validator->addExtension('jyomuin_cd_exists', function ($attribute, $value, $parameters, $validator) {
            $jyomuinExists = DB::table('m_jyomuin')
                ->where('jyomuin_cd', $value)
                ->exists();

            return !$jyomuinExists;
        });
    }
}
