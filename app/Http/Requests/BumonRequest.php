<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class BumonRequest extends FormRequest
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
            'bumon_cd' => 'required|max:255|numeric_string',
            'kana'=> 'max:255',
            'bumon_nm' => 'max:255',
            'kyumin_flg'=> 'max:1',
        ];

        if(!$this->route('bumonCd') && $this->route('bumonCd') !== '0') {
            $rules['bumon_cd'] .= '|bail|bumon_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_bumon');
    }

    public function messages(): array
    {
        return [
            'bumon_cd.bumon_exists' => trans('messages.E0003'),
            'bumon_cd.numeric_string' => trans('messages.E0017')
        ];
    }

    public function withValidator($validator) 
    {
        $validator->addExtension('bumon_exists', function ($attribute, $value, $parameters, $validator) {
            $bumonCdExists = DB::table('m_bumon')
                ->where('bumon_cd', $value)
                ->exists();
            return !$bumonCdExists;
        });
    }
}
