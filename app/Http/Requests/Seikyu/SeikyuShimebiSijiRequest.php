<?php

namespace App\Http\Requests\Seikyu;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class SeikyuShimebiSijiRequest extends FormRequest
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
        switch ($this->action) {
            case 'search':
                $rules = [
                    'seikyu_sime_dt' => 'bail|nullable|date_format:Y/m/d',
                ];
                break;
            
            default:
                $rules = [
                    'seikyu_sime_dt' => 'bail|required|date_format:Y/m/d|seikyu_simebi_siji_exists',
                ];
                break;
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'seikyu_sime_dt' => '対象締日追加'
        ];
    }

    public function messages(): array
    {
        return [
            'seikyu_sime_dt.date_format'                => trans('messages.E0007'),
            'seikyu_sime_dt.seikyu_simebi_siji_exists'  => trans('messages.E0003'),
        ];
    }

    public function withValidator($validator) 
    {
        $validator->addExtension('seikyu_simebi_siji_exists', function ($attribute, $value, $parameters, $validator) {
            $qb = DB::table('t_seikyu_simebi_siji')
                ->where('seikyu_sime_dt', $value)
                ->exists();

            return !$qb;
        });
    }
}