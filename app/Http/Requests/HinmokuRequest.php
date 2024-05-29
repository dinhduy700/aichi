<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class HinmokuRequest extends FormRequest
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
            'hinmoku_cd' => 'required|max:255|numeric_string',
            'kana'=> 'max:255',
            'hinmoku_nm' => 'max:255',
            'kyumin_flg'=> 'max:1',
        ];

        if(!$this->route('hinmokuCd') && $this->route('hinmokuCd') !== '0') {
            $rules['hinmoku_cd'] .= '|bail|hinmoku_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_hinmoku');
    }

    public function messages(): array
    {
        return [
            'hinmoku_cd.hinmoku_exists' => trans('messages.E0003'),
            'hinmoku_cd.numeric_string' => trans('messages.E0017')
        ];
    }

    public function withValidator($validator) 
    {
        $validator->addExtension('hinmoku_exists', function ($attribute, $value, $parameters, $validator) {
            $hinmokuCdExists = DB::table('m_hinmoku')
                ->where('hinmoku_cd', $value)
                ->exists();
            return !$hinmokuCdExists;
        });
    }
}
