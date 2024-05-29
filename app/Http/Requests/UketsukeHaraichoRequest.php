<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UketsukeHaraichoRequest extends FormRequest
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
        $rule = [
            'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
            'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
            'exp.ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
            'exp.ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',
            'exp.kisan_dt_from' => 'nullable|date_format:Y/m/d',
            'exp.kisan_dt_to' => 'nullable|date_format:Y/m/d|after_or_equal:exp.kisan_dt_from',
            'exp.hinmei_cd_from' => 'nullable|exists:m_hinmei,hinmei_cd',
            'exp.hinmei_cd_to' => 'nullable|exists:m_hinmei,hinmei_cd',
        ];
        return $rule;
    }

    public function attributes()
    {
        return [
            'exp.bumon_cd_from' => '部門コードFrom',
            'exp.bumon_cd_to' => '部門コードTo',
            'exp.ninusi_cd_from' => '荷主コードFrom',
            'exp.ninusi_cd_to' => '荷主コードTo',
            'exp.kisan_dt_from' => '日付From',
            'exp.kisan_dt_to' => '日付To',
            'exp.hinmei_cd_from' => '商品コードFrom',
            'exp.hinmei_cd_to' => '商品コードTo',
        ];
    }

    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            'exp.hinmei_cd_from.exists' => trans('messages.E0005', ['table' => '倉庫商品マスタ']),
            'exp.hinmei_cd_to.exists' => trans('messages.E0005', ['table' => '倉庫商品マスタ']),
            'exp.*.after_or_equal' => trans('messages.E0006', ['attrFrom' => '日付From', 'attrTo' => '日付To']),
        ];
    }
}
