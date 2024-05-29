<?php

namespace App\Http\Requests\Seikyu;

use Illuminate\Foundation\Http\FormRequest;

class SeikyuRequest extends FormRequest
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
            case 'seikyu.seikyu_sho.exp.filterValidate':
                return [
                    'exp.hakkou_dt' => 'nullable|date_format:Y/m/d',
                    'seikyu_sime_dt' => 'required|date_format:Y/m/d',
                ];
        }

        return [
            //
        ];
    }

    public function attributes()
    {
        return [
            'exp.hakkou_dt' => '発行日付',
            'seikyu_sime_dt' => '締日選択',
        ];
    }

    public function messages()
    {
        return [
            'exp.*.date_format' => trans('messages.E0007'),
            'seikyu_sime_dt.date_format' => trans('messages.E0007'),
        ];
    }
}