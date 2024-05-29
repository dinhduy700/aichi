<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ZaikoShoukaiFormSearchRequest extends FormRequest
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
            'search_bumon_cd' => 'required|exists:m_bumon,bumon_cd',
            'search_bumon_nm' => 'exists:m_bumon,bumon_nm|nullable',
            'search_kisan_dt' => 'date_format:Y/m/d,Y-m-d|nullable',
            'search_ninusi_cd' => 'required|exists:m_ninusi,ninusi_cd',
            'search_ninusi_ryaku_nm' => 'exists:m_ninusi,ninusi_ryaku_nm|nullable',
            'search_soko_hinmei_cd_from' => 'exists:m_soko_hinmei,hinmei_cd|nullable',
            'search_soko_hinmei_cd_to' => 'exists:m_soko_hinmei,hinmei_cd|nullable',
            'search_soko_hinmei_nm' => 'exists:m_soko_hinmei,hinmei_nm|nullable',
            'search_soko_cd_from' => 'exists:m_soko,soko_cd|nullable',
            'search_soko_cd_to' => 'exists:m_soko,soko_cd|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'search_bumon_cd' => '部門マスタ',
            'search_bumon_nm' => '部門名',
            'search_kisan_dt' => '照会日付',
            'search_ninusi_cd' => '荷主コード',
            'search_ninusi_ryaku_nm' => '荷主名',
            'search_soko_hinmei_cd_from' => '商品コードFrom',
            'search_soko_hinmei_cd_to' => '商品コードTo',
            'search_soko_hinmei_nm' => '商品名',
            'search_soko_cd_from' => '倉庫コードFrom',
            'search_soko_cd_to' => '倉庫コードTo',
        ];
    }

    public function messages(): array
    {
        return [
            'search_bumon_cd.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'search_bumon_nm.exists' => trans('messages.E0005', ['table' => '部門名']),
            '*.date_format' => trans('messages.E0007', ['attribute' => '照会日付']),
            'search_ninusi_cd.exists' => trans('messages.E0005', ['table' => '荷主コード']),
            'search_ninusi_ryaku_nm.exists' => trans('messages.E0005', ['table' => '荷主名']),
            'search_soko_hinmei_cd_from.exists' => trans('messages.E0005', ['table' => '商品コードFrom']),
            'search_soko_hinmei_cd_to.exists' => trans('messages.E0005', ['table' => '商品コードTo']),
            'search_soko_hinmei_nm.exists' => trans('messages.E0005', ['table' => '商品名']),
            'search_soko_cd_from.exists' => trans('messages.E0005', ['table' => '倉庫コードFrom']),
            'search_soko_cd_to.exists' => trans('messages.E0005', ['table' => '倉庫コードTo']),
        ];
    }
}
