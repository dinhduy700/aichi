<?php

namespace App\Http\Requests\Tanaorosi;

use Illuminate\Foundation\Http\FormRequest;

class TanaorosiRequest extends FormRequest
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
        switch ($this->route()->getName()) {
            case 'tanaorosi.exp.filterValidate':
                return [
                    'exp.bumon_cd_from'     => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to'       => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.ninusi_cd_from'    => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to'      => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.soko_cd_from'      => 'nullable|exists:m_soko,soko_cd',
                    'exp.soko_cd_to'        => 'nullable|exists:m_soko,soko_cd',
                    'exp.hinmei_cd_from'    => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                    'exp.hinmei_cd_to'      => 'nullable|exists:m_soko_hinmei,hinmei_cd',
                    'exp.location_from'     => 'nullable|max:255',
                    'exp.location_to'       => 'nullable|max:255',
                ];
        }

        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'exp.bumon_cd_from'     => '部門コードFrom',
            'exp.bumon_cd_to'       => '部門コードTo',
            'exp.ninusi_cd_from'    => '荷主コードFrom',
            'exp.ninusi_cd_to'      => '荷主コードTo',
            'exp.soko_cd_from'      => '倉庫コードFrom',
            'exp.soko_cd_to'        => '倉庫コードTo',
            'exp.hinmei_cd_from'    => '商品コードFrom',
            'exp.hinmei_cd_to'      => '商品コードTo',
            'exp.location_from'     => 'ロケーションFrom',
            'exp.location_to'       => 'ロケーションTo',
        ];
    }


    public function messages()
    {
        return [
            'exp.bumon_cd_from.exists'      => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.bumon_cd_to.exists'        => trans('messages.E0005', ['table' => '部門マスタ']),
            'exp.ninusi_cd_from.exists'     => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.ninusi_cd_to.exists'       => trans('messages.E0005', ['table' => '荷主マスタ']),
            'exp.soko_cd_from.exists'       => trans('messages.E0005', ['table' => '倉庫マスタ']),
            'exp.soko_cd_to.exists'         => trans('messages.E0005', ['table' => '倉庫マスタ']),
            'exp.hinmei_cd_from.exists'     => trans('messages.E0005', ['table' => '品名マスタ']),
            'exp.hinmei_cd_to.exists'       => trans('messages.E0005', ['table' => '品名マスタ']),
        ];
    }
}
