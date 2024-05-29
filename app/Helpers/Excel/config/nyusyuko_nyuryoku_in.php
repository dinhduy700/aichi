<?php
$exp = new \App\Helpers\XlsExportMstMultiRowBlock();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'CG', 'row' => 52],
                'size' => 18,
            ],
            'height' => 52,
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'CG', 'row' => 10],
            'mergeCells' => [
                ['col' => 'I', 'row' => 2, 'w' => 21, 'h' => 2],
                ['col' => 'B', 'row' => 4, 'w' => 2, 'h' => 1],
                ['col' => 'D', 'row' => 4, 'w' => 3, 'h' => 1],
                ['col' => 'B', 'row' => 5, 'w' => 2, 'h' => 1],
                ['col' => 'D', 'row' => 5, 'w' => 3, 'h' => 1],

                ['col' => 'B', 'row' => 7, 'w' => 2, 'h' => 1],
                ['col' => 'B', 'row' => 8, 'w' => 2, 'h' => 1],

                ['col' => 'D', 'row' => 7, 'w' => 4, 'h' => 1],
                ['col' => 'I', 'row' => 7, 'w' => 29, 'h' => 1],

                ['col' => 'D', 'row' => 8, 'w' => 4, 'h' => 1],
                ['col' => 'I', 'row' => 8, 'w' => 29, 'h' => 1],

                ['col' => 'B', 'row' => 10, 'w' => 22, 'h' => 1],
                ['col' => 'X', 'row' => 10, 'w' => 3, 'h' => 1],
                ['col' => 'AA', 'row' => 10, 'w' => 3, 'h' => 1],
                ['col' => 'AD', 'row' => 10, 'w' => 3, 'h' => 1],
                ['col' => 'AG', 'row' => 10, 'w' => 2, 'h' => 1],
                ['col' => 'AI', 'row' => 10, 'w' => 3, 'h' => 1],
                ['col' => 'B', 'row' => 48, 'w' => 1, 'h' => 2],
                ['col' => 'B', 'row' => 50, 'w' => 1, 'h' => 3],
                ['col' => 'M', 'row' => 50, 'w' => 2, 'h' => 3],
            ],
            'others' => [
                ['col' => 'AJ', 'row' => 3, 'constVal' => $exp::VAL_PAGE_NO_OVER_TOTAL_PAGE],
                ['col' => 'AD', 'row' => 3, 'value' => function() {
                    $now = \Carbon\Carbon::now();
                    return $now->format('Y/m/d H:i');
                }],
                ['col' => 'B', 'row' => 5, 'value' => function($data) {
                    return \App\Helpers\Formatter::dateJP($data->first()->denpyo_dt, \App\Helpers\Formatter::DT_SHORT_JP_NENGETUHI);
                }],
                ['col' => 'D', 'row' => 5, 'value' => function($data) {
                    return $data->first()->nyusyuko_den_no;
                }],
                ['col' => 'D', 'row' => 7, 'value' => function($data) {
                    return $data->first()->ninusi_cd;
                }],
                ['col' => 'I', 'row' => 7, 'value' => function($data) {
                    return $data->first()->ninusi_ryaku_nm;
                }],
                ['col' => 'I', 'row' => 8, 'value' => function($data) {
                    return $data->first()->hatuti_nm;
                }],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 11],
            'end' => ['col' => 'CG', 'row' => 12],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   
            'B' => [
                'value' => function($row) {
                    return $row->hinmei_cd . '　'. $row->hinmei_nm;
                },
                'mergeCells' => ['w' => 22, 'h' => 2]
            ],
            'X' => [
                'value' => function($row) {
                    return numberFormat($row->case_su, -1);
                },
                'mergeCells' => ['w' => 3, 'h' => 1]
            ],

            'AA' => [
                'field' => 'su', 
                'value' => function($row) {
                    return numberFormat($row->su, -1);
                },
                'mergeCells' => ['w' => 3, 'h' => 2]
            ],
            'AD' => ['field' => 'jyuryo', 'mergeCells' => ['w' => 3, 'h' => 2]],
            'AG' => ['field' => 'soko_cd', 'mergeCells' => ['w' => 2, 'h' => 2]],
            'AI' => ['field' => 'location', 'mergeCells' => ['w' => 3, 'h' => 2]]
        ],
        [
            'X' => [
                'value' => function($row) {
                    return numberFormat($row->hasu, -1);
                },
                'mergeCells' => ['w' => 3, 'h' => 1]
            ],
        ]
    ],
];
