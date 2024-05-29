<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
$configNyusyukoKbn = configParam('NYUSYUKO_KBN_SUPPORT');
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'BY', 'row' => 65],
                'size' => 23
            ],
            'height' => 65
        ],
        'summary' => [
            'total' => [
                ['col' => 'AN', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('in_su');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 2]],
                ['col' => 'AR', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('in_jyuryo');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 5, 'h' => 2]],
                ['col' => 'BB', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('out_su');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 2]],
                ['col' => 'BF', 'row' => 1, 'value' => function ($list) {
                    return $list->sum('out_jyuryo');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 5, 'h' => 2]],
                ['col' => 'BP', 'row' => 1, 'value' => function ($list) {
                    $last = !empty($list) ? $list->last() : null;
                    return !empty($last) ? $last->zaiko_su : 0;
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 4, 'h' => 2]],
                ['col' => 'BT', 'row' => 1, 'value' => function ($list) {
                    $last = !empty($list) ? $list->last() : null;
                    return !empty($last) ? $last->zaiko_jyuryo : 0;
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 5, 'h' => 2]],
                ['col' => 'W', 'row' => 1,'mergeCells' => ['w' => 12, 'h' => 2, 'style' => 'center'], 'value' => '【　合　計　】']
            ],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'BX', 'row' => 17],
            'mergeCells' => [
                ['col' => 'AH', 'row' => 1, 'w' => 14, 'h' => 3],
                ['col' => 'BO', 'row' => 5, 'w' => 6, 'h' => 1],
                ['col' => 'BU', 'row' => 5, 'w' => 3, 'h' => 1],
                ['col' => 'C', 'row' => 7, 'w' => 4, 'h' => 1],
                ['col' => 'L', 'row' => 8, 'w' => 13, 'h' => 1],
                ['col' => 'C', 'row' => 9, 'w' => 4, 'h' => 1],
                ['col' => 'B', 'row' => 11, 'w' => 2, 'h' => 1],
                ['col' => 'I', 'row' => 11, 'w' => 6, 'h' => 1],
                ['col' => 'B', 'row' => 12, 'w' => 3, 'h' => 2],
                ['col' => 'E', 'row' => 12, 'w' => 2, 'h' => 2],
                ['col' => 'G', 'row' => 12, 'w' => 3, 'h' => 2],
                ['col' => 'J', 'row' => 12, 'w' => 5, 'h' => 2],
                ['col' => 'O', 'row' => 12, 'w' => 3, 'h' => 2],
                ['col' => 'R', 'row' => 12, 'w' => 5, 'h' => 2],
                ['col' => 'W', 'row' => 12, 'w' => 12, 'h' => 2],
                ['col' => 'W', 'row' => 16, 'w' => 12, 'h' => 2],
                ['col' => 'AI', 'row' => 12, 'w' => 14, 'h' => 2],
                ['col' => 'AI', 'row' => 14, 'w' => 3, 'h' => 2],
                ['col' => 'AL', 'row' => 14, 'w' => 2, 'h' => 2],
                ['col' => 'AN', 'row' => 14, 'w' => 4, 'h' => 2],
                ['col' => 'AR', 'row' => 14, 'w' => 5, 'h' => 2],
                ['col' => 'AW', 'row' => 12, 'w' => 14, 'h' => 2],
                ['col' => 'AW', 'row' => 14, 'w' => 3, 'h' => 2],
                ['col' => 'AZ', 'row' => 14, 'w' => 2, 'h' => 2],
                ['col' => 'BB', 'row' => 14, 'w' => 4, 'h' => 2],
                ['col' => 'BF', 'row' => 14, 'w' => 5, 'h' => 2],
                ['col' => 'BK', 'row' => 12, 'w' => 14, 'h' => 2],
                ['col' => 'BK', 'row' => 14, 'w' => 3, 'h' => 2],
                ['col' => 'BN', 'row' => 14, 'w' => 2, 'h' => 2],
                ['col' => 'BP', 'row' => 14, 'w' => 4, 'h' => 2],
                ['col' => 'BT', 'row' => 14, 'w' => 5, 'h' => 2],
                ['col' => 'BK', 'row' => 16, 'w' => 3, 'h' => 2],
                ['col' => 'BN', 'row' => 16, 'w' => 2, 'h' => 2],
                ['col' => 'BP', 'row' => 16, 'w' => 4, 'h' => 2],
                ['col' => 'BT', 'row' => 16, 'w' => 5, 'h' => 2],
            ],
            'others' => [
                ['col' => 'BO', 'row' => 5, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'BU', 'row' => 5, 'constVal' => $exp::VAL_PAGE_NO],
                ['col' => 'C', 'row' => 7, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->bumon_cd : ' ';
                }],
                ['col' => 'G', 'row' => 7, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->bumon_nm : ' ';
                }],
                ['col' => 'L', 'row' => 8, 'value' => function() {
                    $format = new App\Helpers\Formatter();
                    $kisanDtFrom = data_get(request('exp'), 'kisan_dt_from');
                    $kisanDtTo = data_get(request('exp'), 'kisan_dt_to');
                    if (empty($kisanDtFrom) && !empty($kisanDtTo)) {
                        $kisanDtTo = $format->dateJP($kisanDtTo,$format::DT_SHORT_JP_NENGETUHI);
                        return "{$kisanDtTo}から{$kisanDtTo}まで";
                    } elseif (!empty($kisanDtFrom) && empty($kisanDtTo)) {
                        $kisanDtFrom = $format->dateJP($kisanDtFrom, $format::DT_SHORT_JP_NENGETUHI);
                        return "{$kisanDtFrom}から{$kisanDtFrom}まで";
                    } elseif (!empty($kisanDtFrom) && !empty($kisanDtTo)) {
                        $kisanDtFrom = $format->dateJP($kisanDtFrom, $format::DT_SHORT_JP_NENGETUHI);
                        $kisanDtTo = $format->dateJP($kisanDtTo, $format::DT_SHORT_JP_NENGETUHI);
                        return "{$kisanDtFrom}から{$kisanDtTo}まで";
                    } else {
                        return " ";
                    }
                }],
                ['col' => 'C', 'row' => 9, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->ninusi_cd : ' ';
                }],
                ['col' => 'G', 'row' => 9, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->ninusi1_nm : ' ';
                }],
                ['col' => 'B', 'row' => 11, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->hinmei_cd : ' ';
                }],
                ['col' => 'I', 'row' => 11, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->hinmei_nm : ' ';
                }],
                ['col' => 'BK', 'row' => 16, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->zaiko_case_su  : 0;
                }],
                ['col' => 'BN', 'row' => 16, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->zaiko_hasu : 0;
                }],
                ['col' => 'BP', 'row' => 16, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->zaiko_su : 0;
                }],
                ['col' => 'BT', 'row' => 16, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? $first->zaiko_jyuryo : 0;
                }],
                ['col' => 'W', 'row' => 11, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? 'ロット１ ' . $first->lot1 : ' ';
                }],
                ['col' => 'AC', 'row' => 11, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? 'ロット２ ' . $first->lot2 : ' ';
                }],
                ['col' => 'AJ', 'row' => 11, 'value' => function($list) {
                    $first = !empty($list) ? $list->first() : null;
                    return !empty($first) ? 'ロット３ ' . $first->lot3 : ' ';
                }]
            ],
        ],
        'block' => [
            'start' => ['col' => 'C', 'row' => 18],
            'end' => ['col' => 'BC', 'row' => 19],
        ],
    ],
    'block' => [
        [
            'B' => ['field' => 'hidzuke_dt', 'mergeCells' => ['w' => 3, 'h' => 2], 'type' => $exp::DATA_DATETIME],
            'E' => ['value' => function ($row) use ($configNyusyukoKbn) {
                if (!empty($configNyusyukoKbn[data_get($row, 'nyusyuko_kbn')])) {
                    return $configNyusyukoKbn[data_get($row, 'nyusyuko_kbn')];
                }
                return '';
            }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 2, 'h' => 2]],
            'G' => ['field' => 'no_printing1', 'mergeCells' => ['w' => 3, 'h' => 2]],
            'J' => ['field' => 'nyusyuko_den_no', 'mergeCells' => ['w' => 5, 'h' => 2]],
            'O' => ['field' => 'kisan_dt', 'mergeCells' => ['w' => 3, 'h' => 2], 'type' => $exp::DATA_DATETIME],
            'R' => ['field' => 'soko_cd', 'mergeCells' => ['w' => 5, 'h' => 1]],
            'W' => ['field' => 'todokesaki_nm', 'mergeCells' => ['w' => 12, 'h' => 2]],
            'AI' => ['field' => 'in_case_su', 'mergeCells' => ['w' => 3, 'h' => 2]],
            'AL' => ['field' => 'in_hasu', 'mergeCells' => ['w' => 2, 'h' => 2]],
            'AN' => ['field' => 'in_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'AR' => ['field' => 'in_jyuryo', 'mergeCells' => ['w' => 5, 'h' => 2]],
            'AW' => ['field' => 'out_case_su', 'mergeCells' => ['w' => 3, 'h' => 2]],
            'AZ' => ['field' => 'out_hasu', 'mergeCells' => ['w' => 2, 'h' => 2]],
            'BB' => ['field' => 'out_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'BF' => ['field' => 'out_jyuryo', 'mergeCells' => ['w' => 5, 'h' => 2]],
            'BK' => ['field' => 'zaiko_case_su', 'mergeCells' => ['w' => 3, 'h' => 2]],
            'BN' => ['field' => 'zaiko_hasu', 'mergeCells' => ['w' => 2, 'h' => 2]],
            'BP' => ['field' => 'zaiko_su', 'mergeCells' => ['w' => 4, 'h' => 2]],
            'BT' => ['field' => 'zaiko_jyuryo', 'mergeCells' => ['w' => 5, 'h' => 2]],
        ],
        [
            'R' => ['field' => 'location', 'mergeCells' => ['w' => 5, 'h' => 1]],
        ]
    ],
];