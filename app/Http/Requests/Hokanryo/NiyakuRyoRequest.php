<?php

namespace App\Http\Requests\Hokanryo;

use Illuminate\Foundation\Http\FormRequest;

class NiyakuRyoRequest extends FormRequest
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
            case 'hokanryo.niyakuryo.filterValidate':
                return [
                    'exp.bumon_cd_from' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.bumon_cd_to' => 'nullable|exists:m_bumon,bumon_cd',
                    'exp.seikyu_sime_yymm' => 'required|date_format:y/m',
                    'exp.seikyu_sime_dd' => 'required|digits:2',
                    'exp.hakko_dt' => 'required|date_format:Y/m/d',
                    'exp.ninusi_cd_from' => 'nullable|exists:m_ninusi,ninusi_cd',
                    'exp.ninusi_cd_to' => 'nullable|exists:m_ninusi,ninusi_cd',
                ];
        }

        return [
            //
        ];
    }

    public function withValidator($validator)
    {
        switch ($this->route()->getName()) {
            case 'hokanryo.niyakuryo.filterValidate':
                $validator->after(function () use ($validator) {
                    if ($this->filled('exp.seikyu_sime_yymm') && $this->filled('exp.seikyu_sime_dd')) {
                        $format = 'y/m/d';
                        $value = data_get($this->get('exp'), 'seikyu_sime_yymm')
                            . '/' . data_get($this->get('exp'), 'seikyu_sime_dd');
                        $date = \DateTime::createFromFormat('!' . $format, $value);
                        if (!($date && $date->format($format) == $value)) {
                            $validator->errors()->add('seikyu_sime_dd', trans('messages.E0007', ['attribute' => '請求締日']));
                        }
                    }
                });
                break;
        }
    }

    public function messages()
    {
        switch ($this->route()->getName()) {
            case 'hokanryo.niyakuryo.filterValidate':
                return [
                    'exp.bumon_cd_from.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
                    'exp.bumon_cd_to.exists' => trans('messages.E0005', ['table' => '部門マスタ']),
                    'exp.seikyu_sime_yymm.date_format' => trans('messages.E0007'),
                    'exp.seikyu_sime_dd.digits' => trans('messages.E0007'),
                    'exp.ninusi_cd_from.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
                    'exp.ninusi_cd_to.exists' => trans('messages.E0005', ['table' => '荷主マスタ']),
                    'exp.*.date_format' => trans('messages.E0007'),
                ];
        }

        return [];
    }

    public function attributes()
    {
        switch ($this->route()->getName()) {
            case 'hokanryo.niyakuryo.filterValidate':
                return [
                    'exp.bumon_cd_from' => '売上部門コード（開始）',
                    'exp.bumon_cd_to' => '売上部門コード（終了）',
                    'exp.seikyu_sime_yymm' => '年月',
                    'exp.seikyu_sime_dd' => '日',
                    'exp.hakko_dt' => '発行日',
                    'exp.ninusi_cd_from' => '請求先コード（開始）',
                    'exp.ninusi_cd_to' => '請求先コード（終了）',
                ];
        }

        return [];
    }
}
