<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class BikoRequest extends FormRequest
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
            'biko_cd'        => 'required|numeric_string|max:255',
            'kana'           => 'max:255',
            'biko_nm'        => 'max:255',
            'syubetu_kbn'    => 'max:1',
            'kyumin_flg'     => 'max:1',
        ];

        if($this->route('bikoCd') == '') {
            $rules['biko_cd'] .= '|biko_exists';
        }

        return $rules;
    }

    public function attributes(): array
    {
        return trans('attributes.m_biko');
    }

    public function messages(): array
    {
        return [
            'biko_cd.biko_exists' => trans('messages.E0003'),

            'biko_cd.numeric_string'  => trans('messages.E0017'),
        ];
    }

    public function withValidator($validator) 
    {
        $validator->addExtension('biko_exists', function ($attribute, $value, $parameters, $validator) {
            $bikoExists = DB::table('m_biko')
                ->where('biko_cd', $value)
                ->exists();

            return !$bikoExists;
        });
    }
}