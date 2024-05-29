<?php

namespace App\Http\Requests\Nyusyuko\Nyuryoku;

use Illuminate\Foundation\Http\FormRequest;

class FormSearchNyusyukoHeadRequest extends FormRequest
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
            'hed_bumon_nm' => 'nullable|exists:m_bumon,bumon_nm',
            'hed_nyusyuko_kbn' => 'nullable|in:1,2,4,5',
            'denpyo_dt_from' => 'nullable|date_format:Y/m/d,Y-m-d',
            'denpyo_dt_to' => 'nullable|date_format:Y/m/d,Y-m-d|after_or_equal:denpyo_dt_from',
            'todokesaki_nm' => 'nullable',
            'nyusyuko_den_no_from' => 'nullable|integer',
            'nyusyuko_den_no_to' => 'nullable|integer',
            'hinmei_nm' => 'nullable',
            'ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
            'ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',
            'ninusi_nm_from' => 'nullable|exists:m_ninusi,ninusi_ryaku_nm',
            'ninusi_nm_to' => 'nullable|exists:m_ninusi,ninusi_ryaku_nm',
            'hachaku_cd_from' => 'nullable|exists:m_hachaku,hachaku_cd',
            'hachaku_cd_to' => 'nullable|exists:m_hachaku,hachaku_cd'
        ];
    }

    public function attributes(): array
    {
        return [
            'hed_bumon_cd' => '部門',
            'hed_bumon_nm' => '部門',
            'hed_nyusyuko_kbn' => '入出庫区分',
            'denpyo_dt_from' => '伝票日付',
            'denpyo_dt_to' => '伝票日付',
            'todokesaki_nm' => '荷届け先名',
            'nyusyuko_den_no_from' => '伝票NO',
            'nyusyuko_den_no_to' => '伝票NO',
            'hinmei_nm' => '商品名',
            'ninusi_cd_from' => '荷主',
            'ninusi_cd_to' => '荷主',
            'ninusi_nm_from' => '荷主',
            'ninusi_nm_to' => '荷主',
            'hachaku_cd_from' => '荷届け先',
            'hachaku_cd_to' => '荷届け先',
        ];
    }

    public function messages(): array
    {
        return [
            'hed_bumon_cd.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'hed_bumon_nm.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
            'hed_nyusyuko_kbn.in' => '１：入庫、2：出庫、4:棚卸、5:在庫移動',
            '*.date_format' => trans('messages.E0007', ['attribute' => '日付']),
            'denpyo_dt_to.after_or_equal' => trans('messages.E0006', ['attrFrom' => '日From', 'attrTo' => '日To']),
            'nyusyuko_den_no_from.integer' => trans('messages.E0008', ['int' => 11]),
            'nyusyuko_den_no_to.integer' => trans('messages.E0008', ['int' => 11]),

            'ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_nm_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
            'ninusi_nm_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),

            'hachaku_cd_from.exists' =>  trans('messages.E0005', ['table' => '発着地マスタ']),
            'hachaku_cd_to.exists' =>  trans('messages.E0005', ['table' => '発着地マスタ']),
        ];
    }
}
