<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use DB;

class SokoRequest extends FormRequest
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
            'bumon_cd' => 'required|max:255|exists:m_bumon,bumon_cd',
            'soko_cd' => 'required|max:255|numeric_string',
            'kana' => 'max:255',
            'soko_nm' => 'max:255',
            'kyumin_flg' => 'max:1',
        ];

        if (!$this->route('sokoCd') && $this->route('sokoCd') !== '0' && !$this->route('bumonCd')) {
            $rules['soko_cd'] .= '|bail|soko_exists';
        }

        return $rules;
    }
    public function attributes(): array
    {
        return trans('attributes.m_soko');
    }

    public function messages(): array
    {
        return [
            'soko_cd.soko_exists' => trans('messages.E0003'),
            'soko_cd.numeric_string' => trans('messages.E0017'),
            'bumon_cd.exists' => trans('messages.E0005', ['table' => '部門マスタ'])
        ];
    }

    public function withValidator($validator)
    {
        $validator->addExtension('soko_exists', function ($attribute, $value, $parameters, $validator) {
            $sokoExists = DB::table('m_soko')
                ->where('soko_cd', $value)
                ->where('bumon_cd', request()->input('bumon_cd'))
                ->exists();

            return !$sokoExists;
        });
    }
}
