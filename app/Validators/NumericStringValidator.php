<?php 
namespace App\Validators;
use Illuminate\Support\Facades\Validator;

class NumericStringValidator
{
    public static function extendValidator()
    {
        Validator::extend('numeric_string', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^\d+$/', $value);
        });

        Validator::replacer('numeric_string', function ($message, $attribute, $rule, $parameters, $validator) {
            return str_replace(
                [':attribute'],
                [!empty($validator->customAttributes[$attribute]) ? $validator->customAttributes[$attribute] : $attribute],
                !empty($validator->customMessages[$attribute.'.numeric_string']) ? $validator->customMessages[$attribute.'.numeric_string'] : trans('validation.numeric_string')
            );
        });
    }
}