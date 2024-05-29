<?php


namespace App\Helpers;


use Illuminate\Support\Carbon;
use Illuminate\Support\Number;

class Formatter
{
    const ZEN_COLONS = '：';
    const DF_DATE = 'Y/m/d';
    const DF_DATETIME = 'Y/m/d H:i:s';
    const DT_SHORT_JP_YYMMDD_W = 'y/m/d(w)';
    const DT_SHORT_JP_NENGETUHI_YOUBI = 'y年m月d日(w)';
    const DT_SHORT_JP_NENGETUHI = 'y年m月d日';

    public static function kbnCd($key, $value, $glue = self::ZEN_COLONS)
    {
        return "{$key}{$glue}{$value}";
    }

    public static function datetime($value, $format = self::DF_DATETIME)
    {
        if (empty($value)) {
            return $value;
        }
        return Carbon::parse($value)->format($format);
    }

    public static function date($value)
    {
        return self::datetime($value, self::DF_DATE);
    }

    public static function dateJP($value, $format = self::DF_DATETIME)
    {
        if (empty($value)) {
            return $value;
        }
        $date = Carbon::parse($value);
        $dayOfWeekFormatter = \IntlDateFormatter::create(
            'ja_JP@calendar=japanese',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::FULL,
            null, //'Asia/Tokyo',
            \IntlDateFormatter::TRADITIONAL,
            'E'
        );
        switch ($format) {
            case self::DT_SHORT_JP_YYMMDD_W:
                return $date->format('y/m/d') . '（' . $dayOfWeekFormatter->format($date) . '）';
            case self::DT_SHORT_JP_NENGETUHI_YOUBI:
                return $date->format('y年m月d日') . '（' . $dayOfWeekFormatter->format($date) . '）';
            case self::DT_SHORT_JP_NENGETUHI:
                return $date->format('y年m月d日');
        }
        return $date->format($format);
    }

    public static function number($number, $decimals = null, $thousandsSeparator  = ',')
    {
        if ($decimals === null) {
            // https://github.com/symfony/polyfill-intl-icu/blob/1.x/NumberFormatter.php#L731
            // https://github.com/symfony/polyfill-intl-icu/blob/b435f800270efde857f3c9c4e40d2762b90be14f/NumberFormatter.php#L731
            preg_match('/.*\.(.*)/', (string) ($number + 0), $digits);
            if (isset($digits[1])) {
                $decimals = strlen($digits[1]);
            } else {
                $decimals = 0;
            }
        }
        return number_format($number, $decimals, '.', $thousandsSeparator);
    }

    function numberFormat($number, $decimals = 0, $thousandsSeparator  = ',') {
    	if(!is_numeric($number)) return $number;
        if($decimals == -1) {
            $arrNumber = explode('.', $number);
            return number_format($arrNumber[0], 0, '', $thousandsSeparator) . (empty($arrNumber[1]) ? '' : rtrim('.' . (rtrim($arrNumber[1], '0')) , '.')  );
        } else {
            $number = number_format($number, $decimals, '.', $thousandsSeparator);
        }
        return $number;
    }
}
