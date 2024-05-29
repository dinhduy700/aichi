<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $rules = [
            'user_cd' => 'required|regex:/^[a-zA-Z0-9]+$/|max:255',
            'passwd' => 'required|max:255',
            'group' => 'max:2',
            'biko' => 'max:255',
            'kyumin_flg' => 'max:1',
        ];

        if ($this->route('userCd') === null) {
            $rules['user_cd'] .= '|unique:m_user,user_cd';
        }
        return $rules;
    }

    public function attributes(): array
    {
        $attributes = trans('attributes.m_user');
        return $attributes;
    }

    public function messages(): array
    {
        return [
            'user_cd.regex' => trans('messages.E0018'),
        ];
    }
}
