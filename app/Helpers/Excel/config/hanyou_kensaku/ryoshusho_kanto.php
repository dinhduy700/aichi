<?php
$exp = new \App\Helpers\Excel\XlsRyoshusho();
$honten = require(app_path("Helpers/Excel/config/hanyou_kensaku/ryoshusho_honten.php"));
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'S', 'row' => 45],
                'size' => 32,
            ],
            'summary' => [
                'start' => ['col' => 'A', 'row' => 47],
                'end' => ['col' => 'S', 'row' => 62],
                'mergeCells' => [
                    //消費税
                    ['col' => 'H', 'row' => 1, 'w' => 3, 'h' => 1],
                    ['col' => 'K', 'row' => 1, 'w' => 4, 'h' => 1],
                    //合　計
                    ['col' => 'H', 'row' => 2, 'w' => 3, 'h' => 1],
                    ['col' => 'K', 'row' => 2, 'w' => 4, 'h' => 1],

                    //領　収　書 ========================
                    //消費税
                    ['col' => 'P', 'row' => 11, 'w' => 2, 'h' => 1],
                ],
            ],
            'height' => 65,
        ],
        'summary' => [
            //消費税
            ['col' => 'K', 'row' => 1, 'value' => data_get($honten, 'base.summary.tax.value')],
            //合　計
            ['col' => 'K', 'row' => 2, 'value' => data_get($honten, 'base.summary.sum.value')],

            //領　収　書 ========================
            //配達日
            ['col' => 'R', 'row' => 7, 'type' => $exp::DATA_DATETIME, 'value' => function ($groupCollection) {
                return data_get($groupCollection->first(), 'haitatu_dt');
            }],
            //着地
            ['col' => 'C', 'row' => 8, 'value' => function ($groupCollection) {
                return data_get($groupCollection->first(), 'hachaku_nm');
            }],
            //合計
            ['col' => 'A', 'row' => 10, 'value' => data_get($honten, 'base.summary.ryoshusho_sum.value')],
            //消費税
            ['col' => 'P', 'row' => 11, 'value' => data_get($honten, 'base.summary.ryoshusho_tax.value')],
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'S', 'row' => 13],
            'mergeCells' => [
                ['col' => 'A', 'row' => 13, 'w' => 7, 'h' => 1],//品名
                ['col' => 'H', 'row' => 13, 'w' => 3, 'h' => 1],//数量
                ['col' => 'K', 'row' => 13, 'w' => 4, 'h' => 1],//金額
                ['col' => 'O', 'row' => 13, 'w' => 5, 'h' => 1],//備考
            ],
            'others' => [
                ['col' => 'A', 'row' => 4, 'type' => $exp::DATA_DATETIME, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), 'haitatu_dt');
                }],
                ['col' => 'B', 'row' => 9, 'value' => function($groupCollection) {
                    return data_get($groupCollection->first(), 'hachaku_nm');
                }],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 14],
            'end' => ['col' => 'S', 'row' => 14],
        ],
        'middle-block' => [
            'start' => ['col' => 'A', 'row' => 15],
            'end' => ['col' => 'S', 'row' => 15],
        ],
        'last-block' => [
            'start' => ['col' => 'A', 'row' => 45],
            'end' => ['col' => 'S', 'row' => 45],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'hinmei_nm'],//品名
            'H' => ['field' => 'su', 'mergeCells' => ['w'=>3, 'h'=>1]],//数量
            'K' => ['field' => 'kingaku', 'mergeCells' => ['w'=>4, 'h'=>1]],//金額
            'O' => ['field' => 'biko'],//備考
        ],
    ],
];
