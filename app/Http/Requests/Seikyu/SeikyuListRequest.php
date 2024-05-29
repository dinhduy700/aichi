<?php

namespace App\Http\Requests\Seikyu;

use Illuminate\Foundation\Http\FormRequest;

class SeikyuListRequest extends FormRequest
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
            case 'seikyu.list.data_list':
            case 'seikyu.mikakutei.data_list':
            case 'seikyu.kakutei.data_list':
                return [
                    'seikyu_sime_dt' => 'required|date_format:Y/m/d',
                ];
            case 'seikyu.list.filterValidate':
            case 'seikyu.mikakutei.filterValidate':
                return [
                    'exp.selected_items' => 'required',
                ];
            case 'seikyu.kakutei.set':
                return [
                    'selected' => 'required',
                ];
        }

        return [
            //
        ];
    }

    public function attributes()
    {
        switch ($this->route()->getName()) {
            case 'seikyu.list.data_list':
            case 'seikyu.mikakutei.data_list':
            case 'seikyu.kakutei.data_list':
                return [
                    'seikyu_sime_dt' => '締日',
                ];
            case 'seikyu.list.filterValidate':
            case 'seikyu.mikakutei.filterValidate':
                return [
                    'exp.selected_items' => 'selected',
                ];
        }

        return [
            //
        ];
    }

    public function messages()
    {
        return [
            'seikyu_sime_dt.date_format' => trans('messages.E0010'),
        ];
    }
}
