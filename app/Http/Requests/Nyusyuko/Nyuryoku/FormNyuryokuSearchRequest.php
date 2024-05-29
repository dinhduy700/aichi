<?php

namespace App\Http\Requests\Nyusyuko\Nyuryoku;

use Illuminate\Foundation\Http\FormRequest;

class FormNyuryokuSearchRequest extends FormRequest
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
            'hed_bumon_cd' => 'nullable|exists:m_bumon,bumon_cd',
            'hed_nyusyuko_kbn' => 'nullable|in:1,2,4,5'
        ];
    }

    public function messages(): array
    {
        return [
            'hed_bumon_cd.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'hed_nyusyuko_kbn.in' => '1：入庫 2；出庫',
        ];
    }
}
