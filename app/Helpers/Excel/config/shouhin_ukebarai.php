<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();

return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'BP', 'row' => 57],
                'size' => 10
            ],
            'height' => 57
        ],
        'summary' => [
            'total' => [
                ['col' => 'Y', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('zaiko_su');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'AC', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('zaiko_jyuryo');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'AK', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('in_su');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'AO', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('in_jyuryo');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'AW', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('out_su');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'BA', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('out_jyuryo');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'BI', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('zankou_su');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'BM', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('zankou_jyuryo');
                }, 'mergeCells' => ['w' => 4, 'h' => 4]],
                ['col' => 'B', 'row' => 3,'mergeCells' => ['w' => 17, 'h' => 2, 'style' => 'center'], 'value' => '【　合　計　】']
            ],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'BP', 'row' => 13],
            'mergeCells' => [
                ['col' => 'W', 'row' => 2, 'w' => 15, 'h' => 3],
                ['col' => 'BF', 'row' => 3, 'w' => 8, 'h' => 1],
                ['col' => 'BN', 'row' => 3, 'w' => 2, 'h' => 1],
                ['col' => 'E', 'row' => 5, 'w' => 3, 'h' => 1],
                ['col' => 'E', 'row' => 7, 'w' => 3, 'h' => 1],
                ['col' => 'H', 'row' => 7, 'w' => 8, 'h' => 1],
                ['col' => 'P', 'row' => 7, 'w' => 13, 'h' => 1],
                ['col' => 'B', 'row' => 10, 'w' => 12, 'h' => 2],
                ['col' => 'N', 'row' => 10, 'w' => 5, 'h' => 2],
                ['col' => 'B', 'row' => 12, 'w' => 17, 'h' => 2],
                ['col' => 'S', 'row' => 12, 'w' => 2, 'h' => 2],
                ['col' => 'U', 'row' => 10, 'w' => 12, 'h' => 2],
                ['col' => 'U', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'Y', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'AC', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'AG', 'row' => 10, 'w' => 12, 'h' => 2],
                ['col' => 'AG', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'AK', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'AO', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'AS', 'row' => 10, 'w' => 12, 'h' => 2],
                ['col' => 'AS', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'AW', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'BA', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'BE', 'row' => 10, 'w' => 12, 'h' => 2],
                ['col' => 'BE', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'BI', 'row' => 12, 'w' => 4, 'h' => 2],
                ['col' => 'BM', 'row' => 12, 'w' => 4, 'h' => 2],
            ],
            'others' => [
                ['col' => 'BF', 'row' => 3, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'BN', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO],
                ['col' => 'E', 'row' => 5, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->bumon_cd : ' ';
                }],
                ['col' => 'H', 'row' => 5, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->bumon_nm : ' ';
                }],
                ['col' => 'E', 'row' => 7, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->ninusi_cd : ' ';
                }],
                ['col' => 'H', 'row' => 7, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->ninusi1_nm : ' ';
                }],
                ['col' => 'P', 'row' => 8, 'value' => function() {
                    $format = new App\Helpers\Formatter();
                    $kisanDtFrom = data_get(request('exp'), 'kisan_dt_from');
                    $kisanDtTo = data_get(request('exp'), 'kisan_dt_to');
                    if ($kisanDtFrom && $kisanDtTo) {
                        $formatFrom = $format->dateJP($kisanDtFrom, $format::DT_SHORT_JP_NENGETUHI);
                        $formatTo = $format->dateJP($kisanDtTo, $format::DT_SHORT_JP_NENGETUHI);
                        return "{$formatFrom} から {$formatTo} まで";
                    } elseif ($kisanDtFrom) {
                        return $format->dateJP($kisanDtFrom, $format::DT_SHORT_JP_NENGETUHI);
                    } elseif ($kisanDtTo) {
                        return $format->dateJP($kisanDtTo, $format::DT_SHORT_JP_NENGETUHI);
                    } else {
                        return null;
                    }
                }],
                'showLot' => ['col' => 'B', 'row' => 12],
            ],
        ],
        'block' => [
            'start' => ['col' => 'B', 'row' => 14],
            'end' => ['col' => 'BP', 'row' => 17],
        ],
    ],
    'block' => [
        0 => [
            'B' => ['field' => 'hinmei_nm', 'mergeCells' => ['w' => 12, 'h' => 2]],
            'N' => ['field' => 'hinmei_cd', 'mergeCells' => ['w' => 5, 'h' => 2]],
            'S' => ['field' => 'case_nm', 'mergeCells' => ['w' => 2, 'h' => 4]],
            'U' => ['field' => 'zaiko_case_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'Y' => ['field' => 'zaiko_su', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'AC' => ['field' => 'zaiko_jyuryo', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'AG' => ['field' => 'in_case_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'AK' => ['field' => 'in_su', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'AO' => ['field' => 'in_jyuryo', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'AS' => ['field' => 'out_case_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'AW' => ['field' => 'out_su', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'BA' => ['field' => 'out_jyuryo', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'BE' => ['field' => 'zankou_case_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'BI' => ['field' => 'zankou_su', 'mergeCells' => ['w' => 4, 'h' => 4]],
            'BM' => ['field' => 'zankou_jyuryo', 'mergeCells' => ['w' => 4, 'h' => 4]],
        ],
        1 => [],
        2 => [
            'B' => ['field' => 'kikaku', 'mergeCells' => ['w' => 8, 'h' => 2]],
            'J' => ['field' => 'concat_lot', 'mergeCells' => ['w' => 9, 'h' => 2]],
            'U' => ['field' => 'zaiko_hasu', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'AG' => ['field' => 'in_hasu', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'AS' => ['field' => 'out_hasu', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'BE' => ['field' => 'zankou_hasu', 'mergeCells' => ['w' => 4, 'h' => 2]],
        ],
        3 => []
    ]
];