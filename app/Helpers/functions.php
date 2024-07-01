<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

if (!function_exists('configParam')) {
    function configParam($key, $default = null, $customFormat = 0)
    {
        $mixed = config("params.{$key}", $default);
        switch ($customFormat) {
            case 1: //Array {key} => {key}：{value}
                $mapped = \Illuminate\Support\Arr::map($mixed, function ($value, $key) {
                    return \App\Helpers\Formatter::kbnCd($key, $value);
                });
                return $mapped;
        }
        return $mixed;
    }
}

if (!function_exists('getPageTitle')) {
    function getPageTitle($routeName)
    {
        return __("app.titles.{$routeName}");
    }
}

if (!function_exists('getPageHeadTitle')) {
    function getPageHeadTitle($routeName)
    {
        return __('app.name') . " | " . getPageTitle($routeName);
    }
}

if (!function_exists('setKyuminFlagFilter')) {
    function setKyuminFlagFilter(&$qb, $value, $field = 'kyumin_flg')
    {
        if ($value == 0) {
            $qb->where($field, '<>', 1);
        } else {
            $qb->where($field, '=', $value);
        }
    }
}

if (!function_exists('makeEscapeStr')) {
    function makeEscapeStr($str, $likeEscapeChar = "\\") {
        $listEscape = [
            $likeEscapeChar,
            '%',
            '_',
            '\'',
            '"',
        ];

        return str_replace($listEscape, array_map(function ($s) use ($likeEscapeChar) {
            return $likeEscapeChar . $s;
        }, $listEscape), $str);
    }
}

if (!function_exists('removeWidthSizeGridConf')) {
    function removeWidthSizeGridConf($config) {
        return Arr::map($config, function ($value, $key) {
            if (isset($value['width'])) unset($value['width']);
            if (isset($value['class'])) {
                $value['class'] = str_replace(
                    ['size-S', 'size-M', 'size-L', 'size-2L', 'size-3L']
                    , '', $value['class']
                );
            }
            return $value;
        });
    }
}

if (!function_exists('numberFormat')) {
    function numberFormat($number, $decimals = 0, $thousandsSeparator  = ',') {
        $formatter = new \App\Helpers\Formatter;
        return $formatter->numberFormat($number, $decimals, $thousandsSeparator);
    }
}

if (!function_exists('cnvXlsToPdf')) {
    function cnvXlsToPdf($xlsPath, $pdfOutDir, $xlsFileNm = null) {
        switch (env('XLS_TO_PDF_METHOD', 0)) {
            case 1: //API
                $xlsFileNm = $xlsFileNm ?? basename($xlsPath);
                $sharedPath = env('XLS_TO_PDF_SHARE_DIR') . DIRECTORY_SEPARATOR;
                $shareXlsPath = $sharedPath . $xlsFileNm;
                \Illuminate\Support\Facades\File::copy($xlsPath, $shareXlsPath);
                $response = \Illuminate\Support\Facades\Http::timeout(600)->get(env('XLS_TO_PDF_API'), [
                    'file' => $xlsFileNm,
                ]);
                if ($response->failed()) {
                    \Illuminate\Support\Facades\Log::error('API CONVERT PDF FAIL!');
                } else {
                    $pdfFileNm = str_replace('.xlsx', '.pdf', $xlsFileNm);
                    $pdfPath = $sharedPath . $pdfFileNm;
                    if (file_exists($pdfPath)) {
                        \Illuminate\Support\Facades\File::move($pdfPath, $pdfOutDir . DIRECTORY_SEPARATOR . $pdfFileNm);
                    }
                }
                \Illuminate\Support\Facades\File::delete($shareXlsPath);
                return;
            case 2: //LIBRE_OFFICE
                $soffice = '"' . env('LIBRE_OFFICE_SOFFICE_PATH') . '"';
                $pdfOpts = 'pdf:draw_pdf_Export:{"Printing":{"type":"long","value":"2"}}';
                //$command = "$soffice --headless --convert-to $pdfOpts --outdir $pdfOutDir $xlsPath";//Windows
                $command = "export HOME=$pdfOutDir && $soffice --headless --convert-to $pdfOpts --outdir $pdfOutDir $xlsPath";//Linux
                exec($command, $output);
                return;
        }
    }
}

if (!function_exists('roundFromKbnTani')) {
    function roundFromKbnTani($number, $kbn, $tani)
    {
        //端数区分    0：切捨、1：切上、2：四捨五入
        //端数単位    0：１円、1：10円、2：100円、3：1000円
        $function = data_get([0 => 'down', 1 => 'up', 2 => 'round'], $kbn, 'round');
        $precision = data_get([0 => 0, 1 => -1, 2 => -2, 3 => -3], $tani, 0);

        return PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round::$function($number, $precision);
    }
}

if (!function_exists('responseSendForward')) {
    function responseSendForward($qb)
    {
        $total = DB::query()->fromSub($qb, 't')->count();
        return response()->json(
            $total > 0 ? ['sendForward' => 'yes'] : ['sendForward' => 'no', 'message' => trans('messages.no_data')]
        );
    }
}

if (!function_exists('getMaxCodeField')) {
    function getMaxCodeField($table, $field)
    {
        $results = DB::table($table)->selectRaw("COALESCE(MAX({$field}::NUMERIC), 0) AS aggregate")
            ->get('aggregate');
        if (! $results->isEmpty()) {
            return array_change_key_case((array) $results[0])['aggregate'];
        }
    }
}

if (!function_exists('applyOrderBy')) {
    function applyOrderBy($qb, $orders = null)
    {
        if(!empty($orders)) {
            foreach ($orders as $order) {
                $qb->orderBy(...$order);
            }
        }
        return $qb;
    }
}

if (!function_exists('filterMenu')) {
    function filterMenu($array, $parentID, $keySearch = null)
    {
        $convertArrDot = arrayDot($array);
        if ($keySearch == 'href') {
            return $convertArrDot[$parentID . ".href"] ?? null;
        } elseif ($keySearch == 'label') {
            return $convertArrDot[$parentID . ".label"] ?? null;
        } else {
            return substr_count($parentID, ".sub.") + 1;
        }
    }
}

if (!function_exists('array_dot')) {
    function arrayDot($array, $prepend = ''): array
    {
        $results = [];

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $results = array_merge($results, arrayDot($value, $prepend . $key . '.'));
            } else {
                $results[$prepend . $key] = $value;
            }
        }

        return $results;
    }
}
