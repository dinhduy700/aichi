<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UkebaraiShoukaiFormSearchRequest extends FormRequest
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
            'search_kisan_dt_from' => 'date_format:Y/m/d,Y-m-d|nullable',
            'search_kisan_dt_to' => 'date_format:Y/m/d,Y-m-d|nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'search_kisan_dt_from' => '日付範囲_From',
            'search_kisan_dt_to' => '日付範囲_To',
        ];
    }

    public function messages(): array
    {
        return [
            '*.date_format' => trans('messages.E0007', ['attribute' => '日付範囲']),
        ];
    }
}
