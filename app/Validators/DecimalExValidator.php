<?php 
namespace App\Validators;
use Illuminate\Support\Facades\Validator;

class DecimalExValidator
{
    public static function extendValidator()
    {
        Validator::extend('decimal_ex', function ($attribute, $value, $parameters, $validator) {
            $int = $parameters[0] ?? 0;
            $dec = $parameters[1] ?? '';
            $parts = explode('.', $value);
            $integerPart = isset($parts[0]) ? $parts[0] : '0';
            if ($integerPart === '') {
                $integerPart = '0';
                request()->merge([$attribute => '0.' . (isset($parts[1]) ? $parts[1] : '')]);
            }
            if($dec == 0) {
                return preg_match('/^\d{0,'.$int.'}$/', $value);
            }
            return preg_match('/^\d{0,'.$int.'}(\.\d{1,'.$dec.'})?$/', $value);
        });

        Validator::replacer('decimal_ex', function ($message, $attribute, $rule, $parameters, $validator) {
            $int = $parameters[0];
            $dec = $parameters[1];
            return str_replace(
                [':attribute', ':int', ':dec'],
                [!empty($validator->customAttributes[$attribute]) ? $validator->customAttributes[$attribute] : $attribute, $int, $dec],
                !empty($validator->customMessages[$attribute.'.decimal_ex']) ? $validator->customMessages[$attribute.'.decimal_ex'] : trans('validation.decimal_ex')
            );
        });
    }
}