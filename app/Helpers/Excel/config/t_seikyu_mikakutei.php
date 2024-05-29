<?php
$exp = new \App\Helpers\Excel\XlsSeikyuMikakutei();
return [
    'base' => [
        'template' => [
            'page' => [
                'start' => ['col' => 'A', 'row' => 1],
                'end' => ['col' => 'R', 'row' => 61],
                'size' => 35,
                'rowHeight' => 14
            ],
            'summary' => [
                'by_ninusi' => [
                    'start' => ['col' => 'A', 'row' => 63],
                    'end' => ['col' => 'R', 'row' => 63],
                ]
            ],
            'height' => 64,
        ],
        'summary' => [
            'by_ninusi' => [
                ['col' => 'A', 'row' => 1, 'value' => function ($groupCollection) {
                    return data_get($groupCollection->first(), 'ninusi_ryaku_nm');
                }, 'type' => $exp::DATA_CLOSURE, 'mergeCells' => ['w' => 2, 'h' => 1]],
            ]
        ],
        'header' => [
            'start' => ['col' => 'A', 'row' => 1],
            'end' => ['col' => 'R', 'row' => 10],
            'mergeCells' => [
                ['col' => 'I', 'row' => 3, 'w' => 3, 'h' => 3],//運　賃　未　確　定　一　覧　表
                ['col' => 'A', 'row' => 7, 'w' => 3, 'h' => 1],//日締

                ['col' => 'A', 'row' => 8, 'w' => 2, 'h' => 1],//荷主名
                ['col' => 'C', 'row' => 8, 'w' => 1, 'h' => 3],//車番
                ['col' => 'D', 'row' => 8, 'w' => 1, 'h' => 3],//発地
                ['col' => 'E', 'row' => 8, 'w' => 5, 'h' => 3],//着地
                ['col' => 'J', 'row' => 8, 'w' => 1, 'h' => 3],//品名
                ['col' => 'K', 'row' => 8, 'w' => 4, 'h' => 3],//数量
                ['col' => 'O', 'row' => 8, 'w' => 1, 'h' => 3],//単価
                ['col' => 'P', 'row' => 8, 'w' => 1, 'h' => 3],//未確定額
                ['col' => 'Q', 'row' => 8, 'w' => 2, 'h' => 3],//備考
            ],
            'others' => [
                ['col' => 'A', 'row' => 7, 'type' => $exp::DATA_DATETIME, 'value' => function($page) {
                    return data_get($page->first(), 'seikyu_sime_dt');
                }],

                ['col' => 'Q', 'row' => 4, 'constVal' => $exp::VAL_CURRENT_TIME],
                ['col' => 'R', 'row' => 4, 'constVal' => $exp::VAL_PAGE_NO],
            ],
        ],
        'block' => [
            'start' => ['col' => 'A', 'row' => 11],
            'end' => ['col' => 'R', 'row' => 11],
        ],
        'others' => [
        ]
    ],
    'block' => [
        [   // row1
            'A' => ['field' => 'unso_dt', 'type' => $exp::DATA_DATETIME],//運送日
            'B' => ['field' => 'uriage_den_no'],//コード
            'C' => ['field' => 'syaban'],//車番
            'D' => ['field' => 'hatuti_nm'],//発地
            'E' => ['field' => 'hachaku_nm', 'mergeCells' => ['w'=>5, 'h'=>1]],//着地
            'J' => ['field' => 'hinmei_nm'],//品名
            'K' => ['field' => 'su', 'mergeCells' => ['w'=>4, 'h'=>1]],//数量
            'O' => ['field' => '単価'],//単価
            'P' => ['field' => 'mikakutei_kin'],//未確定額
            'Q' => ['field' => 'biko', 'mergeCells' => ['w'=>2, 'h'=>1]],//備考
        ],
    ],
];

