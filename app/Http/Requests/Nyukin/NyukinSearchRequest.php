<?php

namespace App\Http\Requests\Nyukin;

use Illuminate\Foundation\Http\FormRequest;

class NyukinSearchRequest extends FormRequest
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
        return [
            'ninusi_cd' => 'nullable|exists:m_ninusi,ninusi_cd',
            'ninusi_nm' => 'nullable|exists:m_ninusi,ninusi_ryaku_nm',
            'hed_nyukin_dt_from' => 'nullable|date_format:Y/m/d',
            'hed_nyukin_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable|after_or_equal:hed_nyukin_dt_from|bail'
        ];
    }

    public function messages(): array
    {
        
        return [
            'ninusi_cd.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_nm.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            '*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            '*.after_or_equal' => trans('messages.E0006', ['attrFrom' => '日From', 'attrTo' => '日To']), 
        ];
    }
}
